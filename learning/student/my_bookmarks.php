<?php
// --- SETUP AND SECURITY ---
$page_title = 'My Bookmarks';
$allowed_roles = [3]; // Only students have bookmarks
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// --- DATA FETCHING ---
$student_id = $_SESSION['user_id'];
$bookmarks = [];

// Fetch all bookmarked lessons for the current user, joining with lessons and courses to get titles
$sql = "SELECT 
            b.lesson_id, 
            l.title as lesson_title,
            c.title as course_title,
            l.content_type
        FROM bookmarks b
        JOIN lessons l ON b.lesson_id = l.id
        JOIN courses c ON l.course_id = c.id
        WHERE b.user_id = ?
        ORDER BY b.created_at DESC";

$stmt = $db->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bookmarks[] = $row;
    }
}
$stmt->close();

// --- PAGE DISPLAY ---
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1>My Bookmarked Lessons</h1>
        <a href="index.php" class="button button-secondary">Back to Dashboard</a>
    </div>
    <p>Here are all the lessons you've saved for later. Click any lesson to jump right back to it.</p>

    <div class="bookmark-list">
        <?php if (empty($bookmarks)): ?>
            <div class="info-box">
                <p>You have not bookmarked any lessons yet. You can bookmark a lesson by clicking the '★' icon on any lesson page.</p>
            </div>
        <?php else: ?>
            <?php foreach ($bookmarks as $bookmark): ?>
                <?php
                // Determine the correct link for the lesson
                if ($bookmark['content_type'] === 'quiz') {
                    $link = 'take_quiz.php?id=' . $bookmark['lesson_id'];
                } elseif ($bookmark['content_type'] === 'assignment') {
                    $link = 'view_assignment.php?id=' . $bookmark['lesson_id'];
                } else {
                    $link = 'view_lesson.php?id=' . $bookmark['lesson_id'];
                }
                ?>
                <a href="<?php echo $link; ?>" class="bookmark-item-link">
                    <div class="bookmark-item">
                        <span class="lesson-icon icon-<?php echo e($bookmark['content_type']); ?>"></span>
                        <div class="bookmark-details">
                            <span class="bookmark-lesson-title"><?php echo e($bookmark['lesson_title']); ?></span>
                            <span class="bookmark-course-title">From course: <?php echo e($bookmark['course_title']); ?></span>
                        </div>
                        <span class="go-to-lesson">→</span>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>