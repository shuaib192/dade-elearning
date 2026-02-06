<?php
// --- SETUP AND SECURITY ---
$page_title = 'View Lesson';
$allowed_roles = [3]; // Only students can view lessons this way
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// --- VALIDATE LESSON & FETCH DATA ---
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $_SESSION['error_message'] = "Invalid lesson.";
    redirect('/student/index.php');
}
$current_lesson_id = $_GET['id'];
$student_id = $_SESSION['user_id'];

// Fetch current lesson details and its course context
$stmt = $db->prepare("SELECT l.*, c.id as course_id, c.title as course_title FROM lessons l JOIN courses c ON l.course_id = c.id WHERE l.id = ?");
$stmt->bind_param("i", $current_lesson_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    $_SESSION['error_message'] = "Lesson not found.";
    redirect('/student/index.php');
}
$lesson = $result->fetch_assoc();
$course_id = $lesson['course_id'];
$stmt->close();

// --- VERIFY ENROLLMENT ---
$enroll_stmt = $db->prepare("SELECT id FROM enrollments WHERE student_id = ? AND course_id = ?");
$enroll_stmt->bind_param("ii", $student_id, $course_id);
$enroll_stmt->execute();
if ($enroll_stmt->get_result()->num_rows === 0) {
    $_SESSION['error_message'] = "You are not enrolled in this course.";
    redirect('/courses.php');
}
$enroll_stmt->close();

