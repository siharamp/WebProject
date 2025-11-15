<?php

session_start();
include('conn/conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $role     = $_POST['role'] ?? '';
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($role) || empty($username) || empty($password)) {
    $_SESSION['error'] = "All fields are required.";
    header("Location: login.php");
    exit();
}

// Match role to table
$tables = [
    'admin'    => 'admin',
    'landlords' => 'landlords',
    'tenant'   => 'tenates'  // Note: table is named "tenates"
];

if (!isset($tables[$role])) {
    $_SESSION['error'] = "Invalid role selected.";
    header("Location: login.php");
    exit();
}

$table = $tables[$role];

// Fetch user by username
$stmt = $conn->prepare("SELECT * FROM `$table` WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        

        // Redirect based on role
        if ($role === 'admin') {
            header("Location: admin/index.php");
            // Set session
            $_SESSION['admin_id']  = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            $_SESSION['role']     = $role;
        } elseif ($role === 'landlords') {
            header("Location: landlords/index.php");
            $_SESSION['landlord_id']  = $user['id'];
            $_SESSION['landlord_username'] = $user['username'];
            $_SESSION['role']     = $role;
        } elseif ($role === 'tenant') {
            header("Location: index.php");
            $_SESSION['tenant_id']  = $user['id'];
            $_SESSION['tenant_username'] = $user['username'];
            $_SESSION['role']     = $role;
        }
        exit();
    }
}

// Invalid login
$_SESSION['error'] = "Invalid username or password.";
header("Location: login.php");
exit();

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Login - House Rental System</title>
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
    .login-card {
      width: 100%;
      max-width: 400px;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
      background-color: #fff;
    }
    .login-card h3 {
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
  <div class="login-card">
    <img src="https://cdn-icons-png.flaticon.com/512/619/619034.png" alt="logo" class="logo">
    <h3>Login</h3>
    <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php endif; ?>
    <form method="POST">
      <div class="mb-3">
        <label for="role" class="form-label">Login As</label>
        <select name="role" class="form-select" required>
          <option value="">-- Select Role --</option>
          <option value="admin">Admin</option>
          <option value="landlords">Landlord</option>
          <option value="tenant">Tenant</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" name="username" id="username" placeholder="Enter your username" required />
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password" required />
      </div>
      <button type="submit" class="btn btn-primary">Login</button>
      <div class="text-center mt-3">
        <small><a href="#">Forgot password?</a></small>
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
