<?php
session_start();
include 'connection.php';

if (isset($_POST['place_order'])) {
    $customer_id = $_POST['customer_id'];
    $shipping_address = $_POST['shipping_address'];
    $total_amount = $_POST['total_amount'];

    // Insert into orders table
    $insert_order_query = "INSERT INTO orders (customer_id, order_date, shipping_address, total_amount, status)
                           VALUES (?, NOW(), ?, ?, 'Pending')";

    $stmt = $conn->prepare($insert_order_query);
    $stmt->bind_param('isd', $customer_id, $shipping_address, $total_amount);

    if ($stmt->execute()) {
        $order_id = $stmt->insert_id; // Get the inserted order_id

        // Insert into order_items table
        foreach ($_SESSION['cart'] as $product_id => $item) {
            $quantity = $item['quantity'];
            $total_item_amount = $item['quantity'] * $item['price']; // Assuming price is fetched from the products table

            $insert_item_query = "INSERT INTO order_items (order_id, product_id, quantity, total_amount)
                                 VALUES (?, ?, ?, ?)";
            
            $stmt_items = $conn->prepare($insert_item_query);
            $stmt_items->bind_param('iiid', $order_id, $product_id, $quantity, $total_item_amount);
            $stmt_items->execute();
        }

        // Clear the cart after successful order placement
        unset($_SESSION['cart']);
        $_SESSION['success'] = "Order placed successfully. Order ID: $order_id";
        header('Location: order_confirmation.php?order_id=' . $order_id);
        exit();
    } else {
        $_SESSION['error'] = "Failed to place order. Please try again.";
        header('Location: checkout.php');
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid request.";
    header('Location: checkout.php');
    exit();
}
?>
