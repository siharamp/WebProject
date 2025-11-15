<?php
session_start();
include('login-check.php');
include('conn/conn.php');

$tenant_id = $_SESSION['tenant_id'];

$sql = "SELECT * FROM tenates WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tenant_id);
$stmt->execute();
$result = $stmt->get_result();
$tenant = $result->fetch_assoc();

// password change
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['current_password'], $_POST['new_password'], $_POST['confirm_password'])) {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Fetch current hashed password from DB
    $stmt = $conn->prepare("SELECT password, email FROM tenates WHERE id = ?");
    $stmt->bind_param("i", $tenant_id);
    $stmt->execute();
    $stmt->bind_result($hashedPasswordFromDB, $tenantEmail);
    if ($stmt->fetch()) {
        if (password_verify($currentPassword, $hashedPasswordFromDB)) {
            if ($newPassword === $confirmPassword) {
                $newHashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                $stmt->close();

                // Update password
                $update = $conn->prepare("UPDATE tenates SET password = ? WHERE id = ?");
                $update->bind_param("si", $newHashedPassword, $tenant_id);
                if ($update->execute()) {
                    // Send email notification
                    $subject = "Your Password Has Been Changed";
                    $message = "Hello,\n\nYour tenant account password has been successfully changed.\n\nIf you did not request this change, please contact support immediately.\n\nThank you.";
                    $headers = "From: no-reply@yourdomain.com";
                    mail($tenantEmail, $subject, $message, $headers);

                    $success = "Password updated and email notification sent.";
                } else {
                    $error = "Error updating password.";
                }
                $update->close();
            } else {
                $error = "New passwords do not match.";
            }
        } else {
            $error = "Current password is incorrect.";
        }
    } else {
        $error = "Tenant not found.";
    }
    //$stmt->close();
}


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
      background: url('https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80') center/cover no-repeat;
      color: white;
      padding: 100px 0;
      text-align: center;
      position: relative;
    }

    .hero-section h1 {
      font-size: 3rem;
      font-weight: 600;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    .hero-section p {
      text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
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

    .footer {
      background-color: #0d6efd;
      color: white;
      padding: 40px 0;
      text-align: center;
    }

    .footer a {
      color: #fff;
      text-decoration: none;
    }
    
    .profile-card {
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      background-color: white;
    }
    
    .profile-info {
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
<?php include('inc/tenant-header.php'); ?>

  <!-- Hero Section with Image -->
  <section class="hero-section">
    <div class="container">
      <h1>Welcome to Your Tenant Dashboard</h1>
      <p class="lead">Find your perfect home with ease. Explore our properties now!</p>
      
    </div>
  </section>
  
  <!-- Tenant Dashboard Sidebar -->
  <div class="container">
    <h3 class="page-title text-center mt-4">Your Profile</h3>

    <!-- Profile Info Section -->
    <div class="row">
      <div class="col-md-6 mx-auto">
        <div class="profile-card">
          <div class="profile-card-body">
            <h5 class="profile-info">Personal Information</h5>
            <p class="profile-info"><strong>Name:</strong> <?= htmlspecialchars($tenant['name']) ?></p>
            <p class="profile-info"><strong>Email:</strong> <?= htmlspecialchars($tenant['email']) ?></p>
            <p class="profile-info"><strong>Phone Number:</strong> <?= htmlspecialchars($tenant['phone']) ?></p>
            <p class="profile-info"><strong>Address:</strong> <?= htmlspecialchars($tenant['address']) ?></p>

            <!-- Edit Profile Button -->
            <a href="edit-profile.php?id=<?= htmlspecialchars($tenant['id']) ?>" class="btn btn-primary w-100 mt-4">Edit Profile</a>
          </div>
        </div>
      </div>

            <div class="col-6 mx-auto">
        <div class="profile-card">
          <div class="profile-card-body">
            <h5 class="profile-info">Change Password</h5>

            <?php if ($success): ?>
              <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php elseif ($error): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
              <div class="mb-3">
                <label for="currentPassword" class="form-label">Current Password</label>
                <input type="password" class="form-control" id="currentPassword" name="current_password" required />
              </div>
              <div class="mb-3">
                <label for="newPassword" class="form-label">New Password</label>
                <input type="password" class="form-control" id="newPassword" name="new_password" required />
              </div>
              <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm New Password</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required />
              </div>
              <button type="submit" class="btn btn-primary w-100">Change Password</button>
            </form>
          </div>
        </div>
      </div>
    </div>

 
  </div>
  <br>
  <?php include('inc/tenant-footer.php'); ?>

  <script>
    // If no payments are found, display the 'No Payments' message
    const payments = document.querySelector('tbody').rows;
    if (payments.length === 0) {
      document.getElementById('no-payments').style.display = 'block';
    }
  </script>

</body>
</html>