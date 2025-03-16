<?php
session_start();
include "connection.php";

?>

<html>
<head>
  <title>Customer Reviews </title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</head>

<body>
  <?php

  include "header.php";

  ?>
  <div id="carouselExampleDark" class="carousel carousel-dark slide container mb-5">
    <center>
      <h1 class="text-primary mt-5 mb-5">Customer Feedbacks</h1>
    </center>
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active"
        aria-current="true" aria-label="Slide 1"></button>
      <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" aria-label="Slide 2"></button>
      <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
      <div class="carousel-item active" data-bs-interval="10000">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSNtLnYEqvhKKHET_JzfYOv5hZNV1cngGuY_A&s"
          class="d-block w-50 h-50" alt="...">
        <div class="carousel-caption d-none d-md-block" style="margin-left:500px;margin-bottom:100px">
          <h5>John Doe</h5>
          <p>I Like your design and approach to build an dairy management system. <br> I just love it 😍😍</p>
        </div>
      </div>
      <div class="carousel-item" data-bs-interval="2000">
        <img src="https://worldywcacouncil.org/wp-content/uploads/2014/10/speaker-2-v2.jpg" class="d-block w-50 h-50"
          alt="...">
        <div class="carousel-caption d-none d-md-block" style="margin-left:500px;margin-bottom:100px">
          <h5>Jane Doe</h5>
          <p>perfect for dairy management</p>
        </div>
      </div>
      <div class="carousel-item">
        <img src="https://puneautoexpo.in/wp-content/uploads/2017/10/speaker3-min.jpg" class="d-block w-50 h-50"
          alt="...">
        <div class="carousel-caption d-none d-md-block" style="margin-left:500px;margin-bottom:100px">
          <h5>Mark Luther</h5>
          <p>More Advancement makes this website more perfect and stunning</p>
        </div>
      </div>
    </div>
    <button class="carousel-control-prev"  type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>

  <?php
  include "footer.php";
  ?>
  </body>

</html>