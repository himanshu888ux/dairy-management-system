<?php
include "connection.php";
?>
<head>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<nav class="navbar navbar-expand-lg bg-success">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand fs-2 text-light ms-3 me-5" href="index.php"><img src="images/logo.jpeg" height="50" width="50" style="border-radius:50px"> HomeDairy</a>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 fs-4">
                <li class="nav-item">
                    <a class="nav-link active text-light" aria-current="page" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="products.php">Products</a>
                </li>
                <!-- Cart Icon -->
                <li class="nav-item">
                    <a class="nav-link text-light" href="cart.php"><i class="fa fa-cart-shopping fs-5 me-1"></i>Cart</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="feedbacks.php">Feedbacks</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="contactus.php">Contact us</a>
                </li>
                <?php
                if(!isset($_SESSION['customer_id']) || empty($_SESSION['customer_id']))
                {
                ?>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" href="signup.php">Sign Up</a>
                    </li>
                <?php
                }
                else{
                    $customer_id = $_SESSION['customer_id'];
                    $query = "SELECT first_name from customer where customer_id='$customer_id'";
                    $result = mysqli_query($conn,$query);
                    $row = mysqli_fetch_assoc($result);
                    $name = $row['first_name'];

                    echo "<li class='nav-item dropdown'>
                        <a class='nav-link text-light dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'><img src='images/user_image.png' height='20px' width='20px'> $name</a>
                        <ul class='dropdown-menu' aria-labelledby='navbarDropdown'>
                         <li><a class='dropdown-item' href='order_history.php'>Order History</a></li>   
                        <li><a class='dropdown-item' href='logout.php'>Logout</a></li>
                           
                        </ul>
                    </li>";
                }
                ?>
                
            </ul>
          
            <div class="d-flex pt-3" role="search">            
                <a href="admin/admin-login.php" class="btn fs-4 btn-light">Admin</a>
            </div>
        </div>
    </div>
</nav>

<div class="offcanvas offcanvas-start bg-success" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title text-light" id="offcanvasNavbarLabel">HomeDairy</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0 fs-4">
            <li class="nav-item">
                <a class="nav-link active text-light" aria-current="page" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-light" href="products.php">Products</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-light" href="feedbacks.php">Feedbacks</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-light" href="contactus.php">Contact us</a>
            </li>
            <?php
            if(!isset($_SESSION['customer_id']) || empty($_SESSION['customer_id']))
            {
            ?>
                <li class="nav-item">
                    <a class="nav-link text-light" href="login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="signup.php">Sign Up</a>
                </li>
            <?php
            }
            else{
                $customer_id = $_SESSION['customer_id'];
                $query = "SELECT first_name from customer where customer_id='$customer_id'";
                $result = mysqli_query($conn,$query);
                $row = mysqli_fetch_assoc($result);
                $name = $row['first_name'];

                echo "<li class='nav-item dropdown'>
                    <a class='nav-link text-light dropdown-toggle' data-bs-toggle='dropdown' aria-expanded='false'><img src='images/user_image.png' height='20px' width='20px'> $name</a>
                    <ul class='dropdown-menu' aria-labelledby='navbarDropdown'>
                        <li><a class='dropdown-item' href='logout.php'>Logout</a></li>
                    </ul>
                </li>";
            }
            ?>
            <!-- Cart Icon -->
            <li class="nav-item">
                <a class="nav-link text-light" href="cart.php"><i class="bi bi-cart4 fs-5"></i></a>
            </li>
        </ul>
    </div>
</div>
