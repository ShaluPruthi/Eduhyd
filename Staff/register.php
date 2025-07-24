<?php
$base_path = "../";
ob_start();
include '../header.php';
$header = ob_get_clean();
$header = str_replace('<head>', '<head><base href="/eduhyd/">', $header);
echo $header;
include_once('../connect.php');
global $conn;

function uploadFile($file, $destinationFolder) {
    if (!empty($file['name'])) {
        if (!file_exists($destinationFolder)) {
            mkdir($destinationFolder, 0777, true);
        }
        $filename = time() . '_' . basename($file['name']);
        $target = $destinationFolder . $filename;
        if (move_uploaded_file($file['tmp_name'], $target)) {
            return $target;
        }
    }
    return null;
}

if (isset($_POST['submit'])) {

    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $aphone = $_POST['aphone'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $jdate = $_POST['jdate'];

    $bank_name = $_POST['bank_name'];
    $branch_name = $_POST['branch_name'];
    $acnt_number = $_POST['acnt_number'];
    $acnt_type = $_POST['acnt_type'];
    $ifsc = $_POST['ifsc'];
    $pan = $_POST['pan'];
    $aadhar = $_POST['aadhar'];
    $upi = $_POST['upi'];

    $resume = uploadFile($_FILES['resume'], 'uploads/resumes/');
    $photo = uploadFile($_FILES['photo'], 'uploads/photos/');
    $dmc = uploadFile($_FILES['dmc'], 'uploads/dmc_12th/');
    $degree = uploadFile($_FILES['degree'], 'uploads/degrees/');
    $signature = uploadFile($_FILES['signature'], 'uploads/signatures/');
    $other = uploadFile($_FILES['other'], 'uploads/other/');

    $query = "INSERT INTO staff 
    (fname, lname, email, phone, aphone, gender, dob, address, password, jdate,
     bank_name, branch_name, acnt_number, acnt_type, ifsc, pan, aadhar, upi,
     resume, photo, dmc, degree, signature, other)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssssssssssssssssssss", 
        $fname, $lname, $email, $phone, $aphone, $gender, $dob, $address, $password, $jdate,
        $bank_name, $branch_name, $acnt_number, $acnt_type, $ifsc, $pan, $aadhar, $upi,
        $resume, $photo, $dmc, $degree, $signature, $other
    );

    if($stmt->execute()) {
        $staff_id = $stmt->insert_id;

        for ($i = 0; $i < count($_POST['qualification']); $i++) {
            $qualification = $_POST['qualification'][$i];
            $specialization = $_POST['specialization'][$i];
            $passing_year = $_POST['passing_year'][$i];
            $university = $_POST['university'][$i];
            $marks = $_POST['marks'][$i];

            $eduQuery = "INSERT INTO education (staff_id, qualification, specialization, passing_year, university, marks)
                         VALUES (?, ?, ?, ?, ?, ?)";
            $eduStmt = $conn->prepare($eduQuery);
            $eduStmt->bind_param("ississ", $staff_id, $qualification, $specialization, $passing_year, $university, $marks);
            $eduStmt->execute();
        }

        echo "<script>alert('Staff Registered Successfully!'); window.location.href='display.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>ZSAdmin - Registration Form</title>
       <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
      rel="icon"
      href="assets/img/kaiadmin/favicon.ico"
      type="image/x-icon"
    />

    <!-- Fonts and icons -->
    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: ["Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
          urls: ["../assets/css/fonts.min.css"]
        },
        active: function () {
          sessionStorage.fonts = true;
        }
      });
    </script>
       <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />

