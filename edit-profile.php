<?php

session_start();
include('login-check.php');
include('conn/conn.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid tenant ID.');
}
$tenant_id = (int)$_GET['id'];

// Fetch current tenant data
$stmt = $conn->prepare("SELECT name, email, phone, address FROM tenates WHERE id = ?");
$stmt->bind_param("i", $tenant_id);
$stmt->execute();
$stmt->bind_result($name, $email, $phone, $address);
if (!$stmt->fetch()) {
    die('Tenant not found.');
}
$stmt->close();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newName = trim($_POST['name']);
    $newEmail = trim($_POST['email']);
    $newPhone = trim($_POST['phone']);
    $newAddress = trim($_POST['address']);

    if ($newName && $newEmail && $newPhone && $newAddress) {
        $update = $conn->prepare("UPDATE tenates SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
        $update->bind_param("ssssi", $newName, $newEmail, $newPhone, $newAddress, $tenant_id);
        if ($update->execute()) {
            $success = "Profile updated successfully.";
            $name = $newName;
            $email = $newEmail;
            $phone = $newPhone;
            $address = $newAddress;
        } else {
            $error = "Failed to update profile.";
        }
        $update->close();
    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

  <style>
        body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }

    .hero-section {
      background:url('https://images.unsplash.com/photo-1560448204-603b3fc33ddc?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80') center/cover no-repeat;
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
    
    .form-container {
      background-color: white;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      margin-bottom: 30px;
    }
  </style>
</head>
<body>
<?php include('inc/tenant-header.php'); ?>
<!-- Hero Section with Image -->
  <section class="hero-section">
    <div class="container">
      <h1>Edit Your Profile</h1>
      <p class="lead">Update your personal information to keep your account current</p>
    </div>
  </section>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="form-container">
        <h3 class="mb-4">Edit Profile</h3>
        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($name) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($phone) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="3" required><?= htmlspecialchars($address) ?></textarea>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
              <a href="tenant-profile.php" class="btn btn-secondary me-md-2">Cancel</a>
              <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include('inc/tenant-footer.php'); ?>
</body>
</html>