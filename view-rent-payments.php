<?php
session_start();
include('login-check.php');
include('conn/conn.php');

// Assume tenant is logged in and tenant_id is stored in session
$tenant_id = $_SESSION['tenant_id'];

$today = date('Y-m-d');

//all agreements of tenants
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

//all payments of tenant
$sql1 = "
SELECT 
    payments.id,
    payments.amount,
    payments.created_at,
    payments.pay_month,
    payments.pay_year,
    properties.propertyTitle AS title
FROM 
    payments
JOIN agreements ON payments.agreement_id = agreements.id
JOIN properties ON agreements.property_id = properties.id
WHERE agreements.tenant_id = ?
ORDER BY payments.created_at DESC
";

$stmt1 = $conn->prepare($sql1);
$stmt1->execute([$tenant_id]);
$result1 = $stmt1->get_result();
$payments = $result1->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>View Rent Payments</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }

    .hero-section {
      background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1560448204-603b3fc33ddc?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
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

    .stats-container {
      display: flex;
      justify-content: center;
      gap: 30px;
      margin-top: 20px;
      flex-wrap: wrap;
    }

    .stat-item {
      background: rgba(255, 255, 255, 0.1);
      padding: 15px 25px;
      border-radius: 10px;
      backdrop-filter: blur(5px);
    }

    .stat-number {
      font-size: 2rem;
      font-weight: 700;
      display: block;
    }

    .stat-label {
      font-size: 0.9rem;
      opacity: 0.9;
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

    .table th, .table td {
      vertical-align: middle;
    }

    .btn-receipt {
      background-color: #0d6efd;
      color: white;
      border: none;
      font-size: 0.9rem;
    }

    .btn-receipt:hover {
      background-color: #0b5ed7;
    }

    .badge {
      font-weight: 500;
      padding: 8px 12px;
      border-radius: 20px;
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
      
      .stats-container {
        gap: 15px;
      }
      
      .stat-item {
        padding: 10px 15px;
      }
      
      .stat-number {
        font-size: 1.5rem;
      }
    }

    @media (max-width: 576px) {
      .hero-section h1 {
        font-size: 1.8rem;
      }
      
      .stats-container {
        flex-direction: column;
        gap: 10px;
      }
    }
  </style>
</head>
<body>
<?php include('inc/tenant-header.php'); ?>

  <!-- Hero Section -->
  <section class="hero-section">
    <div class="hero-content">
      <h1>Your Rental Dashboard</h1>
      <p>Manage your agreements, track payments, and view your rental history</p>
      
      <div class="stats-container">
        <div class="stat-item">
          <span class="stat-number"><?php echo $result->num_rows; ?></span>
          <span class="stat-label">Total Agreements</span>
        </div>
        <div class="stat-item">
          <span class="stat-number"><?php echo count($payments); ?></span>
          <span class="stat-label">Payments Made</span>
        </div>
        <div class="stat-item">
          <span class="stat-number">
            <?php
              $active_agreements = 0;
              if ($result->num_rows > 0) {
                $result->data_seek(0); // Reset pointer
                while ($row = $result->fetch_assoc()) {
                  if ($row['status'] === 'Approved' && $today >= $row['start_date'] && $today <= $row['end_date']) {
                    $active_agreements++;
                  }
                }
                $result->data_seek(0); // Reset pointer again for later use
              }
              echo $active_agreements;
            ?>
          </span>
          <span class="stat-label">Active Agreements</span>
        </div>
      </div>
    </div>
  </section>

  <div class="container">
    <h3 class="page-title text-center">Your Agreements History</h3>
    <table class="table table-bordered shadow-sm mt-3 bg-white">
      <thead class="thead-light">
        <tr>
          <th>#</th>
          <th>Property</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): 
          $index = 1;
          while ($row = $result->fetch_assoc()) { 
            
            if ($row['status'] === 'Pending') {
              $status = 'Pending';
            } elseif ($row['status'] === 'Rejected') {
              $status = 'Rejected';
            } elseif ($row['status'] === 'Cancelled') {
              $status = 'Cancelled';
            } elseif ($row['status'] === 'Approved') {
              if ($today < $row['start_date']) {
                $status = 'Approved'; // upcoming
              } elseif ($today >= $row['start_date'] && $today <= $row['end_date']) {
                $status = 'Ongoing';
              } else {
                $status = 'Completed';
              }
            } else {
              $status = 'Unknown';
            }

            $statusClass = match($status) {
              'Pending'   => 'bg-warning text-dark',
              'Approved'  => 'bg-info text-white',
              'Ongoing'   => 'bg-success text-white',
              'Cancelled' => 'bg-secondary text-white',
              'Rejected'  => 'bg-danger text-white',
              'Completed' => 'bg-dark text-white',
              default     => 'bg-light text-dark'
            };
          ?>
          <tr>
            <td><?= $index++ ?></td>
            <td><?= htmlspecialchars($row['propertyTitle']); ?></td>
            <td><?= htmlspecialchars($row['start_date']); ?></td>
            <td><?= htmlspecialchars($row['end_date']); ?></td>
            <td>
              <span class="badge <?= $statusClass; ?>">
                <?= $status; ?>
              </span>
            </td>
            <td>
              <?php if ($status === 'Ongoing' || $status === 'Approved'): ?>
                <a href="pay-rent.php?id=<?= htmlspecialchars($row['id']) ?>" class="btn btn-sm btn-primary">Pay Rent</a>
              <?php else: ?>
                <span class="badge <?= $statusClass; ?>"><?= $status; ?></span>
              <?php endif; ?>
            </td>
          </tr>
        <?php } else: ?>
          <tr>
            <td colspan="6" class="text-center py-4">
              <div class="alert alert-info mb-0">
                <i class="fas fa-info-circle me-2"></i> You don't have any rental agreements yet.
              </div>
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>

    <h3 class="page-title text-center">Your Rent Payment History</h3>

    <!-- Rent Payment Table -->
    <table class="table table-striped table-bordered mt-4">
      <thead>
        <tr>
          <th>#</th>
          <th>Payment Date</th>
          <th>Amount Paid</th>
          <th>Payment Month & Year</th>
          <th>Agreement Title</th>
          <th>Receipt</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($payments)): ?>
          <?php foreach ($payments as $index => $payment): ?>
            <tr>
              <td><?= $index + 1 ?></td>
              <td><?= htmlspecialchars($payment['created_at']) ?></td>
              <td>Rs. <?= number_format($payment['amount'], 2) ?></td>
              <td><?= htmlspecialchars($payment['pay_month']) ?> <?= htmlspecialchars($payment['pay_year']) ?></td>
              <td><?= htmlspecialchars($payment['title']) ?></td>
              <td><a href="receipt.php?id=<?= $payment['id'] ?>" target="_blank" class="btn btn-sm btn-success">View</a></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="6" class="text-center py-4">
              <div class="alert alert-info mb-0">
                <i class="fas fa-info-circle me-2"></i> You haven't made any payments yet.
              </div>
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <?php include('inc/tenant-footer.php'); ?>
</body>
</html>