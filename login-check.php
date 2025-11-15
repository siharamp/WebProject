<?php


// Redirect to login if not logged in
if (!isset($_SESSION['tenant_id']) || !isset($_SESSION['role'])) {
    $_SESSION['error'] = "Please log in to continue.";
    header("Location: login.php");
    exit();
}


?>