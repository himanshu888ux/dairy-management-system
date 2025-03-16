<?php
session_start();
include "connection.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dairy Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
        .carousel-inner img {
            width: 100%;
            height: 300px;
        }
        .card-img-top {
            height: 200px; /* Set a fixed height for the card image */
            object-fit: cover; /* Ensure the image covers the entire space without stretching */
        }
    </style>
</head>

<body>
    <!-- Using bootstrap in this project -->

    <!-- Navbar Section-->
    <?php include "header.php"; ?>

    <!-- Banner Section -->
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="images/slider/slider1.jpg" class="d-block w-100" alt="Slide 1">
            </div>
            <div class="carousel-item">
                <img src="images/slider/slider2.jpg" class="d-block w-100" alt="Slide 2">
            </div>
            <div class="carousel-item">
                <img src="images/slider/slider3.jpg" class="d-block w-100" alt="Slide 3">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Products cards or Display Cards -->
    <div class="container">
        <div class="row row-cols-1 row-cols-md-3 g-4 mt-5">
            <?php
            // Fetch products from the database
            $result = $conn->query("SELECT * FROM products");
            while ($row = $result->fetch_assoc()) {
            ?>
                <div class="col">
                    <div class="card h-100">
                        <img src="admin/<?php echo $row['product_image']; ?>" class="card-img-top" alt="<?php echo $row['product_name']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['product_name']; ?></h5>
                            <p class="card-text"><?php echo $row['description']; ?></p>
                            <p class="card-text"><strong>Price:</strong> <?php echo $row['price']; ?> ₹</p>
                            <form action="add_to_cart.php" method="post">   
                                <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                                <div class="input-group mb-3">
                                    <input type="number" name="quantity" min="1" value="1" class="form-control" placeholder="Quantity" aria-label="Quantity">
                                    <button class="btn btn-primary" type="submit">Add to Cart</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>

        <!-- Feedback form -->
        <form class="row g-3 mt-5 ms-5 me-5 bg-light mb-5">
            <center>
                <h2 class="text-primary">Feedback Form</h2>
            </center>
            <br>
            <center><strong class="text-warning">Your feedback matters!</strong></center>
            <div class="col-md-6">
                <label for="fullname" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="fullname" placeholder="John Doe">
            </div>
            <div class="col-md-6">
                <label for="inputEmail4" class="form-label">Email</label>
                <input type="email" class="form-control" id="inputEmail4" placeholder="johndoe@gmail.com">
            </div>
            <div class="col-12">
                <label for="inputAddress" class="form-label">Address</label>
                <input type="text" class="form-control" id="inputAddress" placeholder="new english school , nashirabad">
            </div>
            <div class="col-12">
                <label for="feedback-description" class="form-label">Feedback</label>
                <textarea name="feedback-description" id="feedback-description" class="form-control"></textarea>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>

    <!-- Footer section -->
    <?php include "footer.php"; ?>

</body>
</html>
