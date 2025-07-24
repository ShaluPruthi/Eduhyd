<?php
$base_path = "../";
ob_start();
include '../header.php';
$header = ob_get_clean();
$header = str_replace('<head>', '<head><base href="/eduhyd/">', $header);
echo $header;

include "../connect.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $financial_year = $_POST['year_range'];
    $start_date = $_POST['from_date'];
    $end_date = $_POST['to_date'];

    $check = mysqli_query($conn, "SELECT * FROM financial_year_master WHERE year_range='$financial_year'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Financial year already exists.'); window.location.href='FinancialYear/financial_master.php';</script>";
    } else {
        $query = "INSERT INTO financial_year_master (year_range, from_date, to_date, active) VALUES ('$financial_year', '$start_date', '$end_date', 1)";
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Financial year added successfully.'); window.location.href='FinancialYear/financial_master.php';</script>";
        } else {
            echo "<script>alert('Error while adding data.'); window.location.href='FinancialYear/financial_master.php';</script>";
        }
    }
}


$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$totalQuery = "SELECT COUNT(*) AS total FROM financial_year_master WHERE active = 1";
$totalResult = mysqli_query($conn, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalRecords = $totalRow['total'];
$totalPages = ceil($totalRecords / $limit);

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $updateQuery = "UPDATE financial_year_master SET active = 0 WHERE id = $id";
    mysqli_query($conn, $updateQuery);
    header("Location: FinancialYear/financial_master.php");
    exit;
}

$query = "SELECT * FROM financial_year_master WHERE active = 1 ORDER BY id DESC LIMIT $offset, $limit";
$records = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Financial Year Master</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        .form-section { background: #f8f9fa; padding: 20px; margin-bottom: 30px; border-radius: 10px; box-shadow: 0 0 5px #ccc; }
        .filter-box { display: flex; align-items: center; gap: 15px; margin-bottom: 15px; }
        .table th, .table td { vertical-align: middle; }
        .action-btns i { font-size: 18px; }
        .action-btns a { margin-right: 10px; }
        .search-row input { width: 100%; padding: 5px; }
        .pagination-btns { display: flex; margin-top: 10px; }
    </style>
</head>
<body class="p-4">

    <!-- Form 1: Add Financial Year -->
    <div class="form-section" style="margin-top:100px;">
        <h2>Add Financial Year</h2>
        <form method="POST" action="">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="year_range" class="form-label">Financial Year</label>
                        <select class="form-select" name="year_range" id="year_range" required>
                        <option value="">Select Financial Year</option>
                        <?php
                            $startYear = 2020;
                            $endYear = 2030;
                            for ($i = $startYear; $i <= $endYear; $i++) {
                                $fy = $i . '-' . ($i + 1);
                                echo "<option value=\"$fy\">$fy</option>";
                            }
                        ?>
                        </select>
                </div>
                <div class="col-md-4">
                    <label>From Date</label>
                    <input type="date" name="from_date" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label>End Date</label>
                    <input type="date" name="to_date" class="form-control" required>
                </div>
                <div class="col-12 text-start mt-3">
                    <button type="submit" name="add_financial_year" style="height:40px; width: 200px;" class="btn btn-primary">Add Financial Year</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Form 2: View/Search -->
    <div class="form-section" style="margin-top:100px;">
        <div>
        <h2>Financial Year List</h2>
            <div class="text-end">
                <a href="FinancialYear/deactivate_financial.php" class="btn btn-danger">
                    <i class="fa fa-ban"></i>
                </a>
            </div>
        </div>

        <div class="filter-box">
            <label>Show 
                <select id="entriesFilter" class="form-select d-inline-block w-auto ms-2 me-2">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            entries</label>
        </div>

        <table class="table table-bordered table-striped" id="financialYearTable">
            <thead>
                <tr>
                    <th>Sr No</th>
                    <th>Financial Year</th>
                    <th>From Date</th>
                    <th>End Date</th>
                    <th>Action</th>
                </tr>
                <tr class="search-row">
                    <th><input type="text" onkeyup="filterTable(0)" placeholder="Search Sr No"></th>
                    <th><input type="text" onkeyup="filterTable(1)" placeholder="Search Year"></th>
                    <th><input type="text" onkeyup="filterTable(2)" placeholder="Search From Date"></th>
                    <th><input type="text" onkeyup="filterTable(3)" placeholder="Search To Date"></th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php
                $sr = $offset+1;
                while ($row = mysqli_fetch_assoc($records)) {
                    echo "<tr>
                        <td>$sr</td>
                        <td>{$row['year_range']}</td>
                        <td>{$row['from_date']}</td>
                        <td>{$row['to_date']}</td>
                        <td class='action-btns'>
                            <a href='FinancialYear/deactivate_financial.php?id={$row['id']}' onclick=\"return confirm('Are you sure you want to deactivate this year?')\">
                                <i class='fas fa-ban'></i>
                            </a>
                            <a href='FinancialYear/edit_financial.php?id={$row['id']}'>
                                <i class='fa fa-edit'></i>
                            </a>
                        </td>
                    </tr>";
                    $sr++;
                }
                ?>
            </tbody>
        </table>

        <!-- Pagination -->
<?php
$basePath = "/eduhyd/FinancialYear/financial_master.php";
?>
<div class="pagination text-center mt-3">
    <nav>
        <ul class="pagination justify-content-center">

            <!-- Prev Button -->
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="<?= $basePath ?>?page=<?= $page - 1 ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo; Prev</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Page Numbers -->
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="<?= $basePath ?>?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <!-- Next Button -->
            <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="<?= $basePath ?>?page=<?= $page + 1 ?>" aria-label="Next">
                        <span aria-hidden="true">Next &raquo;</span>
                    </a>
                </li>
            <?php endif; ?>

        </ul>
    </nav>
</div>

    </div>

    <script>

        function filterTable(colIndex) {
            const input = document.querySelectorAll(".search-row input")[colIndex].value.toLowerCase();
            const rows = document.querySelectorAll("#financialYearTable tbody tr");
            rows.forEach(row => {
                const cell = row.cells[colIndex].textContent.toLowerCase();
                row.style.display = cell.includes(input) ? "" : "none";
            });
        }

        displayTable();
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<?php include '../footer.php'; ?>
</body>
</html>