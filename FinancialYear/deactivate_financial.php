<?php 
$base_path = "../";
ob_start();
include '../header.php';
$header = ob_get_clean();
$header = str_replace('<head>', '<head><base href="/eduhyd/">', $header);
echo $header;

include "../connect.php"; 

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $query = "UPDATE financial_year_master SET active = 0 WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo" <script>window.location.href='FinancialYear/deactivate_financial.php'</script>";
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request!";
}

$query = "SELECT * FROM financial_year_master WHERE active = 0 ORDER BY id DESC";
$records = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Deactivated Financial Years</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/kaiadmin.css"> <!-- Optional: If you're using Kaiadmin -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<div class="container" style="max-width:1400px; margin-top:100px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Deactivated Financial Year List</h3>
        <a href="FinancialYear/financial_master.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Master
        </a>
    </div>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Year Range</th>
                <th>From Date</th>
                <th>To Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sr = 1;
            if ($records && mysqli_num_rows($records) > 0) {
                while ($row = mysqli_fetch_assoc($records)) {
                    echo "<tr>
                        <td>{$sr}</td>
                        <td>{$row['year_range']}</td>
                        <td>{$row['from_date']}</td>
                        <td>{$row['to_date']}</td>
                        <td><span class='badge bg-danger'>Deactivated</span></td>
                    </tr>";
                    $sr++;
                }
            } else {
                echo "<tr><td colspan='4' class='text-center'>No deactivated financial years found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<?php include('../footer.php') ?>
</body>
</html>
