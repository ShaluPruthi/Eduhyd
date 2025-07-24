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

if (!isset($_GET['id'])) {
    echo "<script>window.location.href='subject_list.php';</script>";
    exit();
}

$id = $_GET['id'];
$error = "";

// Get subject
$stmt = $conn->prepare("SELECT * FROM subjects WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$subject = $stmt->get_result()->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject_name = trim($_POST['subject_name']);

    if (empty($subject_name)) {
        $error = "Subject name is required.";
    } else {
        $checkQuery = "SELECT * FROM subjects WHERE subject_name = ? AND id != ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("si", $subject_name, $id);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            $error = "Another subject with same name exists!";
        } else {
            $updateQuery = "UPDATE subjects SET subject_name = ? WHERE id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("si", $subject_name, $id);
            if ($updateStmt->execute()) {
                echo "<script>alert('Subject updated successfully'); window.location.href='/eduhyd/Subjects/subject_list.php';</script>";
                exit();
            } else {
                $error = "Update failed.";
            }
        }
    }
}
?>

<div class="container" style="margin-top:200px; width:1500px">
  <div class="card p-4 shadow rounded w-50 mx-auto">
    <h4 class="text-center mb-4">Edit Subject</h4>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
      <div class="form-group">
        <label for="subject_name">Subject Name</label>
        <input type="text" class="form-control" name="subject_name" id="subject_name" value="<?= htmlspecialchars($subject['subject_name']) ?>" required>
      </div>
      <button type="submit" class="btn btn-primary mt-3">Update Subject</button>
    </form>
  </div>
</div>

<?php include '../footer.php'; ?>
