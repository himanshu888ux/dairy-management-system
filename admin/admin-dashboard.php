<?php

require_once '../connection.php';

// Query to fetch total orders
$total_orders_query = "SELECT COUNT(*) as total_orders FROM orders";
$total_orders_result = mysqli_query($conn, $total_orders_query);
if (!$total_orders_result) {
    echo "Error: " . mysqli_error($conn);
    exit;
}
$total_orders_row = mysqli_fetch_assoc($total_orders_result);
$total_orders = $total_orders_row['total_orders'];

// Query to fetch total income
$total_income_query = "SELECT SUM(total_amount) as total_income FROM orders";
$total_income_result = mysqli_query($conn, $total_income_query);
if (!$total_income_result) {
    echo "Error: " . mysqli_error($conn);
    exit;
}
$total_income_row = mysqli_fetch_assoc($total_income_result);
$total_income = $total_income_row['total_income'];

// Query to fetch number of customers
$customers_query = "SELECT COUNT(*) as num_customers FROM customer";
$customers_result = mysqli_query($conn, $customers_query);
if (!$customers_result) {
    echo "Error: " . mysqli_error($conn);
    exit;
}
$customers_row = mysqli_fetch_assoc($customers_result);
$num_customers = $customers_row['num_customers'];

// Query to fetch top selling product
$top_product_query = "SELECT p.product_name, SUM(oi.quantity) as total_quantity 
                      FROM order_items oi 
                      JOIN products p ON oi.product_id = p.product_id 
                      GROUP BY p.product_name 
                      ORDER BY total_quantity DESC 
                      LIMIT 1";
$top_product_result = mysqli_query($conn, $top_product_query);
if (!$top_product_result) {
    echo "Error: " . mysqli_error($conn);
    exit;
}
$top_product_row = mysqli_fetch_assoc($top_product_result);
$top_product = $top_product_row ? $top_product_row['product_name'] : '';

// Query to fetch recent orders
$recent_orders_query = "SELECT o.order_id, o.order_date, c.first_name, c.last_name, o.total_amount 
                        FROM orders o 
                        JOIN customer c ON o.customer_id = c.customer_id 
                        ORDER BY o.order_date DESC 
                        LIMIT 5";
$recent_orders_result = mysqli_query($conn, $recent_orders_query);
if (!$recent_orders_result) {
    echo "Error: " . mysqli_error($conn);
    exit;
}

$ordersData = [
    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    'data' => [120, 150, 180, 210, 250, 200, 190, 220, 240, 270, 230, 260] // Replace with actual data
];

$incomeData = [
    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    'data' => [2500, 2800, 3000, 3200, 3500, 3400, 3300, 3600, 3700, 3800, 3900, 4000] // Replace with actual data
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.bootstrap4.min.css">
  <style>
    .table thead th {
      color: green;
    }
    .dt-button {
      background-color: blue !important;
      color: white !important;
    }
    .content-wrapper {
      margin-left: 250px;
      transition: all 0.5s;
    }
    .content {
      padding: 20px;
      background-color: #fff;
      border-radius: 5px;
      margin-top: 20px;
    }
    .card-body {
      text-align: center;
    }
  </style>
</head>
<body>
  <?php include 'sidebar.php'; ?>

  <!-- Dashboard Container -->
  <div class="content-wrapper">
    <div class="content">
      
        <div class="row">
          <div class="col-md-12">
            <h2 class="mt-2">Admin Dashboard</h2>
            <p>Welcome to the Dairy Management System dashboard!</p>
          </div>
        </div>

        <div class="row">
          <div class="col-md-3 mb-3">
            <div class="card bg-dark text-white ">
              <div class="card-body">
                <h5 class="card-title">Total Orders</h5>
                <p class="card-text"><?php echo $total_orders; ?></p>
              </div>
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
              <div class="card-body">
                <h5 class="card-title">Total Income</h5>
                <p class="card-text">$<?php echo number_format($total_income, 2); ?></p>
              </div>
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <div class="card bg-warning">
              <div class="card-body">
                <h5 class="card-title">Number of Customers</h5>
                <p class="card-text"><?php echo $num_customers; ?></p>
              </div>
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
              <div class="card-body">
                <h5 class="card-title">Top Selling Product</h5>
                <p class="card-text"><?php echo $top_product; ?></p>
              </div>
            </div>
          </div>
        </div>

        <!-- Charts -->
        <div class="row mt-4">
          <div class="col-md-6">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Total Orders Chart</h5>
                <canvas id="ordersChart"></canvas>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Total Income Chart</h5>
                <canvas id="incomeChart"></canvas>
              </div>
            </div>
          </div>
        </div>

        <div class="row mt-4">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Recent Orders</h5>
                <table class="table table-striped">
                  <thead class="thead-dark">
                    <tr>
                      <th>Order ID</th>
                      <th>Order Date</th>
                      <th>Customer Name</th>
                      <th>Total Amount</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php while ($row = mysqli_fetch_assoc($recent_orders_result)) { ?>
                      <tr>
                        <td><?php echo $row['order_id']; ?></td>
                        <td><?php echo $row['order_date']; ?></td>
                        <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                        <td>$<?php echo number_format($row['total_amount'], 2); ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Replace with actual data or adjust dynamically based on backend values
      const ordersChartData = {
        labels: <?php echo json_encode($ordersData['labels']); ?>,
        datasets: [{
          label: 'Total Orders',
          data: <?php echo json_encode($ordersData['data']); ?>,
          backgroundColor: 'rgba(255, 99, 132, 0.6)',
          borderColor: 'rgba(255, 99, 132, 1)',
          borderWidth: 1,
          tension: 0.4
        }]
      };

      const incomeChartData = {
        labels: <?php echo json_encode($incomeData['labels']); ?>,
        datasets: [{
          label: 'Total Income',
          data: <?php echo json_encode($incomeData['data']); ?>,
          backgroundColor: 'rgba(54, 162, 235, 0.6)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1,
          tension: 0.4
        }]
      };

      // Orders chart
      const ordersCtx = document.getElementById('ordersChart').getContext('2d');
      const ordersChart = new Chart(ordersCtx, {
        type: 'bar',
        data: ordersChartData,
        options: {
          scales: {
            y: {
              beginAtZero: true
            }
          },
          plugins: {
            tooltip: {
              callbacks: {
                label: function(tooltipItem) {
                  return 'Orders: ' + tooltipItem.raw.toFixed(0);
                }
              }
            }
          }
        }
      });

      // Income chart
      const incomeCtx = document.getElementById('incomeChart').getContext('2d');
      const incomeChart = new Chart(incomeCtx, {
        type: 'bar',
        data: incomeChartData,
        options: {
          scales: {
            y: {
              beginAtZero: true
            }
          },
          plugins: {
            tooltip: {
              callbacks: {
                label: function(tooltipItem) {
                  return 'Income: $' + tooltipItem.raw.toFixed(2);
                }
              }
            }
          }
        }
      });
    });
  </script>
</body>
</html>
