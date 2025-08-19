<?php
include 'db_connect.php'; 

if (!isset($_GET['bike_id'])) {
    die("Bike not found.");
}

$bike_id = intval($_GET['bike_id']);
$query = "SELECT * FROM bikes WHERE id = $bike_id";
$result = mysqli_query($conn, $query);
$bike = mysqli_fetch_assoc($result);

if (!$bike) {
    die("Bike not found.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = 2; // Replace with dynamic user ID if using sessions
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $hours = intval($_POST['hours']);

    // Validate required fields
    if (empty($name) || empty($email) || empty($phone) || empty($address) || empty($hours) || !isset($_FILES['license'])) {
        die("Error: All fields are required.");
    }

    $licenseFile = $_FILES['license'];

    // Validate file type
    $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
    if (!in_array($licenseFile['type'], $allowedTypes)) {
        die("Error: Invalid license file format. Only JPG, PNG, and PDF allowed.");
    }
    
    // Prepare destination path
    $uploadDir = 'uploads/';
    $filename = preg_replace("/[^a-zA-Z0-9\.\-\_]/", "_", $licenseFile['name']); // clean filename
    $licensePath = $uploadDir . time() . "_" . $filename;
    
    // Move file to uploads directory
    if (!move_uploaded_file($licenseFile['tmp_name'], $licensePath)) {
        die("Error: Failed to upload license.");
    }
    

    $start_time = date('Y-m-d H:i:s'); 
    $end_time = date('Y-m-d H:i:s', strtotime($start_time . " + $hours hours"));

    $query = "INSERT INTO bookings (user_id, name, email, phone, bike_id, start_time, end_time, status, address, license_file) 
              VALUES ('$user_id', '$name', '$email', '$phone', '$bike_id', '$start_time', '$end_time', 'pending', '$address', '$licensePath')";

    if (mysqli_query($conn, $query)) {
        echo "<script> window.location='payment.php';</script>";
        exit();
    } else {
        die("Database Error: " . mysqli_error($conn)); 
    }
}
?>
