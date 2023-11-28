<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Handle hotel deletion
if (isset($_GET["delete"]) && is_numeric($_GET["delete"])) {
    $hotelId = $_GET["delete"];

    // Database connection is assumed to be in the config.php file
    $deleteSql = "DELETE FROM hotel WHERE hotel_id = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $hotelId);

    if ($stmt->execute()) {
        echo "Hotel deleted successfully!";
    } else {
        echo "Error deleting the hotel: " . $conn->error;
    }

    $stmt->close();
}

// Retrieve all hotels
$selectSql = "SELECT * FROM hotel";
$result = $conn->query($selectSql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Hotel Management</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">

    <!-- Add custom CSS for table style -->
    <style>
        /* Style the table header */
        .table thead th {
            background-color: #343a40;
            color: white;
        }

        /* Add some padding to table cells */
        .table td, .table th {
            padding: 10px;
        }

        /* Style the "Add Hotels" button */
        .add-hotel-btn {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include('admin_navbar.php'); ?>
    <div class="container mt-5">
        <h2>Hotel List</h2>
        <a class="mb-3 btn btn-success float-right add-hotel-btn" href="add_hotel.php">Add Hotels</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Hotel ID</th>
                    <th>Hotel Name</th>
                    <th>Location</th>
                    <th>Contact Number</th>
                    <th>Number of Rooms</th>
                    <th>Room Types</th>
                    <th>Amenities</th>
                    <th>Description</th>
                    <th>Hotel Picture</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["hotel_id"] . "</td>";
                        echo "<td>" . $row["hotel_name"] . "</td>";
                        echo "<td>" . $row["location"] . "</td>";
                        echo "<td>" . $row["contact_number"] . "</td>";
                        echo "<td>" . $row["number_of_rooms"] . "</td>";
                        echo "<td>" . $row["room_types"] . "</td>";
                        echo "<td>" . $row["amenities"] . "</td>";
                        echo "<td>" . $row["description"] . "</td>";
                        echo '<td><img src="' . $row["hotel_picture"] . '" alt="Hotel Picture" style="max-width: 100px;"></td>';
                        echo "<td>" . $row["price"] . "</td>";
                        echo '<td><a href="edit_hotel.php?id=' . $row["hotel_id"] . '" class="mb-2 btn btn-primary">Edit</a>  <a href="?delete=' . $row["hotel_id"] . '" class="btn btn-danger">Delete</a></td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='10'>No hotels found.</td></tr>";
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
