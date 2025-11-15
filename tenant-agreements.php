<?php
session_start();
include('login-check.php');
include('conn/conn.php');

// Assume tenant is logged in and tenant_id is stored in session
$tenant_id = $_SESSION['tenant_id'];

// Fetch agreements for this tenant
$sql = "SELECT a.*, p.propertyTitle, p.location, p.rent, l.name AS landlord_name
        FROM agreements a
        JOIN properties p ON a.property_id = p.id
        JOIN landlords l ON p.landlord_id = l.id
        WHERE a.tenant_id = ?
        ORDER BY a.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tenant_id);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Your Rental Agreements - Tenant Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }

    .hero-section {
      background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
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

    .agreement-card {
      border: 1px solid #ddd;
      border-radius: 12px;
      overflow: hidden;
      margin-bottom: 25px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s, box-shadow 0.3s;
      background: white;
    }

    .agreement-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .agreement-card-body {
      padding: 25px;
    }

    .agreement-card-title {
      font-weight: 600;
      font-size: 1.4rem;
      margin-bottom: 15px;
      color: #0d6efd;
    }

    .agreement-status {
      font-size: 0.9rem;
      margin-bottom: 20px;
    }

    .badge {
      font-weight: 500;
      padding: 8px 15px;
      border-radius: 20px;
    }

    .bg-success {
      background-color: #198754 !important;
    }

    .bg-warning {
      background-color: #ffc107 !important;
      color: #000 !important;
    }

    .bg-info {
      background-color: #0dcaf0 !important;
    }

    .bg-secondary {
      background-color: #6c757d !important;
    }

    .bg-danger {
      background-color: #dc3545 !important;
    }

    .bg-dark {
      background-color: #212529 !important;
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

    .no-agreements {
      text-align: center;
      padding: 60px 20px;
      background-color: #f8f9fa;
      border-radius: 12px;
      margin: 30px 0;
    }

    .no-agreements i {
      font-size: 4rem;
      color: #6c757d;
      margin-bottom: 20px;
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
      
      .agreement-card-body {
        padding: 20px;
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
      <h1>Your Rental Agreements</h1>
      <p>Manage and view all your rental agreements in one place</p>
      
      <form action="#" method="GET" class="search-form d-flex">
        <input type="text" class="form-control" placeholder="Search agreements by property name..." name="search" />
        <button type="submit" class="btn"><i class="fas fa-search"></i> Search</button>
      </form>
    </div>
  </section>

  <!-- Tenant Dashboard Content -->
  <div class="container">
    <h3 class="page-title text-center">Your Rental Agreements</h3>
    <?php
      if (isset($_SESSION['message'])) {
          echo "<div class='alert alert-info'>" . $_SESSION['message'] . "</div>";
          unset($_SESSION['message']);
      }
    ?>
    <?php if (isset($_SESSION['success'])): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <!-- Display Agreements -->
    <div class="row">
      <?php if ($result->num_rows > 0): 
        while ($row = $result->fetch_assoc()): 
          // Determine status badge class
          $status = htmlspecialchars($row['status']);
          $statusClass = match($status) {
            'Approved' => 'bg-success',
            'Pending' => 'bg-warning',
            'Rejected' => 'bg-danger',
            'Cancelled' => 'bg-secondary',
            default => 'bg-info'
          };
      ?>
        <div class="col-12 mb-4">
          <div class="agreement-card">
            <div class="agreement-card-body">
              <h5 class="agreement-card-title">
                <i class="fas fa-file-contract me-2"></i><?= htmlspecialchars($row['propertyTitle']); ?>
              </h5>
              <p class="agreement-status">
                Status: <span class="badge <?= $statusClass; ?>"><?= $status; ?></span>
              </p>
              
              <div class="row">
                <div class="col-md-6">
                  <p><strong><i class="fas fa-map-marker-alt me-2"></i>Location:</strong> <?= htmlspecialchars($row['location']); ?></p>
                  <p><strong><i class="fas fa-calendar-start me-2"></i>Start Date:</strong> <?= htmlspecialchars($row['start_date']); ?></p>
                  <p><strong><i class="fas fa-calendar-end me-2"></i>End Date:</strong> <?= htmlspecialchars($row['end_date']); ?></p>
                </div>
                <div class="col-md-6">
                  <p><strong><i class="fas fa-money-bill-wave me-2"></i>Monthly Rent:</strong> LKR <?= number_format(htmlspecialchars($row['rent']), 2); ?></p>
                  <p><strong><i class="fas fa-shield-alt me-2"></i>Security Deposit:</strong> LKR <?= number_format(htmlspecialchars($row['rent']), 2); ?></p>
                  <p><strong><i class="fas fa-user-tie me-2"></i>Landlord:</strong> <?= htmlspecialchars($row['landlord_name']); ?></p>
                </div>
              </div>
              
              <div class="text-center mt-4">
                <a href="cancel-agrements.php?id=<?= $row['id']; ?>" class="btn btn-primary">
                  <i class="fas fa-eye me-2"></i>Cancel Agreements
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php endwhile; else: ?>
        <div class="col-12">
          <div class="no-agreements">
            <i class="fas fa-file-contract"></i>
            <h4>No Rental Agreements Found</h4>
            <p class="text-muted">You don't have any rental agreements yet. Start by browsing available properties.</p>
            <a href="properties.php" class="btn btn-primary mt-3">
              <i class="fas fa-search me-2"></i>Browse Properties
            </a>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <?php include('inc/tenant-footer.php'); ?>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>