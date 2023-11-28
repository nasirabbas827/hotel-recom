<?php
include('config.php');

session_start();

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Fetch all bookings with user and hotel details
$bookingsSql = "SELECT b.booking_id, b.user_id, b.hotel_id, b.check_in_date, b.check_out_date, b.adults, b.childs, b.rooms, u.username, h.hotel_name, h.location
                FROM bookings b
                INNER JOIN users u ON b.user_id = u.id
                INNER JOIN hotel h ON b.hotel_id = h.hotel_id";
$result = mysqli_query($conn, $bookingsSql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Bookings</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Your custom CSS -->
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <?php
 

    // Check if there are bookings
    if (mysqli_num_rows($result) > 0) {
        echo '<h2>All Bookings</h2>';
        echo '<table class="table table-bordered">';
        echo '<tr><th>Booking ID</th><th>User</th><th>Hotel Name</th><th>Location</th><th>Check-In Date</th><th>Check-Out Date</th><th>Adults</th><th>Childs</th><th>Rooms</th><th>Action</th></tr>';

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . $row['booking_id'] . '</td>';
            echo '<td>' . $row['username'] . '</td>';
            echo '<td>' . $row['hotel_name'] . '</td>';
            echo '<td>' . $row['location'] . '</td>';
            echo '<td>' . $row['check_in_date'] . '</td>';
            echo '<td>' . $row['check_out_date'] . '</td>';
            echo '<td>' . $row['adults'] . '</td>';
            echo '<td>' . $row['childs'] . '</td>';
            echo '<td>' . $row['rooms'] . '</td>';
            echo '<td><a class="btn btn-danger"  href="admin_delete_booking.php?booking_id=' . $row['booking_id'] . '">Delete</a></td>';
            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo '<p>No bookings found.</p>';
    }

    mysqli_close($conn);
    ?>
</div>

<!-- Bootstrap JS and your custom JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Your custom JavaScript -->
<script src="script.js"></script>
</body>
</html>
