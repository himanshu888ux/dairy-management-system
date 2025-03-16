<?php
session_start();

if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    if ($quantity > 0 && isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    } elseif ($quantity <= 0) {
        unset($_SESSION['cart'][$product_id]);
    }
}

header("Location: cart.php");
exit();
?>
