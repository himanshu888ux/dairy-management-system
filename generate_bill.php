<?php
session_start();
include 'connection.php';

// Check if user is logged in or redirect to login page
if (!isset($_SESSION['customer_id'])) {
    header('Location: login.php');
    exit();
}

// Initialize variables
$first_name = '';
$last_name = '';
$email = '';
$address = '';
$order_id = '';
$order_date = '';
$total_amount = 0.00;
$ordered_items = [];

// Fetch customer details
$customer_id = $_SESSION['customer_id'];
$query_customer = "SELECT first_name, last_name, email, address FROM customer WHERE customer_id = ?";
$stmt_customer = $conn->prepare($query_customer);
$stmt_customer->bind_param('i', $customer_id);
$stmt_customer->execute();
$result_customer = $stmt_customer->get_result();

if ($result_customer->num_rows > 0) {
    $row_customer = $result_customer->fetch_assoc();
    $first_name = $row_customer['first_name'];
    $last_name = $row_customer['last_name'];
    $email = $row_customer['email'];
    $address = $row_customer['address'];
}

// Fetch order details
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $query_order = "SELECT order_date, total_amount FROM orders WHERE order_id = ?";
    $stmt_order = $conn->prepare($query_order);
    $stmt_order->bind_param('i', $order_id);
    $stmt_order->execute();
    $result_order = $stmt_order->get_result();

    if ($result_order->num_rows > 0) {
        $row_order = $result_order->fetch_assoc();
        $order_date = $row_order['order_date'];
        $total_amount = $row_order['total_amount'];
    }

    // Fetch ordered items with product details
    $query_items = "SELECT p.product_name, p.description, p.price, oi.quantity, oi.total_amount AS item_total
                    FROM order_items oi
                    JOIN products p ON oi.product_id = p.product_id
                    WHERE oi.order_id = ?";
    $stmt_items = $conn->prepare($query_items);
    $stmt_items->bind_param('i', $order_id);
    $stmt_items->execute();
    $result_items = $stmt_items->get_result();

    if ($result_items->num_rows > 0) {
        while ($row_item = $result_items->fetch_assoc()) {
            $ordered_items[] = $row_item;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Bill</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .bill-container {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            margin-top: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .logo-container {
            flex: 0 0 20%;
        }
        .bill-header {
            flex: 0 0 80%;
            text-align: center;
        }
        .customer-details, .order-details, .order-items {
            margin-top: 20px;
        }
        .back-btn {
            margin-top: 20px;
            text-align: center;
        }
        .customer-info {
            margin-bottom: 20px;
        }
        .customer-info p {
            margin-bottom: 5px;
        }
        .item-table {
            width: 100%;
            border-collapse: collapse;
        }
        .item-table th, .item-table td {
            padding: 8px;
            text-align: left;
            border: none;
        }
        .item-table th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="bill-container">
            <div class="logo-container">
                <img src="images/logo.jpeg" alt="Logo" style="max-width: 100%;">
            </div>
            <div class="bill-header">
                <h2>Order Bill</h2>
            </div>
        </div>
        <div class="bill-container">
            <div class="customer-details">
                <h4>Customer Information</h4>
                <div class="customer-info">
                    <p><strong>Customer Name:</strong> <?php echo isset($first_name) ? $first_name . ' ' . $last_name : ''; ?></p>
                    <p><strong>Email:</strong> <?php echo isset($email) ? $email : ''; ?></p>
                    <p><strong>Shipping Address:</strong> <?php echo isset($address) ? $address : ''; ?></p>
                </div>
            </div>
            <div class="order-details">
                <h4>Order Details</h4>
                <p><strong>Order ID:</strong> <?php echo isset($order_id) ? $order_id : ''; ?></p>
                <p><strong>Order Date:</strong> <?php echo isset($order_date) ? $order_date : ''; ?></p>
                <p><strong>Total Amount:</strong> ₹ <?php echo isset($total_amount) ? number_format($total_amount, 2) : '0.00'; ?></p>
            </div>
        </div>
        <div class="bill-container">
            <div class="order-items">
                <h4>Ordered Items</h4>
                <table class="item-table">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Description</th>
                            <th>Price (₹)</th>
                            <th>Quantity</th>
                            <th>Item Total (₹)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($ordered_items)): ?>
                            <?php foreach ($ordered_items as $item): ?>
                                <tr>
                                    <td><?php echo $item['product_name']; ?></td>
                                    <td><?php echo $item['description']; ?></td>
                                    <td><?php echo number_format($item['price'], 2); ?></td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td>₹ <?php echo number_format($item['item_total'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No items found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="back-btn">
            <a href="order_history.php" class="btn btn-primary">Back to Orders</a>
            <button onclick="window.print()" class="btn btn-success">Print</button>
        </div>
    </div>
</body>
</html>
