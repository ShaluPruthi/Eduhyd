<?php $base_path = "../";
ob_start();
include '../header.php';
$header = ob_get_clean();
$header = str_replace('<head>', '<head><base href="/eduhyd/">', $header);
echo $header;

include "../connect.php"; 

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM branch_master WHERE id = $id");
$row = mysqli_fetch_assoc($query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $branch_code = $_POST['branch_code'];
    $branch_name = $_POST['branch_name'];
    $branch_email = $_POST['email'];
    $branch_contact = $_POST['contact'];
    $branch_address = $_POST['branch_address'];

    $update = "UPDATE branch_master SET 
               branch_code='$branch_code', branch_name='$branch_name', 
               email='$branch_email', contact='$branch_contact', 
               branch_address='$branch_address' 
               WHERE id=$id";
    if (mysqli_query($conn, $update)) {
        echo "<script>alert('Branch Updated Successfully'); window.location.href='Branch/branch_master.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Branch</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>
    <div class="container" style="max-width:1400px; margin-top:150px;">
        <div class="card shadow rounded">
            <div class="card-header">
                <h2 class="mb-0">Edit Branch</h2>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="row mb-5">
                        <div class="col-md-4">
                            <label class="form-label">Branch Code</label>
                            <input type="text" name="branch_code" class="form-control" value="<?= $row['branch_code']; ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Branch Name</label>
                            <input type="text" name="branch_name" class="form-control" value="<?= $row['branch_name']; ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Contact</label>
                            <input type="text" name="contact" class="form-control" value="<?= $row['contact']; ?>" required>
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-md-4">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= $row['email']; ?>" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Branch Address</label>
                            <textarea name="branch_address" class="form-control" rows="3" required><?= $row['branch_address']; ?></textarea>
                        </div>
                    </div>

                    <div class="text-start">
                        <button type="submit" name="update" class="btn btn-success" style="width:150px; height:40px; text-align:center;">Update Branch</button>
                        <a href="Branch/branch_master.php" class="btn btn-secondary" style="width:150px; height:40px; text-align:center;">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php include('../footer.php') ?>
</body>
</html>
