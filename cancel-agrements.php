<?php
session_start();
include('conn/conn.php'); // adjust DB connection

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); 

    // Fetch status and start_date
    $sql = "SELECT status, start_date FROM agreements WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($status, $start_date);
    $stmt->fetch();
    $stmt->close();

    if (!$status) {
        $_SESSION['message'] = "Agreement not found.";
    } elseif ($status === 'Cancelled') {
        $_SESSION['message'] = "This agreement is already cancelled.";
    } else {
        $today = date("Y-m-d");
        if ($today >= $start_date) {
            $_SESSION['message'] = "Couldn't cancel. The agreement is ongoing.";
        } else {
            // Cancel only if not started yet
            $sql = "UPDATE agreements SET status = 'Cancelled' WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                $_SESSION['message'] = "Agreement cancelled successfully!";
            } else {
                $_SESSION['message'] = "Error cancelling agreement.";
            }
            $stmt->close();
        }
    }

    $conn->close();
    header("Location: tenant-agreements.php");
    exit();
} else {
    header("Location: tenant-agreements.php");
    exit();
}
?>
