<?php
include "connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $password = trim($_POST['password']);
    $cnf_password = trim($_POST['cnf-password']);

    // Validate input data
    $errors = array();

    if (empty($fname)) {
        $errors[] = "First name is required";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $fname)) {
        $errors[] = "Invalid first name (only letters and spaces allowed)";
    }

    if (empty($lname)) {
        $errors[] = "Last name is required";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $lname)) {
        $errors[] = "Invalid last name (only letters and spaces allowed)";
    }

    if (empty($phone)) {
        $errors[] = "Phone number is required";
    } elseif (!preg_match("/^[0-9]{10,15}$/", $phone)) {
        $errors[] = "Invalid phone number (must be 10-15 digits)";
    }

    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address";
    }

    if (empty($address)) {
        $errors[] = "Address is required";
    }

    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    }

    if (empty($cnf_password)) {
        $errors[] = "Confirm password is required";
    } elseif ($password!= $cnf_password) {
        $errors[] = "Passwords do not match";
    }

    if (!empty($errors)) {
        echo "<script>alert('".implode("\\n", $errors)."');</script>";
        exit;
    }

    // Hash password using md5()
    $password_hash = md5($password);

    // Use prepared statement with parameterized query
    $query = "INSERT INTO customer (`first_name`, `last_name`, `phone_number`, `email`, `address`, `password`) VALUES (?,?,?,?,?,?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssss", $fname, $lname, $phone, $email, $address, $password_hash);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo "<script>alert('Sign up successful');</script>";
        header("location:index.php");
        exit;
    } else {
        echo "<script>alert('Sign up failed');</script>";
    }
}
?>