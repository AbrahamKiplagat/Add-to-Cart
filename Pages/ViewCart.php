<?php
session_start();
require_once('db.php');
error_reporting(E_ALL);
ini_set('display_errors', 2);

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if (!$result) {
    die("Error fetching products: " . $conn->error);
}

if (!empty($_SESSION['checkoutList'])) {
    $checkoutList = $_SESSION['checkoutList'];
} else {
    $checkoutList = array();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_to_cart"])) {
    $productId = $_POST["product_id"];
    $quantity = isset($_POST["quantity"]) ? intval($_POST["quantity"]) : 1;

    $productDetails = getProductDetails($productId);
    if ($productDetails) {
        $totalPrice = $productDetails['price'] * $quantity;

        $checkoutList[$productId] = array(
            'name' => $productDetails['name'],
            'quantity' => $quantity,
            'total_price' => $totalPrice
        );

        $_SESSION['checkoutList'] = $checkoutList;

        // Display a confirmation message or redirect
        echo "Product added to cart successfully!";
        // Alternatively, you can redirect to the ViewProducts page or any other page
        // header("Location: ViewProducts.php");
        // exit();
    } else {
        // Handle product not found error
        echo "Error: Product not found.";
    }
}

function getProductDetails($productId)
{
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $productId);

    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }

    $result = $stmt->get_result();

    if (!$result) {
        die("Error fetching result: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}





// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
    <!-- Add your styles and scripts here -->
</head>

<body>

    <?php include 'Navbar.php'; ?>

    <div class="container mt-5">
        <h2>View Products</h2>

        <div class="row">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <img class="card-img-top" src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h4 class="card-title"><?php echo $row['name']; ?></h4>
                            <h5>$<?php echo $row['price']; ?></h5>
                            <p class="card-text"><?php echo $row['description']; ?></p>
                        </div>
                        <div class="card-footer">
                            <form method="post">
                                <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                                <label for="quantity">Quantity:</label>
                                <input type="number" name="quantity" value="1" min="1">
                                <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

</body>

</html>
