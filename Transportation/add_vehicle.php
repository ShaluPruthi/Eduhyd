<?php
$base_path = "../";
ob_start();
include '../header.php';
$header = ob_get_clean();
$header = str_replace('<head>', '<head><base href="/eduhyd/">', $header);

echo $header;
include_once("../connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehicle_no = trim($_POST["vehicle_no"]);
    $vehicle_name = trim($_POST["vehicle_name"]);
    $driver_name = trim($_POST["driver_name"]);

    $stmt = $conn->prepare("SELECT * FROM vehicles WHERE vehicle_no = ?");
    $stmt->bind_param("s", $vehicle_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Vehicle already exists!');</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO vehicles (vehicle_no, vehicle_name, driver_name) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $vehicle_no, $vehicle_name, $driver_name);

    if ($stmt->execute()) {
        echo "<script>alert('Vehicle Added Successfully'); window.location.href='Transportation/vehicle_list.php';</script>";
    } else {
        echo "<script>alert('Error: Unable to add vehicle');</script>";
    }
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Vehicle</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css">
</head>
<body>
<div class="container" style=" width:1000px; margin-top:150px">

    <form method="POST" class="card shadow p-4">
        <h2 class="mb-4">Add Vehicle</h2>
        <div class="mb-3">
            <label>Vehicle Number *</label>
            <input type="text" class="form-control" name="vehicle_no" required>
        </div>
        <div class="mb-3">
            <label>Vehicle Name *</label>
            <input type="text" class="form-control" name="vehicle_name" required>
        </div>
        <div class="mb-3">
            <label>Driver Name *</label>
            <input type="text" class="form-control" name="driver_name" required>
        </div>
        <div class="text-center">
            <button type="submit" name="submit" class="btn btn-primary">Add Vehicle</button>
            <a href="Transportation/vehicle_list.php" class="btn btn-secondary">View Vehicle</a>
        </div>
    </form>
</div>
<?php include_once("../footer.php"); ?>
</body>
</html>
