<?php
include('config.php');

// Check if the booking_id is provided in the URL
if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];

    // Delete the booking
    $deleteSql = "DELETE FROM bookings WHERE booking_id = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $booking_id);

    if ($stmt->execute()) {
        echo "Booking deleted successfully!";
    } else {
        echo "Error deleting booking: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Booking ID not provided.";
}
?>
