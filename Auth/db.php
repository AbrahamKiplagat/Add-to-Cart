<?php
$servername = "localhost"; // Change this if your MySQL server is hosted elsewhere
$username = "root";
$password = "@Mysql01";
$database = "cart_exercise";

// Create connection
$conn = new mysqli($servername, $username, $password, $database); // Corrected variable name

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully";
}
?>
