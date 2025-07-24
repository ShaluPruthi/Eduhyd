<?php $base_path = "../";
ob_start();
include '../header.php';
$header = ob_get_clean();
$header = str_replace('<head>', '<head><base href="/eduhyd/">', $header);
echo $header;

include "../connect.php"; 

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $update = "UPDATE sub_department_master SET active = 0 WHERE id = $id";
    mysqli_query($conn, $update);

    echo "<script>
        alert('Sub-department deactivated successfully.');
        window.location.href = 'Sub_department/deactivate_sub_department.php';
    </script>";
    exit();
}

$query = "SELECT * FROM sub_department_master WHERE active = 0 ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Deactivated Sub-Departments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/font-awesome/css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
    <div class="container" style="max-width:1400px; margin-top:100px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="header-title">Deactivated Sub-Department</h2>
            <a href="Sub_department/department_master.php" class="btn btn-dark back-btn"><i class="bi bi-arrow-left"></i> Back to Sub-Department Master</a>
        </div>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Sub Department Code</th>
                    <th>Sub Department Name</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['code']); ?></td>
                            <td><?= htmlspecialchars($row['name']); ?></td>
                            <td><?= htmlspecialchars($row['contact']); ?></td>
                            <td><?= htmlspecialchars($row['email']); ?></td>
                            <td><?= htmlspecialchars($row['address']); ?></td>
                            <td><span class="badge bg-danger">Deactivated</span></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No deactivated sub-departments found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>