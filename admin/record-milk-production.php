<?php
// record-milk-production.php

// Include database connection file
require_once '../connection.php';

// Check if the user is logged in (session management)
session_start();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input (you may add more validation as needed)
    $cow_id = $_POST['cow_id'];
    $milk_production = $_POST['milk_production'];
    $production_time = $_POST['production_time']; // morning or evening

    // Perform database operations to record milk production
    $insert_query = "INSERT INTO milk_production (cow_id, production_time, milk_production, production_date) VALUES (?, ?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, 'iss', $cow_id, $production_time, $milk_production);

    if (mysqli_stmt_execute($stmt)) {
        // Update cow's total production in cows table (assuming you have a cows table)
        $update_query = "UPDATE cows SET total_milk_production = total_milk_production + ?, last_production_date = NOW() WHERE cow_id = ?";
        $stmt_update = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt_update, 'di', $milk_production, $cow_id);
        mysqli_stmt_execute($stmt_update);

        // Redirect to a success page or refresh the current page
        header('Location: record-milk-production.php?success=1');
        exit();
    } else {
        // Handle errors if needed
        $error_message = "Failed to record milk production. Please try again.";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

// Retrieve list of cows to display in the form (assuming you have a cows table)
$query_cows = "SELECT cow_id, cow_name FROM cows";
$result_cows = mysqli_query($conn, $query_cows);

// Check if there are any cows
if (mysqli_num_rows($result_cows) > 0) {
    $cows = array();
    while ($row = mysqli_fetch_assoc($result_cows)) {
        $cows[] = $row;
    }
} else {
    $cows = array();
}

// Query to fetch milk production records
$query_milk_production = "SELECT mp.cow_id, c.cow_name, mp.production_time, mp.milk_production, mp.production_date
                          FROM milk_production mp
                          LEFT JOIN cows c ON mp.cow_id = c.cow_id
                          ORDER BY mp.production_date DESC";
$result_milk_production = mysqli_query($conn, $query_milk_production);

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Milk Production</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <style>
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
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .dataTables_empty {
            text-align: center;
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="content-wrapper">
    <div class="content">
        <h2>Record Milk Production</h2>
        <?php if (isset($error_message)) { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php } ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="cow_id">Select Cow:</label>
                <select class="form-control select2" id="cow_id" name="cow_id" required>
                    <option value="">Select Cow</option>
                    <?php foreach ($cows as $cow) { ?>
                        <option value="<?php echo $cow['cow_id']; ?>"><?php echo $cow['cow_name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label>Milk Production Time:</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="production_time" id="morning" value="morning" required>
                    <label class="form-check-label" for="morning">Morning</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="production_time" id="evening" value="evening">
                    <label class="form-check-label" for="evening">Evening</label>
                </div>
            </div>
            <div class="form-group">
                <label for="milk_production">Milk Production (litres):</label>
                <input type="number" class="form-control" id="milk_production" name="milk_production" min="0" step="0.01" required>
            </div>
            <button type="submit" class="btn btn-primary">Record Production</button>
        </form>

        <!-- Milk Production DataTable -->
        <div class="table-responsive">
            <table id="milk-production-table" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Cow Name</th>
                        <th>Production Time</th>
                        <th>Milk Production (litres)</th>
                        <th>Production Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result_milk_production) > 0) {
                        while ($row = mysqli_fetch_assoc($result_milk_production)) {
                            echo '<tr>';
                            echo '<td>' . $row['cow_name'] . '</td>';
                            echo '<td>' . ucfirst($row['production_time']) . '</td>';
                            echo '<td>' . $row['milk_production'] . '</td>';
                            echo '<td>' . $row['production_date'] . '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="4" class="text-center">No milk production records available</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2 on the cows select element
        $('.select2').select2();

        // Initialize DataTables on the milk production table
        $('#milk-production-table').DataTable({
            "language": {
                "emptyTable": "No milk production records available"
            }
        });
    });
</script>
</body>
</html>