</head>
<body class="d-flex flex-column min-vh-110">
    <main class="flex-grow-1">
        <div class="container py-5">
        <div class="content">
            <div class="page-inner">
                <div class="form-wrapper d-flex justify-content-center align-items-start" style="margin-top: 40px;">
                    <div class="card p-4 shadow mx-auto" style="max-width: 100%;">
                        <div class="page-header mb-4">
                            <h3 class="page-title">Staff Registration</h3>
                        </div>

                    <form method="POST" action="#" enctype="multipart/form-data" id="registrationForm" class="needs-validation" nonvalidate>

                    <div class="card mb-4 shadow-sm">
                        <div class="card-header">
                            <h4>Personal Information</h4>
                        </div>
                    <div class="card-body row">
                        <div class="col-md-6 mb-3">
                            <label>First Name *</label>
                            <input type="text" class="form-control" name="fname" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Last Name *</label>
                            <input type="text" class="form-control" name="lname" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Email *</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Phone Number *</label>
                            <input type="text" class="form-control" name="phone" maxlength="10" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Alternative Phone</label>
                            <input type="text" class="form-control" name="aphone" maxlength="10">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Gender *</label>
                            <select name="gender" class="form-control" required>
                                <option value="">Select</option>
                                <option>Male</option>
                                <option>Female</option>
                                <option>Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Date of Birth *</label>
                            <input type="date" class="form-control" name="dob" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Joining Date *</label>
                            <input type="date" class="form-control" name="jdate" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Address *</label>
                            <textarea class="form-control" name="address" rows="3" required></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Password *</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                    </div>
                </div>

                <div class="card mb-4 shadow-sm">
                    <div class="card-header">
                        <h4>Banking Information</h4>
                    </div>
                <div class="card-body row">
                    <div class="col-md-6 mb-3">
                        <label>Bank Name *</label>
                        <input type="text" class="form-control" name="bank_name" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Branch Name *</label>
                        <input type="text" class="form-control" name="branch_name" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Account Number *</label>
                        <input type="text" class="form-control" name="acnt_number" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Account Type *</label>
                        <select class="form-control" name="acnt_type">
                            <option value="">Select</option>
                            <option>Savings</option>
                            <option>Current</option>
                            <option>Salary</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>IFSC Code *</label>
                        <input type="text" class="form-control" name="ifsc" required pattern="[A-Z]{4}0[A-Z0-9]{6}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>PAN Number *</label>
                        <input type="text" class="form-control" name="pan" required pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Aadhar Number *</label>
                        <input type="text" class="form-control" name="aadhar" required pattern="\d{12}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>UPI ID</label>
                        <input type="text" class="form-control" name="upi" pattern="^[a-zA-Z0-9.\-_]{2,}@[a-zA-Z]{2,}$">
                    </div>
                </div>
            </div>

            <!-- Educational Information -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Educational Information</h4>
                    <button type="button" class="btn btn-success btn-sm" id="addEducation"><b>+</b> Add More</button>
                </div>
                <div class="card-body" id="educationContainer">
                    <div class="row education-row">
                        <div class="col-md-6 mb-3">
                            <label>Highest Qualification *</label>
                            <input type="text" class="form-control" name="qualification[]" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Specialization *</label>
                            <input type="text" class="form-control" name="specialization[]" require>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Passing Year *</label>
                            <input type="number" class="form-control" name="passing_year[]" required >
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>University/Board *</label>
                            <input type="text" class="form-control" name="university[]" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Percentage/CGPA *</label>
                            <input type="text" class="form-control" name="marks[]" required>
                        </div>
                        <div class="col-md-1 mb-3 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm remove-education">X</button>
                        </div>
                    </div>
                </div>
            </div>
        <script>
            document.getElementById('addEducation').addEventListener('click', function () {
                let container = document.getElementById('educationContainer');
                let row = document.querySelector('.education-row');
                let clone = row.cloneNode(true);
        
        
                clone.querySelectorAll('input').forEach(input => input.value = '');
        
                container.appendChild(clone);
            });

            document.addEventListener('click', function (e) {
                if (e.target && e.target.classList.contains('remove-education')) {
                    let allRows = document.querySelectorAll('.education-row');
                    if (allRows.length > 1) {
                        e.target.closest('.education-row').remove();
                    } else {
                        alert("At least one educational detail is required.");
                    }
                }
            });
        </script>


            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h4>Document Uploads</h4>
                </div>
                <div class="card-body row">
                    <div class="col-md-6 mb-3">
                        <label>Resume (PDF)*</label>
                        <input type="file" class="form-control" name="resume" accept=".pdf" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Profile Photo (JPG/PNG)*</label>
                        <input type="file" class="form-control" name="photo" accept=".jpg,.jpeg,.png" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>12th Marksheet (PDF)*</label>
                        <input type="file" class="form-control" name="dmc" accept=".pdf" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Degree Certificate (PDF)*</label>
                        <input type="file" class="form-control" name="degree" accept=".pdf" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Signature (JPG/PNG)*</label>
                        <input type="file" class="form-control" name="signature" accept=".jpg,.jpeg,.png" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Other Certificates (optional)</label>
                        <input type="file" class="form-control" name="other" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                    </div>
                </div>

                    <div class="text-center mb-4">
                       <button type="submit" name="submit" class="btn btn-primary px-5 py-2">Submit Registration</button>
                        <a href="Staff/display.php" class="btn btn-secondary px-5 py-2">
                            <i class="bi bi-arrow-left-circle"></i> List
                        </a>
                    </div>
                    </form>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</main>

<?php include_once("../footer.php"); ?>
    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <!-- Datatables -->
    <script src="assets/js/plugin/datatables/datatables.min.js"></script>
    <!-- Kaiadmin JS -->
    <script src="assets/js/kaiadmin.min.js"></script>

<script>
  (function () {
    'use strict';
    const form = document.getElementById('registrationForm');

    form.addEventListener('submit', function (event) {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
        alert("Please fill all required fields correctly before submitting.");
      }
      form.classList.add('was-validated');
    }, false);
  })();
</script>


</body>
</html>