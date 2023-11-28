<?php
include('config.php');

session_start();

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: index.php");
    exit;
}

// Get the user ID from the session
$user_id = $_SESSION["id"];

// Fetch user's bookings with hotel details
$bookingsSql = "SELECT b.booking_id, b.hotel_id, b.check_in_date, b.check_out_date, b.adults, b.childs, b.rooms, h.hotel_name, h.location
                FROM bookings b
                INNER JOIN hotel h ON b.hotel_id = h.hotel_id
                WHERE b.user_id = ?";
$stmt = $conn->prepare($bookingsSql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Bookings</title>
    <!-- Add your Bootstrap CSS link here -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Add your custom CSS link here -->
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

<?php include('navbar.php'); ?>

<div class="container mt-5">
    <?php
    // Check if there are bookings
    if ($result->num_rows > 0) {
        echo '<h2>Your Bookings</h2>';
        echo '<table class="table table-bordered">';
        echo '<tr><th>Booking ID</th><th>Hotel Name</th><th>Location</th><th>Check-In Date</th><th>Check-Out Date</th><th>Adults</th><th>Childs</th><th>Rooms</th><th>Action</th></tr>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['booking_id'] . '</td>';
            echo '<td>' . $row['hotel_name'] . '</td>';
            echo '<td>' . $row['location'] . '</td>';
            echo '<td>' . $row['check_in_date'] . '</td>';
            echo '<td>' . $row['check_out_date'] . '</td>';
            echo '<td>' . $row['adults'] . '</td>';
            echo '<td>' . $row['childs'] . '</td>';
            echo '<td>' . $row['rooms'] . '</td>';
            echo '<td><a href="delete_booking.php?booking_id=' . $row['booking_id'] . '" class="btn btn-danger">Delete</a></td>';
            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo '<p class="alert alert-info">No bookings found.</p>';
    }

    $stmt->close();
    $conn->close();
    ?>
</div>

<!-- Add your Bootstrap JavaScript and custom JavaScript links here -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="script.js"></script>
</body>
</html>

