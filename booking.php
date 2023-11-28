<?php
include('config.php');
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $hotel_id = $_POST['hotel_id'];
    $check_in_date = $_POST['check_in_date'];
    $check_out_date = $_POST['check_out_date'];
    $adults = $_POST['adults'];
    $childs = $_POST['childs'];
    $rooms = $_POST['rooms'];

    // Validate and sanitize the data (Add your validation logic here)

    // Retrieve user_id from the session
    if (isset($_SESSION["id"])) {
        $user_id = $_SESSION["id"];

        // Example: Insert booking information into the database
        $insertSql = "INSERT INTO bookings (user_id, hotel_id, check_in_date, check_out_date, adults, childs, rooms) 
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertSql);
        $stmt->bind_param("iisssss", $user_id, $hotel_id, $check_in_date, $check_out_date, $adults, $childs, $rooms);

        if ($stmt->execute()) {
            echo "Booking successful!";
        } else {
            echo "Error processing booking: " . $conn->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "User ID not found in the session.";
    }
} else {
    // If the form is not submitted, redirect to the home page or display an error message
    header("location: index.php");
    exit;
}
?>
