<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Fetch feedbacks with hotel names
$feedbackSql = "SELECT f.*, h.hotel_name FROM feedback f
               INNER JOIN hotel h ON f.hotel_id = h.hotel_id";
$feedbackResult = mysqli_query($conn, $feedbackSql);

// Function to delete feedback by ID
function deleteFeedback($feedback_id) {
    global $conn;
    $deleteSql = "DELETE FROM feedback WHERE feedback_id = $feedback_id";
    mysqli_query($conn, $deleteSql);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if a delete button was clicked
    if (isset($_POST["delete_feedback"])) {
        $feedback_id_to_delete = $_POST["delete_feedback"];
        deleteFeedback($feedback_id_to_delete);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Feedback Management</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h3>Feedback Management</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Hotel Name</th>
                <th>User</th>
                <th>Feedback</th>
                <th>Sentiment Score</th>
                <th>Sentiment Label</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($feedbackResult) > 0) {
                while ($feedbackRow = mysqli_fetch_assoc($feedbackResult)) {
                    echo '<tr>';
                    echo '<td>' . $feedbackRow['hotel_name'] . '</td>';
                    echo '<td>' . $feedbackRow['user_name'] . '</td>';
                    echo '<td>' . $feedbackRow['feedback_text'] . '</td>';
                    echo '<td>' . $feedbackRow['sentiment_score'] . '</td>';
                    echo '<td>' . $feedbackRow['sentiment_label'] . '</td>';
                    echo '<td>
                            <form method="POST" action="">
                                <input type="hidden" name="delete_feedback" value="' . $feedbackRow['feedback_id'] . '">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                          </td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="6">No feedbacks found.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
