<?php
session_start();

require_once('db.php');

// Function to get product details from the database
function getProductDetails($productId)
{
    global $conn;

    $sql = "SELECT * FROM products WHERE id = $productId";
    $result = $conn->query($sql);

    return $result->fetch_assoc();
}

// Check if the cart is not empty
if (!empty($_SESSION['cart'])) {
    $cartItems = $_SESSION['cart'];
} else {
    $cartItems = array();
}

// Process the checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Perform additional validation and security checks if needed

    // Store the checkout details in the database (example: checkout table)
    foreach ($cartItems as $productId => $item) {
        $product = getProductDetails($productId);

        // Insert into checkout table (modify based on your database schema)
        $sql = "INSERT INTO checkout (user_id, product_id, quantity, total_price) VALUES (
            1, -- Replace with the actual user ID
            $productId,
            {$item['quantity']},
            {$item['total_price']}
        )";
        
        $conn->query($sql);
    }

    // Clear the cart after checkout
    $_SESSION['cart'] = array();

    // Redirect or perform additional actions after successful checkout
    header("Location: thankYou.php"); // Replace with your thank you page
    exit();
}
?>
