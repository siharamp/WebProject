<?php
session_start();
include('conn/conn.php');

// Initialize search variables
$search_query = "";
$properties = [];

// Check if search form was submitted
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
    $search_query = trim($_GET['search']);
    
    // Prepare SQL query with search conditions
    if (!empty($search_query)) {
        $sql = "SELECT * FROM properties 
                WHERE propertyTitle LIKE ? 
                   OR location LIKE ? 
                   OR propertyType LIKE ? 
                   OR description LIKE ?
                ORDER BY date_registered DESC";
        
        $stmt = $conn->prepare($sql);
        $search_param = "%" . $search_query . "%";
        $stmt->bind_param("ssss", $search_param, $search_param, $search_param, $search_param);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        // If search is empty, get all properties
        $sql = "SELECT * FROM properties ORDER BY date_registered DESC";
        $result = $conn->query($sql);
    }
} 
else {
    // Default: get all properties
    $sql = "SELECT * FROM properties ORDER BY date_registered DESC";
    $result = $conn->query($sql);
}

// Store properties in an array
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $properties[] = $row;
    }
}
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
      background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
      background-size: cover;
      background-position: center;
      color: white;
      padding: 100px 0;
      text-align: center;
    }

    .hero-section h1 {
      font-size: 3rem;
      font-weight: 600;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }

    .hero-section p.lead {
      font-size: 1.5rem;
      text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
      margin-bottom: 30px;
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

    .property-card {
      border: 1px solid #ddd;
      border-radius: 8px;
      overflow: hidden;
      margin-bottom: 20px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .property-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    .property-card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }

    .property-card-body {
      padding: 20px;
    }

    .property-card-title {
      font-weight: 600;
      font-size: 1.2rem;
      margin-bottom: 10px;
    }

    .property-card-price {
      font-size: 1.3rem;
      color: #0d6efd;
      font-weight: 600;
      margin-top: 10px;
    }

    .property-card-location {
      color: #6c757d;
      margin-bottom: 8px;
    }

    .property-card-description {
      color: #495057;
      margin-bottom: 15px;
    }

    .btn-primary {
      background-color: #0d6efd;
      border: none;
      border-radius: 30px;
      padding: 10px 20px;
      font-weight: 500;
      transition: background-color 0.3s;
    }

    .btn-primary:hover {
      background-color: #0b5ed7;
    }
    
    .search-results-header {
      margin: 30px 0 20px;
      padding-bottom: 10px;
      border-bottom: 2px solid #0d6efd;
    }
    
    .no-results {
      text-align: center;
      padding: 40px;
      background-color: #f8f9fa;
      border-radius: 10px;
      margin: 30px 0;
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


    /* Responsive adjustments */
    @media (max-width: 768px) {
      .hero-section {
        padding: 70px 0;
      }
      
      .hero-section h1 {
        font-size: 2.2rem;
      }
      
      .hero-section p.lead {
        font-size: 1.2rem;
      }
      
      .search-form input,
      .search-form button {
        padding: 12px 20px;
      }
      
      .footer-content {
        flex-direction: column;
      }
      
      .footer-section {
        margin-bottom: 25px;
      }
    }

    @media (max-width: 576px) {
      .hero-section h1 {
        font-size: 1.8rem;
      }
      
      .hero-section p.lead {
        font-size: 1rem;
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

    <?php include 'inc/tenant-header.php'; ?>
  
  <!-- Hero Section -->
  <section class="hero-section">
    <div class="container">
      <h1>Welcome to Your Tenant Dashboard</h1>
      <p class="lead">Find your perfect home with ease. Explore our properties now!</p>
      
      <form class="search-form d-flex" method="GET" action="">
        <input type="text" class="form-control" name="search" placeholder="Search by location, property type, or features..." value="<?php echo htmlspecialchars($search_query); ?>">
        <button class="btn" type="submit"><i class="fas fa-search"></i> Search</button>
      </form>
    </div>
  </section>

  <!-- Property Listings Section -->
  <section class="container my-5">
    <?php if (!empty($search_query)): ?>
      <div class="search-results-header">
        <h2>Search Results for "<?php echo htmlspecialchars($search_query); ?>"</h2>
        <p class="text-muted"><?php echo count($properties); ?> properties found</p>
        <a href="?" class="btn btn-outline-secondary btn-sm">Clear Search</a>
      </div>
    <?php else: ?>
      <h2 class="text-center mb-4">Featured Properties</h2>
    <?php endif; ?>
    
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <!-- Property Cards -->
      <?php if (!empty($properties)): ?>
        <?php foreach ($properties as $property): ?>
          <div class="col">
            <div class="property-card">
              <img src="img/property/<?= htmlspecialchars($property['image']); ?>" alt="Property Image" />
              <div class="property-card-body">
                <h5 class="property-card-title"><?= htmlspecialchars($property['propertyTitle']); ?></h5>
                <p class="property-card-location"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($property['location']); ?></p>
                <p class="property-card-description"><?= htmlspecialchars($property['propertyType']); ?> | <?= htmlspecialchars($property['bedrooms']); ?> Bed | <?= htmlspecialchars($property['bathrooms']); ?> Bath</p>
                <p class="property-card-price">LKR <?= number_format(htmlspecialchars($property['rent']), 2); ?> / month</p>
                <a href="view-property.php?property_id=<?= $property['id']; ?>" class="btn btn-primary w-100">
                  View Details
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12">
          <div class="no-results">
            <i class="fas fa-search fa-3x mb-3 text-muted"></i>
            <h4>No properties found</h4>
            <p class="text-muted">We couldn't find any properties matching your search criteria.</p>
            <?php if (!empty($search_query)): ?>
              <p>Try adjusting your search terms or <a href="?">browse all properties</a>.</p>
            <?php else: ?>
              <p>Please check back later for new listings.</p>
            <?php endif; ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <?php include 'inc/tenant-footer.php'; ?>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>