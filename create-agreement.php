<?php
session_start();

include('conn/conn.php');

if (!isset($_SESSION['tenant_id']) || !isset($_SESSION['role'])) {
    $_SESSION['error'] = "Please log in to continue.";
    header("Location: login.php");
    exit();
}


$tenant_id = $_SESSION['tenant_id'];

if (!isset($_GET['property_id'])) {
    $_SESSION['error'] = "Failed to load property.";
    header("Location: index.php");
    exit();
}

$property_id = intval($_GET['property_id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


  $tenant_id  = $_POST['tenant_id'];
  $property_id = $_POST['property_id'];
  $start_date = $_POST['start_date'];
  $end_date   = $_POST['End_date'];
  $message    = $_POST['message'];

  // First: check if this tenant already has overlapping agreement for same property
  $stmt = $conn->prepare("SELECT * FROM agreements 
                          WHERE tenant_id = ? AND property_id = ? 
                          AND ((start_date <= ? AND end_date >= ?) 
                               OR (start_date <= ? AND end_date >= ?) 
                               OR (start_date >= ? AND end_date <= ?))");
  $stmt->bind_param("iissssss", $tenant_id, $property_id, $start_date, $start_date, $end_date, $end_date, $start_date, $end_date);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
      $_SESSION['error'] = "You already have an agreement request for this property within the selected dates.";
      header("Location: request-agreement.php?property_id=" . $property_id);
      exit();
  }

  // Second: check if other approved agreements exist for same property and overlapping
  $stmt2 = $conn->prepare("SELECT * FROM agreements 
                           WHERE property_id = ? AND status = 'Approved'
                           AND ((start_date <= ? AND end_date >= ?) 
                                OR (start_date <= ? AND end_date >= ?) 
                                OR (start_date >= ? AND end_date <= ?))");
  $stmt2->bind_param("issssss", $property_id, $start_date, $start_date, $end_date, $end_date, $start_date, $end_date);
  $stmt2->execute();
  $result2 = $stmt2->get_result();

  if ($result2->num_rows > 0) {
      $_SESSION['error'] = "This property is already rented during the selected dates.";
      header("Location: create-agreement.php?property_id=" . $property_id);
      exit();
  }

  // If checks pass, insert agreement
  $stmt3 = $conn->prepare("INSERT INTO agreements (tenant_id, property_id, start_date, end_date, message) VALUES (?, ?, ?, ?, ?)");
  $stmt3->bind_param("iisss", $tenant_id, $property_id, $start_date, $end_date, $message);

  if ($stmt3->execute()) {
      $_SESSION['success'] = "Agreement request submitted successfully.";
  } else {
      $_SESSION['error'] = "Error inserting agreement.";
  }

  header("Location: tenant-agreements.php");
  exit();
}

// Fetch property for display
$prop_stmt = $conn->prepare("SELECT propertyTitle FROM properties WHERE id = ?");
$prop_stmt->bind_param("i", $property_id);
$prop_stmt->execute();
$property = $prop_stmt->get_result()->fetch_assoc();

?> 

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Tenant Homepage</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }

    .hero-section {
      background-color: #0d6efd;
      color: white;
      padding: 100px 0;
      text-align: center;
    }

    .hero-section h1 {
      font-size: 3rem;
      font-weight: 600;
    }

    .search-form input {
      border-radius: 30px;
      border: 1px solid #ccc;
      padding: 12px 20px;
      font-size: 1.1rem;
    }

    .search-form button {
      border-radius: 30px;
      background-color: #0d6efd;
      color: white;
      border: none;
      padding: 12px 20px;
      font-size: 1.1rem;
    }

    .property-card {
      border: 1px solid #ddd;
      border-radius: 8px;
      overflow: hidden;
      margin-bottom: 20px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .property-card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }

    .property-card-body {
      padding: 15px;
    }

    .property-card-title {
      font-weight: 600;
    }

    .property-card-price {
      font-size: 1.2rem;
      color: #0d6efd;
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

  </style>
</head>
<body>
    <?php include 'inc/tenant-header.php'; ?>
  
  <!-- Hero Section -->
  <section class="hero-section">
    <h1>Welcome to Your Tenant Dashboard</h1>
    <p class="lead">Find your perfect home with ease. Explore our properties now!</p>
    <form action="#" method="GET" class="d-flex justify-content-center search-form mt-4">
      <input type="text" class="form-control w-50" placeholder="Search properties by location or type" name="search" required />
      <button type="submit" class="btn btn-primary ms-2">Search</button>
    </form>
  </section>

  <!-- Property Listings Section -->
  <div class="container mt-5">
    <h2>Request Agreement for: <?= htmlspecialchars($property['propertyTitle']); ?></h2>

    
  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php endif; ?>

  <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
  <?php endif; ?>

    <form method="POST">
        <div class="row">
            <input type="hidden" value="<?= $tenant_id;?>" class="form-control" name="tenant_id" id="tenant_id" placeholder="e.g., 2BHK Apartment in City Center" required>
            <input type="hidden" value="<?= $property_id;?>" class="form-control" name="property_id" id="property_id" placeholder="e.g., 2BHK Apartment in City Center" required>

            <div class="mb-3 col-6">
                <label for="message" class="form-label">Start Date</label>
                <input type="date" class="form-control" name="start_date" id="start_date" placeholder="e.g., 2BHK Apartment in City Center" required>
            </div>
            <div class="mb-3 col-6">
                <label for="message" class="form-label">End Date</label>
                <input type="date" class="form-control" name="End_date" id="End_date" placeholder="e.g., 2BHK Apartment in City Center" required>
            </div>
        </div>
        <div class="mb-3">
            <label for="message" class="form-label">Your Message to the Landlord</label>
            <textarea name="message" id="message" class="form-control" rows="5" placeholder="Write your request here..." required></textarea>
        </div>
        <button type="submit" class="btn btn-success">Submit Request</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<br>

  <?php
include('inc/tenant-footer.php');
?>
</body>
</html>
