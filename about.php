<?php include 'db_connect.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Bike Rental</title>
    <link rel="stylesheet" href="assets/css/abouut.css"> <!-- Linking your custom CSS -->
</head>
<body>

<header>
    <nav>
        <div class="logo">  Bike Rental</div>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="bikes.php">Bikes</a></li>
            <li><a href="about.php" class="active">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="my_booking.php">my bookings</a></li>
        </ul>
    </nav>
</header>

<section class="about-section">
    <h2>About Us</h2>
    <p>Welcome to Bike Rental, your trusted partner for renting high-quality bikes at affordable prices. 
       We offer a wide range of bikes suitable for city rides, adventure trips, and daily commutes.</p>

    <div class="features">
        <div class="feature">
            <img src="assets/images/essy.jpg" alt="Easy Booking">
            <h4>Easy Booking</h4>
            <p>Book your ride in just a few clicks.</p>
        </div>

        <div class="feature">
            <img src="assets/images/price.jpg" alt="Affordable Prices">
            <h4>Affordable Prices</h4>
            <p>Best rental rates in the city.</p>
        </div>

        <div class="feature">
            <img src="assets/images/safe.jpg" alt="Safety First">
            <h4>Safety First</h4>
            <p>Well-maintained bikes for a smooth ride.</p>
        </div>
    </div>
</section>

    <script src="assets/js/about.js"></script>
    <?php include 'footer.php'; ?>

</body>
</html>
