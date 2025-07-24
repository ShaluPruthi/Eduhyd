<?php
$base_path = "../";
ob_start();
include '../header.php';
$header = ob_get_clean();

// Inject <base href="/eduhyd/"> after <head>
$header = str_replace('<head>', '<head><base href="/eduhyd/">', $header);

// Output modified header
echo $header;
include_once("../connect.php");

// Assigning transportation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $vehicle_id = $_POST['vehicle_id'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    // Check if student exists before inserting
    $checkStudent = $conn->prepare("SELECT id FROM students WHERE id = ?");
    $checkStudent->bind_param("i", $student_id);
    $checkStudent->execute();
    $result = $checkStudent->get_result();

    if ($result->num_rows > 0) {
        $stmt = $conn->prepare("INSERT INTO transport_assignments (student_id, vehicle_id, from_date, to_date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $student_id, $vehicle_id, $from_date, $to_date);
        if ($stmt->execute()) {
            echo "<script>alert('Transportation Assigned Successfully'); window.location.href='Transportation/view_assignment.php';</script>";
        } else {
            echo "<script>alert('Assignment failed: " . $stmt->error . "'); window.location.href='Transportation/assign_transportation.php';</script>";
        }
    } else {
        echo "<script>alert('Student ID does not exist.'); window.location.href='Transportation/assign_transportation.php';</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Assign Transportation</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css">
</head>
<body>
<div class="container" style="margin-top:120px; width:1000px">

    <form method="POST" class="card shadow p-4">
    
    <h3 class="mb-4">Assign Transportation</h3>
    <div class="row mb-4">
        <div class="col-md-6">
            <label>Select Student *</label>
            <select style="max-width:300px" name="student_id" class="form-control" required>
                <option value="">-- Select --</option>
                <?php
                $students = $conn->query("SELECT id, fname, lname FROM staff");
                while ($s = $students->fetch_assoc()) {
                    echo "<option value='{$s['id']}'>{$s['fname']} {$s['lname']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-6">
            <label>Select Vehicle *</label>
            <select style="max-width:300px" name="vehicle_id" class="form-control" required>
                <option value="">-- Select --</option>
                <?php
                $vehicles = $conn->query("SELECT id, vehicle_no FROM vehicles");
                while ($v = $vehicles->fetch_assoc()) {
                    echo "<option value='{$v['id']}'>{$v['vehicle_no']}</option>";
                }
                ?>
            </select>
        </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <label>From Date *</label>
                <input style="max-width:300px" type="date" class="form-control" name="from_date" required>
            </div>
            <div class="col-md-6">
                <label>To Date *</label>
                <input style="max-width:300px" type="date" class="form-control" name="to_date" required>
            </div>
        </div>
        
        <div class="text-center">
            <button type="submit" name="submit" class="btn btn-success">Assigned</button>
            <a href="Transportation/view_assignment.php" class="btn btn-secondary">View Assignments</a>
        </div>
    </form>
</div>
<?php include_once("../footer.php"); ?>
</body>
</html>
