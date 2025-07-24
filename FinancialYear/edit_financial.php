<?php
$base_path = "../";
ob_start();
include '../header.php';
$header = ob_get_clean();
$header = str_replace('<head>', '<head><base href="/eduhyd/">', $header);
echo $header;

include "../connect.php";

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM financial_year_master WHERE id=$id"));

if (isset($_POST['update'])) {
    $code = $_POST['code'];
    $range = $_POST['year_range'];
    $from = $_POST['from_date'];
    $to = $_POST['to_date'];

    mysqli_query($conn, "UPDATE financial_year_master SET code='$code', year_range='$range', from_date='$from', to_date='$to' WHERE id=$id");
    echo "<script>alert('Updated successfully'); window.location.href='FinancialYear/financial_master.php';</script>";
}
?>

<div class="container" style="margin-top:150px; max-width:1400px;">
<div class="card p-4">
    <h4>Edit Financial Year</h4>
    <form method="post">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Code</label>
                <input type="text" name="code" class="form-control" value="<?= $data['code'] ?>" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="<?= $data['title'] ?>" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>From Date</label>
                <input type="date" name="from_date" class="form-control" value="<?= $data['from_date'] ?>" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>To Date</label>
                <input type="date" name="to_date" class="form-control" value="<?= $data['to_date'] ?>" required>
            </div>
        </div>
        <button class="btn btn-primary" name="update">Update</button>
        <a href="FinancialYear/financial_master.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</div>

<?php include '../footer.php'; ?>
