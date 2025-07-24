<?php

$base_path = "../";
ob_start();
include '../header.php';
$header = ob_get_clean();

$header = str_replace('<head>', '<head><base href="/eduhyd/">', $header);

echo $header;
include "../connect.php";

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM company_master WHERE id=$id");
$data = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['company_code'];
    $name = $_POST['company_name'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $website = $_POST['company_website'];
    $address = $_POST['company_address'];

    $update = "UPDATE company_master SET 
        company_code='$code',
        company_name='$name',
        contact='$contact',
        email='$email',
        company_website='$website',
        company_address='$address'
        WHERE id=$id";

    if ($conn->query($update)) {
        echo "<script>alert('Company updated successfully'); window.location.href='/eduhyd/Company/company_master.php';</script>";
        exit;
    } else {
        $error = "Update failed: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Company</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://kairo-ui.kodity.com/assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="container" style="margin-top:100px; width:1000px;">
    <div class="card">
        <div class="card-header">
            <h3>Edit Company</h3>
        </div>
        <div class="card-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label>Company Code</label>
                    <input type="text" name="company_code" class="form-control" value="<?= $data['company_code'] ?>" required>
                </div>
                <div class="mb-3">
                    <label>Company Name</label>
                    <input type="text" name="company_name" class="form-control" value="<?= $data['company_name'] ?>" required>
                </div>
                <div class="mb-3">
                    <label>Contact</label>
                    <input type="text" name="contact" class="form-control" value="<?= $data['contact'] ?>">
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="<?= $data['email'] ?>">
                </div>
                <div class="mb-3">
                    <label>Website</label>
                    <input type="text" name="company_website" class="form-control" value="<?= $data['company_website'] ?>">
                </div>
                <div class="mb-3">
                    <label>Address</label>
                    <textarea name="company_address" class="form-control"><?= $data['company_address'] ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Update Company</button>
                <a href="Company/company_master.php" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</div>

<?php include('../footer.php')?>
</body>
</html>
