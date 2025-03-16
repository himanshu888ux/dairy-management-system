<?php
// calculate-bill.php

// Include database connection file
require_once '../connection.php';

// Initialize variables
$price_per_litre = 0;
$selected_month = '';
$customer_totals = array();
$customer_litres = array();
$overall_total_amount = 0;
$overall_total_litres = 0;
$error_message = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    if (isset($_POST['price_per_litre']) && is_numeric($_POST['price_per_litre']) && $_POST['price_per_litre'] > 0) {
        $price_per_litre = floatval($_POST['price_per_litre']);
    } else {
        $error_message = 'Please enter a valid price per litre.';
    }

    if (isset($_POST['month']) && !empty($_POST['month'])) {
        $selected_month = $_POST['month'];
    } else {
        $error_message = 'Please select a valid month.';
    }

    // If inputs are valid, fetch milk distribution data with customer names and quantities
    if (empty($error_message)) {
        $query = "SELECT customer_name, quantity_litres, status 
                  FROM milk_distribution
                  WHERE DATE_FORMAT(distribution_datetime, '%Y-%m') = '$selected_month'";
        $result = mysqli_query($conn, $query);

        // Check if query was successful
        if ($result) {
            // Check if there are any milk distribution records
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $customer_name = $row['customer_name'];
                    $quantity_litres = $row['quantity_litres'];
                    $status = $row['status'];

                    // Calculate total amount for this customer
                    $total_amount = $quantity_litres * $price_per_litre;

                    // Accumulate customer total amount and total litres
                    if (!isset($customer_totals[$customer_name])) {
                        $customer_totals[$customer_name] = 0;
                        $customer_litres[$customer_name] = 0;
                    }
                    $customer_totals[$customer_name] += $total_amount;
                    $customer_litres[$customer_name] += $quantity_litres;

                    // Accumulate overall total amount and overall total litres
                    $overall_total_amount += $total_amount;
                    $overall_total_litres += $quantity_litres;
                }
            }
        } else {
            // Display SQL error message
            $error_message = 'Query error: ' . mysqli_error($conn);
        }
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculate Bill</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.bootstrap4.min.css">
    <style>
        .content-wrapper {
            padding: 20px;
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .content {
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            margin-top: 20px;
        }
        .form-group label {
            font-weight: bold;
        }
        .btn-primary {
            margin-right: 10px;
        }
        @media (max-width: 576px) {
            .content-wrapper {
                padding: 10px;
            }
            .content {
                padding: 10px;
            }
            .btn-primary {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<!-- Dashboard Container -->
<div class="content-wrapper">
    <div class="content">
        <h1 class="text-center mb-4">Calculate Bill</h1>

        <!-- Form to input price per litre and select month -->
        <div class="mb-4">
            <h2>Enter Price Per Litre and Select Month</h2>
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="price_per_litre">Price Per Litre (INR):</label>
                    <input type="number" class="form-control" id="price_per_litre" name="price_per_litre" step="0.01" min="0" required>
                </div>
                <div class="form-group">
                    <label for="month">Select Month:</label>
                    <input type="month" class="form-control" id="month" name="month" required>
                </div>
                <button type="submit" class="btn btn-primary">Calculate</button>
            </form>
        </div>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error_message)): ?>
        <!-- Display overall totals -->
            <div class="container d-inline-block text-success mb-5">
            <div class="row">

            <div class="md-6 mr-5 ml-5 pl-5 pr-5">
                <h2>Overall Total Litres</h2>
                <h3><?php echo number_format($overall_total_litres, 2); ?></h3>
            </div>
            <div class="md-6 ml-5 mr-0 pl-5" style="margin-start:100px !important">
                
                <h2>Overall Total Amount (INR)</h2>
                <h3><?php echo number_format($overall_total_amount, 2)." Rs"; ?></h3>
                
            </div>
            </div>

            </div>

        <!-- Display total amounts for each customer -->
        <div class="mb-4">
            <h2 class="text-danger">Total Amounts for Customers</h2>
            <div class="table-responsive">
                <table id="customerTotalsTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Customer Name</th>
                            <th>Total Litres</th>
                            <th>Total Amount (INR)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($customer_totals as $customer_name => $total_amount): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($customer_name); ?></td>
                                <td><?php echo number_format($customer_litres[$customer_name], 2); ?></td>
                                <td><?php echo number_format($total_amount, 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        
        <?php endif; ?>
    </div>
</div>

<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script>
 $(document).ready(function() {
    $('#customerTotalsTable').DataTable({
      dom: 'Bfrtip',
      buttons: [
        {
          extend: 'copy',
          text: 'Copy',
          className: 'btn btn-primary'
        },
        {
          extend: 'csv',
          text: 'CSV',
          className: 'btn btn-primary'
        },
        {
          extend: 'pdf',
          text: 'PDF',
          className: 'btn btn-primary'
        },
        {
          extend: 'print',
          text: 'Print',
          className: 'btn btn-primary'
        }
      ],
      "language": {
        "emptyTable": "No data available"
      }
    });
  });
</script>
</body>
</html>
