<?php
// Include the database connection file
require_once('db.php');

// Fetch products from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"> -->
</head>

<body>

    <?php include 'Navbar.php'; ?>

    <div class="container mt-5">
        <h2>View Products</h2>

        <div class="row">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <?php
                        // Check if the image is stored in the database or in the uploads folder
                        if (strpos($row['image'], 'data:image') === 0) {
                            // Display the image directly from the database using base64 encoding
                            $imageData = $row['image'];
                        } else {
                            // Display the image from the uploads folder
                            $imagePath = $row['image'];
                            $imageData = base64_encode(file_get_contents($imagePath));
                        }
                        $imageSrc = "data:image/jpeg;base64,$imageData";
                        ?>
                        <img class="card-img-top" src="<?php echo $imageSrc; ?>" alt="<?php echo $row['name']; ?>" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h4 class="card-title"><?php echo $row['name']; ?></h4>
                            <h5>$<?php echo $row['price']; ?></h5>
                            <p class="card-text"><?php echo $row['description']; ?></p>
                        </div>
                        <div class="card-footer">
                            <form action="add_to_cart.php" method="post">
                                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-primary">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <?php
    // Close the database connection
    $conn->close();
    ?>

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script> -->
</body>

</html>
