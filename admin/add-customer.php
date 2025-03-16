<?php
// add-customer.php

// Include database connection file
require_once '../connection.php';

// Check if the user is logged in (session management)
session_start();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input (you may add more validation as needed)
    $customer_name = $_POST['customer_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Insert customer into database
    $insert_query = "INSERT INTO dairy_customers (customer_name, email, phone, address) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, 'ssss', $customer_name, $email, $phone, $address);

    if (mysqli_stmt_execute($stmt)) {
        // Redirect to manage-dairy-customers.php with success message
        header('Location: manage-dairy-customers.php?success=1');
        exit();
    } else {
        // Handle errors if needed
        $error_message = "Failed to add customer. Please try again.";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
