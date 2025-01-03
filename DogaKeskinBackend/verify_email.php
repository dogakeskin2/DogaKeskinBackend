<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_SESSION['email'];
  $code = $_POST['code'];
  $user_type = $_SESSION['user_type'];

  if ($code == $_SESSION['verification_code']) {
    try {
      $password = $_SESSION['password'];
      $hash = password_hash($password, PASSWORD_BCRYPT);

      if ($user_type === 'marketuser') {
        $market_name = $_SESSION['market_name'];
        $city = $_SESSION['city'];
        $district = $_SESSION['district'];
        $address = $_SESSION['address'];
        $verificationCode = $_SESSION['verification_code'];

        $sql = "INSERT INTO marketuser (email, name, password, city, district, address, verificationcode) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$email, $market_name, $hash, $city, $district, $address, $verificationCode]);
      } elseif ($user_type === 'customer') {
        $name = $_SESSION['name'];
        $city = $_SESSION['city'];
        $district = $_SESSION['district'];
        $address = $_SESSION['address'];

        $sql = "INSERT INTO customeruser (email, password, name, city, district, address) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$email, $hash, $name, $city, $district, $address]);
      }      
      header("Location: index.php");
      exit();
    } catch (Exception $e) {
      $error = $e->getMessage();
    }
  } else {
    $error = "Invalid verification code.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Email Verification</title>
  <link rel="stylesheet" href="./css/verify_mail.css">
</head>
<body>
  <div class="verification-container">
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
      <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" readonly>
      <input type="text" name="code" placeholder="Enter Confirmation Code" required>
      <button type="submit">Verify</button>
      <?php if (isset($error)) : ?>
        <div class="error-message"><?php echo $error; ?></div>
      <?php endif; ?>
    </form>
  </div>
</body>
</html>
