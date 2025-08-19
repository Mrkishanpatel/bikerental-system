<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (!isset($_POST['booking_id']) || !isset($_POST['total_amount'])) {
        die("Error: Missing booking details.");
    }

    $booking_id = intval($_POST['booking_id']);
    $amount = floatval($_POST['total_amount']) * 100; 

    // Razorpay API credentials
    $keyId = "rzp_test_o1aEQZGKk1aKAM"; 
    $keySecret = "EZm4Fqu3lQH4wX0wm6XNe8o9";
    $currency = "INR";

    // Fetch booking details securely
    $query = "SELECT * FROM bookings WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $booking_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result || mysqli_num_rows($result) == 0) {
        die("Error: Invalid Booking ID.");
    }

    $booking = mysqli_fetch_assoc($result);

    // Check if 'bike_name' exists
    $bike_name = isset($booking['bike_name']) ? htmlspecialchars($booking['bike_name']) : "Unknown Bike";

    // Razorpay Order API request
    $url = "https://api.razorpay.com/v1/orders";
    $data = json_encode([
        "amount" => $amount,
        "currency" => $currency,
        "receipt" => "receipt_$booking_id",
        "payment_capture" => 1 
    ]);

    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERPWD, "$keyId:$keySecret");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    
    $order = json_decode($response);

    if ($http_code !== 200 || !$order || empty($order->id)) {
        die("Error: Failed to create Razorpay Order. Please try again.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UPI Payment - Razorpay</title>
    <link rel="stylesheet" href="assets/css/pay.css">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    <div class="container">
    <img src="assets/images/images.jpg" alt="UPI Payment" class="payment-logo">
        <h2>Processing UPI Payment for Booking # <?php echo $booking_id; ?></h2>
        <h3>Bike: <?php echo $bike_name; ?></h3>
        <h3>Total Amount: ₹<?php echo number_format($amount / 100, 2); ?></h3>
        <button id="payBtn">Pay Now</button>
        </div>
</body>

    <script>
        var options = {
            "key": "<?php echo $keyId; ?>",
            "amount": "<?php echo $amount; ?>",
            "currency": "<?php echo $currency; ?>",
            "name": "Bike Rental",
            "description": "Payment for <?php echo $bike_name; ?>",
            "order_id": "<?php echo $order->id; ?>",
            "handler": function (response) {
                alert("Payment Successful! Payment ID: " + response.razorpay_payment_id);
                window.location.href = "confirmation.php?booking_id=<?php echo $booking_id; ?>&payment_id=" + response.razorpay_payment_id;
            },
            "theme": {
                "color": "#3399cc"
            }
        };

        var rzp = new Razorpay(options);
        document.getElementById("payBtn").onclick = function(e) {
            rzp.open();
            e.preventDefault();
        };
    </script>
</body>
</html>