// --- FORM HANDLING: BOOKMARKS, NOTES, COMPLETION ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'mark_complete') {
        $check_stmt = $db->prepare("SELECT id FROM lesson_progress WHERE student_id = ? AND lesson_id = ?");
        $check_stmt->bind_param("ii", $student_id, $current_lesson_id);
        $check_stmt->execute();
        if ($check_stmt->get_result()->num_rows === 0) {
            $insert_stmt = $db->prepare("INSERT INTO lesson_progress (student_id, lesson_id, course_id) VALUES (?, ?, ?)");
            $insert_stmt->bind_param("iii", $student_id, $current_lesson_id, $course_id);
            $insert_stmt->execute();
            $insert_stmt->close();
        }
        $check_stmt->close();

        $next_lesson_stmt = $db->prepare("SELECT id FROM lessons WHERE course_id = ? AND lesson_order > ? ORDER BY lesson_order ASC LIMIT 1");
        $next_lesson_stmt->bind_param("ii", $course_id, $lesson['lesson_order']);
        $next_lesson_stmt->execute();
        $next_lesson_result = $next_lesson_stmt->get_result();
        if ($next_lesson_result->num_rows > 0) {
            $next_lesson_id = $next_lesson_result->fetch_assoc()['id'];
            redirect("/student/view_lesson.php?id=" . $next_lesson_id);
        } else {
            // This was the last lesson. Check certificate rules.
            $course_rules_stmt = $db->prepare("SELECT is_certificate_course, final_quiz_lesson_id, passing_grade FROM courses WHERE id = ?");
            $course_rules_stmt->bind_param("i", $course_id);
            $course_rules_stmt->execute();
            $rules = $course_rules_stmt->get_result()->fetch_assoc();
            $course_rules_stmt->close();

            $can_earn_certificate = true;
            if (!$rules['is_certificate_course']) {
                $can_earn_certificate = false;
            }
            if ($can_earn_certificate && $rules['final_quiz_lesson_id']) {
                $quiz_id_stmt = $db->prepare("SELECT id FROM quizzes WHERE lesson_id = ?");
                $quiz_id_stmt->bind_param("i", $rules['final_quiz_lesson_id']);
                $quiz_id_stmt->execute();
                $quiz = $quiz_id_stmt->get_result()->fetch_assoc();
                $quiz_id_stmt->close();
                if ($quiz) {
                    $score_stmt = $db->prepare("SELECT MAX(score) as highest_score FROM quiz_attempts WHERE student_id = ? AND quiz_id = ?");
                    $score_stmt->bind_param("ii", $student_id, $quiz['id']);
                    $score_stmt->execute();
                    $attempt = $score_stmt->get_result()->fetch_assoc();
                    $score_stmt->close();
                    if (!$attempt || $attempt['highest_score'] < $rules['passing_grade']) {
                        $can_earn_certificate = false;
                    }
                } else {
                    $can_earn_certificate = false;
                }
            }

            if ($can_earn_certificate) {
                award_badge($student_id, 'COMPLETE_FIRST_COURSE', $db);

                $count_stmt = $db->prepare("SELECT COUNT(DISTINCT course_id) as completed_count FROM (SELECT lp.course_id, COUNT(l.id) as total_lessons FROM lesson_progress lp JOIN lessons l ON lp.course_id = l.course_id WHERE lp.student_id = ? GROUP BY lp.course_id HAVING COUNT(lp.lesson_id) >= total_lessons) as completed_courses");
                $count_stmt->bind_param("i", $student_id);
                $count_stmt->execute();
                $completed_count = $count_stmt->get_result()->fetch_assoc()['completed_count'] ?? 0;
                if ($completed_count >= 5) {
                    award_badge($student_id, 'COMPLETE_5_COURSES', $db);
                }
                $count_stmt->close();

                $cert_check_stmt = $db->prepare("SELECT id FROM certificates WHERE student_id = ? AND course_id = ?");
                $cert_check_stmt->bind_param("ii", $student_id, $course_id);
                $cert_check_stmt->execute();
                if ($cert_check_stmt->get_result()->num_rows === 0) {
                    $certificate_code = 'DADE-' . bin2hex(random_bytes(16));
                    $cert_insert_stmt = $db->prepare("INSERT INTO certificates (student_id, course_id, certificate_code) VALUES (?, ?, ?)");
                    $cert_insert_stmt->bind_param("iis", $student_id, $course_id, $certificate_code);
                    $cert_insert_stmt->execute();
                    $cert_insert_stmt->close();
                }
                $cert_check_stmt->close();
            }

            $_SESSION['success_message'] = "Congratulations! You have completed all lessons in this course!";
            redirect("/student/index.php");
        }
    }

    if ($action === 'toggle_bookmark') {
        $is_bookmarked_stmt = $db->prepare("SELECT id FROM bookmarks WHERE user_id = ? AND lesson_id = ?");
        $is_bookmarked_stmt->bind_param("ii", $student_id, $current_lesson_id);
        $is_bookmarked_stmt->execute();
        if ($is_bookmarked_stmt->get_result()->num_rows > 0) {
            $delete_stmt = $db->prepare("DELETE FROM bookmarks WHERE user_id = ? AND lesson_id = ?");
            $delete_stmt->bind_param("ii", $student_id, $current_lesson_id);
            $delete_stmt->execute();
        } else {
            $insert_stmt = $db->prepare("INSERT INTO bookmarks (user_id, lesson_id) VALUES (?, ?)");
            $insert_stmt->bind_param("ii", $student_id, $current_lesson_id);
            $insert_stmt->execute();
        }
        $is_bookmarked_stmt->close();
        redirect("/student/view_lesson.php?id=" . $current_lesson_id);
    }

    if ($action === 'save_note') {
        $note_content = trim($_POST['note_content']);
        $note_stmt = $db->prepare("INSERT INTO notes (user_id, lesson_id, note_content) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE note_content = VALUES(note_content)");
        $note_stmt->bind_param("iis", $student_id, $current_lesson_id, $note_content);
        $note_stmt->execute();
        $note_stmt->close();
        $_SESSION['success_message'] = "Your note has been saved!";
        redirect("/student/view_lesson.php?id=" . $current_lesson_id);
    }
}

// --- DATA FETCHING FOR DISPLAY ---
$all_lessons = [];
$nav_stmt = $db->prepare("SELECT id, title, content_type FROM lessons WHERE course_id = ? ORDER BY lesson_order ASC");
$nav_stmt->bind_param("i", $course_id);
$nav_stmt->execute();
$nav_result = $nav_stmt->get_result();
while ($row = $nav_result->fetch_assoc()) {
    $all_lessons[] = $row;
}
$nav_stmt->close();

