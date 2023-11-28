<!DOCTYPE html>
<html>
<head>
    <title>Hotel Recommendations</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <style>
.jumbotron {
            height: 550px;
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('./images/hotel.jpg');
            background-size: cover;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .jumbotron h1 {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .jumbotron p {
            font-size: 1.5rem;
        }
    </style>
</head>
<body>

<?php
include('navbar.php');
?>

<div class="jumbotron text-center">
    <h1>Welcome to Hotel Recommendation System</h1>
    <p>Discover Your Perfect Hotel with Opinion Mining</p>
    <a href="login.php" class="btn btn-primary btn-lg">Login to Explore</a>
</div>
<div class="container mt-5">
        <!-- Search Bar -->
        <form action="search.php" method="get">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Search by location..." name="location">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">Search</button>
                </div>
            </div>
        </form>

    <h3>Our Hotels</h3>
    <div class="row">
    <?php
   include('config.php');
        
   session_start();
   // Fetch and display hotels
   $hotelSql = "SELECT * FROM hotel";
   $hotelResult = mysqli_query($conn, $hotelSql);
    $displayResult = isset($searchResult) ? $searchResult : $hotelResult;

    if (mysqli_num_rows($displayResult) > 0) {
        while ($hotelRow = mysqli_fetch_assoc($displayResult)) {
            // Display hotel cards
            echo '<div class="col-md-4">';
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
            echo '<a href="login.php" class="btn btn-primary">Book Now</a>';

            // Add "Feedback" button
            echo '<a href="login.php" class="ml-2 btn btn-secondary">Feedback</a>';

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
</div> <!-- Closing container for hotels -->
<!-- Additional Content Section -->
<div class="container my-5">
    <h3>About Hotel Recommendation System</h3>
    <p>
        Welcome to the Hotel Recommendation System, where you can find the perfect hotel for your next stay. Our system utilizes Opinion Mining to provide you with valuable insights from other guests' experiences, making your decision easier and more informed.
    </p>
    <p>
        Whether you're looking for a luxurious getaway or a cozy staycation, we've got you covered. Browse through our wide selection of hotels and discover your ideal destination today.
    </p>
</div>



<!-- Footer Section -->
<footer class="bg-dark text-white py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h5>Contact Us</h5>
                <p>Email: contact@example.com</p>
                <p>Phone: +123-456-7890</p>
            </div>
            <div class="col-md-6">
                <h5>Follow Us</h5>
                <a href="#" class="text-white">Facebook</a><br>
                <a href="#" class="text-white">Twitter</a><br>
                <a href="#" class="text-white">Instagram</a>
            </div>
        </div>
    </div>
</footer>



</body>
</html>
