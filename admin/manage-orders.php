<?php
// manage-orders.php

// Include database connection file
require_once '../connection.php';

// Start session
session_start();

// Check if the user is logged in
/*
if (!isset($_SESSION['username'])) {
  header('Location: login.php');
  exit;
}
*/

// Initialize orders array
$orders = array();

// Retrieve order information from database
$query = "SELECT * FROM orders";
$result = mysqli_query($conn, $query);

// Check if query was successful
if ($result) {
  // Check if there are any orders
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $orders[] = $row;
    }
  }
} else {
  die("Query failed: " . mysqli_error($conn));
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Orders</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.bootstrap4.min.css">
  <style>
    .table thead th {
      color: green;
    }
    .dt-button {
      background-color: blue !important;
      color: white !important;
    }
    .content-wrapper {
      margin-left: 260px;
      transition: all 0.5s;
      background-color: #f8f9fa;
      padding: 20px;
      min-height: 100vh;
    }
    .content {
      padding: 20px;
      background-color: #fff;
      border-radius: 5px;
    }
  </style>
</head>
<body>
  <?php include 'sidebar.php'; ?>

  <!-- Dashboard Container -->
  <div class="content-wrapper">
    <div class="content">
      <h1>Manage Orders</h1>
      <table id="orders-table" class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Order Date</th>
            <th>Total</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $order) { ?>
          <tr>
            <td><?= $order['order_id'] ?></td>
            <td><?= $order['customer_name'] ?></td>
            <td><?= $order['order_date'] ?></td>
            <td><?= $order['total'] ?></td>
            <td><?= $order['status'] ?></td>
            <td>
              <a href="view-order.php?id=<?= $order['order_id'] ?>" class="btn btn-primary">View</a>
              <a href="edit-order.php?id=<?= $order['order_id'] ?>" class="btn btn-warning">Edit</a>
              <a href="delete-order.php?id=<?= $order['order_id'] ?>" class="btn btn-danger">Delete</a>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.bootstrap4.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script>
    $(document).ready(function() {
      $('#orders-table').DataTable({
        dom: 'Bfrtip',
        buttons: [
          {
            extend: 'copy',
            text: 'Copy',
            className: 'btn btn-primary'
          },
          {
            extend: 'csv',
            text: 'CSV',
            className: 'btn btn-primary'
          },
          {
            extend: 'excel',
            text: 'Excel',
            className: 'btn btn-primary'
          },
          {
            extend: 'pdf',
            text: 'PDF',
            className: 'btn btn-primary'
          },
          {
            extend: 'print',
            text: 'Print',
            className: 'btn btn-primary'
          }
        ],
        "language": {
          "emptyTable": "No orders available"
        }
      });
    });
  </script>
</body>
</html>
