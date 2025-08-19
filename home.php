<?php include 'header.php'; ?>
<?php include 'db_connect.php'; // Ensure database connection ?>


<section class="featured-bikes">
    <h2>Featured Bikes</h2>
    <div class="bike-list">
        <?php
        $query = "SELECT * FROM bikes LIMIT 3"; 
        $result = mysqli_query($conn, $query);

        if (!$result) {
            die("<p class='error'>SQL Query Failed: " . mysqli_error($conn) . "</p>"); 
        }

        if (mysqli_num_rows($result) == 0) {
            echo "<p>No bikes available.</p>";
        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                $bike_name = htmlspecialchars($row['bike_name'] ?? 'Unknown');
                $image = 'assets/images/' . ($row['image'] ?? 'default.jpg');
                $price_per_hour = htmlspecialchars($row['price_per_hour'] ?? 'N/A');
                $availability = ($row['availability'] == 1);
                $availability_text = $availability ? "Available" : "Not Available";
                $availability_class = $availability ? "available" : "not-available";
                
                echo '<div class="bike-card">
                        <img src="' . $image . '" alt="' . $bike_name . '">
                        <h3>' . $bike_name . '</h3>
                        <p><strong>Price:</strong> ₹' . $price_per_hour . '/day</p>
                        <p class="availability ' . $availability_class . '">
                            <strong>Availability:</strong> ' . $availability_text . '
                        </p>';
                        
                echo $availability 
                    ? '<a href="booking.php?bike_id=' . $row['id'] . '" class="btn">Book Now</a>' 
                    : '<span class="not-available-msg">Currently Unavailable</span>';

                echo '</div>';
            }
        }
        ?>
    </div>
</section>
<section class="offers-slider">
    <h2>Special Offers & Discounts</h2>
    <div class="slider-container">
        <div class="slider">
            <?php
            $query = "SELECT * FROM offers WHERE active = 3 ORDER BY id DESC";
            
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) == 0) {
                echo '<div class="slide">
                        <img src="assets/offers/default-offer.jpg" alt="Default Offer">
                        <div class="offer-content">
                            <h3>No Current Offers</h3>
                            <p>Check back soon for exciting deals!</p>
                        </div>
                      </div>';
            } else {
                while ($row = mysqli_fetch_assoc($result)) {
                    $title = htmlspecialchars($row['title']);
                    $description = htmlspecialchars($row['description']);
                    $image = 'assets/offers/' . htmlspecialchars($row['image']);
                    $coupon_code = htmlspecialchars($row['coupon_code'] ?? '');
                    $button_text = htmlspecialchars($row['button_text'] ?? '');
                    $button_link = htmlspecialchars($row['button_link'] ?? '');

                    echo '<div class="slide">
                            <img src="' . $image . '" alt="' . $title . '">
                            <div class="offer-content">
                                <h3>' . $title . '</h3>
                                <p>' . $description . '</p>';
                    
                    if (!empty($coupon_code)) {
                        echo '<div class="coupon-code">
                                <span>Use Code: </span>
                                <strong>' . $coupon_code . '</strong>
                              </div>';
                    }

                    if (!empty($button_text) && !empty($button_link)) {
                        echo '<a href="' . $button_link . '" class="btn offer-btn">' . $button_text . '</a>';
                    }

                    echo '</div></div>';
                }
            }
            ?>
        </div>
        <button class="slider-btn prev">❮</button>
        <button class="slider-btn next">❯</button>
    </div>
</section>

<footer class="how-it-works">
<h2>How It Works</h2>
  <div class="steps">
    <div class="step">
      <h3>1. Choose a Bike</h3>
      <p>Browse our selection of high-quality rental bikes.</p>
    </div>
    <div class="step">
      <h3>2. Book & Pay</h3>
      <p>Securely book your bike online with easy payment options.</p>
    </div>
    <div class="step">
      <h3>3. Enjoy the Ride</h3>
      <p>Pick up your bike and hit the road hassle-free!</p>
    </div>
  </div>

  <div class="social-media">
    <a href="https://www.instagram.com" target="_blank" class="social-link">
      <i class="fab fa-instagram"></i> Instagram
    </a>
    <a href="https://twitter.com" target="_blank" class="social-link">
      <i class="fab fa-twitter"></i> Twitter
    </a>
    <a href="mailto:contact@bikerental.com" class="social-link">
      <i class="fas fa-envelope"></i> Email Us
    </a>
  </div>
        </footer>



<script src="assets/js/home-navigation.js" defer></script>

<!-- php include 'footer.php'; ?>   -->
