<?php
session_start();
require "db.php";

$user = $_SESSION["user"];

if (!isAuthenticated()) {
    header("Location: index.php?error");
    exit;
}

function markExpiredProducts($products)
{
    $today = date("Y-m-d");
    foreach ($products as &$product) {
        if ($product["expirationdate"] < $today) {
            $product["expired"] = true;
        } else {
            $product["expired"] = false;
        }
    }
    return $products;
}

//EDIT

if (isset($_GET['edit'])) {
    $editedUserId = $_GET['edit'];
   
    $_SESSION['user'] = getMarketUser($editedUserId);
}

if (!empty($_POST)) {
    extract($_POST);
    
    try {
        
        $upload = new Upload("image", "uploads"); 
        $imageFileName = $upload->file;
        
        $stmt = $db->prepare("INSERT INTO marketproducts (email, title, stock, normalprice, discountedprice, expirationdate, image) VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([$user['email'], $title, $stock, $normalprice, $discountedprice, $expirationdate, $imageFileName]);
        
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
    }
}


if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["delete"])) {
    $id = $_GET["delete"];
    $marketproducts = getMarketProducts($id);
    if ($marketproducts !== false) {
        $stmt = $db->prepare("DELETE FROM marketproducts where id = ?");
        $stmt->execute([$id]);
        
    } 
}

function getMarketProducts($id)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM marketproducts WHERE id = ?");
    $stmt->execute([$id]);
    
    return $stmt->fetch();
}

$rs = $db->prepare("SELECT * FROM marketproducts WHERE email = ?");
$rs->execute([$user['email']]);
$market_products = $rs->fetchAll();


$market_products = markExpiredProducts($market_products);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Product Page</title>
    <link rel="stylesheet" href="./css/marketuser.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

    <div class="container">
        <div class="sidebar">
            <h2>Welcome Back <?= $_SESSION['user']['name'] ?></h2>
            <a href="edit.php?id=<?= $user["id"] ?>">Edit Info</a>
            <div class="addnewproduct"><a href="?id=<?= $_SESSION['user']['id'] ?>">Add New Product</a></div>
            <div class="logout"><a href="logout.php">Log Out</a></div>
        </div>
        <div class="products">
            <h2>Products</h2>
            <table>
                <tr>
                    <th>TITLE</th>
                    <th>STOCK</th>
                    <th>NORMAL PRICE</th>
                    <th>DISCOUNTED PRICE</th>
                    <th>EXPIRATION DATE</th>
                    <th>IMAGE</th>
                    <th>DELETE/EDIT</th>
                </tr>
                <?php foreach ($market_products as $pro) : ?>
                    <tr <?php if ($pro["expired"]) echo 'class="expired"'; ?>>
                        <td><?= $pro["title"] ?></td>
                        <td><?= $pro["stock"] ?></td>
                        <td>$<?= $pro["normalprice"] ?> </td>
                        <td>$<?= $pro["discountedprice"] ?></td>
                        <td><?= $pro["expirationdate"] ?></td>
                        <td><img src="uploads/<?= $pro['image'] ?>" alt="<?= $pro['title'] ?>" style="width: 75px; height: 75px;"></td>
                        <td>
                            <a class="btn" href="?delete=<?= $pro["id"] ?>" title="delete"><i class="fa-solid fa-trash-can"></i></a>
                            <a class="btn" href="editproduct.php?id=<?= $pro["id"] ?>" title="edit"><i class="fa-solid fa-pen"></i></i></a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </table>
        </div>
    </div>

    <?php
    if (isset($_GET["id"]) && !isset($_POST["title"])) {
        $id = $_GET["id"];

        echo "<div class='overlay-container'>";
        echo "<div class='overlay'>";
        echo "<form method='POST' enctype='multipart/form-data'>";
        echo "<table>";
        echo "<tr>";
        echo "<td>New Product Name:</td>";
        echo "<td><input type='text' name='title' id=''></td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td>Stock:</td>";
        echo "<td><input type='text' name='stock' id=''></td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td>Normal Price:</td>";
        echo "<td><input type='text' name='normalprice' id=''></td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td>Discounted Price:</td>";
        echo "<td><input type='text' name='discountedprice' id=''></td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td>Expiration Date:</td>";
        echo "<td><input type='date' name='expirationdate' id=''></td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td>Image:</td>";
        echo "<td><input type='file' name='image' id='image'></td>";
        echo "</tr>";
        echo "</table>";
        echo "<button>Add</button>";
        echo "</form>";
        echo "</div>";
        echo "</div>";
    }


    class Upload {
        public $file = null ; 
        const MAX_FS = 1024 * 1024 ; // 1MB
    
        // $fb: filebox name   $folder: application upload folder
        public function __construct($fb, $folder)
        {
            if ( !empty($_FILES[$fb]["tmp_name"])) {
               extract($_FILES[$fb]) ; // $name, $tmp_name, $size, $error ...
               $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION)) ;
               $imgExt = ["png", "jpg", "jpeg"] ; // white list
               if ( !in_array($ext, $imgExt)) {
                throw new Exception("Not an image file") ; 
               } else if ( $size > self::MAX_FS ) {
                 throw new Exception("Too big image file") ;
               } else {
                  $this->file = sha1($tmp_name . $name . $size . uniqid()) . ".$ext";
                  move_uploaded_file($tmp_name, $folder . "/" . $this->file) ;
               }
            } else {
                throw new Exception("No file or invalid file selected") ;
            }
        }
    }
    ?>
</body>

</html>