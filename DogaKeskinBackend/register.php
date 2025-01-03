<?php
session_start();
require "db.php";
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendVerificationEmail($to, $subject, $message) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'asmtp.bilkent.edu.tr';
        $mail->SMTPAuth = true;
        $mail->Username = 'doga12162@gmail.com';
        $mail->Password = 'Testmail123';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('doga12162@gmail.com', 'MARKET101');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['market_registration'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $marketName = isset($_POST['market_name']) ? $_POST['market_name'] : '';
        $city = $_POST['city'];
        $district = $_POST['district'];
        $address = $_POST['address'];
        $user_type = $_POST['user_type'];
        $randCode = rand(100000, 999999);

        $emailSent = sendVerificationEmail($email, "Verification Code", "Your verification code is $randCode");

        if ($emailSent) {
            $_SESSION['verification_code'] = $randCode;
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $password;
            $_SESSION['name'] = $name;
            $_SESSION['market_name'] = $marketName;
            $_SESSION['city'] = $city;
            $_SESSION['district'] = $district;
            $_SESSION['address'] = $address;
            $_SESSION['user_type'] = $user_type;
            header("Location: verify_email.php");
            exit;
        } else {
            $error = "Failed to send verification email.";
        }
    }
    else if (isset($_POST['consumer_registration'])){
        $email = $_POST['email'];
        $password = $_POST['password'];
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $city = $_POST['city'];
        $district = $_POST['district'];
        $address = $_POST['address'];
        $user_type = $_POST['user_type'];
        $randCode = rand(100000, 999999);

        $emailSent = sendVerificationEmail($email, "Verification Code", "Your verification code is $randCode");

        if ($emailSent) {
            $_SESSION['verification_code'] = $randCode;
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $password;
            $_SESSION['name'] = $name;
            $_SESSION['city'] = $city;
            $_SESSION['district'] = $district;
            $_SESSION['address'] = $address;
            $_SESSION['user_type'] = $user_type;
            header("Location: verify_email.php");
            exit;
        } else {
            $error = "Failed to send verification email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Page</title>
  <link rel="stylesheet" href="./css/style.css">
</head>
<body>
  <h1>Welcome to the Market System</h1>
  <div class="registration-container">
    <div class="form-toggle">
      <button id="marketRegBtn">Market User</button>
      <button id="consumerRegBtn">Consumer User</button>
      <button><a href="index.php" class="back-to-login">Back to Login</a></button>
    </div>
    <div class="registration-form" id="marketRegistration">
      <h2>Market Registration</h2>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="market_registration" value="1">
        <input type="hidden" name="user_type" value="marketuser">
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="market_name" placeholder="Market Name" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="text" name="city" placeholder="City" required>
        <input type="text" name="district" placeholder="District" required>
        <input type="text" name="address" placeholder="Address" required>
        <button type="submit">Register</button>
      </form>
    </div>

    <div class="registration-form" id="consumerRegistration">
      <h2>Consumer Registration</h2>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="consumer_registration" value="1">
        <input type="hidden" name="user_type" value="customer">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="text" name="city" placeholder="City" required>
        <input type="text" name="district" placeholder="District" required>
        <input type="text" name="address" placeholder="Address" required>
        <button type="submit">Register</button>
      </form>
    </div>
  </div>
  <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
  <script src="./js/script.js"></script>
</body>
</html>
