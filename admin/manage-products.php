<?php
// manage-products.php

// Include database connection file
require_once '../connection.php';

// Check if the user is logged in
session_start();

// Retrieve products from database
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);

// Check if there are any products
if (mysqli_num_rows($result) > 0) {
  $products = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
  }
} else {
  $products = array();
}

// Close database connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Products</title>
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
    .add-product-btn {
      float: right;
      margin-bottom: 10px;
    }
    
    .product-image {
      max-width: 100px; /* Adjust as per your design */
      height: auto; /* Maintain aspect ratio */
      display: block; /* Ensure proper display */
      margin: 0 auto; /* Center align if needed */
    }
  </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<!-- Dashboard Container -->
<div class="content-wrapper">
  <div class="content">
    <h1>Manage Products</h1>
    <button class="btn btn-primary add-product-btn" data-toggle="modal" data-target="#addProductModal">Add Product</button>
    <table id="products-table" class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>Product ID</th>
          <th>Product Name</th>
          <th>Product Description</th>
          <th>Price</th>
          <th>Image</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($products as $product) { ?>
        <tr>
          <td><?= $product['product_id'] ?></td>
          <td><?= $product['product_name'] ?></td>
          <td><?= $product['description'] ?></td>
          <td><?= $product['price'] ?></td>
          <td><img src="<?= $product['product_image'] ?>" alt="<?= $product['product_name'] ?>" class="product-image"></td>
          <td>
            <a href="edit-product.php?id=<?= $product['product_id'] ?>" class="btn btn-primary">Edit</a>
            <a href="delete-product.php?id=<?= $product['product_id'] ?>" class="btn btn-danger">Delete</a>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="add_products.php" method="POST" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="productName">Product Name</label>
            <input type="text" class="form-control" id="productName" name="productName" required>
          </div>
          <div class="form-group">
            <label for="productDescription">Product Description</label>
            <textarea class="form-control" id="productDescription" name="productDescription" rows="3" required></textarea>
          </div>
          <div class="form-group">
            <label for="productPrice">Price</label>
            <input type="number" class="form-control" id="productPrice" name="productPrice" step="0.01" required>
          </div>
          <div class="form-group">
            <label for="productStock">Stock</label>
            <input type="number" class="form-control" id="productStock" name="productStock" required>
          </div>
          <div class="form-group">
            <label for="productImage">Product Image</label>
            <input type="file" class="form-control-file" id="productImage" name="productImage" accept="image/*" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Add Product</button>
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
    $('#products-table').DataTable({
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
        "emptyTable": "No products available"
      }
    });
  });
</script>
</body>
</html>
