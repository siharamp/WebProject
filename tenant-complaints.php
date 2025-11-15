<?php
session_start();
include('login-check.php');
include('conn/conn.php');

$tenant_id = $_SESSION['tenant_id'];
$today = date('Y-m-d');

$ongoing_stmt = $conn->prepare("
    SELECT agreements.id AS agreement_id, properties.propertyTitle 
    FROM agreements 
    JOIN properties ON agreements.property_id = properties.id 
    WHERE agreements.tenant_id = ? 
      AND agreements.status = 'Approved' 
      AND ? BETWEEN agreements.start_date AND agreements.end_date
");
$ongoing_stmt->bind_param("is", $tenant_id, $today);
$ongoing_stmt->execute();
$ongoing_result = $ongoing_stmt->get_result();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $agreement_id = $_POST['ongoing_agreement_id'];
    $description = trim($_POST['description']);
    $incident_date = $_POST['date'];

    if (empty($agreement_id) || empty($description) || empty($incident_date)) {
        $_SESSION['error'] = "All fields are required.";
    } else {
        // Get landlord email and property info from agreement
        $query = "
            SELECT l.email AS landlord_email, p.propertyTitle, t.name AS tenant_name 
            FROM agreements a
            JOIN properties p ON a.property_id = p.id
            JOIN landlords l ON p.landlord_id = l.id
            JOIN tenates t ON a.tenant_id = t.id
            WHERE a.id = ? AND a.tenant_id = ?
        ";
        $stmt_info = $conn->prepare($query);
        $stmt_info->bind_param("ii", $agreement_id, $tenant_id);
        $stmt_info->execute();
        $result = $stmt_info->get_result();

        if ($result->num_rows > 0) {
            $info = $result->fetch_assoc();
            $landlord_email = $info['landlord_email'];
            $propertyTitle = $info['propertyTitle'];
            $tenant_name = $info['tenant_name'];

            // Insert complaint
            $stmt = $conn->prepare("INSERT INTO complaints ( tenant_id, agreement_id, description, date_of_incident, status) VALUES (?, ?, ?, ?, 'Pending')");
            $stmt->bind_param("iiss", $tenant_id, $agreement_id, $description, $incident_date);

            if ($stmt->execute()) {
                // Send Email to Landlord
                $subject = "New Complaint for Your Property: $propertyTitle";
                $message = "
                    Dear Landlord,<br><br>
                    A new complaint has been submitted by tenant <strong>$tenant_name</strong> for the property <strong>$propertyTitle</strong>.<br><br>
                    <strong>Description:</strong><br>
                    $description<br><br>
                    <strong>Date of Incident:</strong> $incident_date<br><br>
                    Please login to your portal to respond.<br><br>
                    Regards,<br>Property Management System
                ";
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= "From: siharamp1999@gmail.com";

                mail($landlord_email, $subject, $message, $headers);

                $_SESSION['success'] = "Complaint submitted successfully and landlord notified.";
            } else {
                $_SESSION['error'] = "Failed to submit complaint. Please try again.";
            }
        } else {
            $_SESSION['error'] = "Agreement not found or unauthorized access.";
        }

        header("Location: tenant-complaints.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Submit Complaints - Tenant Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }

    .hero-section {
      background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1560472354-b33ff0c44a43?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2128&q=80');
      background-size: cover;
      background-position: center;
      color: white;
      padding: 80px 0;
      text-align: center;
      margin-bottom: 30px;
    }

    .hero-content {
      max-width: 800px;
      margin: 0 auto;
    }

    .hero-section h1 {
      font-size: 2.8rem;
      font-weight: 600;
      margin-bottom: 20px;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }

    .hero-section p {
      font-size: 1.2rem;
      margin-bottom: 30px;
      text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
    }

    .search-form {
      max-width: 600px;
      margin: 0 auto;
    }

    .search-form input {
      border-radius: 30px 0 0 30px;
      border: none;
      padding: 15px 25px;
      font-size: 1.1rem;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .search-form button {
      border-radius: 0 30px 30px 0;
      background-color: #0d6efd;
      color: white;
      border: none;
      padding: 15px 25px;
      font-size: 1.1rem;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      transition: background-color 0.3s;
    }

    .search-form button:hover {
      background-color: #0b5ed7;
    }

    .complaint-card {
      border: 1px solid #ddd;
      border-radius: 8px;
      overflow: hidden;
      margin-bottom: 20px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .complaint-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    .complaint-card-body {
      padding: 20px;
    }

    .complaint-card-title {
      font-weight: 600;
      font-size: 1.2rem;
      margin-bottom: 10px;
      color: #0d6efd;
    }

    .complaint-status {
      font-size: 0.9rem;
      margin-bottom: 15px;
    }

    .badge {
      font-weight: 500;
      padding: 8px 12px;
      border-radius: 20px;
    }

    .bg-success {
      background-color: #198754 !important;
    }

    .bg-warning {
      background-color: #ffc107 !important;
      color: #000 !important;
    }

    .container {
      margin-top: 30px;
    }

    .page-title {
      font-weight: 600;
      color: #0d6efd;
      margin-bottom: 25px;
      padding-bottom: 10px;
      border-bottom: 2px solid #0d6efd;
    }

         /* Footer */
        footer {
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 30px 0 15px; 
            bottom: 0;
            z-index: 100;
        }
        
        .footer-content {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        
        .footer-section {
            flex: 1;
            min-width: 200px;
            margin-bottom: 20px;
        }
        
        .footer-section h4 {
            font-size: 16px;
            margin-bottom: 15px;
            color: white;
            border-bottom: 1px solid #4a6572;
            padding-bottom: 8px;
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
            columns: 2; 
            -webkit-columns: 2;
            -moz-columns: 2;
        }

        .footer-links li {
            margin-bottom: 8px;
            break-inside: avoid; 
        }
        .footer-links a {
            color: #ecf0f1;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }
        
        .footer-links a:hover {
            color: #3a57e8;
        }
        
        .contact-info {
            flex: 2;
            min-width: 250px;
        }
        
        .contact-info p {
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }
        
        .social-links a {
            color: #ecf0f1;
            font-size: 18px;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .social-links a:hover {
            color: #3a57e8;
        }
        
        .footer-bottom {
            width: 100%;
            text-align: center;
            padding-top: 10px;
            border-top: 1px solid #4a6572;
            margin-top: 0;
        }
        
        .footer-bottom p {
            font-size: 12px;
            color: #a0b0b9;
        }

    /* Form Styling */
    .form-label {
      font-weight: 500;
      color: #495057;
      margin-bottom: 8px;
    }

    .form-control, .form-select {
      border-radius: 8px;
      padding: 12px 15px;
      border: 1px solid #ced4da;
      transition: border-color 0.3s, box-shadow 0.3s;
    }

    .form-control:focus, .form-select:focus {
      border-color: #0d6efd;
      box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    textarea.form-control {
      min-height: 120px;
      resize: vertical;
    }

    .btn-primary {
      background-color: #0d6efd;
      border: none;
      border-radius: 30px;
      padding: 12px 30px;
      font-weight: 500;
      transition: background-color 0.3s;
    }

    .btn-primary:hover {
      background-color: #0b5ed7;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .hero-section {
        padding: 60px 0;
      }
      
      .hero-section h1 {
        font-size: 2.2rem;
      }
      
      .hero-section p {
        font-size: 1rem;
      }
      
      .search-form input,
      .search-form button {
        padding: 12px 20px;
      }
    }

    @media (max-width: 576px) {
      .hero-section h1 {
        font-size: 1.8rem;
      }
      
      .search-form {
        flex-direction: column;
      }
      
      .search-form input {
        border-radius: 30px;
        margin-bottom: 10px;
      }
      
      .search-form button {
        border-radius: 30px;
      }
    }
  </style>
</head>
<body>
<?php include('inc/tenant-header.php'); ?>

  <!-- Hero Section -->
  <section class="hero-section">
    <div class="hero-content">
      <h1>Report Maintenance Issues</h1>
      <p>Submit complaints and maintenance requests for your rental property</p>

    </div>
  </section>

  <!-- Tenant Dashboard Sidebar -->
  <div class="container">
    <h3 class="page-title text-center">Submit a Complaint</h3>

    <?php if (isset($_SESSION['success'])): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <!-- Complaint Form -->
    <form action="" method="POST" class="mt-4">
      <div class="mb-3">
        <label for="ongoing_agreements" class="form-label">Select Property</label>
        <select name="ongoing_agreement_id" id="ongoing_agreements" class="form-select" required>
          <option value="">-- Select Property --</option>
          <?php 
          if ($ongoing_result->num_rows > 0) {
            $ongoing_result->data_seek(0); // Reset pointer
            while ($row = $ongoing_result->fetch_assoc()): ?>
              <option value="<?= $row['agreement_id']; ?>">
                <?= htmlspecialchars($row['propertyTitle']); ?>
              </option>
            <?php endwhile; 
          } else { ?>
            <option value="" disabled>No active properties found</option>
          <?php } ?>
        </select>
      </div>
      
      <div class="mb-3">
        <label for="complaintDescription" class="form-label">Description</label>
        <textarea class="form-control" id="complaintDescription" name="description" rows="4" required placeholder="Provide detailed description of the issue"></textarea>
      </div>
      
      <div class="mb-3">
        <label for="complaintDate" class="form-label">Date of Incident</label>
        <input type="date" class="form-control" id="complaintDate" name="date" required max="<?= date('Y-m-d'); ?>" />
      </div>
      
      <button type="submit" class="btn btn-primary w-100">
        <i class="fas fa-paper-plane me-2"></i>Submit Complaint
      </button>
    </form>

    <!-- Display Submitted Complaints -->
    <h3 class="page-title text-center mt-5">Your Complaint History</h3>
    <div class="row">
      <?php
      // Fetch complaint data with property info
      $sql = "SELECT c.description, c.date_of_incident, c.status, p.propertyTitle
              FROM complaints c
              JOIN agreements a ON c.agreement_id = a.id
              JOIN properties p ON a.property_id = p.id
              WHERE c.tenant_id = ?
              ORDER BY c.date_of_incident DESC";

      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i", $tenant_id);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
          $propertyTitle = htmlspecialchars($row['propertyTitle']);
          $description = htmlspecialchars($row['description']);
          $date = htmlspecialchars($row['date_of_incident']);
          $status = htmlspecialchars($row['status']);
          $badgeClass = ($status == 'Resolved') ? 'bg-success' : 'bg-warning';
      ?>
        <div class="col-12 mb-3">
          <div class="complaint-card">
            <div class="complaint-card-body">
              <h5 class="complaint-card-title">
                <i class="fas fa-home me-2"></i><?= $propertyTitle; ?>
              </h5>
              <p class="complaint-status">
                Status: <span class="badge <?= $badgeClass; ?>"><?= $status; ?></span>
              </p>
              <p><strong>Description:</strong> <?= $description; ?></p>
              <p><strong>Date of Incident:</strong> <?= $date; ?></p>
            </div>
          </div>
        </div>
      <?php endwhile; else: ?>
        <div class="col-12">
          <div class="text-center py-4">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <p class="text-muted">No complaints submitted yet.</p>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <?php include('inc/tenant-footer.php'); ?>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <