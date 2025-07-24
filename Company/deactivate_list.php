<?php
$base_path = "../";
ob_start();
include '../header.php';
$header = ob_get_clean();
$header = str_replace('<head>', '<head><base href="/eduhyd/">', $header);
echo $header;

include "../connect.php";

$sql = "SELECT * FROM company_master WHERE status = 0 ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Deactivated Companies</title>
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
            <h2 class="header-title">Deactivated Companies</h2>
            <a href="Company/company_master.php" class="btn btn-dark back-btn"><i class="bi bi-arrow-left"></i> Back to Company Master</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Company Code</th>
                        <th>Company Name</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <th>Website</th>
                        <th>Address</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['company_code']) ?></td>
                                <td><?= htmlspecialchars($row['company_name']) ?></td>
                                <td><?= htmlspecialchars($row['contact']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= htmlspecialchars($row['company_website']) ?></td>
                                <td><?= htmlspecialchars($row['company_address']) ?></td>
                                <td><span class="badge bg-danger">Deactivated</span></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center">No deactivated companies found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
