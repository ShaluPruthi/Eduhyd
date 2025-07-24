<?php

$base_path = "../";
ob_start();
include '../header.php';
$header = ob_get_clean();
$header = str_replace('<head>', '<head><base href="/eduhyd/">', $header);
echo $header;

include '../connect.php';

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $deleteSql = "DELETE FROM assignments WHERE id = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo "<script>alert('Assignment deleted successfully'); window.location.href='view_assignment.php';</script>";
}

// Fetch assignments
$sql = "SELECT * FROM assignments ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Assignment List</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f2f2f2;
        }
        /* .container {
            margin-top: 100px;
        } */
        .table {
            background-color: #fff;
        }
        .action-icons i {
            cursor: pointer;
            font-size: 18px;
            margin: 0 5px;
        }
        .header-buttons {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .card {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 20px;
            border-radius: 15px;
        }
        .btn-sm {
            padding: 4px 10px;
        }
    </style>
</head>
<body>

<div class="container" style="width:1300px; margin-top:100px">
    <div class="header-buttons">
        <h3>Assignments List</h3>
        <div>
            <a href="Assignment/add_assignment.php" style="height:50px" class="btn btn-success btn-sm"> <h4>➕ Add Assignment</h4></a>
        </div>
    </div>

    <div class="card">
        <table class="table table-bordered table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Assignment</th>
                    <th>From Date</th>
                    <th>To Date</th>
                    <th>Class Name</th>
                    <th>Subject</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): 
                    $count = 1;
                    while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $count++ ?></td>
                            <td><?= htmlspecialchars($row['assignment_text']) ?></td>
                            <td><?= htmlspecialchars($row['from_date']) ?></td>
                            <td><?= htmlspecialchars($row['to_date']) ?></td>
                            <td><?= htmlspecialchars($row['class_name']) ?></td>
                            <td><?= htmlspecialchars($row['subject_name']) ?></td>
                            <td class="action-icons">
                                <a href="Assignment/edit_assignment.php?id=<?= $row['id'] ?>" title="Edit"><i class="bi bi-pencil-square text-primary"></i></a>
                                <a href="Assignment/view_assignment.php?delete=<?= $row['id'] ?>" title="Delete" onclick="return confirm('Are you sure you want to delete this assignment?');"><i class="bi bi-trash text-danger"></i></a>
                            </td>
                        </tr>
                <?php endwhile; else: ?>
                    <tr>
                        <td colspan="7">No assignments found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include('../footer.php')?>
</body>
</html>