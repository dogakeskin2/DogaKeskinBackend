<?php
   session_start() ;
   require "db.php" ;

   if ( !isAuthenticated()) {
      header("Location: index.php") ;
      exit ;
   }

   // delete remember me part
   // Call unsetTokenByEmail or unsetTokenByEmailCustomer when logging out based on user type
   if (isset($_SESSION["user"]["type"]) && $_SESSION["user"]["type"] == "MarketUser") {
      setTokenByEmail($_SESSION["user"]["email"],null);
   } else if (isset($_SESSION["user"]["type"]) && $_SESSION["user"]["type"] == "CustomerUser") {
      setTokenByEmailCustomer($_SESSION["user"]["email"],null);
   }

   setTokenByEmail($_SESSION["user"]["email"], null) ;
   setTokenByEmailCustomer($_SESSION["user"]["email"], null) ;
   setcookie("access_token", "", 1) ; 
   
   // delete session file
   session_destroy() ;
   // delete session cookie
   setcookie("PHPSESSID", "", 1 , "/") ; // delete memory cookie 

   // redirect to login page.
   header("Location: index.php") ;
?>