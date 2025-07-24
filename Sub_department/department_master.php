<?php $base_path = "../";
ob_start();
include '../header.php';
$header = ob_get_clean();
$header = str_replace('<head>', '<head><base href="/eduhyd/">', $header);
echo $header;

include "../connect.php"; 

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Get total records count for pagination
$totalQuery = "SELECT COUNT(*) AS total FROM sub_department_master WHERE active = 1";
$totalResult = mysqli_query($conn, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalRecords = $totalRow['total'];
$totalPages = ceil($totalRecords / $limit);

$query = "SELECT * FROM sub_department_master WHERE active = 1 ORDER BY id DESC LIMIT $offset, $limit";
$records = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sub Department List</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/font-awesome.min.css">
    <style>
        .container-box {
            background: #fff;
            padding: 25px;
            margin-top: 30px;
            box-shadow: 0px 0px 8px #dcdcdc;
            border-radius: 10px;
        }
        .top-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .top-actions .left-inputs input {
            margin-right: 10px;
        }
        .filter-box select {
            padding: 5px 10px;
            border-radius: 5px;
        }
        .action-icons i {
            font-size: 18px;
            margin-right: 10px;
            cursor: pointer;
        }
        .deactivate-btn {
            float: right;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 5px 15px;
            font-size: 14px;
        }
        table th, table td {
            vertical-align: middle !important;
        }
    </style>
</head>
<body>

<div class="container" style="max-width:1400px; margin-top:100px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold">Sub Department</h2>
                <div>
                    <a href="Sub_department/add_sub_department.php" class="btn btn-success"><i class="fa fa-plus"></i></a>
                    <a href="Sub_department/deactivate_sub_department.php" class="btn btn-danger"><i class="fa fa-ban"></i></a>
                </div>
        </div>
    <div class="card p-2">
        <div class="row mb-3">
            <div class="col-md-2">
                    <input type="text" id="codeFilter" class="form-control" placeholder="Sub Department Code">
                </div>
                <div class="col-md-2">
                    <input type="text" id="nameFilter" class="form-control" placeholder="Sub Department Name">
                </div>
                <div class="col-md-2">
                    <input type="text" id="contactFilter" class="form-control" placeholder="contact">
                </div>
                <div class="col-md-2">
                    <input type="text" id="emailFilter" class="form-control" placeholder="Email">
                </div>
                <div class="col-md-2">
                    <input type="text" id="addressFilter" class="form-control" placeholder="Address">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-success" onclick="searchSubDepartments()">SEARCH</button>
                </div>                
            </div>
        <div class="top-actions mt-4">
        <div class="filter-box">
            <select class="form-select" id="entriesFilter" onchange="paginateTable()">
                <option value="10">Show 10 entries</option>
                <option value="25">Show 25 entries</option>
                <option value="50">Show 50 entries</option>
                <option value="100">Show 100 entries</option>
            </select>
        </div>
    </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>SN</th>
                            <th>Sub Department Code</th>
                            <th>Sub Department Name</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="subDepartmentTable">
                        <?php
                        $query = "SELECT * FROM sub_department_master WHERE active = 1";
                        $result = mysqli_query($conn, $query);
                        $sn = $offset + 1;
                        while ($row = mysqli_fetch_assoc($records)) {
                            echo "<tr>
                                <td>{$sn}</td>
                                <td>{$row['code']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['contact']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['address']}</td>
                                <td>
                                    <a href='Sub_department/edit_sub_department.php?id={$row['id']}' class='btn btn-primary me-1'>
                                        <i class='fa fa-edit'></i>
                                    </a>
                                    <a href='Sub_department/deactivate_sub_department.php?id={$row['id']}' class='btn btn-danger'>
                                        <i class='fa fa-ban'></i>
                                    </a>
                                </td>
                            </tr>";
                            $sn++;
                        }
                        ?>
                        <tr id="noDataRow" style="display: none;">
                            <td colspan="7" class="text-center text-danger">No records found.</td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
<?php
$basePath = "/eduhyd/Sub_department/department_master.php";
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
</div>

<script>
    let currentPage = 1;
    const rowsPerPage = 10;

    function showPage(page) {
        const table = document.getElementById("subDepartmentTable");
        const rows = table.querySelectorAll("tr:not(#noDataRow)");
        const totalRows = rows.length;
        const totalPages = Math.ceil(totalRows / rowsPerPage);

        if (page < 1) page = 1;
        if (page > totalPages) page = totalPages;

        rows.forEach((row, index) => {
            row.style.display = (index >= (page - 1) * rowsPerPage && index < page * rowsPerPage) ? "" : "none";
        });

        document.getElementById("pageNumber").innerText = page;
        document.getElementById("entryInfo").innerText =
            totalRows === 0
                ? "Showing 0 of 0 entries"
                : `Showing ${(page - 1) * rowsPerPage + 1} to ${Math.min(page * rowsPerPage, totalRows)} of ${totalRows} entries`;

        document.getElementById("noDataRow").style.display = totalRows === 0 ? "" : "none";
    }

    function nextPage() {
        const table = document.getElementById("subDepartmentTable");
        const rows = table.querySelectorAll("tr:not(#noDataRow)");
        const totalPages = Math.ceil(rows.length / rowsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            showPage(currentPage);
        }
    }

    function prevPage() {
        if (currentPage > 1) {
            currentPage--;
            showPage(currentPage);
        }
    }

    function filterTable() {
        const code = document.getElementById("codeFilter").value.toLowerCase();
        const name = document.getElementById("nameFilter").value.toLowerCase();
        const contact = document.getElementById("contactFilter").value.toLowerCase();
        const email = document.getElementById("emailFilter").value.toLowerCase();
        const address = document.getElementById("addressFilter").value.toLowerCase();

        const table = document.getElementById("subDepartmentTable");
        const rows = table.querySelectorAll("tr:not(#noDataRow)");
        let visibleCount = 0;

        rows.forEach(row => {
            const cells = row.getElementsByTagName("td");
            const match =
                cells[1].innerText.toLowerCase().includes(code) &&
                cells[2].innerText.toLowerCase().includes(name) &&
                cells[3].innerText.toLowerCase().includes(contact) &&
                cells[4].innerText.toLowerCase().includes(email) &&
                cells[5].innerText.toLowerCase().includes(address);

            row.style.display = match ? "" : "none";
            if (match) visibleCount++;
        });

        document.getElementById("noDataRow").style.display = visibleCount === 0 ? "" : "none";

        // Optional: Reset to first page after filtering
        currentPage = 1;
        showPage(currentPage);
    }

    document.addEventListener("DOMContentLoaded", function () {
        showPage(currentPage);

        document.getElementById("codeFilter").addEventListener("input", filterTable);
        document.getElementById("nameFilter").addEventListener("input", filterTable);
        document.getElementById("contactFilter").addEventListener("input", filterTable);
        document.getElementById("emailFilter").addEventListener("input", filterTable);
        document.getElementById("addressFilter").addEventListener("input", filterTable);
    });
</script>



<?php include('../footer.php')?>