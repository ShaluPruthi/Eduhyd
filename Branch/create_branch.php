<?php $base_path = "../";
ob_start();
include '../header.php';
$header = ob_get_clean();
$header = str_replace('<head>', '<head><base href="/eduhyd/">', $header);
echo $header;

include "../connect.php"; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $branch_code = $_POST['branch_code'];
    $branch_name = $_POST['branch_name'];
    $branch_email = $_POST['email'];
    $branch_contact = $_POST['contact'];
    $branch_address = $_POST['branch_address'];

    $query = "SELECT * FROM branch_master WHERE branch_code = '$branch_code' OR email = '$branch_email' OR contact = '$branch_contact'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Branch with same code, email or contact already exists!'); window.location.href='Branch/branch_master.php';</script>";
    } else {
        $insert = "INSERT INTO branch_master (branch_code, branch_name, email, contact, branch_address, active) 
                   VALUES ('$branch_code', '$branch_name', '$branch_email', '$branch_contact', '$branch_address', 1)";
        if (mysqli_query($conn, $insert)) {
            echo "<script>alert('Branch Added Successfully'); window.location.href='Branch/branch_master.php';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Branch</title>
    <link href="../assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container" style="margin-top:150px; max-width:1400px;">
<div class="card p-4">
    <h2>Add New Branch</h2>
    <form method="POST" action="">
        
        <div class="row m-3">
            <div class="col-md-4 mb-3 p-3">
                <label>Branch Code</label>
                <input type="text" name="branch_code" class="form-control" maxlength="5" pattern="\d{1,5}" required>
            </div>
            <div class="col-md-4 mb-3 p-3">
                <label>Branch Name</label>
                <input type="text" name="branch_name" class="form-control" maxlength="50" required>
            </div>
            <div class="col-md-4 mb-3 p-3">
                <label>Contact</label>
                <input type="text" name="contact" class="form-control" maxlength="10" pattern="\d{10}">
            </div>
            <div class="col-md-4 mb-3 p-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control">
            </div>
            <div class="col-md-6 mb-3 p-3">
                <label>Branch Address</label>
                <textarea name="branch_address" class="form-control" maxlength="300"></textarea>
            </div>
            <div class="col-md-12 p-3 text-start">
                <button type="submit" class="btn btn-success" style="width:150px; height:40px;">Save</button>
                <a href="Branch/branch_master.php" class="btn btn-secondary" style="width:150px; height:40px;">Back</a>
            </div>
        </div>

    </form>
</div>
</div>
<?php include('../footer.php')?>

</body>
</html>
