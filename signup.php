<?php 
session_start();
?>
<html>

<head>
  <title>Signup Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

  <style>
    label {
      font-weight: 600;
      color: #666;
    }

    body {
      background: #f1f1f1;
    }

    .box8 {
      box-shadow: 0px 0px 5px 1px #999;
    }

    .mx-t3 {
      margin-top: 3rem;
    }
  </style>
</head>

<body>
  <?php
  include "header.php";
  ?>

  <div class="container mt-3">
    <form action="signup_process.php" method="POST">
      <div class="row jumbotron box8">
        <div class="col-sm-12 mx-t3 mb-4">
          <h2 class="text-center text-info">Register</h2>
        </div>
        <div class="col-sm-6 form-group">
          <label for="name-f">First Name</label>
          <input type="text" class="form-control" name="fname" id="name-f" placeholder="Enter your first name."
            required>
        </div>
        <div class="col-sm-6 form-group">
          <label for="name-l">Last name</label>
          <input type="text" class="form-control" name="lname" id="name-l" placeholder="Enter your last name." required>
        </div>
        <div class="col-sm-6 form-group">
          <label for="tel">Phone</label>
          <input type="tel" name="phone" class="form-control" id="tel" placeholder="Enter Your Contact Number."
            required>
        </div>

        <div class="col-sm-6 form-group">
          <label for="email">Email</label>
          <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email." required>
        </div>
        <div class="col-sm-12 form-group">
          <label for="address">Address</label>
          <input type="address" class="form-control" name="address" id="address" placeholder="Locality/House/Street no."
            required>
        </div>


        <div class="col-sm-6 form-group">
          <label for="pass">Password</label>
          <input type="Password" name="password" class="form-control" id="pass" placeholder="Enter your password."
            required>
        </div>
        <div class="col-sm-6 form-group">
          <label for="pass2">Confirm Password</label>
          <input type="Password" name="cnf-password" class="form-control" id="pass2"
            placeholder="Re-enter your password." required>
        </div>

        <div class="col-sm-12 form-group mt-4 d-flex justify-content-center mb-5">
          <button class="btn btn-success " type="submit">Submit</button>
          <a class="btn btn-danger ms-2 " href="index.php">Back to Home</a>
        </div>

      </div>
    </form>
  </div>
</body>

</html>