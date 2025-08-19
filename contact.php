<?php include 'db_connect.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Bike Rental</title>
    <link rel="stylesheet" href="assets/css/contactus.css">
</head>
<body>

<header>
    <nav>
        <div class="logo">Bike Rental</div>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="bikes.php">bikes</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php" class="active">Contact</a></li>
            <li><a href="my_booking.php">my bookings</a></li>
            
        </ul>
    </nav>
</header>

<section class="contact-section">
    <div class="container">
        <h2>Contact Us</h2>
        <p>Have questions or need assistance? Feel free to reach out to us.</p>

        <div class="contact-details">
            <div class="contact-info">
                <h3>Our Office</h3>
                <p><strong>Address:</strong> 123 Bike Rental Street, City, Country</p>
                <p><strong>Phone:</strong> +123 456 7890</p>
                <p><strong>Email:</strong> support@bikerental.com</p>
            </div>

            <div class="contact-form">
                <h3>Send Us a Message</h3>
                <form action="process_contact.php" method="POST">
                    <label>Name:</label>
                    <input type="text" name="name" >
                    
                    <label>Email:</label>
                    <input type="email" name="email" >
                    
                    <label>Message:</label>
                    <textarea name="message" rows="5" ></textarea>
                    
                    <button type="submit" class="btn">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>


    <script src="assets/js/contact.js"></script>

    <?php include 'footer.php'; ?>

</body>
</html>
