<?php
session_start();
include 'connection.php';
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php"); // Redirect to your login page
    exit(); // Ensure that script execution stops after redirection
}
// Check if cart is empty
if (empty($_SESSION['cart'])) {
    $_SESSION['error'] = "Your cart is empty. Please add some items.";
    header('Location: index.php');
    exit();
}

// Assuming customer_id is stored in session
$customer_id = $_SESSION['customer_id']; // Replace with your actual session variable

// Fetch shipping address if it exists
$shipping_address = isset($_SESSION['shipping_address']) ? $_SESSION['shipping_address'] : '';

// Function to calculate total amount
function calculateTotalAmount() {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['quantity'] * $item['price']; // Assuming price is fetched from products table
    }
    return $total;
}

$total_amount = calculateTotalAmount();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Checkout</h2>
        <form action="place_order.php" method="post">
            <div class="form-group">
                <label for="shipping_address">Shipping Address</label>
                <input type="text" class="form-control" id="shipping_address" name="shipping_address" value="<?php echo htmlspecialchars($shipping_address); ?>" required>
            </div>
            
            <h4>Order Summary</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['cart'] as $product_id => $item) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($item['price']); ?> ₹</td>
                            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($item['quantity'] * $item['price']); ?> ₹</td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3" class="text-right"><strong>Total Amount:</strong></td>
                        <td><strong><?php echo htmlspecialchars($total_amount); ?> ₹</strong></td>
                    </tr>
                </tbody>
            </table>
            
            <input type="hidden" name="customer_id" value="<?php echo htmlspecialchars($customer_id); ?>">
            <input type="hidden" name="total_amount" value="<?php echo htmlspecialchars($total_amount); ?>">
            
            <button type="submit" class="btn btn-primary" name="place_order">Place Order</button>
        </form>
    </div>
</body>
</html>
