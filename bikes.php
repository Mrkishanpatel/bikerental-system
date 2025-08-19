<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bike Rental</title>
    <link rel="stylesheet" href="assets/css/bik11.css">
</head>
<body>

    <nav>
        <div class="logo">Bike Rental</div>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="bikes.php" class="active">Bikes</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="my_booking.php">my bookings</a></li>
        </ul>
    </nav>

    <section class="bikes-section">
        <h2>All Bikes</h2>
        <div class="bike-list">
            <?php
            include 'db_connect.php'; // Ensure database connection

            $query = "SELECT * FROM bikes"; // Fetch all bikes
            $result = mysqli_query($conn, $query);

            if (!$result) {
                die("SQL Query Failed: " . mysqli_error($conn));
            }

            if (mysqli_num_rows($result) == 0) {
                echo "<p>No bikes available.</p>";
            }

            while ($row = mysqli_fetch_assoc($result)) {
                $image_path = !empty($row['image']) ? 'assets/images/' . $row['image'] : 'assets/images/default.jpg';
                $availability_text = ($row['availability'] == 1) ? "Available" : "Not Available";
                $availability_class = ($row['availability'] == 1) ? "available" : "not-available";

                echo '<div class="bike-card">
                        <img src="' . $image_path . '" alt="' . htmlspecialchars($row['bike_name']) . '" onerror="this.onerror=null;this.src=\'assets/images/default.jpg\';">
                        <h3>' . htmlspecialchars($row['bike_name']) . ' (' . htmlspecialchars($row['bike_type']) . ')</h3>
                        <p><strong>Price:</strong> ₹' . htmlspecialchars($row['price_per_hour']) . '/hour</p>
                        <p class="availability ' . $availability_class . '"><strong>Availability:</strong> ' . $availability_text . '</p>
                        ' . ($row['availability'] == 1 ? 
                        '<a href="booking.php?bike_id=' . $row['id'] . '" class="btn">Add to Booking</a>' 
                            : '<span class="not-available-msg">Currently Unavailable</span>') . '
                      </div>';
            }
            ?>
        </div>
    </section>
    <script src="assets/js/about.js"></script>
 <?php include 'footer.php'; ?>

 </body>
 </html>

