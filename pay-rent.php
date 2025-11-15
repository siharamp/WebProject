<?php
session_start();
include('login-check.php');
include('conn/conn.php');

// Assume tenant is logged in and tenant_id is stored in session
$tenant_id = $_SESSION['tenant_id'];


if (isset($_GET['id'])) {

    $agreement_id = $_GET['id'];

    $total_rent = 0;
    $total_days = 0;
    $monthly_rent = 0;

    $stmt = $conn->prepare("
        SELECT a.start_date, a.end_date, a.status, p.rent 
        FROM agreements a 
        JOIN properties p ON a.property_id = p.id 
        WHERE a.id = ?
    ");
    $stmt->bind_param("i", $agreement_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        $start_date = new DateTime($row['start_date']);
        $end_date = new DateTime($row['end_date']);
        $monthly_rent = $row['rent'];

        // Calculate number of days (inclusive)
        $interval = $start_date->diff($end_date);
        $total_days = $interval->days + 1;

        // Assume 30-day month for average daily rent
        $daily_rent = $monthly_rent / 30;
        $total_rent = $daily_rent * $total_days;
    }
  }


$success="";
$error="";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect & sanitize form data
    $card_name       = htmlspecialchars($_POST['card_name']);
    $card_number     = htmlspecialchars($_POST['card_number']);
    $expiry_month    = htmlspecialchars($_POST['expiry_month']);
    $expiry_year     = htmlspecialchars($_POST['expiry_year']);
    $cvv             = htmlspecialchars($_POST['cvv']);
    $billing_address = htmlspecialchars($_POST['billing_address']);
    $postal_code     = htmlspecialchars($_POST['postal_code']);
    $month           = htmlspecialchars($_POST['month']);
    $year            = htmlspecialchars($_POST['year']);
    $amount            = htmlspecialchars($_POST['amount']);
    $agreement_id    = intval($_POST['ag_id']);
    $tenant_id    = intval($_POST['tenant_id']);

    // Optional: hash or encrypt card number and CVV (never store raw in production)
    // Example: $hashed_card = password_hash($card_number, PASSWORD_DEFAULT);

    // Insert into payments table
    $stmt = $conn->prepare("INSERT INTO payments 
        (agreement_id, tenant_id, card_name, card_number, expiry_month, expiry_year, cvv, billing_address, amount, postal_code, pay_month, pay_year, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, NOW())");

    $stmt->bind_param("iisssissssss", 
        $agreement_id, $tenant_id, $card_name, $card_number, 
        $expiry_month, $expiry_year, $cvv, 
        $billing_address,$amount, $postal_code, $month, $year
    );

    if ($stmt->execute()) {
        $success ="Payment successful.";
        
        $agreement_id = $_POST['ag_id'];

// Fetch landlord and rent details
  $query = "
      SELECT 
          l.name AS landlord_name, l.email, 
          t.name AS tenant_name, 
          p.propertyTitle AS property_name, 
          p.rent
      FROM agreements a
      JOIN properties p ON a.property_id = p.id
      JOIN landlords l ON p.landlord_id = l.id
      JOIN tenates t ON a.tenant_id = t.id
      WHERE a.id = ?
  ";

  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $agreement_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
      $data = $result->fetch_assoc();

      $to = $data['email'];
      $subject = "Rent Payment Received for " . $data['property_name'];
      $message = "
          Dear {$data['landlord_name']},<br><br>
          This is to inform you that the rent has been paid by <strong>{$data['tenant_name']}</strong> for the property <strong>{$data['property_name']}</strong>.<br><br>
          <strong>Amount Paid:</strong> LKR " . $amount . "<br><br>
          Regards,<br>
          House Rental System
      ";
      $headers = "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
      $headers .= 'From: noreply@yourdomain.com' . "\r\n";

      if (mail($to, $subject, $message, $headers)) {
          echo "Email sent to landlord successfully.";
      } else {
          echo "Failed to send email.";
      }
  }
    } else {

        $error="Payment failed. $stmt->error ";
        //echo "<div class='alert alert-danger'>Payment failed: " . $stmt->error . "</div>";
    }
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

  <div class="container">
<h1 class="page-title text-center mt-4">Pay your Rent here</h1>

<br>
<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
  <?php elseif ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
  <?php endif; ?>
<br>
<form method="POST" action="">
  <div class="mb-3">
    <label class="form-label">Cardholder Name</label>
    <input type="text" name="card_name" class="form-control" required> 
  </div>

  <input type="hidden" name="ag_id" value="<?= $agreement_id;?>"  class="form-control" required>
<input type="hidden" name="tenant_id" value="<?= $tenant_id;?>" class="form-control" required>
<input type="hidden" name="total_days" value="<?= $total_days;?>" class="form-control">

  <div class="mb-3">
    <label class="form-label">Card Number</label>
    <input type="text" name="card_number" maxlength="16" class="form-control" required>
    
  </div>

  <div class="mb-3">
    <label class="form-label">Expiry</label>
    <div class="d-flex gap-2">
      <select name="expiry_month" class="form-control" required>
        <option value="">MM</option>
        <?php for($m=1;$m<=12;$m++): ?>
        <option value="<?= str_pad($m,2,'0',STR_PAD_LEFT) ?>"><?= str_pad($m,2,'0',STR_PAD_LEFT) ?></option>
        <?php endfor; ?>
      </select>
      <select name="expiry_year" class="form-control" required>
        <option value="">YYYY</option>
        <?php for($y=date('Y');$y<=date('Y')+10;$y++): ?>
        <option value="<?= $y ?>"><?= $y ?></option>
        <?php endfor; ?>
      </select>
    </div>
  </div>

  <div class="mb-3">
    <label class="form-label">CVV</label>
    <input type="password" name="cvv" maxlength="4" class="form-control" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Billing Address (Optional)</label>
    <input type="text" name="billing_address" class="form-control">
  </div>

  <div class="mb-3">
  <label class="form-label">Monthly Rent</label>
  <?php if ($total_days >= 30): ?>
    <input type="text" value="<?= htmlspecialchars($monthly_rent); ?>" name="amount" class="form-control" readonly>
  <?php else: ?>
    <input type="text" value="<?= htmlspecialchars($total_rent); ?>" name="amount" class="form-control" readonly>
  <?php endif; ?>
</div>

  <div class="mb-3">
    <label class="form-label">Postal Code (Optional)</label>
    <input type="text" name="postal_code" class="form-control">
  </div>

  <div class="mb-3">
    <label class="form-label">Select Month of Payment</label>
    <select name="month" class="form-control">
      <option value="">Select Month</option>
      <option value="January">January</option>
      <option value="February">February</option>
      <option value="March">March</option>
      <option value="April">April</option>
      <option value="May">May</option>
      <option value="June">June</option>
      <option value="July">July</option>
      <option value="August">August</option>
      <option value="September">September</option>
      <option value="October">October</option>
      <option value="November">November</option>
      <option value="December">December</option>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">Enter Year of Payment</label>
    <input type="number" name="year" class="form-control">
  </div>

  <button type="submit" class="btn btn-primary mb-5">Pay Now</button>
</form>

  </div>
    
    
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
