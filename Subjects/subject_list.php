<?php
$base_path = "../";
ob_start();
include '../header.php';
$header = ob_get_clean();

// Inject <base href="/eduhyd/"> after <head>
$header = str_replace('<head>', '<head><base href="/eduhyd/">', $header);
echo $header;

include("../connect.php");

// Handle Delete Request
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM subjects WHERE id = $delete_id");
    echo "<script>alert('Subject deleted successfully!'); window.location.href='Subjects/subject_list.php';</script>";
    exit;
}

// Pagination Logic
$limit = 5;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$total_query = "SELECT COUNT(*) AS total FROM subjects";
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

// Fetch paginated subjects
$sql = "SELECT * FROM subjects ORDER BY id DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>

<div class="container" style="margin-top:100px; width:1000px">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Subject List</h2>
        <a href="Subjects/add_subject.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Subject
        </a>
    </div>

    <div class="table-responsive bg-white shadow rounded">
        <table class="table table-bordered table-striped text-center mb-0" style="font-weight: 700; font-size: 20px;">
            <thead class="table-dark">
                <tr>
                    <th>Sr No.</th>
                    <th>Subject Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): 
                    $count = $offset + 1;
                    while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $count++ ?></td>
                        <td><?= htmlspecialchars($row['subject_name']) ?></td>
                        <td>
                            <a href="Subjects/edit_subject.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning me-2" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="Subjects/subject_list.php?delete_id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this subject?');" title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr><td colspan="3">No subjects found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <nav class="mt-3">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>
<?php include('../footer.php')?>
