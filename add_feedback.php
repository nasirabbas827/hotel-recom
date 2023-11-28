<?php
include('config.php');

$hotel_id = $_POST["hotel_id"];
$user_name = $_POST["user_name"];
$feedback_text = $_POST["feedback_text"];

// Run the Python script to analyze sentiment
$command = "python sentiment_analysis.py " . escapeshellarg($feedback_text);
$output = shell_exec($command);

// Parse sentiment analysis result
list($sentiment_score, $sentiment_label) = explode(",", $output);

// Insert the feedback and sentiment analysis result into the database
$insertSql = "INSERT INTO feedback (hotel_id, user_name, feedback_text, sentiment_score, sentiment_label)
              VALUES ($hotel_id, '$user_name', '$feedback_text', $sentiment_score, '$sentiment_label')";

if (mysqli_query($conn, $insertSql)) {
    header("location: feedback.php?hotel_id=$hotel_id");
    exit;
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
