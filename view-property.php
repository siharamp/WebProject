<?php
session_start();

include('conn/conn.php');
include('login-check.php');

if (!isset($_GET['property_id'])) {
    header("Location: index.php");
    exit();
}

$property_id = intval($_GET['property_id']);

// Fetch property details
$stmt = $conn->prepare("SELECT p.*, l.name AS landlord_name, l.email AS landlord_email 
                        FROM properties p 
                        JOIN landlords l ON p.landlord_id = l.id 
                        WHERE p.id = ?");
$stmt->bind_param("i", $property_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Property not found.";
    exit();
}

$property = $result->fetch_assoc();

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

<?php include('inc/tenant-header.php'); ?>

  <!-- Property Listings Section -->
  <div class="container mt-5">
    <h2><?= htmlspecialchars($property['propertyTitle']) ?></h2>
    <img src="img/property/<?= htmlspecialchars($property['image']) ?>" class="img-fluid mb-3" style="max-height: 400px;">
    
    <p><strong>Type:</strong> <?= $property['propertyType'] ?></p>
    <p><strong>Bedrooms:</strong> <?= $property['bedrooms'] ?></p>
    <p><strong>Bathrooms:</strong> <?= $property['bathrooms'] ?></p>
    <p><strong>Location:</strong> <?= $property['location'] ?></p>
    <p><strong>Rent:</strong> LKR <?= number_format($property['rent'], 2) ?> / month</p>
    <p><strong>Description:</strong><br><?= nl2br(htmlspecialchars($property['description'])) ?></p>
    <p><strong>Landlord:</strong> <?= htmlspecialchars($property['landlord_name']) ?> (<?= $property['landlord_email'] ?>)</p>

    <a href="create-agreement.php?property_id=<?= $property['id']; ?>" class="btn btn-success mt-3">
        <i class="fas fa-file-signature me-1"></i> Request Agreement
    </a>
    <a href="index.php" class="btn btn-secondary mt-3">Back</a>
</div>

<br>

  <?php
include('inc/tenant-footer.php');
?>
</body>
</html>
