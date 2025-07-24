<?php
$base_path = "../";
ob_start();
include '../header.php';
$header = ob_get_clean();
$header = str_replace('<head>', '<head><base href="/eduhyd/">', $header);
echo $header;

include "../connect.php";

$searchCode = $_GET['code'] ?? '';
$searchName = $_GET['name'] ?? '';

$sql = "SELECT * FROM company_master WHERE status = 'active'";
if ($searchCode !== '') {
    $sql .= " AND company_code LIKE '%$searchCode%'";
}
if ($searchName !== '') {
    $sql .= " AND company_name LIKE '%$searchName%'";
}

$result = mysqli_query($conn, $sql);
$total = mysqli_num_rows($result);

$limit = 2;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$totalQuery = "SELECT COUNT(*) AS total FROM company_master WHERE status = 'active'";
$totalResult = mysqli_query($conn, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalRecords = $totalRow['total'];
$totalPages = ceil($totalRecords / $limit);

$query = "SELECT * FROM company_master WHERE status = 'active' ORDER BY id DESC LIMIT $offset, $limit";
$records = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Master</title>
    <link rel="stylesheet" href="../assets/css/kaiadmin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        .filter-input {
            width: 100%;
            padding: 4px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 13px;
        }
        .action-btn {
            padding: 8px 12px;
            font-size: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            color: white;
            border: none;
            transition: 0.2s ease-in-out;
        }
        .action-btn.green {
            background-color: #28a745;
        }
        .action-btn.red {
            background-color: #dc3545;
        }
        .action-btn i {
            font-size: 18px;
        }
    </style>
    <script>
        function filterTable() {
            const code = document.getElementById('filterCode').value;
            const name = document.getElementById('filterName').value;
            const website = document.getElementById('filterWebsite').value;
            const address = document.getElementById('filterAddress').value;
            window.location.href = `company_master.php?code=${code}&name=${name}&website=${website}&address=${address}`;
        }
    </script>
</head>
<body>
<div class="container" style="margin-top:100px; width:1400px">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold">Company</h2>
        <div>
            <a href="Company/create_company.php" class="action-btn green me-2" title="Add Company">
                <i class="fas fa-plus"></i>
            </a>
            <a href="Company/deactivate_list.php" class="action-btn red" title="Deactivated Company">
                <i class="fas fa-ban"></i>
            </a>
        </div>
    </div>

    <div class="card p-2">
        <div class="row align-items-end mb-3">
            <div class="col-md-3">
                <input type="text" id="filterCode" class="filter-input" placeholder="Company Code" value="<?= htmlspecialchars($searchCode) ?>">
            </div>
            <div class="col-md-3">
                <input type="text" id="filterName" class="filter-input" placeholder="Company Name" value="<?= htmlspecialchars($searchName) ?>">
            </div>
            <div class="col-md-3">
                <input type="text" id="filterWebsite" class="filter-input" placeholder="Website Url">
            </div>
            <div class="col-md-2">
                <input type="text" id="filterAddress" class="filter-input" placeholder="Company Address">
            </div>
            <div class="col-md-1">
                <button class="btn btn-success btn-sm" style="margin: 0 auto;" onclick="filterTable()">SEARCH</button>
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
                <label>Show
                    <select class="form-select d-inline w-auto" onchange="location.href='?limit=' + this.value;">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                entries</label>
            </div>
            <div>
                <input type="text" class="form-control form-control-sm" style="width:200px" placeholder="Search..." onkeyup="filterTable()">
            </div>
        </div>

        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>SN</th>
                    <th>Company Code</th>
                    <th>Company Name</th>
                    <th>Website Url</th>
                    <th>Company Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sr = $offset+1;
                while ($row = mysqli_fetch_assoc($records)) {
                    echo "<tr>
                        <td>$sr</td>
                        <td>{$row['company_code']}</td>
                        <td>{$row['company_name']}</td>
                        
                        <td>{$row['company_website']}</td>
                        <td>{$row['company_address']}</td>
                        <td class='action-btns'>
                        <a href='Company/deactivate_company.php?id={$row['id']}' onclick=\"return confirm('Are you sure you want to deactivate this year?')\">
                                <i class='fas fa-ban'></i>
                            </a>
                            <a href='Company/edit_company.php?id={$row['id']}'>
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
$basePath = "/eduhyd/Company/company_master.php";
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
</div>

<?php include('../footer.php')?>
</body>
</html>
