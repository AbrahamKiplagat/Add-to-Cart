<?php
session_start();
require_once('db.php');

class Checkout
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function addToCart($productId, $quantity)
    {
        $productDetails = $this->getProductDetails($productId);

        if ($productDetails) {
            $totalPrice = $productDetails['price'] * $quantity;

            $checkoutList = isset($_SESSION['checkoutList']) ? $_SESSION['checkoutList'] : array();

            $checkoutList[$productId] = array(
                'name' => $productDetails['name'],
                'quantity' => $quantity,
                'total_price' => $totalPrice
            );

            $_SESSION['checkoutList'] = $checkoutList;

            // Make an AJAX POST request to checkout.php
            $postData = array(
                'product_id' => $productId,
                'quantity' => $quantity,
                'total_price' => $totalPrice
            );

            $url = 'checkout.php';
            $options = array(
                'http' => array(
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method' => 'POST',
                    'content' => http_build_query($postData)
                )
            );
            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);

            // Handle the result from checkout.php
            echo $result;
        } else {
            // Handle product not found error
            echo "Error: Product not found.";
        }
    }

    private function getProductDetails($productId)
    {
        $sql = "SELECT * FROM products WHERE id = $productId";
        $result = $this->conn->query($sql);

        return $result->fetch_assoc();
    }
}

// Handle errors during database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Instantiate the Checkout class
$checkout = new Checkout($conn);

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_to_cart"])) {
    $productId = $_POST["product_id"];
    $quantity = isset($_POST["quantity"]) ? intval($_POST["quantity"]) : 1;

    // Call the addToCart method
    $checkout->addToCart($productId, $quantity);
}

// Close the database connection
$conn->close();
?>
