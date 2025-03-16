<?php
require_once '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $new_password = md5($_POST['new_password']); // Use md5 hashing for the new password

    $query = "SELECT * FROM admins WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $update_query = "UPDATE admins SET password = ? WHERE username = ?";
        $update_stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($update_stmt, 'ss', $new_password, $username);
        mysqli_stmt_execute($update_stmt);
        mysqli_stmt_close($update_stmt);
        $success_message = "Password has been reset successfully.";
    } else {
        $error_message = "Username not found";
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
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: aquamarine;
        }
        .forgot-container {
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
    <div class="forgot-container">
        <h2 class="text-center">Forgot Password</h2>
        <?php if (isset($error_message)) { echo "<div class='alert alert-danger'>$error_message</div>"; } ?>
        <?php if (isset($success_message)) { echo "<div class='alert alert-success'>$success_message</div>"; } ?>
        <form action="forgot-password.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
            <a href="admin-login.php" class="d-block text-center mt-3">Back to Login</a>
        </form>
    </div>
</body>
</html>
