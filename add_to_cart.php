<?php
session_start();
include 'connection.php';

if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    // Validate quantity (positive integer)
    if ($quantity > 0) {
        // Fetch product details from the database
        $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
        $stmt->bind_param('i', $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        if ($product) {
            $product_name = $product['product_name'];
            $price = $product['price'];
            $product_image = $product['product_image'];

            // Initialize cart if not already set
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = array();
            }

            // Check if product already exists in cart
            if (isset($_SESSION['cart'][$product_id])) {
                // Update quantity if product exists in cart
                $_SESSION['cart'][$product_id]['quantity'] += $quantity;
            } else {
                // Add new product to the cart
                $_SESSION['cart'][$product_id] = array(
                    'product_id' => $product_id,
                    'product_name' => $product_name,
                    'price' => $price,
                    'quantity' => $quantity,
                    'product_image' => $product_image
                );
            }

            // Set success message
            $_SESSION['success'] = "Product added to cart successfully.";
        } else {
            // Set error message if product not found
            $_SESSION['error'] = "Product not found.";
        }
    } else {
        // Set error message if quantity is invalid
        $_SESSION['error'] = "Invalid quantity. Please enter a valid quantity.";
    }
} else {
    // Set error message if product_id or quantity is not set
    $_SESSION['error'] = "Invalid request. Please try again.";
}

// Redirect back to the products page or any other page
header("Location: index.php");
exit();
?>
