<?php
session_start();
require "db.php";

// initialize shopping cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$user = $_SESSION["user"];
$market_products = [];
const PAGESIZE = 4;
if (!isAuthenticated()) {
    header("Location: index.php?error");
    exit;
}

if (isset($_GET['edit'])) {
    $editedUserId = $_GET['edit'];
    $_SESSION['user'] = getCustomerUser($editedUserId);
}

function getMarketUserCityByEmail($email) {
    global $db;
    $stmt = $db->prepare("SELECT city FROM marketuser WHERE email = ?");
    $stmt->execute([$email]);
    $city = $stmt->fetch(PDO::FETCH_COLUMN);
    return $city;
}

function getCustomerCity() {
    global $db;
    $userId = $_SESSION['user']['id'];
    $stmt = $db->prepare("SELECT city FROM customeruser WHERE id = ?");
    $stmt->execute([$userId]);
    $city = $stmt->fetch(PDO::FETCH_COLUMN);
    return $city;
}

function getMarketProductsByCity($city) {
    global $db;
    $currentDate = date("Y-m-d");
    $stmt = $db->prepare("SELECT * FROM marketproducts WHERE email IN (SELECT email FROM marketuser WHERE city = ?) AND expirationdate >= ?");
    $stmt->execute([$city, $currentDate]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_GET['market'])) {
    $marketUserEmail = $_GET['market'];

    $marketCity = getMarketUserCityByEmail($marketUserEmail);

    if ($marketCity) {
        $customerCity = getCustomerCity();

        if ($marketCity === $customerCity) {
            $market_products = getMarketProductsByCity($marketCity);
        } else {
            echo "<p class='error-message'>Sorry, you are not in $marketCity.</p>";
        }
    }
}

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $_GET['search'];

    // filtering
    $market_products = filterProductsByKeyword($market_products, $searchTerm);
    
    // sorting
    usort($market_products, function($a, $b) use ($customerCity) {
        $aDistrict = getDistrictByCity($a['email']);
        $bDistrict = getDistrictByCity($b['email']);
        if ($aDistrict === $customerCity && $bDistrict !== $customerCity) {
            return -1;
        } elseif ($aDistrict !== $customerCity && $bDistrict === $customerCity) {
            return 1;
        } else {
            return 0;
        }
    });

    // removing expired products
    $currentDate = date("Y-m-d");
    $market_products = array_filter($market_products, function($product) use ($currentDate) {
        return $product['expirationdate'] >= $currentDate;
    });

    // paging
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $start = ($page - 1) * PAGESIZE;
    $market_products = array_slice($market_products, $start, PAGESIZE);

    $totalProducts = count($market_products);
    $pageSize = ceil($totalProducts / PAGESIZE);
}

function filterProductsByKeyword($products, $keyword)
{
    $filteredProducts = [];
    foreach ($products as $product) {
        //case insensitive + finds position of first occurences
        if (stripos($product['title'], $keyword) !== false) {
            $filteredProducts[] = $product;
        }
    }
    return $filteredProducts;
}

function getMarketProducts($email)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM marketproducts WHERE email = ?");
    $stmt->execute([$email]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getDistrictByCity($city)
{
    global $db;
    $stmt = $db->prepare("SELECT * FROM marketuser WHERE city = ?");
    $stmt->execute([$city]);
    $district = $stmt->fetch(PDO::FETCH_COLUMN);
    return $district;
}

function getMarkets()
{
    global $db;
    $query = "SELECT * FROM marketuser";

    $statement = $db->prepare($query);
    $statement->execute();

    $markets = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $markets;
}
//for images,taken from serkan hoca's code
class Upload {
    public $file = null;
    const MAX_FS = 1024 * 1024; // 1MB

    public function __construct($fb, $folder)
    {
        if (isset($_FILES[$fb]) && $_FILES[$fb]['error'] === UPLOAD_ERR_OK) {
            $this->file = basename($_FILES[$fb]['name']);
            $targetFile = $folder . '/' . $this->file;

            if ($_FILES[$fb]['size'] <= self::MAX_FS) {
                move_uploaded_file($_FILES[$fb]['tmp_name'], $targetFile);
            } else {
                throw new Exception('File size exceeds the maximum limit.');
            }
        } else {
            throw new Exception('Failed to upload the file.');
        }
    }
}

if (!empty($_POST)) {
    extract($_POST);

    try {
        // Handle file upload
        $upload = new Upload("image", "uploads");
        $imageFileName = $upload->file;

        // Insert the image file name into the database along with other product data
        $stmt = $db->prepare("INSERT INTO marketproducts (email, title, stock, normalprice, discountedprice, expirationdate, image) VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([$user['email'], $title, $stock, $normalprice, $discountedprice, $expirationdate, $imageFileName]);
        
        // Redirect or display success message
    } catch (Exception $e) {
        // Handle file upload errors
        $errorMessage = $e->getMessage();
        // Display error message to the user
    }
}

function getProductById($id) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM marketproducts WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
// calculating total of shopping card
function calculateGrandTotal($cart) {
    $total = 0;
    foreach ($cart as $productId => $quantity) {
        $product = getProductById($productId);
        $total += $product['discountedprice'] * $quantity;
    }
    return $total;
}

// shopping card updates
if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantities'] as $productId => $quantity) {
        if ($quantity == 0) {
            unset($_SESSION['cart'][$productId]);
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
    }
    header("Location: customer.php?market=" . (isset($_GET['market']) ? $_GET['market'] : ''));
    exit;
}
//shopping card remove
if (isset($_POST['remove'])) {
    $removeProductId = $_POST['remove'];
    unset($_SESSION['cart'][$removeProductId]);
    header("Location: customer.php?market=" . (isset($_GET['market']) ? $_GET['market'] : ''));
    exit;
}

// shopping card purchase
if (isset($_POST['purchase'])) {
    try {
        //beginTransaction, commit and rollback work together to check if data is processed 
        //fully successful or not.
        $db->beginTransaction();
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $product = getProductById($productId);
            $newStock = $product['stock'] - $quantity;
            // Update database with new stock quantity
            $stmt = $db->prepare("UPDATE marketproducts SET stock = ? WHERE id = ?");
            $stmt->execute([$newStock, $productId]);
        }
        $db->commit();
        //clearing
        $_SESSION['cart'] = []; 
        header("Location: customer.php?market=" . (isset($_GET['market']) ? $_GET['market'] : ''));
        exit;
    } catch (Exception $e) {
        $db->rollback();
        
    }
}


