<?php
include 'db_connect.php';

// Get the latest booking for the current user
$user_id = 2; // Using the hardcoded user_id from the booking page
$query = "SELECT b.*, bk.bike_name, bk.bike_type, bk.price_per_hour, bk.image 
          FROM bookings b 
          JOIN bikes bk ON b.bike_id = bk.id 
          WHERE b.user_id = $user_id 
          ORDER BY b.id DESC 
          LIMIT 1";

$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("No booking found. Please try again.");
}

$booking = mysqli_fetch_assoc($result);

// Calculate rental duration and total price
$start_time = new DateTime($booking['start_time']);
$end_time = new DateTime($booking['end_time']);
$duration = $start_time->diff($end_time);
$hours = $duration->h + ($duration->days * 24);
$totalPrice = $hours * $booking['price_per_hour'];

// Process payment
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    
    // Update booking status to paid
    $booking_id = $booking['id'];
    $update_query = "UPDATE bookings SET status = 'confirmed', payment_method = '$payment_method' WHERE id = $booking_id";
    
    // Insert payment record in payments table
    $totalAmount = $totalPrice + 150;
    $insert_payment_query = "INSERT INTO payments (booking_id, amount, payment_method, payment_status, transaction_date) 
                         VALUES ('$booking_id', '$totalAmount', '$payment_method', 'pending', NOW())";
    
    if (mysqli_query($conn, $update_query) && mysqli_query($conn, $insert_payment_query)) {
        // Redirect to confirmation page
        echo "<script>alert('Payment successful!'); window.location='confirmation.php?booking_id=$booking_id';</script>";
        exit();
    } else {
        die("Payment Error: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Bike Rental</title>
    <link rel="stylesheet" href="assets/css/payment.css">
    
</head>
<body>

<nav>
    <h1>Bike Rental</h1>
</nav>

<section class="payment-section">
    <h2>Complete Your Payment</h2>
    
    <div class="booking-summary">
        <div class="booking-image">
            <img src="assets/images/<?php echo htmlspecialchars($booking['image'] ?: 'default.png'); ?>"
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
            <span>₹<?php echo number_format($totalPrice, 2); ?></span>
        </div>
        <div class="price-row">
            <span>Delivery Fee</span>
            <span>₹50.00</span>
        </div>
        <div class="price-row">
            <span>Service Fee</span>
            <span>₹100.00</span>
        </div>
        <div class="price-row total-price">
            <span>Total Amount</span>
            <span>₹<?php echo number_format($totalPrice + 150, 2); ?></span>
        </div>
    </div>
    <form id="paymentForm" method="POST">
    <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
    <input type="hidden" name="total_amount" value="<?php echo $totalPrice + 150; ?>">
    
    <div class="payment-methods">
        <h3>Select Payment Method</h3>

        <label class="payment-option">
            <img src="assets/images/upi.jpg" alt="UPI Payment" class="payment-logo">
            <input type="radio" name="payment_method" value="upi" required>
            <strong>UPI Payment</strong>
            <div>Pay using Razorpay or other UPI apps</div>
        </label>

        <label class="payment-option">
            <img src="assets/images/cash.jpg" alt="Cash on Delivery" class="payment-logo">
            <input type="radio" name="payment_method" value="cash" required>
            <strong>Cash on Delivery</strong>
            <div>Pay cash when the bike is delivered</div>
        </label>
    </div>

    <button type="submit" class="btn">Proceed to Payment</button>
</form>

<script>
document.getElementById('paymentForm').addEventListener('submit', function(e) {
    var paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
    
    if (paymentMethod === 'upi') {
        this.action = 'upi.php';
    } else if (paymentMethod === 'cash') {
        this.action = 'cash.php';
    }
});
</script>
</section>
<style>
    .payment-logo {
    width: 40px; /* Adjust the size as needed */
    height: auto;
    margin-top: 5px;
    display: block;
}

    </style>
    
<?php include 'footer.php'; ?>
</body>
</html>