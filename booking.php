<?php
include 'db_connect.php';
//for email under sending message 
// for Mail sending
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if (!isset($_GET['bike_id'])) {
    die("Bike not found.");
}

$bike_id = intval($_GET['bike_id']);
$query = "SELECT * FROM bikes WHERE id = $bike_id";
$result = mysqli_query($conn, $query);
$bike = mysqli_fetch_assoc($result);

if (!$bike) {
    die("Bike not found.");
}

// Initialize session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Handle OTP verification
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['verify_otp'])) {
    // Combine OTP digits
    $otp = $_POST['otp1'] . $_POST['otp2'] . $_POST['otp3'] . $_POST['otp4'];
    
    if (isset($_SESSION['email_otp']) && $otp == $_SESSION['email_otp']) {
        // OTP is correct, proceed with booking
        $user_id = 2;
        
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $hours = intval($_POST['hours']);
        
        if (empty($name) || empty($phone) || empty($address) || empty($hours) || empty($email)) {
            $error_message = "Error: All fields are required.";
        } else {
            $start_time = date('Y-m-d H:i:s');
            $end_time = date('Y-m-d H:i:s', strtotime($start_time . " + $hours hours"));
            
            $query = "INSERT INTO bookings (user_id, name, email, phone, bike_id, start_time, end_time, status, address)
                        VALUES ('$user_id', '$name', '$email', '$phone', '$bike_id', '$start_time', '$end_time', 'pending', '$address')";
            
            if (mysqli_query($conn, $query)) {
                // Clear session data
                unset($_SESSION['email_otp']);
                
                echo "<script>alert('Booking Confirmed!'); window.location='payment.php';</script>";
                exit();
            } else {
                $error_message = "Database Error: " . mysqli_error($conn);
            }
        }
    } else {
        $otp_error = "Invalid OTP. Please try again.";
    }
} 

