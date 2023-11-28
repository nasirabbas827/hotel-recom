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

// Initialize variables
$hotelRow = $feedbackResult = [];

// Retrieve hotel ID from the URL
if (isset($_GET['hotel_id'])) {
    $hotel_id = $_GET['hotel_id'];

    // Fetch hotel details
    $hotelSql = "SELECT * FROM hotel WHERE hotel_id = ?";
    $stmt = $conn->prepare($hotelSql);
    $stmt->bind_param("i", $hotel_id);
    $stmt->execute();
    $hotelResult = $stmt->get_result();

    if ($hotelRow = $hotelResult->fetch_assoc()) {
        // Fetch feedback for the hotel
        $feedbackSql = "SELECT * FROM feedback WHERE hotel_id = ?";
        $stmt = $conn->prepare($feedbackSql);
        $stmt->bind_param("i", $hotel_id);
        $stmt->execute();
        $feedbackResult = $stmt->get_result();
    } else {
        echo "Hotel not found.";
        exit;
    }
} else {
    echo "Hotel ID not provided.";
    exit;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Details</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h3><?php echo $hotelRow['hotel_name']; ?></h3>
    <div class="row">
        <div class="col-md-6">
            <img src="./admin/<?php echo $hotelRow['hotel_picture']; ?>" class="img-fluid" height="400px" width="400px" alt="<?php echo $hotelRow['hotel_name']; ?>">
        </div>
        <div class="col-md-6">
            <p><strong>Location:</strong> <?php echo $hotelRow['location']; ?></p>
            <p><strong>Contact Number:</strong> <?php echo $hotelRow['contact_number']; ?></p>
            <p><strong>Number of Rooms:</strong> <?php echo $hotelRow['number_of_rooms']; ?></p>
            <p><strong>Room Types:</strong> <?php echo $hotelRow['room_types']; ?></p>
            <p><strong>Amenities:</strong> <?php echo $hotelRow['amenities']; ?></p>
            <p><strong>Description:</strong> <?php echo $hotelRow['description']; ?></p>
            <p><strong>Price: Pkr </strong> <?php echo $hotelRow['price']; ?></p>
            <a href="feedback.php?hotel_id=<?php echo $hotelRow['hotel_id']; ?>" class="btn btn-secondary">View Feedback</a>
        </div>
    </div>
</div>



<div class="container mt-5">
    <h3>Book Now</h3>
    <form action="booking.php" method="post">
        <input type="hidden" name="hotel_id" value="<?php echo $hotelRow['hotel_id']; ?>">
        <div class="form-group">
            <label for="check_in_date">Check-In Date:</label>
            <input type="date" class="form-control" name="check_in_date" required>
        </div>
        <div class="form-group">
            <label for="check_out_date">Check-Out Date:</label>
            <input type="date" class="form-control" name="check_out_date" required>
        </div>
        <div class="form-group">
            <label for="adults">Adults:</label>
            <input type="number" class="form-control" name="adults" required>
        </div>
        <div class="form-group">
            <label for="childs">Childs:</label>
            <input type="number" class="form-control" name="childs" required>
        </div>
        <div class="form-group">
            <label for="rooms">Rooms:</label>
            <input type="number" class="form-control" name="rooms" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit Booking</button>
    </form>
</div>
<div class="container mt-5">
    <h3>Feedback</h3>
    <div class="row">
        <?php if ($feedbackResult->num_rows > 0): ?>
            <?php while ($feedbackRow = $feedbackResult->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-body">
                            <p><strong>User:</strong> <?php echo $feedbackRow['user_name']; ?></p>
                            <p><strong>Feedback Text:</strong> <?php echo $feedbackRow['feedback_text']; ?></p>
                            <p><strong>Sentiment Score:</strong> <?php echo $feedbackRow['sentiment_score']; ?></p>
                            <p><strong>Sentiment Label:</strong> <?php echo $feedbackRow['sentiment_label']; ?></p>
                            <p><strong>Created At:</strong> <?php echo $feedbackRow['created_at']; ?></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-md-12">
                <p>No feedback available for this hotel.</p>
            </div>
        <?php endif; ?>
    </div>
</div>


<!-- Bootstrap JS and your custom JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Your custom JavaScript -->
<script src="script.js"></script>
</body>
</html>
