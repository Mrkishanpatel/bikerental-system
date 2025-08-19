<?php
$servername = "localhost";
$username = "root";     // Default MySQL username in XAMPP
$password = "";         // Default MySQL password in XAMPP (empty)
$database = "bike_rental"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(" Connection failed: " . $conn->connect_error);
}
?>

