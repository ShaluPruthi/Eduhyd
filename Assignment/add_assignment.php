<?php

$base_path = "../";
ob_start();
include '../header.php';
$header = ob_get_clean();
$header = str_replace('<head>', '<head><base href="/eduhyd/">', $header);
echo $header;

include '../connect.php';

$assignment_text = $from_date = $to_date = $class_name = $subject_name = "";
$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $assignment_text = $_POST['assignment_text'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $class_name = $_POST['class_name'];
    $subject_name = $_POST['subject_name'];

    $insert = "INSERT INTO assignments (assignment_text, from_date, to_date, class_name, subject_name)
               VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert);
    $stmt->bind_param("sssss", $assignment_text, $from_date, $to_date, $class_name, $subject_name);

    if ($stmt->execute()) {
        echo "<script>alert('Assignment Added Successfully'); window.location.href='Assignment/view_assignment.php';</script>";
    } else {
        $error = "Failed to add assignment.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Assignment</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById("from_date").setAttribute("min", today);
            document.getElementById("to_date").setAttribute("min", today);
        });
    </script>
    <style>
        .form-container {
            max-width: 1000px;
            margin: 50px auto;
            background-color: #fff;
            padding: 50px;
            border-radius: 12px;
            box-shadow: 0 0 10px #ccc;
        }

        .form-label{
            font-weight: 500;
        }
    </style>
</head>
<body>
<div class="container ">   
    <form method="post" class="form-container">

        <h2 class="mb-4 text-center">Add Assignment</h2>
        <?php if ($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
        
        <div class="col">
            <label>Assignment Text</label>
            <textarea name="assignment_text" style="max-width:750px" class="form-control" required></textarea>
        </div>

        <div class="row mb-4 mt-4">
            <div class="col-md-6">
                <label class="form-label" >From Date</label>
                <input type="date" style="max-width:300px" id="from_date" name="from_date" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">To Date</label>
                <input type="date" style="max-width:300px" id="to_date" name="to_date" class="form-control" required>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-md-6">
                <label>Class Name</label>
                <input type="text" style="max-width:300px" name="class_name" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label>Subject</label>
                <input type="text" style="max-width:300px" name="subject_name" class="form-control" required>
            </div>
        </div>

        <div class="text-center">
            <a href="Assignment/view_assignment.php" class="btn btn-secondary">View Assignments</a>
            <button type="submit" class="btn btn-primary">Add Assignment</button>
        </div>
    </form>
</div>

<?php include("../footer.php") ?>
</body>
</html>
