
<?php
include 'db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to fetch user based on email
    $result = $conn->query("SELECT * FROM users WHERE email='$email'");

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($password === $row['password']) {
            // Store the user information in session
            $_SESSION['user'] = $row['username'];  // Store 'username' in session (change if needed)
            // Redirect to home page after successful login
            header("Location: home.php");
            exit();  // Make sure to call exit to stop further script execution
        } else {
            echo "<script>alert('Invalid credentials');</script>";
        }
    } else {
        echo "<script>alert('User not found');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login - Bike Rental</title>
    <link rel="stylesheet" href="assets/css/style1.css">
</head>
<body>
    <div class="container">
        <div class="left">
            <h2>Welcome Back!</h2>
            
            <br>
            <p>To keep connected with us, please login with your personal info</p>
            <br>
            <a href="signup.php" class="btn">SIGN UP</a>
        </div>
        <div class="right">
            <h2>Login</h2>
            <br>
            <form method="post">
            <input type="email" name="email" placeholder="Email" style="width: 280px; height: 30px; border-radius: 8px; border: 1px solid #ccc; padding: 10px;">
                 <span class="email-error" style="color: red;"></span>
                <br>
                <br>
                <input type="password" name="password" placeholder="Password" style="width: 280px; height: 30px; border-radius: 8px; border: 1px solid #ccc; padding: 10px;">
                <span class="password-error" style="color: red;"></span>
                <br><br>
                <button type="submit">Login</button>
                <br><br>
            </form>
            <p>Don't have an account? <a href="signup.php">Signup</a></p>
        </div>
    </div>
    <script src="assets/js/login-validation.js"></script>
</body>
</html>
