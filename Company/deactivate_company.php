<?php
include '../connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("UPDATE company_master SET status='inactive' WHERE id=$id");
}
echo "<script>alert('Company deactivated successfully'); window.location.href='/eduhyd/Company/company_master.php';</script>";
exit;
?>
