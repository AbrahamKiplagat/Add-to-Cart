<?php
session_start();

include('../db.php'); // Replace with the correct path to your database connection file

function isUsernameExists($conn, $username) {
    $count = 0; // Initialize $count to 0
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    return $count > 0;
}

function isEmailExists($conn, $email) {
    $count = 0; // Initialize $count to 0
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    return $count > 0;
}

function registerUser($conn, $username, $email, $password, $role) {
    if (isUsernameExists($conn, $username)) {
        return "Username already exists";
    }

    if (isEmailExists($conn, $email)) {
        return "Email already exists";
    }

    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $password, $role);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    if (empty($username) || empty($email) || empty($_POST['password']) || empty($role)) {
        $_SESSION['error'] = "All fields are required";
    } else {
        // Check if the provided role is either 'Admin' or 'User'
        if ($role !== 'Admin' && $role !== 'User') {
            $_SESSION['error'] = "Invalid role";
            header("Location: ../Auth/register.php");
            exit();
        }

        $registrationResult = registerUser($conn, $username, $email, $password, $role);

        if ($registrationResult === true) {
            $_SESSION['success'] = "Registration successful. You can now log in.";
            // header("Location: ../Pages/Home.php");
            exit();
        } else {
            $_SESSION['error'] = "Registration failed. " . $registrationResult;
            header("Location: ../Auth/register.php");
            exit();
        }
    }
}

$conn->close();
?>
<!-- Rest of your HTML code remains unchanged -->
<!DOCTYPE html>
<!-- rest of your HTML code remains unchanged -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Registration Form</h2>
        <?php
            if (isset($_SESSION['success'])) {
                echo '<p style="color: green;">' . $_SESSION['success'] . '</p>';
                unset($_SESSION['success']);
            } elseif (isset($_SESSION['error'])) {
                echo '<p class="error-message">' . $_SESSION['error'] . '</p>';
                unset($_SESSION['error']);
            }
        ?>
        <form method="post" action="">
            <label for="username">Username:</label>
            <input type="text" name="username" required>
            
            <label for="email">Email:</label>
            <input type="email" name="email" required>
            
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            
            <label for="role">Role:</label>
            <select name="role" required>
                <option value="Admin">Admin</option>
                <option value="User">User</option>
            </select>
            
            <input type="submit" name="register" value="Register">
            <p>Already have an account? <a href="login.php">Go to login</a></p>
        </form>
    </div>
</body>
</html>
