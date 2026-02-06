<?php
$page_title = 'Instructor Dashboard';

// --- SECURITY AND SETUP ---
$allowed_roles = [1, 2];
require_once __DIR__ . '/../includes/auth_check.php';

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// --- DATA FETCHING ---
$instructor_id = $_SESSION['user_id'];
$courses = [];

$sql = "SELECT 
            c.id, c.title, c.status, c.created_at,
            (SELECT AVG(rating) FROM course_ratings WHERE course_id = c.id) as avg_rating,
            (SELECT COUNT(id) FROM course_ratings WHERE course_id = c.id) as total_ratings
        FROM courses c 
        WHERE c.instructor_id = ? 
        ORDER BY c.created_at DESC";

$stmt = $db->prepare($sql);
$stmt->bind_param("i", $instructor_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}
$stmt->close();

$success_message = $_SESSION['success_message'] ?? null;
unset($_SESSION['success_message']);

// --- PAGE DISPLAY ---
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1>Instructor Dashboard</h1>
        <a href="<?php echo $base; ?>/instructor/create_course.php" class="button button-primary">Create New Course</a>
    </div>
    <p>Welcome, <?php echo e($_SESSION['username']); ?>! From here you can create and manage your courses.</p>

    <?php if ($success_message): ?>
        <div class="form-message success" role="status">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>

    <div class="dashboard-content">
        <h2>Your Courses</h2>

        <?php if (empty($courses)): ?>
            <p>You have not created any courses yet. <a href="create_course.php">Create your first course now!</a></p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="course-list-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Rating</th>
                            <th>Date Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <td data-label="Title"><?php echo e($course['title']); ?></td>
                                <td data-label="Status">
                                    <span class="status-badge status-<?php echo e($course['status']); ?>">
                                        <?php echo e(ucfirst(str_replace('_', ' ', $course['status']))); ?>
                                    </span>
                                </td>
                                <td data-label="Rating">
                                    <?php if ($course['total_ratings'] > 0): ?>
                                        <div class="stars-display" data-rating="<?php echo e($course['avg_rating']); ?>"></div>
                                        <small>(<?php echo e($course['total_ratings']); ?>)</small>
                                    <?php else: ?>
                                        <small>No ratings yet</small>
                                    <?php endif; ?>
                                </td>
                                <td data-label="Date Created">
                                    <?php echo date('M j, Y', strtotime($course['created_at'])); ?>
                                </td>
                                <td data-label="Actions" class="actions-cell">
                                    <a href="edit_course.php?id=<?php echo $course['id']; ?>" class="button button-secondary button-small">Manage</a>
                                    <a href="<?php echo $base; ?>/student/forum.php?course_id=<?php echo $course['id']; ?>" class="button button-info button-small">Forum</a>
                                    
                                    <!-- THIS IS THE RESTORED DELETE BUTTON FORM -->
                                    <form action="delete_handler.php" method="POST" class="inline-form delete-form">
                                        <input type="hidden" name="action" value="delete_course">
                                        <input type="hidden" name="id" value="<?php echo $course['id']; ?>">
                                        <button type="submit" class="button button-danger button-small">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>