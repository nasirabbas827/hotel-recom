<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Initialize variables
$hotel_id = $hotel_name = $location = $contact_number = $number_of_rooms = $room_types = $amenities = $description = $hotel_picture = $price = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the hotel ID from the form
    $hotel_id = $_POST["hotel_id"];

    // Retrieve form data
    $hotel_name = $_POST['hotel_name'];
    $location = $_POST['location'];
    $contact_number = $_POST['contact_number'];
    $number_of_rooms = $_POST['number_of_rooms'];
    $room_types = $_POST['room_types'];
    $amenities = $_POST['amenities'];
    $description = $_POST['description'];
    $price = $_POST['price']; // Add this line to retrieve the price

    // Update hotel information in the database
    $updateSql = "UPDATE hotel SET hotel_name=?, location=?, contact_number=?, number_of_rooms=?, room_types=?, amenities=?, description=?, price=? WHERE hotel_id=?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("sssssssis", $hotel_name, $location, $contact_number, $number_of_rooms, $room_types, $amenities, $description, $price, $hotel_id);

    if ($stmt->execute()) {
        echo "Hotel information updated successfully!";
    } else {
        echo "Error updating hotel information: " . $conn->error;
    }

    $stmt->close();
}

// Retrieve hotel information by ID
if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $hotel_id = $_GET["id"];

    // Query to retrieve hotel information
    $selectSql = "SELECT * FROM hotel WHERE hotel_id=?";
    $stmt = $conn->prepare($selectSql);
    $stmt->bind_param("i", $hotel_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hotel_name = $row['hotel_name'];
        $location = $row['location'];
        $contact_number = $row['contact_number'];
        $number_of_rooms = $row['number_of_rooms'];
        $room_types = $row['room_types'];
        $amenities = $row['amenities'];
        $description = $row['description'];
        $hotel_picture = $row['hotel_picture'];
        $price = $row['price'];
    } else {
        echo "Hotel not found.";
        exit;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Hotel</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">

</head>

<body>
    <?php include('admin_navbar.php'); ?>
    <div class="container mt-5">
        <h1>Edit Hotel</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="hotel_id" value="<?php echo $hotel_id; ?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="hotelName">Hotel Name:</label>
                        <input type="text" class="form-control" name="hotel_name" value="<?php echo $hotel_name; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="location">Location:</label>
                        <input type="text" class="form-control" name="location" value="<?php echo $location; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="contactNumber">Contact Number:</label>
                        <input type="text" class="form-control" name="contact_number" value="<?php echo $contact_number; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="number_of_rooms">Number of Rooms:</label>
                        <input type="text" class="form-control" name="number_of_rooms" value="<?php echo $number_of_rooms; ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="roomTypes">Room Types:</label>
                        <input type="text" class="form-control" name="room_types" value="<?php echo $room_types; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="amenities">Amenities:</label>
                        <input type="text" class="form-control" name="amenities" value="<?php echo $amenities; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="price">Price:</label>
                        <input type="text" class="form-control" name="price" value="<?php echo $price; ?>" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" name="description" rows="4" required><?php echo $description; ?></textarea>
            </div>

            <div class="form-group text-center">
                <button type="submit" class="btn btn-primary" name="submit">Update Hotel</button>
                <a href="manage_hotels.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>

    </div>
</body>

</html>