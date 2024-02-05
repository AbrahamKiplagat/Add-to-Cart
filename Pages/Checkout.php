<!-- Checkout.php -->
<?php
session_start();
require_once('db.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if checkoutList is not empty
if (!empty($_SESSION['checkoutList'])) {
    $checkoutList = $_SESSION['checkoutList'];
} else {
    $checkoutList = array();
}

// Initialize total sum
$totalSum = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>

<body>

    <?php include 'Navbar.php'; ?>

    <div class="container mt-5">
        <h2>Checkout</h2>

        <!-- Display items in the checkout list -->
        <?php foreach ($checkoutList as $productId => $item): ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo $item['name']; ?></h3>
                </div>
                <div class="panel-body">
                    <p><strong>Quantity:</strong> <?php echo $item['quantity']; ?></p>
                    <p><strong>Total Price:</strong> $<?php echo number_format($item['total_price'], 2); ?></p>
                </div>
            </div>

            <!-- Accumulate total sum -->
            <?php $totalSum += $item['total_price'] * $item['quantity']; ?>
        <?php endforeach; ?>

        <!-- Display the total sum -->
        <div class="panel panel-default mt-3">
            <div class="panel-heading">
                <h3 class="panel-title">Total Sum</h3>
            </div>
            <div class="panel-body">
                <p><strong>Total Sum:</strong> $<?php echo number_format($totalSum, 2); ?></p>
            </div>
        </div>
    </div>
</body>

</html>
