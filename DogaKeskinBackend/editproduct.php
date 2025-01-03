<?php
session_start();
require "db.php";

$user = $_SESSION["user"];

$id = $_GET["id"];
$editedProduct = getMarketProduct($id);

if (!isAuthenticated()) {
    header("Location: index.php?error");
    exit;
}

if (!empty($_POST)) {
    extract($_POST);
    global $db;
    $stmt = $db->prepare("UPDATE marketproducts SET title = ?, stock = ?, normalprice = ?, discountedprice = ?, expirationdate = ? WHERE id = ?");
    $stmt->execute([$title, $stock, $normalprice, $discountedprice, $expirationdate, $id]);
    header("Location: market.php?editedid=$id"); 
    exit;
}

function getMarketProduct($id)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM marketproducts WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="./css/marketuser.css">
</head>
<body>
    <div class='overlay-container'>
        <div class='overlay'>
            <form method='POST'>
                <table>
                    <tr>
                        <input type="hidden" name="id" value="<?= $editedProduct['id'] ?>">
                    </tr>
                    <tr>
                        <td>New Product Name:</td>
                        <td><input type='text' name='title' value="<?= $editedProduct['title'] ?>"></td>
                    </tr>
                    <tr>
                        <td>Stock:</td>
                        <td><input type='text' name='stock' value="<?= $editedProduct['stock'] ?>"></td>
                    </tr>
                    <tr>
                        <td>Normal Price:</td>
                        <td><input type='text' name='normalprice' value="<?= $editedProduct['normalprice'] ?>"></td>
                    </tr>
                    <tr>
                        <td>Discounted Price:</td>
                        <td><input type='text' name='discountedprice' value="<?= $editedProduct['discountedprice'] ?>"></td>
                    </tr>
                    <tr>
                        <td>Expiration Date:</td>
                        <td><input type='date' name='expirationdate' value="<?= $editedProduct['expirationdate'] ?>"></td>
                    </tr>
                    <tr>
                        <td>Image:</td>
                        <td><input type='file' name='image' value="<?= $editedProduct['image'] ?>"></td>
                    </tr>
                </table>
                <button>Edit</button>
            </form>
        </div>
    </div>
</body>
</html>