$completed_lessons = [];
$progress_stmt = $db->prepare("SELECT lesson_id FROM lesson_progress WHERE student_id = ? AND course_id = ?");
$progress_stmt->bind_param("ii", $student_id, $course_id);
$progress_stmt->execute();
$progress_result = $progress_stmt->get_result();
while ($row = $progress_result->fetch_assoc()) {
    $completed_lessons[$row['lesson_id']] = true;
}
$progress_stmt->close();

$is_bookmarked = false;
$bookmark_check = $db->prepare("SELECT id FROM bookmarks WHERE user_id = ? AND lesson_id = ?");
$bookmark_check->bind_param("ii", $student_id, $current_lesson_id);
$bookmark_check->execute();
if ($bookmark_check->get_result()->num_rows > 0) {
    $is_bookmarked = true;
}
$bookmark_check->close();

$user_note = '';
$note_fetch = $db->prepare("SELECT note_content FROM notes WHERE user_id = ? AND lesson_id = ?");
$note_fetch->bind_param("ii", $student_id, $current_lesson_id);
$note_fetch->execute();
$note_result = $note_fetch->get_result();
if ($note_result->num_rows > 0) {
    $user_note = $note_result->fetch_assoc()['note_content'];
}
$note_fetch->close();

$success_message = $_SESSION['success_message'] ?? null;
unset($_SESSION['success_message']);

$page_title = $lesson['title'];
require_once __DIR__ . '/../includes/header.php';
?>

