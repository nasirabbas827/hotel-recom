<!-- search.php -->
<?php
include('config.php');

if (isset($_GET['location'])) {
    $searchLocation = mysqli_real_escape_string($conn, $_GET['location']);

    // Fetch hotels based on the location
    $searchSql = "SELECT * FROM hotel WHERE location LIKE '%$searchLocation%'";
    $searchResult = mysqli_query($conn, $searchSql);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Hotel Recommendations - Search Results</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <!-- Add your additional styles if needed -->
</head>

<body>

    <?php
    include('navbar.php');
    ?>

    <div class="container mt-5">
        <!-- Display Search Results -->
        <h3 class="mb-4">Search Results</h3>
        <div class="row">
            <?php
            if (isset($searchResult) && mysqli_num_rows($searchResult) > 0) {
                while ($hotelRow = mysqli_fetch_assoc($searchResult)) {
                    // Display search result hotel cards
                    echo '<div class="col-md-4">';
                    echo '<div class="card mb-4" style="height: 100%;">';
                    echo '<img src="./admin/' . $hotelRow['hotel_picture'] . '" class="card-img-top" alt="' . $hotelRow['hotel_name'] . '" style="height: 200px; width: 100%; object-fit: cover;">';
                    echo '<div class="card-header">' . $hotelRow['hotel_name'] . '</div>';
                    echo '<div class="card-body">';
                    echo '<p><strong>Location:</strong> ' . $hotelRow['location'] . '</p>';
                    // Add other hotel details as needed
                    echo '<a href="login.php" class="btn btn-primary">Book Now</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p class="col-12">No hotels found for the specified location.</p>';
            }
            ?>
        </div>
    </div>

    <!-- Your existing additional content and footer sections -->

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Add your additional scripts if needed -->

</body>

</html>
