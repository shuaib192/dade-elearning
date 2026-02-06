<?php
// --- SETUP AND SECURITY ---
$page_title = 'Manage Courses';
$allowed_roles = [1]; // Only Admins can access this page
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// --- FORM HANDLING: ADMIN ACTIONS ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $course_id_to_update = filter_var($_POST['course_id'], FILTER_VALIDATE_INT);
    $new_status = '';

    if ($action === 'approve_course') {
        $new_status = 'published';
    }
    if ($action === 'reject_course') {
        $new_status = 'draft'; // Rejects back to draft for instructor to fix
    }

    if ($course_id_to_update && !empty($new_status)) {
        $stmt = $db->prepare("UPDATE courses SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $course_id_to_update);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Course status updated successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to update course status.";
        }
        $stmt->close();
    }
    // We redirect to the same page to show the changes
    redirect('/admin/manage_courses.php');
}


// --- DATA FETCHING FOR DISPLAY ---
$courses = [];
// Order by status to bring pending courses to the top for review
$sql = "SELECT c.id, c.title, c.status, c.created_at, u.username as instructor_name
        FROM courses c
        JOIN users u ON c.instructor_id = u.id
        ORDER BY FIELD(c.status, 'pending_review', 'draft', 'published', 'archived'), c.created_at DESC";
$result = $db->query($sql);
if ($result) {
    while($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}

$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);

// --- PAGE DISPLAY ---
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1>Manage All Courses</h1>
        <a href="index.php" class="button button-secondary">Back to Admin Dashboard</a>
    </div>

    <?php if ($success_message): ?><div class="form-message success"><?php echo e($success_message); ?></div><?php endif; ?>
    <?php if ($error_message): ?><div class="form-message error"><?php echo e($error_message); ?></div><?php endif; ?>

    <div class="table-responsive">
        <table class="user-management-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Instructor</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($courses)): ?>
                    <tr>
                        <td colspan="4">No courses have been created on the platform yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($courses as $course): ?>
                        <tr class="<?php echo ($course['status'] === 'pending_review') ? 'pending-application' : ''; ?>">
                            <td data-label="Title"><?php echo e($course['title']); ?></td>
                            <td data-label="Instructor"><?php echo e($course['instructor_name']); ?></td>
                            <td data-label="Status">
                                <span class="status-badge status-<?php echo e($course['status']); ?>">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $course['status']))); ?>
                                </span>
                            </td>
                            <td data-label="Action" class="actions-cell">
                                <?php if ($course['status'] === 'pending_review'): ?>
                                    <form action="manage_courses.php" method="POST" class="inline-form">
                                        <input type="hidden" name="action" value="approve_course">
                                        <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                                        <button type="submit" class="button button-success button-small">Approve</button>
                                    </form>
                                    <form action="manage_courses.php" method="POST" class="inline-form">
                                        <input type="hidden" name="action" value="reject_course">
                                        <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                                        <button type="submit" class="button button-danger button-small">Reject</button>
                                    </form>
                                <?php endif; ?>
                                
                                <a href="<?php echo $base; ?>/instructor/edit_course.php?id=<?php echo $course['id']; ?>" class="button button-secondary button-small">View/Edit</a>
                                
                                <!-- THIS IS THE RESTORED DELETE BUTTON FORM -->
                                <form action="<?php echo $base; ?>/instructor/delete_handler.php" method="POST" class="inline-form delete-form">
                                    <input type="hidden" name="action" value="delete_course">
                                    <input type="hidden" name="id" value="<?php echo $course['id']; ?>">
                                    <button type="submit" class="button button-danger button-small">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>