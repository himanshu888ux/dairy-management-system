<?php
session_start();
include 'connection.php';

// Check if order_id is provided in the URL
if (isset($_GET['order_id'])) {
    $order_id = htmlspecialchars($_GET['order_id']);

    // Fetch order details from the database
    $stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
    $stmt->bind_param('i', $order_id);
    $stmt->execute();
    $order_result = $stmt->get_result();

    if ($order_result->num_rows > 0) {
        $order = $order_result->fetch_assoc();

        // Fetch order items from the database
        $stmt_items = $conn->prepare("SELECT oi.quantity, oi.total_amount, p.product_name, p.price FROM order_items oi INNER JOIN products p ON oi.product_id = p.product_id WHERE oi.order_id = ?");
        $stmt_items->bind_param('i', $order_id);
        $stmt_items->execute();
        $order_items_result = $stmt_items->get_result();
    } else {
        $_SESSION['error'] = "Order not found.";
        header('Location: index.php');
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid order details.";
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Dairy Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- Navbar Section-->
    <?php include "header.php"; ?>

    <div class="container mt-4">
        <h2>Order Confirmation</h2>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="card mt-3">
            <div class="card-header">
                <h4>Order Details</h4>
            </div>
            <div class="card-body">
                <p><strong>Order ID:</strong> <?php echo $order['order_id']; ?></p>
                <p><strong>Order Date:</strong> <?php echo date('d M Y, H:i', strtotime($order['order_date'])); ?></p>
                <p><strong>Total Amount:</strong> <?php echo $order['total_amount']; ?> ₹</p>
                <p><strong>Status:</strong> <?php echo $order['status']; ?></p>
                <p><strong>Shipping Address:</strong><br><?php echo htmlspecialchars($order['shipping_address']); ?></p>
            </div>
        </div>

        <div class="mt-4">
            <h4>Order Items</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = $order_items_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                            <td><?php echo $item['price']; ?> ₹</td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td><?php echo $item['total_amount']; ?> ₹</td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <a href="index.php" class="btn btn-secondary">Continue Shopping</a>
        </div>
    </div>

    <!-- Footer section -->
    <?php include "footer.php"; ?>
</body>

</html>
