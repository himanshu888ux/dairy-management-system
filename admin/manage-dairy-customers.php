<?php
// manage-dairy-customers.php

// Include database connection file
require_once '../connection.php';

// Check if the user is logged in (session management)
session_start();

// Retrieve dairy customers from database
$query = "SELECT * FROM dairy_customers";
$result = mysqli_query($conn, $query);

// Check if there are any dairy customers
if (mysqli_num_rows($result) > 0) {
  $customers = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $customers[] = $row;
  }
} else {
  $customers = array();
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Dairy Customers</title>
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
    <h1>Manage Dairy Customers</h1>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#addCustomerModal">
      Add Customer
    </button>
    <br><br>
    <table id="customers-table" class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>Customer ID</th>
          <th>Customer Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Address</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($customers as $customer) { ?>
        <tr>
          <td><?= $customer['customer_id'] ?></td>
          <td><?= $customer['customer_name'] ?></td>
          <td><?= $customer['email'] ?></td>
          <td><?= $customer['phone'] ?></td>
          <td><?= $customer['address'] ?></td>
          <td>
            <a href="edit-customer.php?id=<?= $customer['customer_id'] ?>" class="btn btn-primary">Edit</a>
            <a href="delete-customer.php?id=<?= $customer['customer_id'] ?>" class="btn btn-danger">Delete</a>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCustomerModalLabel">Add Customer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Customer Form -->
        <form id="addCustomerForm" action="add-customer.php" method="post">
          <div class="form-group">
            <label for="customer_name">Customer Name</label>
            <input type="text" class="form-control" id="customer_name" name="customer_name" required>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" required>
          </div>
          <div class="form-group">
            <label for="address">Address</label>
            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Add Customer</button>
        </form>
      </div>
    </div>
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
    $('#customers-table').DataTable({
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
        "emptyTable": "No customers available"
      }
    });

    // Submit form using AJAX (Optional: Uncomment if AJAX form submission is needed)
    // $('#addCustomerForm').submit(function(e) {
    //   e.preventDefault();
    //   $.ajax({
    //     type: 'POST',
    //     url: 'add-customer.php',
    //     data: $(this).serialize(),
    //     success: function(response) {
    //       console.log('Customer added successfully');
    //       // Optionally, update DataTable or close modal, etc.
    //     },
    //     error: function(error) {
    //       console.error('Error adding customer:', error);
    //     }
    //   });
    // });
  });
</script>
</body>
</html>
