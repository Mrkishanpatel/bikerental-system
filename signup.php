<?php
include 'db_connect.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = $_POST['username'];  
    $email = $_POST['email'];
    $password = $_POST['password'];
    $dob = $_POST['dob']; // Added DOB field

    // $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // if user want to take hash password than  uncomment this

    // Updated SQL query to include DOB
    $sql = "INSERT INTO users (username, email, password, dob) VALUES ('$username', '$email', '$password', '$dob')";
 
    // Execute the query
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Signup successful! Please login.'); window.location='login.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Signup - Bike Rental</title>
    <link rel="stylesheet" href="assets/css/style1.css">
</head>
<body>
    <div class="container">
        <div class="left">
            <h2>Welcome Back!</h2>
            <p>To keep connected with us, please login with your personal info</p>
            <br>
            <br>
            <a href="login.php" class="btn">SIGN IN</a>
        </div>
        <div class="right">
            <h2>Create Account</h2>
            <br>
            <form method="post" id="signup">
                <input type="text" name="username" id="username" placeholder="Username" style="width: 280px; height: 30px; border-radius: 8px; border: 1px solid #ccc; padding: 10px;">
                <span class="username-error" style="color: red;"></span>
                <br>
                <br>
                <input type="email" name="email" id="email" placeholder="Email" style="width: 280px; height: 30px; border-radius: 8px; border: 1px solid #ccc; padding: 10px;">
                <span class="email-error" style="color: red;"></span>
                <br>
                <br>
                <input type="password" name="password" id="password" placeholder="Password" style="width: 280px; height: 30px; border-radius: 8px; border: 1px solid #ccc; padding: 10px;">
                <span class="password-error" style="color: red;"></span>
                <br>
                <br>
                <input type="date" name="dob" id="dob" style="width: 280px; height: 30px; border-radius: 8px; border: 1px solid #ccc; padding: 10px;">
<div class="dob-error" style="color: red; margin-top: 5px; font-size: 14px;"></div>
<br>
                <button type="submit">Sign Up</button>
            </form>
            <br>
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>
    <script src="assets/js/signup-validation.js"></script>
</body>
</html>