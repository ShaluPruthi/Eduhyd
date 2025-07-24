<?php $base_path = "../";
ob_start();
include '../header.php';
$header = ob_get_clean();
$header = str_replace('<head>', '<head><base href="/eduhyd/">', $header);
echo $header;

include "../connect.php"; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = $_POST['code'];
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    $checkQuery = "SELECT * FROM sub_department_master WHERE code='$code' OR contact='$contact' OR email='$email'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        echo "<script>alert('Sub-department code, contact, or email already exists!');
        window.location.href='Sub_department/add_sub_department.php';</script>";
    } else {
        $insertQuery = "INSERT INTO sub_department_master (code, name, contact, email, address, active)
                        VALUES ('$code', '$name', '$contact', '$email', '$address', 1)";
        if (mysqli_query($conn, $insertQuery)) {
            echo "<script>alert('Sub-department added successfully.');
            window.location.href='Sub_department/department_master.php';</script>";
        } else {
            echo "<script>alert('Error adding sub-department.');</script>";
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
    <h2 class="mb-4">Add Sub-Department</h2>
    <form action="" method="post">
        <div class="row m-3">
            <div class="col-md-4 mb-5">
                <label>Sub-Department Code</label>
                <input type="text" name="code" maxlength="5" pattern="\d{1,5}" required class="form-control">
            </div>
            <div class="col-md-4 mb-5">
                <label>Sub-Department Name</label>
                <input type="text" name="name" maxlength="50" pattern="[A-Za-z0-9\s]+" required class="form-control">
            </div>
            <div class="col-md-4 mb-5">
                <label>Contact</label>
                <input type="text" name="contact" maxlength="10" pattern="\d{10}" required class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label>Email</label>
                <input type="email" name="email" required class="form-control">
            </div>
            <div class="col-md-6 mb-3">
                <label>Sub-Department Address</label>
                <input type="text" name="address" maxlength="300" class="form-control">
            </div>
        </div>
        <button type="submit" class="btn btn-success mt-3" style="height:40px;">Add Sub-Department</button>
    </form>
</div>
</div>



<?php include('../footer.php')?>