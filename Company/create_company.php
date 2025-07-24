<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

$base_path = "../";
ob_start();
include '../header.php';
$header = ob_get_clean();

$header = str_replace('<head>', '<head><base href="/eduhyd/">', $header);

echo $header;
include "../connect.php";

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['company_code'];
    $name = $_POST['company_name'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $website = $_POST['company_website'];
    $address = $_POST['company_address'];
    $status = $_POST['status'];

    // Check for duplicate company code
    $checkCode = $conn->query("SELECT * FROM company_master WHERE company_code='$code'");
    if ($checkCode && $checkCode->num_rows > 0) {
        $error = 'Company Code already exists!';
    }

    // Check for duplicate contact
    $checkContact = $conn->query("SELECT * FROM company_master WHERE contact='$contact'");
    if (!$error && $checkContact && $checkContact->num_rows > 0) {
        $error = 'Contact Number already exists!';
    }

    // Check for duplicate website
    $checkWebsite = $conn->query("SELECT * FROM company_master WHERE company_website='$website'");
    if (!$error && $checkWebsite && $checkWebsite->num_rows > 0) {
        $error = 'Company Website already exists!';
    }

    $checkEmail = $conn->query("SELECT * FROM company_master WHERE email='$email'");
    if (!$error && $checkEmail && $checkEmail->num_rows > 0) {
        $error = 'Company Email already exists!';
    }

    // If no errors, insert the record
    if (!$error) {
        $insert = "INSERT INTO company_master (company_code, company_name, contact, email, company_website, company_address, status)
                   VALUES ('$code', '$name', '$contact', '$email', '$website', '$address', 'active')";

        if ($conn->query($insert)) {
            echo "<script>alert('Company Added Successfully'); window.location.href='Company/company_master.php';</script>";
        } else {
            $error = 'Error adding company.';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Company</title>
    <link rel="stylesheet" href="style.css"> <!-- Add your Bootstrap or Kaiadmin link here -->
</head>
<body>
<div class="container" style="max-width: 1400px; margin-top:100px;">
    <div class="card shadow">
            <h2 class="text-center mt-4 mb-4">Add Company</h2>

    <?php if ($error): ?>
        <script>
            alert("<?= $error ?>");
        </script>
    <?php endif; ?>

<div class="card-body">
    <form method="post" action="">
        <div class="row mb-4">
            <div class="form-group col-md-4">
                <label>Company Code</label>
                <input type="text" name="company_code" class="form-control" required>
            </div>
            <div class="form-group col-md-4">
                <label>Company Name</label>
                <input type="text" name="company_name" class="form-control" required>
            </div>
            <div class="form-group col-md-4">
                <label>Contact</label>
                <input type="text" name="contact" class="form-control" required>
            </div>
        </div>

        <div class="row mb-4">
            <div class="form-group col-md-6">
                <label>Email</label>
                <input type="email" name="email" class="form-control">
            </div>
            <div class="form-group col-md-6">
                <label>Company Website</label>
                <input type="text" name="company_website" class="form-control" required>
            </div>
        </div>

            <div class="form-group">
                <label>Company Address</label>
                <textarea name="company_address" class="form-control"></textarea>
            </div>

        <br>
        <div class="text-center">
            <button type="submit" class="btn btn-success px-4">Submit</button>
            <a href="Company/company_master.php" class="btn btn-secondary px-4">Cancel</a>
        </div>
    </div>
    </form>
    </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<?php include('../footer.php')?>
</body>
</html>
