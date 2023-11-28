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

// Fetch user's bookings
$bookingsSql = "SELECT * FROM bookings WHERE user_id = ? ORDER BY booking_date DESC LIMIT 1";
$stmt = $conn->prepare($bookingsSql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if there are bookings
if ($result->num_rows > 0) {
    // Fetch details of the latest booking
    $latestBooking = $result->fetch_assoc();
    $hotel_id = $latestBooking['hotel_id'];

    // Fetch details of the latest hotel booking
    $hotelSql = "SELECT * FROM hotel WHERE hotel_id = ?";
    $stmt = $conn->prepare($hotelSql);
    $stmt->bind_param("i", $hotel_id);
    $stmt->execute();
    $hotelResult = $stmt->get_result();

    if ($hotelRow = $hotelResult->fetch_assoc()) {
        $location = $hotelRow['location'];
        $roomTypes = $hotelRow['room_types'];
        $amenities = $hotelRow['amenities'];
        $description = $hotelRow['description'];
        $price = $hotelRow['price'];

        // Search for similar hotels in the same location
        $recommendationSql = "SELECT * FROM hotel 
                              WHERE location = ? 
                              AND (room_types = ? OR amenities = ? OR description = ? OR price = ?) 
                              AND hotel_id != ?";
        $stmt = $conn->prepare($recommendationSql);
        $stmt->bind_param("sssssi", $location, $roomTypes, $amenities, $description, $price, $hotel_id);
        $stmt->execute();
        $recommendationResult = $stmt->get_result();

    }
} 
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recommended Hotels</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Add your CSS links here -->
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <?php  
        include('navbar.php');
    ?>

<div class="container mt-5">
        <?php
        if (isset($recommendationResult) && $recommendationResult->num_rows > 0) {
            echo '<h2>Recommended Hotels</h2>';
            echo '<div class="row">'; // Start row
            
            while ($row = $recommendationResult->fetch_assoc()) {
                echo '<div class="col-md-4 mb-4">'; // Set column width for medium-sized screens
                echo '<div class="card h-100">'; // Set height to make all cards same height
                echo '<div class="card-body">';
                echo '<img src="./admin/' . $row['hotel_picture'] . '" class="card-img-top" alt="' . $row['hotel_name'] . '" style="height: 200px; width: 100%; object-fit: cover;">'; // Set height and width for the picture
                echo '<h5 class="card-title mt-2">' . $row['hotel_name'] . '</h5>';
                echo '<p class="card-text"><strong>Location:</strong> ' . $row['location'] . '</p>';
                echo '<p class="card-text"><strong>Room Types:</strong> ' . $row['room_types'] . '</p>';
                echo '<p class="card-text"><strong>Amenities:</strong> ' . $row['amenities'] . '</p>';
                echo '<p class="card-text"><strong>Description:</strong> ' . $row['description'] . '</p>';
                echo '<p class="card-text"><strong>Price:</strong> ' . $row['price'] . '</p>';
                echo '<a href="process_booking.php?hotel_id=' . $row['hotel_id'] . '" class="btn btn-primary">Book Now</a>';
                echo '<a href="feedback.php?hotel_id=' . $row['hotel_id'] . '" class="ml-2 btn btn-secondary">Feedback</a>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }

            echo '</div>'; // End row
        } else {
            echo '<p>No recommendations found.</p>';
        }
        ?>
    </div>

    <!-- Add Bootstrap JS and Popper.js scripts (if needed) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Add your JavaScript links here -->
    <script src="script.js"></script>
</body>
</html>
