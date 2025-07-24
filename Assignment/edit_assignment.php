<?php
$base_path = "../";
ob_start();
include '../header.php';
$header = ob_get_clean();
$header = str_replace('<head>', '<head><base href="/eduhyd/">', $header);
echo $header;

include '../connect.php';

if (!isset($_GET['id'])) {
    echo "No assignment ID provided.";
    exit;
}

$id = $_GET['id'];
$query = "SELECT * FROM assignments WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$assignment = $result->fetch_assoc();

if (!$assignment) {
    echo "Assignment not found.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $assignment_text = $_POST['assignment_text'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $class_name = $_POST['class_name'];
    $subject = $_POST['subject'];

    $update = "UPDATE assignments SET assignment_text=?, from_date=?, to_date=?, class_name=?, subject=? WHERE id=?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("sssssi", $assignment_text, $from_date, $to_date, $class_name, $subject, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Assignment Updated Successfully'); window.location.href='view_assignment.php';</script>";
    } else {
        echo "<script>alert('Update Failed');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Assignment</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .form-container {
      max-width: 1000px;
      margin: 50px auto;
      padding: 50px;
      border-radius: 20px;
      box-shadow: 0px 0px 15px #ddd;
      background-color: #fff;
    }
    .form-label {
      font-weight: 500;
    }
  </style>
</head>
<body>

<div class="container">
  <form method="POST" class="form-container">
  <h2 class="text-center mb-4">Edit Assignment</h2>
  
    <div class="mb-3">
      <label class="form-label">Assignment Text</label>
      <textarea class="form-control" name="assignment_text" required><?= htmlspecialchars($assignment['assignment_text']) ?></textarea>
    </div>

    <div class="row">
      <div class="mb-3 col-md-6">
        <label class="form-label">From Date</label>
        <input type="date" class="form-control" name="from_date" value="<?= $assignment['from_date'] ?>" min="<?= date('Y-m-d') ?>" required>
      </div>
      <div class="mb-3 col-md-6">
        <label class="form-label">To Date</label>
        <input type="date" class="form-control" name="to_date" value="<?= $assignment['to_date'] ?>" min="<?= date('Y-m-d') ?>" required>
      </div>
    </div>

    <div class="row">
      <div class="mb-3 col-md-6">
        <label class="form-label">Class Name</label>
        <input type="text" class="form-control" name="class_name" value="<?= htmlspecialchars($assignment['class_name']) ?>" required>
      </div>
      <div class="mb-3 col-md-6">
        <label class="form-label">Subject</label>
        <input type="text" class="form-control" name="subject_name" value="<?= htmlspecialchars($assignment['subject_name']) ?>" required>
      </div>
    </div>

    <div class="text-center">
      <button type="submit" class="btn btn-success">Update Assignment</button>
      <a href="view_assignment.php" class="btn btn-secondary">Back</a>
    </div>
  </form>
</div>
<?php include('../footer.php')?>
</body>
</html>
