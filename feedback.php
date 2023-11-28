<?php
include('config.php');
session_start();

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: index.php");
    exit;
}

// Get the hotel ID from the query parameter
$hotel_id = $_GET["hotel_id"];

// Fetch and display previous feedback for the hotel
$feedbackSql = "SELECT * FROM feedback WHERE hotel_id = $hotel_id";
$feedbackResult = mysqli_query($conn, $feedbackSql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Hotel Feedback</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>


<div class="container">

    <h3>Add New Feedback</h3>
    <form method="POST" action="add_feedback.php">
        <input type="hidden" name="hotel_id" value="<?php echo $hotel_id; ?>">
        <div class="form-group">
            <label for="user_name">Your Name:</label>
            <input type="text" class="form-control" id="user_name" name="user_name" required>
        </div>
        <div class="form-group">
            <label for="feedback_text">Your Feedback:</label>
            <textarea class="form-control" id="feedback_text" name="feedback_text" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit Feedback</button>
    </form>
</div>

<div class="container mt-5">

    <h3>Previous Feedback</h3>
    <?php
if (mysqli_num_rows($feedbackResult) > 0) {
    echo '<div class="row">';
    while ($feedbackRow = mysqli_fetch_assoc($feedbackResult)) {
        echo '<div class="col-md-4">';
        echo '<div class="card mb-3">';
        echo '<div class="card-body">';
        echo '<p><strong>User:</strong> ' . $feedbackRow['user_name'] . '</p>';
        echo '<p><strong>Feedback:</strong> ' . $feedbackRow['feedback_text'] . '</p>';
        echo '<p><strong>Sentiment Score:</strong> ' . $feedbackRow['sentiment_score'] . '</p>';
        echo '<p><strong>Sentiment Label:</strong> ' . $feedbackRow['sentiment_label'] . '</p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    echo '</div>'; // Closing the row
} else {
    echo '<p>No feedback found for this hotel.</p>';
}
?>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
