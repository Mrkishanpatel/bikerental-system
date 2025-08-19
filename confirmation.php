<?php
include 'db_connect.php';

if (!isset($_GET['booking_id']) || empty($_GET['booking_id'])) {
    die("Error: Booking ID is missing.");
}

$booking_id = mysqli_real_escape_string($conn, $_GET['booking_id']);

$booking_query = "SELECT b.*, u.username FROM bookings b 
                  JOIN users u ON b.user_id = u.id 
                  WHERE b.id = '$booking_id'";


$booking_result = mysqli_query($conn, $booking_query);

if (!$booking_result || mysqli_num_rows($booking_result) == 0) {
    die("Error: Booking not found.");
}


$booking = mysqli_fetch_assoc($booking_result);
$user_id = $booking['user_id'];
$bike_id = $booking['bike_id'];
$payment_method = $booking['payment_method'];
$bike_name = $booking['bike_name'];
$user_name = $booking['name'];
$start_time = new DateTime($booking['start_time']);
$end_time = new DateTime($booking['end_time']);
$interval = $start_time->diff($end_time);
$hours = $interval->h + ($interval->days * 24);

// Fetch bike rental rate from bikes table
$bike_query = "SELECT price_per_hour FROM bikes WHERE id = '$bike_id'";
$bike_result = mysqli_query($conn, $bike_query);

if (!$bike_result || mysqli_num_rows($bike_result) == 0) {
    die("Error: Bike not found.");
}

$bike = mysqli_fetch_assoc($bike_result);
$price_per_hour = $bike['price_per_hour'];

// Calculate costs
$rental_cost = $hours * $price_per_hour;
$delivery_fee = 50.00; // Fixed delivery fee
$service_fee = 100.00; // Fixed service fee
$total_amount = $rental_cost + $delivery_fee + $service_fee;

// Insert payment details into payments table
$insert_payment = "INSERT INTO payments (user_id, booking_id, amount, payment_method, payment_status,
                                         payment_date, bike_name, payment_id, name, created_at)
                   VALUES ('$user_id', '$booking_id', '$total_amount', '$payment_method', 'Pending',
                           NOW(), '$bike_name', UUID(), '$user_name', NOW())";

if (mysqli_query($conn, $insert_payment)) {
    // Update booking status to confirmed
    $update_booking = "UPDATE bookings SET status = 'confirmed' WHERE id = '$booking_id'";
    
    if (mysqli_query($conn, $update_booking)) {
        echo "<script>alert('Booking confirmed successfully!'); window.location='my_booking.php';</script>";
    } else {
        die("Error updating booking status: " . mysqli_error($conn));
    }
} else {
    die("Error inserting payment details: " . mysqli_error($conn));
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <link rel="stylesheet" href="assets/css/pay.css">
</head>
<body>

<nav>
    <h1>Bike Rental</h1>
</nav>

<section class="confirmation-section">
    <h2>Confirm Your Booking</h2>
    
    <div class="booking-summary">
        <div class="booking-image">
            <img src="assets/images/<?php echo htmlspecialchars($booking['image'] ?: 'default.jpg'); ?>" 
                 alt="<?php echo htmlspecialchars($booking['bike_name']); ?>">
        </div>
        <div class="booking-details">
            <h3><?php echo htmlspecialchars($booking['bike_name'] . ' (' . $booking['bike_type'] . ')'); ?></h3>
            <p><strong>Booking ID:</strong> <?php echo htmlspecialchars($booking['id']); ?></p>
            <p><strong>Rental Period:</strong> <?php echo $hours; ?> hours</p>
            <p><strong>Start Time:</strong> <?php echo htmlspecialchars($start_time->format('M d, Y H:i')); ?></p>
            <p><strong>End Time:</strong> <?php echo htmlspecialchars($end_time->format('M d, Y H:i')); ?></p>
            <p><strong>Delivery Address:</strong> <?php echo htmlspecialchars($booking['address']); ?></p>
        </div>
    </div>
    
    <div class="price-details">
    <h3>Price Details</h3>
    <div class="price-row">
        <span>Base Price (₹<?php echo htmlspecialchars($booking['price_per_hour']); ?>/hour × <?php echo $hours; ?> hours)</span>
        <span>₹<?php echo number_format($base_price, 2); ?></span>
    </div>
    <div class="price-row">
        <span>Delivery Fee</span>
        <span>₹<?php echo number_format($delivery_fee, 2); ?></span>
    </div>
    <div class="price-row">
        <span>Service Fee</span>
        <span>₹<?php echo number_format($service_fee, 2); ?></span>
    </div>
    <div class="price-row total-price">
        <span>Total Amount</span>
        <span>₹<?php echo number_format($total_amount, 2); ?></span>
    </div>
    <div class="price-row">
        <span>Payment Method</span>
        <span><?php echo htmlspecialchars($booking['payment_method']); ?></span>
    </div>
</div>

    <form method="POST" onsubmit="return confirmBooking();">
        <input type="hidden" name="payment_method" value="<?php echo htmlspecialchars($booking['payment_method']); ?>">
        <button type="submit" class="btn">Confirm Booking</button>
    </form>
    
    <button class="btn cancel-btn" onclick="cancelBooking();">Cancel</button>
</section>

<script>
function confirmBooking() {
    return confirm("Are you sure you want to confirm this booking?");
}

function cancelBooking() {
    alert("Booking cancelled!");
    window.location = "home.php"; // Redirect to homepage
}
</script>

<style>
    .btn {
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        border: none;
        cursor: pointer;
        font-size: 16px;
    }
    .btn:hover {
        background-color: #0056b3;
    }
    .cancel-btn {
        background-color: red;
    }
</style>

<?php include 'footer.php'; ?>
</body>
</html>

