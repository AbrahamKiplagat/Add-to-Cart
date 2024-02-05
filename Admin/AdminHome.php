<?php
session_start();

// Check if a session is not started or the username is empty
if (!isset($_SESSION['username']) || empty($_SESSION['username']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../Auth/login.php");
    exit();
}

$welcomeMessage = "Welcome Admin: " . $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Admin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
<?php
include 'Navbar.php';
?>
<style>
    /* body {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
        background-color: #f8f9fa;  Bootstrap background color 
    }*/
/*
    .container {
        width: 400px;
        margin-top: 50px;
    }

    .alert {
        margin-top: 20px;
    }

    form {
        margin-top: 20px;
    }

    .mt-3 {
        margin-top: 20px;
    }

    p {
        text-align: center;
    } */
</style>

    <div class="container mt-5">
        <h2>Welcome Admin</h2>
        <p><?php echo $welcomeMessage; ?></p>
        <a href="../Auth/logout.php">Logout</a>
    </div>
</body>
</html>
