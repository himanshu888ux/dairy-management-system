<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sidebar with Toggle Button</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <style>
    .sidebar {
      height: 100vh;
      width: 250px;
      background-color: #333;
      padding: 20px;
      color: #fff;
      transition: all 0.5s;
      position: fixed;
      top: 0;
      left: 0;
      z-index: 1000;
    }
    .nav-button {
      background-color: #333;
      color: #fff;
      border: none;
      padding: 10px;
      font-size: 24px;
      cursor: pointer;
      position: absolute;
      top: 0;
      left: 250px;
      z-index: 1001;
      transition: all 0.5s;
    }
    .sidebar-closed + .nav-button {
      left: 10px;
    }
    .sidebar-closed + .content-wrapper .content {
      margin-left: 10px;
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
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <h2 class="text-white">Dairy Management System</h2>
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link text-white" href="admin-dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="manage-customers.php"><i class="fas fa-users"></i> Manage Customers</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="manage-products.php"><i class="fas fa-box"></i> Manage Products</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="manage-orders.php"><i class="fas fa-shopping-cart"></i> Manage Orders</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="manage-delivery.php"><i class="fas fa-truck"></i> Manage Delivery</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="manage-payments.php"><i class="fas fa-credit-card"></i> Manage Payments</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="manage-cows.php"><i class="fas fa-hippo"></i> Manage Cows</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="manage-dairy-customers.php"><i class="fas fa-user"></i>  Dairy Customers</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="record-milk-production.php"><i class="fas fa-wine-bottle"></i> Milk Production</a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link text-white" href="manage-milk-distribution.php"><i class="fas fa-briefcase"></i> Milk Distribution</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="calculate-bill.php"><i class="far fa-money-bill-alt"></i> Calculate Bill</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="feedbacks.php"><i class="fas fa-comment"></i> Feedbacks</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="admin-logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
      </li>
    </ul>
  </div>
  <!-- Toggle Button -->
  <button class="nav-button" id="nav-button">
    <i class="fas fa-bars" id="nav-icon"></i>
  </button>
  <div class="m-4">
  <!-- JavaScript -->
  <script>
    let sidebarOpen = true;
    document.getElementById('nav-button').addEventListener('click', function() {
      const sidebar = document.getElementById('sidebar');
      const navButton = document.getElementById('nav-button');
      const contentWrapper = document.querySelector('.content-wrapper');

      if (sidebarOpen) {
        sidebar.style.left = '-250px';
        navButton.style.left = '10px';
        contentWrapper.style.marginLeft = '10px';
        sidebarOpen = false;
      } else {
        sidebar.style.left = '0';
        navButton.style.left = '250px';
        contentWrapper.style.marginLeft = '260px';
        sidebarOpen = true;
      }
    });
  </script>

