<?php
    session_start() ;
    require "db.php" ;

    if ( !empty($_POST)) {
      extract($_POST) ;
      if ( checkUser($email, $password, $user) ) {
         
          
          // remember me part
          if ( isset($remember)) {
            $token = sha1(uniqid() . "Private Key is Here" . time() ) ; // generate a random text
            setcookie("access_token", $token, time() + 60*60*24*365*10) ; // for 10 years
            setTokenByEmail($email, $token) ;
          }

          
          $_SESSION["user"] = $user ;
          
          header("Location: market.php") ;
          exit ;
      }

      else if ( checkCustomerUser($email, $password, $user) ) {
        
        // remember me part
        if ( isset($remember)) {
          $token = sha1(uniqid() . "Private Key is Here" . time() ) ;
          setcookie("access_token", $token, time() + 60*60*24*365*10) ;
          setTokenByEmailCustomer($email, $token) ;
        }

        
        $_SESSION["user"] = $user ; 
        header("Location: customer.php") ;
        exit ;
    }
      else { $fail = true; }
  }

  if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_COOKIE["access_token"])) {
    $marketUser = getMarketUserByToken($_COOKIE["access_token"]);
    $customerUser = getCustomerUserByToken($_COOKIE["access_token"]);
    
    if ($marketUser) {
        $_SESSION["user"] = $marketUser; // auto login for MarketUser
        header("Location: index.php");
        exit;
    } elseif ($customerUser) {
        $_SESSION["user"] = $customerUser; // auto login for CustomerUser
        header("Location: index.php");
        exit;
    }
  }

  if ($_SERVER["REQUEST_METHOD"] == "GET" && isAuthenticated()) {
  
  if (isset($_SESSION["user"]["type"]) && $_SESSION["user"]["type"] == "MarketUser") {
      header("Location: market.php"); 
  } elseif (isset($_SESSION["user"]["type"]) && $_SESSION["user"]["type"] == "CustomerUser") {
      header("Location: customer.php"); 
  }
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <link rel="stylesheet" href="./css/style.css">
</head>
<body>
  <h1>Welcome to the Market System</h1>
  <div class="registration-container">
    <div class="form-toggle">
      <button id="marketRegBtn">Market User</button>
      <button id="consumerRegBtn">Consumer User</button>
      <button><a href="register.php" class="register-button">Register</a></button>
    </div>
    
    <div class="registration-form" id="marketRegistration">
      <h2>Market Registration</h2>
      <form action="" method="POST">
        <input type="hidden" name="market_index" value="1">
        <input type="email" name="email" placeholder="Email">
        <input type="password" name="password" placeholder="Password">
        
        <div>
            <span>Remember :</span>
            <input type="checkbox" name="remember">
        </div>
        <button>Login</button>
      </form>
    </div>

    <div class="registration-form" id="consumerRegistration">
      <h2>Consumer Registration</h2>
      <form action="" method="POST">
        <input type="hidden" name="consumer_index" value="1">
        <input type="email" name="email" placeholder="Email">
        <input type="password" name="password" placeholder="Password">
       
        <div>
            <span>Remember :</span>
            <input type="checkbox" name="remember">
        </div>
        <button>Login</button>
      </form>
    </div>

  </div>
  <?php
      if ( isset($fail)) {
         echo "<p class='error'>Wrong email or password</p>" ; 
      }
      
      if ( isset($_GET["error"])) {  
        echo "<p class='error'>You tried to access market.php directly</p>" ; 
      }
    ?>
  <script src="./js/script.js"></script>
</body>
</html>