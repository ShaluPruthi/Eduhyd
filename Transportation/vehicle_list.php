<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$base_path = "../";
ob_start();
include '../header.php';
$header = ob_get_clean();
$header = str_replace('<head>', '<head><base href="/eduhyd/">', $header);

echo $header;

include_once ("../connect.php");

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM vehicles WHERE id = $id");
    echo "<script>alert('Vehicle deleted successfully'); window.location.href='Transportation/vehicle_list.php';</script>";
    exit();
}


$limit = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

$result = $conn->query("SELECT * FROM vehicles LIMIT $start, $limit");
$total = $conn->query("SELECT COUNT(*) as total FROM vehicles")->fetch_assoc()['total'];
$pages = ceil($total / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Vehicle List</title>
    <base href="/eduhyd/">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/kaiadmin.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="container" style="margin-top:100px; width:1000px">
    <div class="card-header d-flex justify-content-between mt-5">
        <h2>Vehicle List</h2>
        <div style="font-size:20px;">
            <a href="Transportation/add_vehicle.php" class="btn btn-success btn-sm me-3">➕ Add New Vehicle</a>
            <a href="Transportation/assign_transportation.php" class="btn btn-primary btn-sm">🚐 Assign Transportation</a>
        </div>
    </div>

    <div class="card shadow p-3 mx-auto">
        <table class="table table-bordered text-center small">
            <thead class="table-secondary">
                <tr>
                    <th>Vehicle Number</th>
                    <th>Vehicle Name</th>
                    <th>Driver Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['vehicle_no'] ?></td>
                    <td><?= $row['vehicle_name'] ?></td>
                    <td><?= $row['driver_name'] ?></td>
                    <td>
                        <a href="/eduhyd/Transportation/edit_vehicle.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                        <a href="/eduhyd/Transportation/vehicle_list.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete this Vehicle?')"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>

        <nav class="mt-3 d-flex justify-content-center">
            <ul class="pagination pagination-sm">
                <?php for ($i = 1; $i <= $pages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
</div>

<?php include('../footer.php')?>

</body>
</html>