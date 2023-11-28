<?php
include('config.php');

session_start();

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Check if booking_id is provided in the URL
if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];

    // Delete the booking from the database
    $deleteSql = "DELETE FROM bookings WHERE booking_id = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $booking_id);

    if ($stmt->execute()) {
        echo "Booking deleted successfully!";
    } else {
        echo "Error deleting the booking: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Booking ID not provided.";
}

$conn->close();
?>
