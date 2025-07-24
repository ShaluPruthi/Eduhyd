<?php

$base_path = "../";
ob_start();
include '../header.php';
$header = ob_get_clean();
$header = str_replace('<head>', '<head><base href="/eduhyd/">', $header);

echo $header;
include_once("../connect.php");

if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_sql = "DELETE FROM transport_assignments WHERE id = $delete_id";
    $conn->query($delete_sql);
    echo "<script>alert('Assignment deleted successfully.'); window.location='Transportation/view_assignment.php';</script>";
    exit;
}

$query = "
    SELECT a.*, s.fname, s.lname, v.vehicle_no 
    FROM transport_assignments a
    JOIN students ON a.student_id = s.id
    JOIN vehicles v ON a.vehicle_id = v.id
";
$data = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Assignments</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/kaiadmin.min.css">
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4">Transportation Assignments</h3>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Student</th>
                <th>Vehicle</th>
                <th>From Date</th>
                <th>To Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $data->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['fname'] . ' ' . $row['lname'] ?></td>
                    <td><?= $row['vehicle_no'] ?></td>
                    <td><?= $row['from_date'] ?></td>
                    <td><?= $row['to_date'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php include_once("../footer.php"); ?>
</body>
</html>
