<?php
$base_path = "../";
ob_start();
include '../header.php';
$header = ob_get_clean();

// Inject <base href="/eduhyd/"> after <head>
$header = str_replace('<head>', '<head><base href="/eduhyd/">', $header);

// Output modified header
echo $header;
include '../connect.php';
global $conn;

if (!isset($_GET['id'])) {
    echo "<script>alert('No staff selected.'); window.location.href='display.php';</script>";
    exit;
}

$staff_id = $_GET['id'];
$staff = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM staff WHERE id = $staff_id"));
$education = mysqli_query($conn, "SELECT * FROM education WHERE staff_id = $staff_id");

function inputValue($key, $data) {
    return htmlspecialchars($data[$key] ?? '');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Staff</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />
    <style>
        body {
            padding: 30px;
        }
        .card {
            border-radius: 1rem;
        }
        .form-header {
            background: #f8f9fa;
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
        }
        .form-container {
            padding: 2rem;
            background: #ffffff;
            border-radius: 1rem;
        }
        .remove-education {
            margin-top: 32px;
        }
    </style>
</head>
<body>

<div class="container" style="margin-top:100px; width:1300px">
    <div class="card  shadow">
        <div class="form-header">
            <h3>Update Staff: <?= inputValue('fname', $staff) . ' ' . inputValue('lname', $staff) ?></h3>
        </div>
        <div class="form-container">
            <form action="update_process.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="staff_id" value="<?= $staff_id ?>">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>First Name</label>
                        <input type="text" class="form-control" name="fname" value="<?= inputValue('fname', $staff) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label>Last Name</label>
                        <input type="text" class="form-control" name="lname" value="<?= inputValue('lname', $staff) ?>" required>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" value="<?= inputValue('email', $staff) ?>" required>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label>Phone</label>
                        <input type="text" class="form-control" name="phone" maxlength="10" value="<?= inputValue('phone', $staff) ?>" required>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label>Gender</label>
                        <select class="form-control" name="gender" required>
                            <option <?= $staff['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                            <option <?= $staff['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                            <option <?= $staff['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label>Date of Birth</label>
                        <input type="date" class="form-control" name="dob" value="<?= inputValue('dob', $staff) ?>" required>
                    </div>
                </div>

                <hr>
                <h5>Bank Information</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Bank Name</label>
                        <input type="text" class="form-control" name="bank_name" value="<?= inputValue('bank_name', $staff) ?>">
                    </div>
                    <div class="col-md-6">
                        <label>Branch Name</label>
                        <input type="text" class="form-control" name="branch_name" value="<?= inputValue('branch_name', $staff) ?>">
                    </div>
                    <div class="col-md-6 mt-3">
                        <label>Account Number</label>
                        <input type="text" class="form-control" name="acnt_number" value="<?= inputValue('acnt_number', $staff) ?>">
                    </div>
                    <div class="col-md-6 mt-3">
                        <label>IFSC Code</label>
                        <input type="text" class="form-control" name="ifsc" value="<?= inputValue('ifsc', $staff) ?>">
                    </div>
                </div>

                <hr>
                <h5>Education Details</h5>
                <div id="educationContainer">
                    <?php while ($edu = mysqli_fetch_assoc($education)): ?>
                        <div class="row education-row mb-2">
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="qualification[]" placeholder="Qualification" value="<?= $edu['qualification'] ?>" required>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="specialization[]" placeholder="Specialization" value="<?= $edu['specialization'] ?>" required>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="passing_year[]" placeholder="Year" value="<?= $edu['passing_year'] ?>" required>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="university[]" placeholder="University" value="<?= $edu['university'] ?>" required>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger remove-education">X</button>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                <button type="button" class="btn btn-success btn-sm mt-2" id="addEducation">+ Add More</button>

                <hr>
                <h5>Upload Documents (Optional to Re-upload)</h5>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Resume</label>
                        <input type="file" name="resume" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>Profile Photo</label>
                        <input type="file" name="photo" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>Signature</label>
                        <input type="file" name="signature" class="form-control">
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" name="submit" class="btn btn-primary px-5">Update Staff</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('addEducation').addEventListener('click', () => {
        const container = document.getElementById('educationContainer');
        const row = document.querySelector('.education-row');
        const clone = row.cloneNode(true);
        clone.querySelectorAll('input').forEach(input => input.value = '');
        container.appendChild(clone);
    });

    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('remove-education')) {
            let rows = document.querySelectorAll('.education-row');
            if (rows.length > 1) e.target.closest('.education-row').remove();
            else alert("At least one education entry is required.");
        }
    });
</script>
<?php include_once("../footer.php"); ?>
</body>
</html>
