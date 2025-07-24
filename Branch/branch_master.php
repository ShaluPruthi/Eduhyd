<?php
$base_path = "../";
ob_start();
include '../header.php';
$header = ob_get_clean();
$header = str_replace('<head>', '<head><base href="/eduhyd/">', $header);
echo $header;

include "../connect.php";

$searchCode = isset($_GET['branch_code']) ? trim($_GET['branch_code']) : '';
$searchName = isset($_GET['branch_name']) ? trim($_GET['branch_name']) : '';
$searchAddress = isset($_GET['branch_address']) ? trim($_GET['branch_address']) : '';

$where = "WHERE active = 1";
if (!empty($searchCode)) {
    $where .= " AND branch_code LIKE '%$searchCode%'";
}
if (!empty($searchName)) {
    $where .= " AND branch_name LIKE '%$searchName%'";
}
if (!empty($searchAddress)) {
    $where .= " AND branch_address LIKE '%$searchAddress%'";
}

// Pagination setup
$limit = 2;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Get total records count for pagination
$totalQuery = "SELECT COUNT(*) AS total FROM branch_master $where";
$totalResult = mysqli_query($conn, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalRecords = $totalRow['total'];
$totalPages = ceil($totalRecords / $limit);

$query = "SELECT * FROM branch_master $where ORDER BY id DESC LIMIT $offset, $limit";
$records = mysqli_query($conn, $query);
$total = mysqli_num_rows($records);

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
</head>
<body>

<div class="container" style="max-width:1400px; margin-top:100px;">
    <div class="d-flex justify-content-between mb-3">
        <h2>Branch</h2>
        <div>
            <a href="Branch/create_branch.php" class="action-btn green me-2" title="Add Company">
                <i class="fas fa-plus"></i>
            </a>
            <a href="Branch/deactivate_branch.php" class="action-btn red" title="Deactivated Company">
                <i class="fas fa-ban"></i>
            </a>
        </div>
    </div>

    <div class="card p-2">
        <div class="row align-items-end mb-3">
        <div class="col-md-3">
            <input type="text" name="code" value="<?= $searchCode ?>" class="form-control" placeholder="Branch Code">
        </div>
        <div class="col-md-3">
            <input type="text" name="name" value="<?= $searchName ?>" class="form-control" placeholder="Branch Name">
        </div>
        <div class="col-md-3">
            <input type="text" name="address" value="<?= $searchAddress ?>" class="form-control" placeholder="Branch Address">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-success w-100">SEARCH</button>
        </div>
    </div>

    <table class="table table-bordered text-center align-middle">
        <thead class="table-light">
            <tr>
                <th>SN</th>
                <th>Branch Code</th>
                <th>Branch Name</th>
                <th>Contact</th>
                <th>Email</th>
                <th>Branch Address</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($total > 0) {
                $sn = $offset + 1;
                while ($row = mysqli_fetch_assoc($records)) {
                    echo "<tr>
                        <td>{$sn}</td>
                        <td>{$row['branch_code']}</td>
                        <td>{$row['branch_name']}</td>
                        <td>{$row['contact']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['branch_address']}</td>
                        <td>
                            <a href='Branch/edit_branch.php?id={$row['id']}' class='text-primary me-2'><i class='fa fa-edit'></i></a>
                            <a href='Branch/deactivate_branch.php?id={$row['id']}' class='text-danger'><i class='fa fa-ban'></i></a>
                        </td>
                    </tr>";
                    $sn++;
                }
            } else {
                echo "<tr><td colspan='7'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>


    <!-- Pagination -->
<?php
$basePath = "/eduhyd/Branch/branch_master.php";
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
<?php include '../footer.php'; ?>

</body>
</html>