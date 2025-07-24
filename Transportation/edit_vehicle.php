<?php
$base_path = "../";
ob_start();
include '../header.php';
$header = ob_get_clean();
$header = str_replace('<head>', '<head><base href="/eduhyd/">', $header);
echo $header;

include("../connect.php");

$message = '';
$vehicle_id = $_GET['id'] ?? null;

if (!$vehicle_id) {
    echo "<script>alert('No vehicle selected.'); window.location.href='vehicle_list.php';</script>";
    exit;
}

$sql = "SELECT * FROM vehicles WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vehicle_id);
$stmt->execute();
$result = $stmt->get_result();
$vehicle = $result->fetch_assoc();

if (!$vehicle) {
    echo "<script>alert('Vehicle not found.'); window.location.href='vehicle_list.php';</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehicle_number = trim($_POST["vehicle_number"]);
    $vehicle_name = trim($_POST["vehicle_name"]);
    $driver_name = trim($_POST["driver_name"]);

    $update_sql = "UPDATE vehicles SET vehicle_number = ?, vehicle_name = ?, driver_name = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssi", $vehicle_number, $vehicle_name, $driver_name, $vehicle_id);

    if ($stmt->execute()) {
        echo "<script>alert('Vehicle updated successfully'); window.location.href='vehicle_list.php';</script>";
    } else {
        $message = "Error updating vehicle.";
    }
}
?>

<div class="container mt-5 d-flex justify-content-center">
    <div class="card shadow p-4 w-50">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Edit Vehicle</h4>
            <a href="vehicle_list.php" class="btn btn-secondary btn-sm">Back to List</a>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert alert-danger text-center"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group mb-3">
                <label for="vehicle_number"><strong>Vehicle Number</strong></label>
                <input type="text" class="form-control" name="vehicle_number" value="<?= htmlspecialchars($vehicle['vehicle_number']) ?>" required>
            </div>

            <div class="form-group mb-3">
                <label for="vehicle_name"><strong>Vehicle Name</strong></label>
                <input type="text" class="form-control" name="vehicle_name" value="<?= htmlspecialchars($vehicle['vehicle_name']) ?>" required>
            </div>

            <div class="form-group mb-4">
                <label for="driver_name"><strong>Driver Name</strong></label>
                <input type="text" class="form-control" name="driver_name" value="<?= htmlspecialchars($vehicle['driver_name']) ?>" required>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Update Vehicle</button>
            </div>
        </form>
    </div>
</div>

<?php include '../footer.php'; ?>
