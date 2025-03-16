<?php
session_start();
include "connection.php";

// Initialize an empty array for the cart items
$cart_items = [];

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    // Fetch product details from the database based on the cart item IDs
    $cart_ids = array_keys($_SESSION['cart']);
    $cart_ids_str = implode(",", array_map('intval', $cart_ids));
    $query = "SELECT * FROM products WHERE product_id IN ($cart_ids_str)";
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $product_id = $row['product_id'];
        if (isset($_SESSION['cart'][$product_id])) {
            $row['quantity'] = $_SESSION['cart'][$product_id]['quantity'];
            $cart_items[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - Dairy Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <?php include "header.php"; ?>
    <div class="container mt-4">
        <h2>Your Cart</h2>
        <div class="row row-cols-1 row-cols-md-3 g-4 mt-3">
            <?php
            foreach ($cart_items as $product) {
            ?>
                <div class="col">
                    <div class="card h-100">
                        <img src="admin/<?php echo $product['product_image']; ?>" class="card-img-top" alt="<?php echo $product['product_name']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $product['product_name']; ?></h5>
                            <p class="card-text"><?php echo $product['description']; ?></p>
                            <p class="card-text"><strong>Price:</strong> <?php echo $product['price']; ?> ₹</p>
                            <p class="card-text"><strong>Quantity:</strong> <?php echo $product['quantity']; ?></p>
                            <form action="update_cart.php" method="post">
                                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                <div class="input-group mb-3">
                                    <input type="number" name="quantity" value="<?php echo $product['quantity']; ?>" min="1" class="form-control">
                                    <button class="btn btn-primary" type="submit">Update</button>
                                </div>
                            </form>
                            <form action="remove_from_cart.php" method="post">
                                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                <button class="btn btn-danger" type="submit">Remove</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
        <div class="mt-4n mb-4">
            <a href="index.php" class="btn btn-secondary">Continue Shopping</a>
            <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
        </div>
    </div>
    <?php include "footer.php"; ?>
</body>
</html>
