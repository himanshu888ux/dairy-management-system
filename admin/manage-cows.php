<?php
// manage-cows.php

// Include database connection file
require_once '../connection.php';

// Check if the user is logged in (session management)
session_start();

// Retrieve cows from database
$query = "SELECT * FROM cows";
$result = mysqli_query($conn, $query);

// Check if there are any cows
if (mysqli_num_rows($result) > 0) {
  $cows = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $cows[] = $row;
  }
} else {
  $cows = array();
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Cows</title>
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
    .add-cow-btn {
      float: right;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<!-- Dashboard Container -->
<div class="content-wrapper">
  <div class="content">
    <h1>Manage Cows</h1>
    <button class="btn btn-primary add-cow-btn" data-toggle="modal" data-target="#addCowModal">Add Cow</button>
    <table id="cows-table" class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>Cow ID</th>
          <th>Cow Name</th>
          <th>Breed</th>
          <th>Date of Birth</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($cows as $cow) { ?>
        <tr>
          <td><?= $cow['cow_id'] ?></td>
          <td><?= $cow['cow_name'] ?></td>
          <td><?= $cow['breed'] ?></td>
          <td><?= $cow['dob'] ?></td>
          <td>
            <a href="edit-cow.php?id=<?= $cow['cow_id'] ?>" class="btn btn-primary">Edit</a>
            <a href="delete-cow.php?id=<?= $cow['cow_id'] ?>" class="btn btn-danger">Delete</a>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Add Cow Modal -->
<div class="modal fade" id="addCowModal" tabindex="-1" role="dialog" aria-labelledby="addCowModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="add-cow.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="addCowModalLabel">Add Cow</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="cowName">Cow Name</label>
            <input type="text" class="form-control" id="cowName" name="cowName" required>
          </div>
          <div class="form-group">
            <label for="breed">Breed</label>
            <input type="text" class="form-control" id="breed" name="breed" required>
          </div>
          <div class="form-group">
            <label for="dob">Date of Birth</label>
            <input type="date" class="form-control" id="dob" name="dob" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Add Cow</button>
        </div>
      </form>
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
    $('#cows-table').DataTable({
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
        "emptyTable": "No cows available"
      }
    });
  });
</script>
</body>
</html>
