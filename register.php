<?php
error_reporting(0);
include('conn/conn.php');

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
$name     = trim($_POST['name']);
$email    = trim($_POST['email']);
$phone    = trim($_POST['phone']);
$username = trim($_POST['username']);
$password = trim($_POST['password']);
$hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$address  = trim($_POST['address']);

//mail
$to = $email;
$subject ="Registration successfull";
$body ="Hi $name, You are successfully registered on House rental as a tenate. Your username is '$username' and password is '$password'!";
$header ="From:siharamp1999@gmail.com";

// Check if username or email exists
$check = $conn->prepare("SELECT id FROM tenates WHERE username = ? ");
$check->bind_param("s", $username);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    $error = "Username already exists.";
} else {
    $stmt = $conn->prepare("INSERT INTO tenates (name, email, phone, username, password, address) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $email, $phone, $username, $hashed_password, $address);
    if ($stmt->execute()) {
        $success = "Tenate registration successful.";
        mail($to, $subject, $body, $header);
    } else {
        $error = "Error in registration.";
    }
    $stmt->close();
}
$check->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register - House Rental System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background: linear-gradient(to right, #e0f7fa, #ffffff);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Segoe UI', sans-serif;
    }
    .register-card {
      width: 100%;
      max-width: 500px;
      padding: .5rem;
      padding-right:2rem;
      padding-left:2rem;
      border-radius: 12px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
      background-color: #fff;
    }
    .register-card h3 {
      margin-bottom: 1.5rem;
      text-align: center;
      color: #0d6efd;
    }
    .form-control:focus {
      border-color: #0d6efd;
      box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    .btn-primary {
      width: 100%;
    }
    .logo {
      display: block;
      margin: 0 auto 1rem auto;
      width: 60px;
    }
  </style>
</head>
<body>
  <div class="register-card">
    <img src="https://cdn-icons-png.flaticon.com/512/619/619034.png" alt="logo" class="logo">
    <h3>Create an Account</h3>

    <?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
    <?php elseif ($error): ?>
      <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
    
      <div class="mb-3">
        <label for="fullname" class="form-label">Full Name</label>
        <input type="text" class="form-control" name="name" id="name" placeholder="John Doe" required />
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" class="form-control" name="email" id="email" placeholder="email@example.com" required />
      </div>
      <div class="row">
          <div class="mb-3 col-md-6">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="tel" class="form-control" name="phone" id="phone" placeholder="07XXXXXXXX" required />
          </div>
          <div class="mb-3 col-md-6">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" name="username" id="username" placeholder="Create a password" required />
          </div>
          <div class="mb-3 col-md-6">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Create a password" required />
          </div>
          <div class="mb-3 col-md-6">
            <label for="confirm" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" name="confirm-password" id="confirm" placeholder="Confirm your password" required />
          </div>
      </div>
      

      <div class="mb-3">
        <label for="address" class="form-label">address</label>
        <textarea name="address" rows="3"  name="address" class="form-control" placeholder="Enter address" required></textarea>
        </div>
      <button type="submit" class="btn btn-primary">Register</button>
      <div class="text-center mt-3">
        <small>Already have an account? <a href="login.html">Login here</a></small>
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
