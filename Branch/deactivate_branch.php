<?php

error_reporting(E_ALL); ini_set('display_errors', 1);

$base_path = "../";
ob_start();
include '../header.php';
$header = ob_get_clean();
$header = str_replace('<head>', '<head><base href="/eduhyd/">', $header);
echo $header;

include "../connect.php"; 

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("UPDATE branch_master SET active = 0 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Fetch deactivated branches
$sql = "SELECT * FROM branch_master WHERE active = 0";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Deactivated Branches</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table th, .table td {
            vertical-align: middle;
        }
        .table thead {
            background-color: #f1f1f1;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }
        .container-box {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
        }
        .action-btn {
            font-size: 14px;
            padding: 5px 10px;
        }
        .header-title {
            font-size: 24px;
            font-weight: 600;
        }
        .back-btn {
            float: right;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="container" style="margin-top:100px; max-width:1400px;">
    <div class="container-box">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="header-title">Deactivated Branches</h2>
            <a href="Branch/branch_master.php" class="btn btn-dark back-btn"><i class="bi bi-arrow-left"></i> Back to Branch Master</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Branch Code</th>
                        <th>Branch Name</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Status</th>
                    </tr>
                </thead>
        <tbody>
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['branch_code']) ?></td>
                    <td><?= htmlspecialchars($row['branch_name']) ?></td>
                    <td><?= htmlspecialchars($row['contact']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['branch_address']) ?></td>
                    <td><span class="badge badge-danger status-badge">Deactivated</span></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center text-muted">No deactivated branches found.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
        </div>
        </div>

<?php include('../footer.php')?>
</body>
</html>