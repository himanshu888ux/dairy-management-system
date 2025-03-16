<?php
// manage-milk-distribution.php

// Include database connection file
require_once '../connection.php';

// Initialize variables for form data
$quantity_litres = $status = $distribution_time = '';
$errors = array();

// Fetch customer names from database
$customers_query = "SELECT customer_name FROM dairy_customers";
$customers_result = mysqli_query($conn, $customers_query);
$customers = array();
if (mysqli_num_rows($customers_result) > 0) {
    while ($row = mysqli_fetch_assoc($customers_result)) {
        $customers[] = $row['customer_name'];
    }
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $customer_name = $_POST['customer_name'];
    $quantity_litres = trim($_POST['quantity_litres']);
    $status = $_POST['status'];
    $distribution_time = $_POST['distribution_time'];

    // Validate customer name
    if (empty($customer_name)) {
        $errors[] = "Customer name is required.";
    } elseif (!in_array($customer_name, $customers)) {
        $errors[] = "Invalid customer name.";
    }

    // Validate quantity litres
    if (empty($quantity_litres) || !is_numeric($quantity_litres)) {
        $errors[] = "Valid quantity in litres is required.";
    }

    // Validate status
    if (empty($status)) {
        $errors[] = "Status is required.";
    } elseif (!in_array($status, ['Delivered', 'Cancelled'])) {
        $errors[] = "Invalid status.";
    }

    // Validate distribution time
    if (empty($distribution_time)) {
        $errors[] = "Distribution time is required.";
    }

    // If no errors, insert data into database
    if (empty($errors)) {
        // Prepare insert statement
        $insert_query = "INSERT INTO milk_distribution (customer_name, distribution_datetime, quantity_litres, status, distribution_time) VALUES (?, NOW(), ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert_query);

        // Bind parameters and execute insert query
        mysqli_stmt_bind_param($stmt, 'sdss', $customer_name, $quantity_litres, $status, $distribution_time);
        if (mysqli_stmt_execute($stmt)) {
            // Success message
            $success_message = "Milk distribution record added successfully.";
            $customer_name = $quantity_litres = $status = $distribution_time = ''; // Clear form fields after successful submission
        } else {
            // Error message
            $error_message = "Failed to add milk distribution record. Please try again.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }
}

// Retrieve milk distribution data from database
$query = "SELECT distribution_id, customer_name, distribution_datetime, quantity_litres, status, distribution_time FROM milk_distribution";
$result = mysqli_query($conn, $query);

// Check if there are any milk distribution records
if (mysqli_num_rows($result) > 0) {
    $distributions = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $distributions = array();
}

// Close database connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Milk Distribution</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css">
    <style>
        .table thead th {
            color: green;
        }
        .dt-button {
            background-color: blue !important;
            color: white !important;
        }
        .content-wrapper {
            padding: 20px;
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .content {
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 20px; /* Adjust spacing between form groups */
        }
        .select2 {
            width: 100% !important;
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<!-- Dashboard Container -->
<div class="content-wrapper">
    <div class="content">
        <h1 class="text-center mb-4">Manage Milk Distribution</h1>
        
        <!-- Form to add new milk distribution -->
        <div class="mb-4">
            <h2>Add New Milk Distribution</h2>
            
            <?php if (isset($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $error_message; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if (isset($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $success_message; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="customer_name">Customer Name:</label>
                            <select class="form-control select2" id="customer_name" name="customer_name" required>
                                <option value="">Select Customer</option>
                                <?php foreach ($customers as $customer): ?>
                                    <option value="<?php echo $customer; ?>"><?php echo htmlspecialchars($customer); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="quantity_litres">Quantity (Litres):</label>
                            <input type="number" class="form-control" id="quantity_litres" name="quantity_litres" min="0" step="0.01" value="<?php echo htmlspecialchars($quantity_litres); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status">Status:</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="Delivered" <?php if ($status === 'Delivered') echo 'selected'; ?>>Delivered</option>
                                <option value="Cancelled" <?php if ($status === 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="distribution_time">Distribution Time:</label>
                            <select class="form-control" id="distribution_time" name="distribution_time" required>
                                <option value="">Select Time</option>
                                <option value="Morning" <?php if ($distribution_time === 'Morning') echo 'selected'; ?>>Morning</option>
                                <option value="Evening" <?php if ($distribution_time === 'Evening') echo 'selected'; ?>>Evening</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Add Distribution</button>
            </form>
        </div>
        
        <!-- Display milk distribution data in DataTable -->
        <div>
            <h2 class="text-center mb-4">Distribution Records</h2>
            <div class="table-responsive">
                <table id="distribution-table" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Distribution ID</th>
                            <th>Customer Name</th>
                            <th>Distribution Date</th>
                            <th>Quantity (Litres)</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($distributions as $distribution): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($distribution['distribution_id']); ?></td>
                                <td><?php echo htmlspecialchars($distribution['customer_name']); ?></td>
                                <td><?php echo htmlspecialchars($distribution['distribution_datetime']); ?></td>
                                <td><?php echo htmlspecialchars($distribution['quantity_litres']); ?></td>
                                <td><?php echo htmlspecialchars($distribution['status']); ?></td>
                                <td>
                                    <a href="edit-distribution.php?id=<?php echo $distribution['distribution_id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="delete-distribution.php?id=<?php echo $distribution['distribution_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<!-- DataTables JavaScript -->
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

<!-- DataTables Buttons JavaScript -->
<script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<!-- Select2 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<!-- Custom JavaScript -->
<script>
    $(document).ready(function() {
        // Initialize Select2 dropdowns
        $('.select2').select2();

        // Initialize DataTable
        $('#distribution-table').DataTable({
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
                "emptyTable": "No distributions available"
            }
        });
    });
</script>

</body>
</html>