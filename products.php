<?php
session_start();
include "connection.php";
?>

<html>

<head>
    <title>Products Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script>
        function clearSearch() {
            const searchInput = document.getElementById('searchInput');
            searchInput.value = '';
            searchInput.form.submit();
        }
    </script>
</head>

<body>
    <!-- header section -->
    <?php 
    include "header.php";
    ?>

    <!-- Products cards or Display Cards -->
    <div class="container">
        <center>
            <h1 class="text-info mt-5">All Products</h1>
        </center>
        <br><br>

        <!-- Search form -->
        <form class="form" method="GET" action="">
            <div class="input-group mb-3">
                <?php
                // Get the search query if set
                $search = isset($_GET['search']) ? $_GET['search'] : '';
                ?>
                <input type="text" name="search" id="searchInput" class="form-control border-success" placeholder="Search for products" aria-label="Search for products" aria-describedby="button-search" value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn btn-success fs-4" type="submit" id="button-search">Search</button>
                <?php if ($search): ?>
                <button type="button" class="btn btn-danger fs-4 ms-2" onclick="clearSearch()"><i class="fa fa-window-close"></i></button>
                <?php endif; ?>
            </div>
        </form>

        <div class="row row-cols-1 row-cols-md-3 g-4 mt-5">
            <?php
            // Fetch products from the database
            $query = "SELECT * FROM products";
            if ($search) {
                $query .= " WHERE product_name LIKE '%$search%' OR description LIKE '%$search%'";
            }
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
            ?>
            <div class="col">
                <div class="card h-100">
                    <img src="admin/<?php echo $row['product_image']; ?>" class="card-img-top" height="300px" alt="...">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title ms-0"><?php echo $row['product_name']; ?></h5>
                            <button class="btn btn-primary me-md-2" type="button">Add to Cart</button>
                        </div>
                        <p class="card-text"><?php echo $row['description']; ?></p>
                        <p class="card-text"><strong>Price:</strong> <?php echo $row['price']; ?> ₹</p>
                    </div>
                </div>
            </div>
            <?php
                }
            } else {
                echo '<div class="col"><div class="alert alert-warning" role="alert">No products found.</div></div>';
            }
            ?>
        </div>
    </div>
    <br>

    <!-- footer section -->
    <?php
    include "footer.php";
    ?>
</body>

</html>
