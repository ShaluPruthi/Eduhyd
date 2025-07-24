<?php
$base_path = "../";
ob_start();
include '../header.php';
$header = ob_get_clean();

// Inject <base href="/eduhyd/"> after <head>
$header = str_replace('<head>', '<head><base href="/eduhyd/">', $header);

// Output modified header
echo $header;


include("../connect.php");

// Handle form submission
$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject_name = trim($_POST["subject_name"]);

    // Check for uniqueness
    $check_sql = "SELECT * FROM subjects WHERE subject_name = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $subject_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = "Subject already exists!";
    } else {
        $insert_sql = "INSERT INTO subjects (subject_name) VALUES (?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("s", $subject_name);
        if ($stmt->execute()) {
        echo "<script>alert('Subject Added Successfully'); window.location.href='/eduhyd/Subjects/subject_list.php';</script>";
        } else {
        echo "<script>alert('Error: Unable to add subject');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <base href="/eduhyd/">
    <meta charset="utf-8" />
    <title>Add Subject</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Correct Relative Paths -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/kaiadmin.min.css" rel="stylesheet" />
    <link href="assets/css/demo.css" rel="stylesheet" />
</head>
<body>
    <div class="container mt-5 d-flex justify-content-center">

        <div class="card shadow p-5" style= "width:800px; height: 300px; margin-top: 100px">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Add Subject</h2>
            <a href="/eduhyd/Subjects/subject_list.php" class="btn btn-secondary">
              <i class="fa fa-list"></i> Subject List
            </a>
          </div>

        <?php if (!empty($message)): ?>
          <div class="alert alert-info text-center"><?= $message ?></div>
        <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group mb-3">
                    <label for="subject_name"><strong>Subject Name</strong></label>
                    <input type="text" class="form-control" style="max-width:500px" id="subject_name" name="subject_name" required placeholder="Enter subject name">
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Add Subject</button>
                </div>
            </form>
        </div>
    </div>

<?php include '../footer.php'; ?>
<!-- Scripts -->
<script src="assets/js/core/jquery-3.7.1.min.js"></script>
<script src="assets/js/core/bootstrap.bundle.min.js"></script>
<script src="assets/js/kaiadmin.min.js"></script>

</body>
</html>
