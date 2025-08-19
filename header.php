<?php 
include 'db_connect.php'; 
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bike Rental System</title>
    <link rel="stylesheet" href="assets/css/home11.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>   
    <style> 
        .user-info {
            color:rgb(12, 12, 12); /* Blue color for username */
            font-weight: bold;
            padding: 8px 12px;
            text-decoration: none;
        }

        .logout-btn {
            color:rgb(34, 32, 32) !important; /* Red color for logout */
            font-weight: bold;
            padding: 8px 12px;
            text-decoration: none;
        }

        .logout-btn:hover {
            color: darkred !important; /* Darker red on hover */
        }
        .user-info {
            color: darkred !important;
        }
        
    </style>
</head>
<body>

<header>
    <nav>
        <div class="logo">Bike Rental</div>
        <ul>
            <li><a href="home.php" class="active">Home</a></li>
            <li><a href="bikes.php">Bikes</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="my_booking.php">My Bookings</a></li>
            
            <?php if (isset($_SESSION['user'])): ?>
                <li><a href="#" class="user-info">Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?></a></li>
                <li><a href="logout.php" class="logout-btn">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php" class="login-btn">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
 
    <div class="banner">
      <h1>Book Your Dream Bike</h1>
      <br><br>
      <h2>And Enjoy Your Life</h2>
      <a href="bikes.php" class="btn">Rent Now</a>
    </div>
</header>

</body>
</html>
