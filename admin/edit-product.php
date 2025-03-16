<?php
require_once '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['productName'];
    $product_description = $_POST['productDescription'];
    $product_price = $_POST['productPrice'];
    $product_stock = $_POST['productStock'];

    // Check if a new image file is uploaded
    if ($_FILES['productImage']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['productImage']['tmp_name'];
        $file_name = $_FILES['productImage']['name'];
        $file_size = $_FILES['productImage']['size'];
        $file_type = $_FILES['productImage']['type'];
        
        // Move uploaded file to a permanent location (e.g., "products/" directory)
        $upload_dir = 'products/';
        $target_file = $upload_dir . basename($file_name);
        
        if (move_uploaded_file($file_tmp, $target_file)) {
            $product_image = $target_file;

            // Update product details including the image path
            $query = "UPDATE products SET product_name = ?, description = ?, price = ?, stock = ?, product_image = ? WHERE product_id = ?";
            $stmt = mysqli_prepare($conn, $query);
            
            if ($stmt === false) {
                echo 'Failed to prepare statement: ' . mysqli_error($conn);
                exit;
            }
    
            mysqli_stmt_bind_param($stmt, 'ssdisi', $product_name, $product_description, $product_price, $product_stock, $product_image, $product_id);
        } else {
            echo 'Failed to move uploaded file.';
            exit;
        }
    } else {
        // If no new image is uploaded, update other product details excluding the image path
        $query = "UPDATE products SET product_name = ?, description = ?, price = ?, stock = ? WHERE product_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        
        if ($stmt === false) {
            echo 'Failed to prepare statement: ' . mysqli_error($conn);
            exit;
        }

        mysqli_stmt_bind_param($stmt, 'ssdii', $product_name, $product_description, $product_price, $product_stock, $product_id);
    }

    // Execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        header('Location: manage-products.php'); // Redirect to manage-products.php after successful update
        exit;
    } else {
        echo 'Error updating product: ' . mysqli_error($conn);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        exit;
    }
}

// Fetch product details based on product_id from GET parameter
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $query = "SELECT * FROM products WHERE product_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    
    if ($stmt === false) {
        echo 'Failed to prepare statement: ' . mysqli_error($conn);
        exit;
    }

    mysqli_stmt_bind_param($stmt, 'i', $product_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        if ($row) {
            $product_name = $row['product_name'];
            $product_description = $row['description'];
            $product_price = $row['price'];
            $product_stock = $row['stock'];
            $product_image = $row['product_image'];
        } else {
            echo 'Product not found.';
            exit;
        }
    } else {
        echo 'Failed to execute statement: ' . mysqli_error($conn);
        exit;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    echo 'Product ID not provided.';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="content-wrapper">
        <div class="content mt-5 ms-5" style="margin-left:10%">
            <h1>Edit Product</h1>
            <form action="edit-product.php?id=<?php echo htmlspecialchars($_GET['id']); ?>" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                    <label for="productName">Product Name</label>
                    <input type="text" class="form-control" id="productName" name="productName"
                        value="<?php echo htmlspecialchars($product_name); ?>" required>
                </div>
                <div class="form-group">
                    <label for="productDescription">Product Description</label>
                    <textarea class="form-control" id="productDescription" name="productDescription"
                        rows="3"><?php echo htmlspecialchars($product_description); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="productPrice">Price</label>
                    <input type="number" class="form-control" id="productPrice" name="productPrice" step="0.01"
                        value="<?php echo $product_price; ?>" required>
                </div>
                <div class="form-group">
                    <label for="productStock">Stock</label>
                    <input type="number" class="form-control" id="productStock" name="productStock"
                        value="<?php echo $product_stock; ?>" required>
                </div>
                <div class="form-group">
                    <label for="productImage">Product Image</label>
                    <input type="file" class="form-control-file" id="productImage" name="productImage" accept="image/*">
                    <small class="form-text text-muted">Leave blank if you don't want to change the image.</small>
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="manage-products.php" class="btn btn-secondary ml-2">Cancel</a>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
