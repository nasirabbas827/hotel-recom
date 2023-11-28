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

// Handle hotel search
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"])) {
    $searchTerm = $_POST["searchTerm"];

    // Fetch and display search results
    $searchSql = "SELECT * FROM hotel WHERE hotel_name LIKE ? OR location LIKE ?";
    $stmt = $conn->prepare($searchSql);
    $searchTerm = "$searchTerm"; // Add wildcard for partial matching
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $searchResult = $stmt->get_result();

    // Insert search history
    $insertSearchSql = "INSERT INTO search_history (user_id, search_term) VALUES (?, ?)";
    $insertStmt = $conn->prepare($insertSearchSql);
    $insertStmt->bind_param("is", $user_id, $searchTerm);
    $insertStmt->execute();
    $insertStmt->close();
} else {
    // Fetch and display all hotels
    $hotelSql = "SELECT * FROM hotel";
    $hotelResult = mysqli_query($conn, $hotelSql);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5 mb-5">

    <h3>Our Hotels</h3>

    <!-- Hotel Search Form -->
    <form action="" method="post">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Search by name or location" name="searchTerm">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit" name="search">Search</button>
            </div>
        </div>
    </form>

    <div class="row mb-4">
    <?php
    // Display search results or all hotels
    $displayResult = isset($searchResult) ? $searchResult : $hotelResult;

    if (mysqli_num_rows($displayResult) > 0) {
        while ($hotelRow = mysqli_fetch_assoc($displayResult)) {
            // Display hotel cards
            echo '<div class="col-md-4 mt-3 ">';
            echo '<div class="card mb-4" style="height: 100%;">'; // Set fixed height for the card
            echo '<img src="./admin/' . $hotelRow['hotel_picture'] . '" class="card-img-top" alt="' . $hotelRow['hotel_name'] . '" style="height: 200px; width: 100%; object-fit: cover;">'; // Set height and width for the picture
            echo '<div class="card-header">' . $hotelRow['hotel_name'] . '</div>';
            echo '<div class="card-body">';
            echo '<p><strong>Location:</strong> ' . $hotelRow['location'] . '</p>';
            echo '<p><strong>Contact Number:</strong> ' . $hotelRow['contact_number'] . '</p>';
            echo '<p><strong>Number of Rooms:</strong> ' . $hotelRow['number_of_rooms'] . '</p>';
            echo '<p><strong>Room Types:</strong> ' . $hotelRow['room_types'] . '</p>';
            echo '<p><strong>Amenities:</strong> ' . $hotelRow['amenities'] . '</p>';
            echo '<p><strong>Description:</strong> ' . $hotelRow['description'] . '</p>';
            echo '<p><strong>Price: Pkr </strong> ' . $hotelRow['price'] . '</p>';

            // Add "Book Now" button
            echo '<a href="process_booking.php?hotel_id=' . $hotelRow['hotel_id'] . '" class="btn btn-primary">Book Now</a>';

            // Add "Feedback" button
            echo '<a href="feedback.php?hotel_id=' . $hotelRow['hotel_id'] . '" class="ml-2 btn btn-secondary">Feedback</a>';

            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<p>No hotels found.</p>';
    }
    ?>
</div>

</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
