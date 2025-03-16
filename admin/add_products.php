<?php
// add_products.php

// Include database connection file
require_once '../connection.php';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Escape user inputs for security
  $productName = mysqli_real_escape_string($conn, $_POST['productName']);
  $productDescription = mysqli_real_escape_string($conn, $_POST['productDescription']);
  $productPrice = $_POST['productPrice'];
  $productStock = $_POST['productStock'];

  // File upload handling
  $targetDir = "products/";
  $fileName = basename($_FILES["productImage"]["name"]);
  $targetFilePath = $targetDir . $fileName;
  $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

  // Allow certain file formats
  $allowTypes = array('jpg', 'jpeg', 'png', 'gif');
  if (in_array($fileType, $allowTypes)) {
    // Upload file to server
    if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $targetFilePath)) {
      // Insert product data into database
      $insertQuery = "INSERT INTO products (product_name, description, price, stock, product_image)
                      VALUES ('$productName', '$productDescription', '$productPrice', '$productStock', '$targetFilePath')";
      if (mysqli_query($conn, $insertQuery)) {
        echo "Product added successfully.";
      } else {
        echo "Error: " . $insertQuery . "<br>" . mysqli_error($conn);
      }
    } else {
      echo "Sorry, there was an error uploading your file.";
    }
  } else {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
  }

  // Close database connection
  mysqli_close($conn);
}
?>
