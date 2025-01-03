<?php
   session_start();
   require "db.php";

   if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
    }

   $user = $_SESSION["user"];

   if (!empty($_POST)) {
       extract($_POST);

       $params = [$email, $market_name, $city, $district, $address, $_SESSION["user"]["id"]];

       if (!empty($password)) {
           if ($password !== $password_confirm) {
               die("Passwords do not match.");
           }
           $hash = password_hash($password, PASSWORD_BCRYPT);
           $stmt = $db->prepare("UPDATE marketuser SET email = ?, name = ?, password = ?, city = ?, district = ?, address = ? WHERE id = ?");
           $params = [$email, $market_name, $hash, $city, $district, $address, $_SESSION["user"]["id"]];
       } else {
           $stmt = $db->prepare("UPDATE marketuser SET email = ?, name = ?, city = ?, district = ?, address = ? WHERE id = ?");
       }

       if ($stmt->execute($params)) {
           $_SESSION['user']['email'] = $email;
           $_SESSION['user']['name'] = $market_name;
           $_SESSION['user']['city'] = $city;
           $_SESSION['user']['district'] = $district;
           $_SESSION['user']['address'] = $address;

           // Redirect to market page
           header("Location: market.php");
           exit;
       } else {
           // Handle error
           die("Failed to update user information.");
       }
   }
   session_regenerate_id(true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Page</title>
    <link rel="stylesheet" href="./css/edit.css">
</head>
<body>
    <h1>Edit Market User Information</h1>
    <form action="" method="post">
        <table>
            <tr>
                <td>Email</td>
                <td>
                    <input type="email" name="email" value="<?= htmlspecialchars($_SESSION['user']['email']) ?>">
                </td>
            </tr>
            <tr>
                <td>Market Name</td>
                <td>
                    <input type="text" name="market_name" value="<?= htmlspecialchars($_SESSION['user']['name']) ?>">
                </td>
            </tr>
            <tr>
                <td>New Password</td>
                <td>
                    <input type="password" name="password">
                </td>
            </tr>
            <tr>
                <td>Confirm Password</td>
                <td>
                    <input type="password" name="password_confirm">
                </td>
            </tr>
            <tr>
                <td>City</td>
                <td>
                    <input type="text" name="city" value="<?= htmlspecialchars($_SESSION['user']['city']) ?>">
                </td>
            </tr>
            <tr>
                <td>District</td>
                <td>
                    <input type="text" name="district" value="<?= htmlspecialchars($_SESSION['user']['district']) ?>">
                </td>
            </tr>
            <tr>
                <td>Address</td>
                <td>
                    <input type="text" name="address" value="<?= htmlspecialchars($_SESSION['user']['address']) ?>">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                   <button type="submit">Edit</button>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>
