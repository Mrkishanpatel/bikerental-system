<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['bike_id'])) {
    $bike_id = $_GET['bike_id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO bookings (user_id, bike_id, total_cost) VALUES (?, ?, ?)");
    $stmt->bind_param("iid", $user_id, $bike_id, $total_cost);

    $total_cost = 10.00; 

    if ($stmt->execute()) {
        echo "Bike booked successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
