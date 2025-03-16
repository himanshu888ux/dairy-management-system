<html>
    <head>
        <title>Contact Us Page</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

        <style>
            body {
  background: #fafbfb;
}


/* FOOTER STYLES
–––––––––––––––––––––––––––––––––––––––––––––––––– */
.page-footer {
  position: fixed;
  right: 0;
  bottom: 50px;
  display: flex;
  align-items: center;
  padding: 5px;
  z-index: 1;
}

.page-footer a {
  display: flex;
  margin-left: 4px;
}
        </style>
    </head>
    <body class="bg-dark">
    <div class="container my-5 bg-white">
  <div class="row justify-content-center">
    <div class="col-lg-9 mt-5">
      <h1 class="mb-3">Contact Us</h1>
      <form >
        <div class="row g-3">
          <div class="col-md-6">
            <label for="your-name" class="form-label">Your Name</label>
            <input type="text" class="form-control" id="your-name" name="your-name" required>
          </div>
          <div class="col-md-6">
            <label for="your-surname" class="form-label">Your Surname</label>
            <input type="text" class="form-control" id="your-surname" name="your-surname" required>
          </div>
          <div class="col-md-6">
            <label for="your-email" class="form-label">Your Email</label>
            <input type="email" class="form-control" id="your-email" name="your-email" required>
          </div>
          <div class="col-md-6">
            <label for="your-subject" class="form-label">Your Subject</label>
            <input type="text" class="form-control" id="your-subject" name="your-subject">
          </div>
          <div class="col-12">
            <label for="your-message" class="form-label">Your Message</label>
            <textarea class="form-control" id="your-message" name="your-message" rows="5" required></textarea>
          </div>
          <div class="col-12 mb-5">
            <div class="row">
              <div class="col-md-6">
                <a class="btn btn-dark w-100 fw-bold">Send</a>
              </div>
              <div class="col-md-6">
                  <a class="btn btn-dark w-100 fw-bold" href="index.php" >Back</a>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<footer class="page-footer">
  <span>made by </span>
  <a href="https://georgemartsoukos.com/" target="_blank">
    <img width="24" height="24" src="https://assets.codepen.io/162656/george-martsoukos-small-logo.svg" alt="George Martsoukos logo">
  </a>
</footer>
    </body>
</html>