<?php
$dsn = "mysql:host=localhost;port=3306;dbname=test;charset=utf8mb4" ;
$user = "root" ;
$pass = "" ;

try {
  $db = new PDO($dsn, $user, $pass) ;
} catch( PDOException $ex) {
     
  exit ;
}

function getMarketUser($id) {
  global $db ;
  $stmt = $db->prepare("SELECT * FROM marketuser WHERE id = ?") ;
  $stmt->execute([$id]) ;
  
  return $stmt->fetch() ; 
}

function getCustomerUser($id) {
  global $db ;
  $stmt = $db->prepare("SELECT * FROM customeruser WHERE id = ?") ;
  $stmt->execute([$id]) ;

  return $stmt->fetch() ; 
}

function checkUser($email, $password, &$user) {
  global $db ;

  $stmt = $db->prepare("select * from marketuser where email=?") ;
  $stmt->execute([$email]) ;
  $user = $stmt->fetch() ;
  if ( $user) {
      return password_verify($password, $user["password"]) ;
  }
  return false ;
}

function checkCustomerUser($email, $password, &$user) {
  global $db ;

  $stmt = $db->prepare("select * from customeruser where email=?") ;
  $stmt->execute([$email]) ;
  $user = $stmt->fetch() ;
  if ( $user) {
      return password_verify($password, $user["password"]) ;
  }
  return false ;
}


function isAuthenticated() {
  return isset($_SESSION["user"]) ;
}

function getMarketUserByToken($token) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM marketuser WHERE remember = ?");
    $stmt->execute([$token]);
    return $stmt->fetch();
}

function getCustomerUserByToken($token) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM customeruser WHERE remember = ?");
    $stmt->execute([$token]);
    return $stmt->fetch();
}

function setTokenByEmail($email, $token) {
 global $db ;
 $stmt = $db->prepare("update marketuser set remember = ? where email = ?") ;
 $stmt->execute([$token, $email]) ;
}

function setTokenByEmailCustomer($email, $token) {
  global $db ;
  $stmt = $db->prepare("update customeruser set remember = ? where email = ?") ;
  $stmt->execute([$token, $email]) ;
 }