// Generate OTP
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['generate_otp'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    if (empty($email)) {
        $error_message = "Email is required to send OTP.";
    } else {
        // Generate a 4-digit OTP
        $otp = rand(1000, 9999);
        $_SESSION['email_otp'] = $otp;
        
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->SMTPDebug = 0;                      // Set to 0 for production (no debug output)
            $mail->isSMTP();                           // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';      // SMTP server
            $mail->SMTPAuth   = true;                  // Enable SMTP authentication
            $mail->Username   = 'patellkishannn@gmail.com'; // SMTP username
            $mail->Password   = 'kfchbdfhbyvabmpv';    // SMTP password (use App Password for Gmail)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use STARTTLS instead of SMTPS
            $mail->Port       = 587;                   // TCP port to connect to; use 587 for STARTTLS

            // Recipients
            $mail->setFrom('patellkishannn@gmail.com', 'Bike Rental System');
            $mail->addAddress($email);                 // Add recipient

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Your OTP for Bike Rental Booking';
            $mail->Body    = '
                <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
                    <h2 style="color: #2ecc71; text-align: center;">Bike Rental Verification</h2>
                    <p>Hello,</p>
                    <p>Your One Time Password (OTP) for bike rental booking verification is:</p>
                    <div style="text-align: center; margin: 30px 0;">
                        <span style="font-size: 32px; font-weight: bold; background-color: #f5f5f5; padding: 10px 20px; border-radius: 5px; letter-spacing: 5px;">' . $otp . '</span>
                    </div>
                    <p>This OTP is valid for 10 minutes. Please do not share it with anyone.</p>
                    <p>Thank you for choosing our service!</p>
                </div>
            ';
            $mail->AltBody = 'Your OTP for bike rental booking is: ' . $otp;

            $mail->send();
            $otp_generated = true;
            $otp_success_message = "OTP has been sent to your email address.";
        } catch (Exception $e) {
            $error_message = "Failed to send OTP. Error: " . $mail->ErrorInfo;
            // Keep the OTP available for development purposes
            $dev_otp_message = "Development mode: Your OTP is $otp";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Booking</title>
    <link rel="stylesheet" href="assets/css/bik11.css">
    <style>
        .error-message {
            color: red;
            margin-top: 5px;
        }
        .success-message {
            color: green;
            margin-top: 5px;
        }
        .dev-message {
            background-color: #ffe;
            border: 1px solid #fd0;
            padding: 10px;
            margin: 10px 0;
            color: #333;
        }
        
        /* Email field with OTP button */
        .email-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }
        .email-container input[type="email"] {
            flex-grow: 1;
        }
        .otp-button {
            background-color: #2ecc71;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            white-space: nowrap;
        }
        
        /* OTP Input Boxes */
        .otp-container {
            margin-top: 10px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            display: <?php echo isset($otp_generated) ? 'block' : 'none'; ?>;
        }
        .otp-title {
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
            color: #333;
        }
        .otp-boxes {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 15px;
        }
        .otp-box {
            width: 50px;
            height: 50px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 20px;
            text-align: center;
        }
        .otp-box:focus {
            border-color: #2ecc71;
            outline: none;
        }
        .validate-otp-btn {
            display: block;
            width: 100%;
            background-color: #2ecc71;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<nav>
    <h1>Booking</h1>
</nav>

<section class="booking-section">
    <h2>Confirm Your Booking</h2>
    <br>
    <div class="bike-details">
        <img src="assets/images/<?php echo htmlspecialchars($bike['image'] ?: 'default.jpg'); ?>"
              alt="<?php echo htmlspecialchars($bike['bike_name']); ?>"
              style="width: 100%; max-height: 200px; object-fit: cover; border-radius: 5px;">
        <h3><?php echo htmlspecialchars($bike['bike_name']) . ' (' . htmlspecialchars($bike['bike_type']) . ')'; ?></h3>
        <p><strong>Price:</strong> ₹<?php echo htmlspecialchars($bike['price_per_hour']); ?>/hour</p>
    </div>
    
    <?php if (isset($error_message)): ?>
        <p class="error-message"><?php echo $error_message; ?></p>
    <?php endif; ?>
    
    <form action="?bike_id=<?php echo $bike_id; ?>" method="POST" enctype="multipart/form-data" id="bookingForm">
        <input type="hidden" name="bike_id" value="<?php echo $bike_id; ?>">
        
        <label for="name">Full Name:</label>
        <input type="text" name="name" id="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>
        
        <!-- Email field with OTP button -->
        <label for="email">Email:</label>
        <div class="email-container">
            <input type="email" name="email" id="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
            <button type="submit" name="generate_otp" class="otp-button">Send OTP</button>
        </div>
        
        <?php if (isset($otp_success_message)): ?>
            <p class="success-message"><?php echo $otp_success_message; ?></p>
        <?php endif; ?>
        
        <!-- OTP Input Section -->
        <div class="otp-container" id="otpContainer">
            <div class="otp-title">One Time Password</div>
            
            <?php if (isset($otp_error)): ?>
                <p class="error-message" style="text-align: center;"><?php echo $otp_error; ?></p>
            <?php endif; ?>
            
            <?php if (isset($dev_otp_message)): ?>
                <div class="dev-message">
                    <strong>Developer Note:</strong> <?php echo $dev_otp_message; ?>
                </div>
            <?php endif; ?>
            
            <div class="otp-boxes">
                <input type="text" maxlength="1" class="otp-box" name="otp1" id="otp1" onkeyup="moveToNext(this, 'otp2')">
                <input type="text" maxlength="1" class="otp-box" name="otp2" id="otp2" onkeyup="moveToNext(this, 'otp3')">
                <input type="text" maxlength="1" class="otp-box" name="otp3" id="otp3" onkeyup="moveToNext(this, 'otp4')">
                <input type="text" maxlength="1" class="otp-box" name="otp4" id="otp4">
                
            </div>
            <button type="submit" name="verify_otp" class="validate-otp-btn">Validate Code</button>
        </div>
        
        <label for="phone">Phone:</label>
        <input type="text" name="phone" id="phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" required>
        
        <label for="address">Enter Address:</label>
        <div id="address-container">
            <input type="text" id="address" name="address" value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>" placeholder="Start typing..." autocomplete="off" onkeyup="fetchAddress(this.value)">
            <ul id="address-list"></ul>
        </div>
        
        <label for="hours">Hours of Rental:</label>
        <input type="number" name="hours" id="hours" min="1" value="<?php echo isset($_POST['hours']) ? htmlspecialchars($_POST['hours']) : '1'; ?>" required>
        
        <label for="license">Upload License (JPG, PNG, PDF):</label>
        <input type="file" name="license" id="license" accept=".jpg,.jpeg,.png,.pdf" required>
        
        <button type="submit" name="complete_booking" class="btn">Complete Booking</button>
    </form>
</section>
<script src="assets/js/booking.js"></script>

<?php include 'footer.php'; ?>
<?php if (isset($otp_generated)): ?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById('otpContainer').style.display = 'block';
        document.getElementById('otp1').focus();
    });
</script>
<?php endif; ?>

</html>

