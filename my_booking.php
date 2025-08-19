<?php
include 'db_connect.php';
session_start();

$user_id = $_SESSION['user_id'] ?? 2; 

// Fetch all bookings for the logged-in user
$query = "SELECT b.id AS booking_id, b.start_time, b.end_time, b.status, 
                 bk.bike_name, bk.bike_type, bk.price_per_hour, bk.image,
                 p.amount, p.payment_method, p.payment_status, p.payment_date
          FROM bookings b
          JOIN bikes bk ON b.bike_id = bk.id
          LEFT JOIN payments p ON b.id = p.booking_id
          WHERE b.user_id = ?
          ORDER BY b.id DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Bike Rental</title>
    <link rel="stylesheet" href="assets/css/my_booking.css">
</head>
<body>

<nav>
        <div class="logo">my bookings</div>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="bikes.php" >Bikes</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="my_booking.php" class="active">my bookings</a></li>
        </ul>
    </nav>

<section class="booking-list">
    <h2>Your Bookings</h2>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($booking = $result->fetch_assoc()): ?>
            <div class="booking-card">
                <div class="booking-image">
                    <img src="assets/images/<?php echo htmlspecialchars($booking['image'] ?: 'default.jpg'); ?>" 
                         alt="<?php echo htmlspecialchars($booking['bike_name']); ?>">
                </div>
                <div class="booking-details">
                    <h3><?php echo htmlspecialchars($booking['bike_name'] . ' (' . $booking['bike_type'] . ')'); ?></h3>
                    <p><strong>Booking ID:</strong> <?php echo htmlspecialchars($booking['booking_id']); ?></p>
                    <p><strong>Start Time:</strong> <?php echo date('M d, Y H:i', strtotime($booking['start_time'])); ?></p>
                    <p><strong>End Time:</strong> <?php echo date('M d, Y H:i', strtotime($booking['end_time'])); ?></p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($booking['status']); ?></p>
                </div>
                <div class="payment-details">
                    <h4>Payment Details</h4>
                    <p><strong>Amount Paid:</strong> ₹<?php echo number_format($booking['amount'], 2); ?></p>
                    <p><strong>Payment Method:</strong> <?php echo htmlspecialchars(strtoupper($booking['payment_method'])); ?></p>
                    <p><strong>Payment Status:</strong> <?php echo htmlspecialchars($booking['payment_status']); ?></p>
                    <p><strong>Payment Date:</strong> <?php echo date('M d, Y H:i', strtotime($booking['payment_date'])); ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No bookings found.</p>
    <?php endif; ?>
</section>
<?php include 'footer.php'; ?>

<style>
   * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
}

