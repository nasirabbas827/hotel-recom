<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Handle hotel addition form submission
if (isset($_POST["submit"])) {
    // Retrieve form data
    $hotelName = $_POST['hotelName'];
    $location = $_POST['location'];
    $contactNumber = $_POST['contactNumber'];
    $numberOfRooms = $_POST['numberOfRooms'];
    $roomTypes = $_POST['roomTypes'];
    $amenities = $_POST['amenities'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Upload and store hotel picture
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["hotelPicture"]["name"]);

    if (move_uploaded_file($_FILES["hotelPicture"]["tmp_name"], $targetFile)) {
        // Insert hotel information into the database
        $sql = "INSERT INTO hotel (hotel_name, location, contact_number, hotel_picture, 
                          number_of_rooms, room_types, amenities, description, price) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssssssd",
            $hotelName,
            $location,
            $contactNumber,
            $targetFile,
            $numberOfRooms,
            $roomTypes,
            $amenities,
            $description,
            $price
        );

        if ($stmt->execute()) {
            echo "Hotel added successfully!";
        } else {
            echo "Error adding the hotel: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo "Error uploading the hotel picture.";
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Hotel</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <?php include('admin_navbar.php'); ?>
    <div class="container mt-5">
        <h1 class="text-center">Add Hotel</h1>
        <div class="row justify-content-center">
            <div class="col-md-4">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="hotelPicture">Hotel Picture:</label>
                        <input type="file" class="form-control-file" name="hotelPicture" accept="image/*" required>
                    </div>
                    <div class="form-group">
                        <label for="hotelName">Hotel Name:</label>
                        <input type="text" class="form-control" name="hotelName" required>
                    </div>
                    <div class="form-group">
                        <label for="location">Location:</label>
                        <input type="text" class="form-control" name="location" required>
                    </div>
                    <div class="form-group">
                        <label for="contactNumber">Contact Number:</label>
                        <input type="text" class="form-control" name="contactNumber" required>
                    </div>
                    <div class="form-group">
                        <label for="numberOfRooms">Number of Rooms:</label>
                        <input type="text" class="form-control" name="numberOfRooms" required>
                    </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="roomTypes">Room Types:</label>
                    <input type="text" class="form-control" name="roomTypes" required>
                </div>
                <div class="form-group">
                    <label for="amenities">Amenities:</label>
                    <input type="text" class="form-control" name="amenities" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea class="form-control" name="description" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="text" class="form-control" name="price" required>
                </div>


            </div>
            <div class="form-group text-center col-12">
                <button type="submit" class="btn btn-primary" name="submit">Add Hotel</button>
            </div>
            </form>
        </div>
    </div>
</body>

</html>