<?php
// --- SETUP AND SECURITY ---
$page_title = 'Discussion Forum';
$allowed_roles = [1, 2, 3];
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// --- VALIDATE COURSE ID ---
if (!isset($_GET['course_id']) || !filter_var($_GET['course_id'], FILTER_VALIDATE_INT)) {
    $_SESSION['error_message'] = "Invalid course ID for forum.";
    redirect('/student/index.php');
}
$course_id = $_GET['course_id'];
$user_id = $_SESSION['user_id'];

// --- VERIFY USER ACCESS & GET COURSE INFO ---
$course_stmt = $db->prepare("SELECT instructor_id, title FROM courses WHERE id = ?");
$course_stmt->bind_param("i", $course_id);
$course_stmt->execute();
$course_result = $course_stmt->get_result();
if ($course_result->num_rows === 0) {
    $_SESSION['error_message'] = "Course not found.";
    redirect('/student/index.php');
}
$course_data = $course_result->fetch_assoc();
$course_data['id'] = $course_id;

if ($_SESSION['role_id'] == 3) {
    $enroll_stmt = $db->prepare("SELECT id FROM enrollments WHERE student_id = ? AND course_id = ?");
    $enroll_stmt->bind_param("ii", $user_id, $course_id);
    $enroll_stmt->execute();
    if ($enroll_stmt->get_result()->num_rows === 0) {
        $_SESSION['error_message'] = "You must be enrolled to view this forum.";
        redirect('/view_course.php?id=' . $course_id);
    }
}

// --- DATA FETCHING ---
$threads = [];
$sql = "SELECT t.id, t.title, t.created_at, u.username as author, t.is_pinned,
               (SELECT COUNT(*) FROM forum_replies WHERE thread_id = t.id) as reply_count,
               COALESCE((SELECT MAX(created_at) FROM forum_replies WHERE thread_id = t.id), t.created_at) as last_activity
        FROM forum_threads t
        JOIN users u ON t.student_id = u.id
        WHERE t.course_id = ?
        ORDER BY t.is_pinned DESC, last_activity DESC";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();
while($row = $result->fetch_assoc()) {
    $threads[] = $row;
}

// --- PAGE DISPLAY ---
$page_title = "Forum: " . e($course_data['title']);
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1><?php echo e($course_data['title']); ?>: Discussion Forum</h1>
        <a href="view_thread.php?action=new&course_id=<?php echo $course_id; ?>" class="button button-primary">Start New Thread</a>
    </div>

    <div class="table-responsive">
        <table class="forum-table">
            <thead>
                <tr>
                    <th>Topic</th>
                    <th>Author</th>
                    <th>Replies</th>
                    <th>Last Activity</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($threads)): ?>
                    <tr><td colspan="4">No discussions have been started yet. Be the first!</td></tr>
                <?php else: ?>
                    <?php foreach ($threads as $thread): ?>
                        <tr class="<?php echo $thread['is_pinned'] ? 'pinned-thread' : ''; ?>">
                            <td data-label="Topic">
                                <a href="view_thread.php?thread_id=<?php echo $thread['id']; ?>">
                                    <?php if ($thread['is_pinned']): ?>📌<?php endif; ?>
                                    <?php echo e($thread['title']); ?>
                                </a>
                            </td>
                            <td data-label="Author"><?php echo e($thread['author']); ?></td>
                            <td data-label="Replies"><?php echo $thread['reply_count']; ?></td>
                            <td data-label="Last Activity">
                                <?php echo date('M j, Y, g:i a', strtotime($thread['last_activity'])); ?>
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