<?php $base_path = "../";
ob_start();
include '../header.php';
$header = ob_get_clean();
$header = str_replace('<head>', '<head><base href="/eduhyd/">', $header);
echo $header;

include "../connect.php"; 

$id = $_GET['id'];
$query = "SELECT * FROM sub_department_master WHERE id = $id";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);
?>

<div class="container" style="margin-top: 150px; max-width: 1400px;">
    <div class="card p-4">
        <h4 class="mb-4">Edit Sub-Department</h4>
        <form action="" method="post">
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="code" class="form-label">Code</label>
                    <input type="text" id="code" name="code" value="<?= $data['code'] ?>" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" id="name" name="name" value="<?= $data['name'] ?>" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label for="contact" class="form-label">Contact</label>
                    <input type="text" id="contact" name="contact" value="<?= $data['contact'] ?>" class="form-control" required>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" value="<?= $data['email'] ?>" class="form-control" required>
                </div>
                <div class="col-md-8">
                    <label for="address" class="form-label">Address</label>
                    <textarea id="address" name="address" class="form-control" rows="3" required><?= $data['address'] ?></textarea>
                </div>
            </div>

            <div class="text-start">
                <button type="submit" name="update" class="btn btn-primary">Update</button>
                <a href="Sub_department/department_master.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include '../footer.php'; ?>

<?php
if (isset($_POST['update'])) {
    $code = $_POST['code'];
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    $update = "UPDATE sub_department_master SET code='$code', name='$name', contact='$contact', email='$email', address='$address' WHERE id=$id";
    mysqli_query($conn, $update);
    echo "<script>window.location.href='Sub_department/department_master.php';</script>";
}
?>