// shopping card: add to cart
if (isset($_GET['add_to_cart'])) {
    $productId = $_GET['add_to_cart'];
    if (!isset($_SESSION['cart'][$productId])) {
        //initial value
        $_SESSION['cart'][$productId] = 1; 
    } else {
        // increment
        $_SESSION['cart'][$productId]++; 
    }
    header("Location: customer.php?market=" . (isset($_GET['market']) ? $_GET['market'] : ''));
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Product Page</title>
    <link rel="stylesheet" href="./css/customeruser.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Welcome Back <?= $_SESSION['user']['name'] ?></h2>
            <a href="editcustomer.php?id=<?= $user["id"] ?>">Edit Info</a>
            <div class="logout"><a href="logout.php">Log Out</a></div>
        </div>
        <div class="products">
            <div class="market-selection">
                <form action="" method="GET">
                    <label for="market">Select Market:</label>
            
                    <select name="market" id="market" onchange="this.form.submit()">
                        <option value="">Select a Market</option>
                        <?php
                        $markets = getMarkets();
                        foreach ($markets as $marketItem) {
                            $selected = (isset($_GET['market']) && $marketItem['email'] == $_GET['market']) ? 'selected' : '';
                            echo "<option value='" . $marketItem['email'] . "' $selected>" . $marketItem['name'] . "</option>";
                        }
                        ?>
                    </select>
                </form>
            </div>
            <div class="search-bar">
                <form action="customer.php" method="GET">
                    <input type="hidden" name="market" value="<?php echo isset($_GET['market']) ? $_GET['market'] : ''; ?>">
                    <input type="text" id="search" name="search" placeholder="Search products..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                    <button id="buttons" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
            </div>
            <h2>Products</h2>
            <table>
                <tr>
                    <th>TITLE</th>
                    <th>STOCK</th>
                    <th>NORMAL PRICE</th>
                    <th>DISCOUNTED PRICE</th>
                    <th>EXPIRATION DATE</th>
                    <th>IMAGE</th>
                    <th>CART</th>
                </tr>

                <?php foreach ($market_products as $pro) : ?>
                <tr>
                    <td><?= $pro["title"] ?></td>
                    <td><?= $pro["stock"] ?></td>
                    <td>$<?= $pro["normalprice"] ?></td>
                    <td>$<?= $pro["discountedprice"] ?></td>
                    <td><?= $pro["expirationdate"] ?></td>
                    <td><img src="uploads/<?= $pro['image'] ?>" alt="<?= $pro['title'] ?>" style="width: 50px; height: 50px;"></td>
                    <td><a href="customer.php?market=<?= $_GET['market'] ?>&add_to_cart=<?= $pro["id"] ?>"><i class="fa-solid fa-cart-shopping"></i></a></td>
                </tr>
                <?php endforeach ?>
            </table>
            <div class="pages">
                <?php
                if (isset($_GET['search']) && !empty($_GET['search'])) {
                    for ($i = 1; $i <= $pageSize; $i++) {
                        $active = ($i == $page) ? 'active' : '';
                        echo "<a class='$active' href='?market=" . $_GET['market'] . "&search=" . $_GET['search'] . "&page=$i'>$i</a>";
                    }
                }
                ?>
            </div>

            
            <h2>Shopping Cart</h2>
            <form action="customer.php?market=<?= isset($_GET['market']) ? $_GET['market'] : '' ?>" method="POST">
                <table>
                    <tr>
                        <th>TITLE</th>
                        <th>QUANTITY</th>
                        <th>UNIT PRICE</th>
                        <th>TOTAL</th>
                        <th>REMOVE</th>
                    </tr>
                    <?php foreach ($_SESSION['cart'] as $productId => $quantity) :
                        $product = getProductById($productId);
                    ?>
                    <tr>
                        <td><?= $product["title"] ?></td>
                        <td><input type="number" name="quantities[<?= $productId ?>]" value="<?= $quantity ?>" min="0" max="<?= $product["stock"] ?>"></td>
                        <td>$<?= $product["discountedprice"] ?></td>
                        <td>$<?= $product["discountedprice"] * $quantity ?></td>
                        <td><button id="buttons" type="submit" name="remove" value="<?= $productId ?>">Remove</button></td></tr>
                    <?php endforeach ?>
                </table>
                <h3>Grand Total: $<?= calculateGrandTotal($_SESSION['cart']) ?></h3>
                <button type="submit" name="update_cart" id="buttons">Update Cart</button>
                <button type="submit" name="purchase" id="buttons">Purchase</button>
            </form>
        </div>
    </div>
</body>
</html>
