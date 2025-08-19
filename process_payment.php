<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['booking_id']) || empty($_POST['booking_id'])) {
        die("Error: Booking ID is missing.");
    }

    $booking_id = mysqli_real_escape_string($conn, $_POST['booking_id']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);

    // Redirect to the appropriate payment page
    if ($payment_method == "cash") {
        header("Location: cash.php?booking_id=$booking_id");
        exit();
    } elseif ($payment_method == "credit_card") {
        header("Location: credit.php?booking_id=$booking_id");
        exit();
    } else {
        die("Invalid payment method.");
    }
}
?>