<div class="learning-wrapper">
    <aside class="lesson-sidebar">
        <h3><?php echo e($lesson['course_title']); ?></h3>
        <a href="<?php echo $base; ?>/student/forum.php?course_id=<?php echo $lesson['course_id']; ?>" class="button button-secondary button-fullwidth">Discussion Forum</a>
        <nav class="lesson-nav-list">
            <ul>
                <?php foreach ($all_lessons as $nav_lesson): ?>
                    <?php $is_complete = isset($completed_lessons[$nav_lesson['id']]); ?>
                    <li class="<?php echo ($nav_lesson['id'] == $current_lesson_id) ? 'active' : ''; ?>">
                        <a href="<?php
                                    if ($nav_lesson['content_type'] === 'quiz') {
                                        echo $base . '/student/take_quiz.php?id=' . $nav_lesson['id'];
                                    } elseif ($nav_lesson['content_type'] === 'assignment') {
                                        echo $base . '/student/view_assignment.php?id=' . $nav_lesson['id'];
                                    } else {
                                        echo $base . '/student/view_lesson.php?id=' . $nav_lesson['id'];
                                    }
                                    ?>">
                            <span class="lesson-icon icon-<?php echo e($nav_lesson['content_type']); ?> <?php echo $is_complete ? 'completed' : ''; ?>"></span>
                            <?php echo e($nav_lesson['title']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </aside>

    <main class="lesson-content">
        <?php if ($success_message): ?><div class="form-message success"><?php echo e($success_message); ?></div><?php endif; ?>

        <div class="lesson-header-actions">
            <h1><?php echo e($lesson['title']); ?></h1>
            <form action="<?php echo $base; ?>/student/view_lesson.php?id=<?php echo $current_lesson_id; ?>" method="POST" class="inline-form">
                <input type="hidden" name="action" value="toggle_bookmark">
                <button type="submit" class="button button-secondary button-small bookmark-button <?php echo $is_bookmarked ? 'bookmarked' : ''; ?>" title="<?php echo $is_bookmarked ? 'Remove Bookmark' : 'Add Bookmark'; ?>">
                    <span class="bookmark-icon">★</span>
                    <span class="bookmark-text"><?php echo $is_bookmarked ? 'Bookmarked' : 'Bookmark'; ?></span>
                </button>
            </form>
        </div>

        <?php if ($lesson['content_type'] === 'text' || ($lesson['content_type'] === 'assignment' && !empty($lesson['content_text']))): ?>
            <div class="lesson-actions-bar">
                <button id="ai-summary-button" class="button button-secondary button-small">💡 Get AI Summary</button>
            </div>
        <?php endif; ?>

        <div class="lesson-body">
            <?php
            switch ($lesson['content_type']):
                case 'text':
                case 'assignment':
                    echo '<div class="text-content">' . nl2br(e($lesson['content_text'])) . '</div>';
                    break;
                case 'video':
                    $media_stmt = $db->prepare("SELECT file_path, youtube_url, subtitle_path FROM lesson_media WHERE lesson_id = ?");
                    $media_stmt->bind_param("i", $current_lesson_id);
                    $media_stmt->execute();
                    $media = $media_stmt->get_result()->fetch_assoc();
                    $media_stmt->close();
                    if ($media && !empty($media['youtube_url'])):
            ?>
                        <div class="video-container youtube-embed">
                            <iframe src="<?php echo e($media['youtube_url']); ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    <?php
                    elseif ($media && !empty($media['file_path'])):
                    ?>
                        <div class="video-container">
                            <video controls width="100%" crossorigin="anonymous">
                                <source src="<?php echo $base; ?>/<?php echo ltrim($media['file_path'], '/'); ?>" type="video/mp4">
                                <?php if (!empty($media['subtitle_path'])): ?>
                                    <track label="English" kind="subtitles" srclang="en" src="<?php echo $base; ?>/<?php echo ltrim($media['subtitle_path'], '/'); ?>" default>
                                <?php endif; ?>
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    <?php
                    else:
                        echo '<p>Video content is not available for this lesson.</p>';
                    endif;
                    break;
                case 'pdf':
                    $media_stmt = $db->prepare("SELECT file_path FROM lesson_media WHERE lesson_id = ?");
                    $media_stmt->bind_param("i", $current_lesson_id);
                    $media_stmt->execute();
                    $media = $media_stmt->get_result()->fetch_assoc();
                    $media_stmt->close();
                    if ($media && !empty($media['file_path'])):
                    ?>
                        <div class="pdf-container">
                             <iframe src="<?php echo $base; ?>/<?php echo ltrim($media['file_path'], '/'); ?>" width="100%" height="800px" title="<?php echo e($lesson['title']); ?>"></iframe>
                        </div>
            <?php
                    else:
                        echo '<p>PDF document is not available for this lesson.</p>';
                    endif;
                    break;
                default:
                    echo '<div class="info-box"><p>Content for this lesson type is currently being prepared.</p></div>';
                    break;
            endswitch;
            ?>
        </div>

        <div class="lesson-footer-nav">
            <form action="<?php echo $base; ?>/student/view_lesson.php?id=<?php echo $current_lesson_id; ?>" method="POST">
                <input type="hidden" name="action" value="mark_complete">
                <?php if (!isset($completed_lessons[$current_lesson_id])): ?>
                    <button type="submit" class="button button-primary button-large">Mark as Complete & Continue</button>
                <?php else: ?>
                    <div class="button button-success button-large">✔️ Completed</div>
                <?php endif; ?>
            </form>
        </div>

        <hr>
        <div class="notes-section management-section">
            <h2><span class="icon">📝</span> My Private Notes</h2>
            <p>Your notes for this lesson are saved to your account and are not visible to anyone else.</p>
            <form action="<?php echo $base; ?>/student/view_lesson.php?id=<?php echo $current_lesson_id; ?>" method="POST">
                <input type="hidden" name="action" value="save_note">
                <div class="form-group"><textarea name="note_content" rows="8" placeholder="Start typing your notes here..."><?php echo e($user_note); ?></textarea></div>
                <button type="submit" class="button button-primary">Save Notes</button>
            </form>
        </div>
    </main>
</div>

<div id="ai-summary-modal" class="modal-overlay" aria-hidden="true">
    <div class="modal-container" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <div class="modal-header">
            <h2 id="modal-title">AI-Powered Lesson Summary</h2>
            <button class="modal-close-button" aria-label="Close modal">&times;</button>
        </div>
        <div id="ai-summary-content" class="modal-content">
            <div class="spinner"></div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>