<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Error: Invalid request.");
}

if (!isset($_POST['booking_id']) || empty($_POST['booking_id'])) {
    die("Error: Booking ID is missing.");
}

$booking_id = mysqli_real_escape_string($conn, $_POST['booking_id']);

// Check if payment_method exists before updating
$update_query = "UPDATE bookings SET status = 'confirmed' WHERE id = '$booking_id'";

if (mysqli_query($conn, $update_query)) {
    echo "<script>alert('Booking confirmed! You will pay cash on delivery.'); window.location='confirmation.php?booking_id=$booking_id';</script>";
} else {
    die("Payment Error: " . mysqli_error($conn));
}
?>

<script>
    function updateFormAction(actionUrl) {
    var bookingId = document.querySelector('input[name="booking_id"]').value;
    document.getElementById('paymentForm').action = actionUrl + "?booking_id=" + bookingId;
}

    </script>