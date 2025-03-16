<?php
session_start();
include 'connection.php';

// Check if user is logged in or redirect to login page
if (!isset($_SESSION['customer_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch orders based on status
$statuses = ['pending', 'confirmed', 'delivered', 'cancelled'];
$current_status = isset($_GET['status']) ? $_GET['status'] : 'all';
$condition = '';
if ($current_status !== 'all' && in_array($current_status, $statuses)) {
    $condition = " WHERE status = '$current_status'";
}

// Query to fetch orders
$query = "SELECT order_id, order_date, total_amount, status FROM orders" . $condition;
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <!-- Include Bootstrap CSS and DataTables CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Order History</h2>
        <div class="d-flex justify-content-end">
            <a class="btn btn-danger" href="index.php">Go To Home</a>
        </div>
        <!-- Tabs for different order statuses -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link <?php if ($current_status === 'all') echo 'active'; ?>" 
                   href="order_history.php?status=all">All Orders</a>
            </li>
            <?php foreach ($statuses as $status): ?>
                <li class="nav-item">
                    <a class="nav-link <?php if ($current_status === $status) echo 'active'; ?>" 
                       href="order_history.php?status=<?php echo $status; ?>"><?php echo ucfirst($status); ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
        
        <!-- Table to display orders -->
        <div class="tab-content mt-3" id="myTabContent">
            <div class="tab-pane fade show active" id="all_orders" role="tabpanel" aria-labelledby="all_orders-tab">
                <table id="orderTable" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Order Date</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $row['order_id']; ?></td>
                                <td><?php echo $row['order_date']; ?></td>
                                <td><?php echo $row['total_amount']; ?></td>
                                <td><?php echo ucfirst($row['status']); ?></td>
                                <td>
                                    <?php if ($row['status'] === 'delivered'): ?>
                                        <a href="generate_bill.php?order_id=<?php echo $row['order_id']; ?>" 
                                           class="btn btn-sm btn-info" target="_blank">Generate Bill</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Include jQuery, Bootstrap JS, and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>

    <!-- Initialize DataTables -->
    <script>
        $(document).ready(function() {
            $('#orderTable').DataTable();
        });
    </script>
</body>
</html>
