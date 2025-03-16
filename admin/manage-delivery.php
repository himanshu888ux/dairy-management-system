<?php
// manage-delivery.php

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

// Initialize deliveries array
$deliveries = array();

// Retrieve delivery information from database
$query = "SELECT * FROM deliveries";
$result = mysqli_query($conn, $query);

// Check if query was successful
if ($result) {
  // Check if there are any deliveries
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $deliveries[] = $row;
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
  <title>Manage Deliveries</title>
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
      <h1>Manage Deliveries</h1>
      <table id="deliveries-table" class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>Delivery ID</th>
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Delivery Address</th>
            <th>Delivery Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($deliveries as $delivery) { ?>
          <tr>
            <td><?= $delivery['delivery_id'] ?></td>
            <td><?= $delivery['order_id'] ?></td>
            <td><?= $delivery['customer_name'] ?></td>
            <td><?= $delivery['delivery_address'] ?></td>
            <td><?= $delivery['delivery_status'] ?></td>
            <td>
              <a href="edit-delivery.php?id=<?= $delivery['delivery_id'] ?>" class="btn btn-primary">Edit</a>
              <a href="delete-delivery.php?id=<?= $delivery['delivery_id'] ?>" class="btn btn-danger">Delete</a>
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
      $('#deliveries-table').DataTable({
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
          "emptyTable": "No deliveries available"
        }
      });
    });
  </script>
</body>
</html>
