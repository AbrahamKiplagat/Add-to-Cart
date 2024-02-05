<?php
session_start();

// Check if a session is not started or the username is empty
if (!isset($_SESSION['username']) || empty($_SESSION['username']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../Auth/login.php"); // Redirect to the login page
    exit();
}

require_once('db.php'); // Include the database connection file

class Product
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function addProduct($name, $image, $price, $description)
    {
        $conn = $this->db;

        $name = $conn->real_escape_string($name);
        $image = $conn->real_escape_string($image);
        $price = $conn->real_escape_string($price);
        $description = $conn->real_escape_string($description);

        $query = "INSERT INTO products (name, image, price, description) VALUES ('$name', '$image', '$price', '$description')";

        if ($conn->query($query)) {
            echo "Product added successfully.";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

// Create a Product instance with the existing database connection
$product = new Product($conn);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $price = $_POST["price"];
    $description = $_POST["description"];

    // Handle image upload
    $targetDir = "../uploads/";
    $targetFile = $targetDir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);

    // Add product to the database
    $product->addProduct($name, $targetFile, $price, $description);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
</head>

<body>

    <?php include 'Navbar.php'; ?>

    <div class="container mt-5">
        <h2 class="mb-4">Add Product</h2>

        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Product Name:</label>
                <input type="text" class="form-control" name="name" required>
            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="text" class="form-control" name="price" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" name="description" required></textarea>
            </div>

            <div class="form-group">
                <label for="image">Image:</label>
                <input type="file" class="form-control-file" name="image" required>
            </div>


            <button type="submit" class="btn btn-primary">Add Product</button>
        </form>
    </div>

</body>

</html>