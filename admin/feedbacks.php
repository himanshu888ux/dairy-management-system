<?php
// Include database connection file
require_once '../connection.php';

// Check if the user is logged in (assuming session_start() is already called in connection.php or another included file)
session_start();

// Retrieve feedbacks from database
$query = "SELECT feedback_id, customer_name, email, address, feedback, created_at FROM customer_feedbacks";
$result = mysqli_query($conn, $query);

// Check if there are any feedbacks
if ($result) {
    if (mysqli_num_rows($result) > 0) {
        $feedbacks = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $feedbacks = array(); // No feedbacks found
    }
} else {
    die('Error: ' . mysqli_error($conn)); // Query execution failed
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Feedbacks</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <style>
        .table thead th {
            color: green;
        }
        .dt-button {
            background-color: blue !important;
            color: white !important;
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
    <?php include 'sidebar.php'; ?>

    <!-- Feedbacks Container -->
    <div class="content-wrapper">
        <div class="content">
            <h1>Customer Feedbacks</h1>
            <table id="feedbacks-table" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Feedback ID</th>
                        <th>Customer Name</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Feedback</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($feedbacks as $feedback) { ?>
                        <tr>
                            <td><?= $feedback['feedback_id'] ?></td>
                            <td><?= $feedback['customer_name'] ?></td>
                            <td><?= $feedback['email'] ?></td>
                            <td><?= $feedback['address'] ?></td>
                            <td><?= $feedback['feedback'] ?></td>
                            <td><?= $feedback['created_at'] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#feedbacks-table').DataTable({
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
                    "emptyTable": "No feedbacks available"
                }
            });
        });
    </script>
</body>
</html>
