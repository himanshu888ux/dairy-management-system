<?php
session_start();
require_once '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Use md5 hashing for the password

    // Check if connection was successful
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT * FROM admin WHERE username = ? AND password = ?";
    $stmt = mysqli_prepare($conn, $query);

    // Check if preparation of statement was successful
    if ($stmt === false) {
        die('mysqli error: ' . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, 'ss', $username, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $row['id'];
        header('Location: admin-dashboard.php');
        exit;
    } else {
        $error_message = "Invalid username or password";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: aquamarine;
        }
        .login-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        .btn-primary {
            background-color: green;
            border-color: green;
        }
        .btn-primary:hover {
            background-color: darkgreen;
            border-color: darkgreen;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="text-center">Admin Login</h2>
        <?php if (isset($error_message)) { echo "<div class='alert alert-danger'>$error_message</div>"; } ?>
        <form action="admin-login.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
            <a href="forgot-password.php" class="d-block text-center mt-3">Forgot Password?</a>
        </form>
    </div>
</body>
</html>
