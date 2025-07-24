<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$base_path = "../";
ob_start();
include '../header.php';
$header = ob_get_clean();
$header = str_replace('<head>', '<head><base href="/eduhyd/">', $header);
echo $header;
include '../connect.php';
global $conn;

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $conn->query("DELETE FROM education WHERE staff_id = $delete_id");
    $conn->query("DELETE FROM staff WHERE id = $delete_id");
    echo "<script>window.location.href='display.php';</script>";
    exit();
}

$limit = 10; 
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start_from = ($page - 1) * $limit;

$query = "SELECT * FROM staff ORDER BY id DESC LIMIT $start_from, $limit";
$data = mysqli_query($conn, $query);

$total_query = "SELECT COUNT(*) FROM staff";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_row($total_result);
$total_records = $total_row[0];
$total_pages = ceil($total_records / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Records - Paginated</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        .icon-btn {
            font-size: 1.2rem;
            padding: 6px 10px;
            border: none;
            background: none;
        }
        .icon-btn:hover {
            color: #007bff;
            cursor: pointer;
        }

        
    </style>
</head>

<body>
<div class="container py-5" style="width:1300px; margin-top:100px">
    <h3 class="mb-4">Staff Records</h3>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Bank</th>
                <th>Documents</th>
                <th>Education</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (mysqli_num_rows($data) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($data)): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['fname'] ?> <?= $row['lname'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['phone'] ?></td>
                    <td><?= $row['bank_name'] ?></td>
                    <td>
                        <a href='<?= $row['resume'] ?>' target='_blank'>Resume</a> |
                        <a href='<?= $row['photo'] ?>' target='_blank'>Photo</a>
                    </td>
                    <td>
                        <?php 
                            $staffId = $row['id'];
                            $eduCount = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM education WHERE staff_id = $staffId"));
                            echo $eduCount;
                        ?>
                    </td>
                    <td class="text-center">
                        <a href='Staff/update.php?id=<?= $row['id'] ?>' title="Update">
                            <i class="fas fa-pencil-alt text-warning icon-btn"></i>
                        </a>
                        <a href='?delete_id=<?= $row['id'] ?>' onclick='return confirm("Are you sure to delete this record?")' title="Delete">
                            <i class="fas fa-trash-alt text-danger icon-btn"></i>
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="8" class="text-center">No Records Found</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <nav aria-label="Page navigation example">
      <ul class="pagination justify-content-center">
        <?php if($page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="display.php?page=<?= ($page-1) ?>">Previous</a>
            </li>
        <?php endif; ?>

        <?php for ($i=1; $i<=$total_pages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                <a class="page-link" href="display.php?page=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <?php if($page < $total_pages): ?>
            <li class="page-item">
                <a class="page-link" href="display.php?page=<?= ($page+1) ?>">Next</a>
            </li>
        <?php endif; ?>
      </ul>
    </nav>

</div>

<script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