body {
    background-color: #f9f9f9;
    color: #333;
    line-height: 1.6;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

/* Navigation Styles */
nav {
    background-color: #2c3e50;
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.logo {
    color: white;
    font-size: 24px;
    font-weight: 700;
    letter-spacing: 1px;
}

nav ul {
    display: flex;
    list-style: none;
}

nav ul li {
    margin-left: 25px;
}

nav ul li a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    font-weight: 500;
    padding: 8px 15px;
    border-radius: 20px;
    transition: all 0.3s;
}

nav ul li a:hover {
    color: white;
    background-color: rgba(255, 255, 255, 0.1);
}

nav ul li a.active {
    color: #2c3e50;
    background-color: #f1c40f;
}
.booking-list {
    flex: 1;
    padding: 60px 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.booking-list h2 {
    text-align: center;
    margin-bottom: 40px;
    color: #2c3e50;
    font-size: 32px;
    position: relative;
    padding-bottom: 15px;
}

.booking-list h2:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background: linear-gradient(to right, #3498db, #1abc9c);
}

/* Booking Card */
.booking-card {
    background-color: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
    display: flex;
    flex-wrap: wrap;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.booking-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
}

.booking-image {
    flex: 1;
    min-width: 250px;
    max-width: 300px;
}

.booking-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-right: 1px solid #f0f0f0;
}

.booking-details {
    flex: 2;
    min-width: 300px;
    padding: 20px;
    border-right: 1px solid #f0f0f0;
}

.booking-details h3 {
    margin-bottom: 15px;
    color: #2c3e50;
    font-size: 22px;
}

.booking-details p {
    margin-bottom: 8px;
    font-size: 15px;
}

.payment-details {
    flex: 1;
    min-width: 250px;
    padding: 20px;
    background-color: #f8f9fa;
}

.payment-details h4 {
    margin-bottom: 15px;
    color: #2c3e50;
    font-size: 18px;
    padding-bottom: 8px;
    border-bottom: 2px solid #3498db;
    display: inline-block;
}

.payment-details p {
    margin-bottom: 8px;
    font-size: 15px;
}

/* Status styling */
.booking-details p strong {
    color: #2c3e50;
}

.booking-details p:nth-child(5) strong {
    margin-right: 5px;
}

.booking-details p:nth-child(5) {
    padding: 5px 0;
    font-weight: 600;
}

/* Footer styles (assuming you have a footer.php) */
footer {
    background-color: #2c3e50;
    color: white;
    padding: 40px 20px;
    text-align: center;
    margin-top: auto;
}

footer .footer-content {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
}

footer .footer-section {
    flex: 1;
    min-width: 250px;
    margin-bottom: 20px;
}

footer h3 {
    margin-bottom: 15px;
    font-size: 18px;
    color: #f1c40f;
}

footer ul {
    list-style: none;
}

footer ul li {
    margin-bottom: 8px;
}

footer ul li a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: color 0.3s;
}

footer ul li a:hover {
    color: #f1c40f;
}

footer .copyright {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    width: 100%;
    font-size: 14px;
    color: rgba(255, 255, 255, 0.6);
}

/* Empty state */
.booking-list > p {
    text-align: center;
    font-size: 18px;
    color: #7f8c8d;
    padding: 40px;
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

/* Responsive styles */
@media (max-width: 992px) {
    .booking-card {
        flex-direction: column;
    }
    
    .booking-image {
        max-width: 100%;
        height: 200px;
    }
    
    .booking-image img {
        border-right: none;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .booking-details {
        border-right: none;
        border-bottom: 1px solid #f0f0f0;
    }
}

@media (max-width: 768px) {
    nav {
        padding: 15px;
    }
    
    .booking-list {
        padding: 40px 15px;
    }
    
    .booking-list h2 {
        font-size: 26px;
    }
}

@media (max-width: 480px) {
    .booking-list {
        padding: 30px 10px;
    }
    
    .booking-details, 
    .payment-details {
        padding: 15px;
    }
    
    .booking-details h3 {
        font-size: 18px;
    }
    
    .booking-details p,
    .payment-details p {
        font-size: 14px;
    }
}

/* Status colors */
.booking-details p:nth-child(5) {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 5px;
}

.booking-details p:nth-child(5)[status="confirmed"] {
    background-color: rgba(39, 174, 96, 0.1);
    color: #27ae60;
}

.booking-details p:nth-child(5)[status="pending"] {
    background-color: rgba(241, 196, 15, 0.1);
    color: #f39c12;
}

.booking-details p:nth-child(5)[status="cancelled"] {
    background-color: rgba(231, 76, 60, 0.1);
    color: #e74c3c;
}

/* Payment status colors */
.payment-details p:nth-child(3) {
    font-weight: 600;
}

.payment-details p:nth-child(3) span.paid {
    color: #27ae60;
}

.payment-details p:nth-child(3) span.pending {
    color: #f39c12;
}

.payment-details p:nth-child(3) span.failed {
    color: #e74c3c;
}

/* Fix for default images */
.booking-image img[src="assets/images/default.jpg"] {
    background-color: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
}
    </style>
</body>
</html>
