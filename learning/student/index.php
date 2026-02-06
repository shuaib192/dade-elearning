<?php
$page_title = 'My Dashboard';
$allowed_roles = [1, 2, 3];
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$student_id = $_SESSION['user_id'];
$enrolled_courses = [];

$sql = "SELECT 
            c.id, c.title, c.cover_image,
            c.is_certificate_course, c.final_quiz_lesson_id, c.passing_grade,
            (SELECT COUNT(*) FROM lessons WHERE course_id = c.id) as total_lessons,
            (SELECT COUNT(*) FROM lesson_progress WHERE student_id = ? AND course_id = c.id) as completed_lessons
        FROM courses c
        JOIN enrollments e ON c.id = e.course_id
        WHERE e.student_id = ? AND c.status = 'published'";

$stmt = $db->prepare($sql);
$stmt->bind_param("ii", $student_id, $student_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $enrolled_courses[] = $row;
    }
}
$stmt->close();

$success_message = $_SESSION['success_message'] ?? null;
unset($_SESSION['success_message']);

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container">

    <style>
        /* --- Dashboard Button Layout Fix --- */
        .button-row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }

        .button-row .button {
            flex: 1 1 48%;
            text-align: center;
            padding: 10px 0;
            border-radius: 6px;
            font-weight: 600;
            transition: 0.3s ease;
        }

        /* Specific button colors for clarity */
        .button-success {
            background-color: #115c2d;
            color: #fff;
        }

        .button-success:hover {
            background-color: #0d4a25;
        }

        .button-secondary {
            background-color: #005f73;
            color: #fff;
        }

        .button-secondary:hover {
            background-color: #014e5f;
        }
    </style>
    <div class="page-header">
        <h1>My Enrolled Courses</h1>
        <div class="dashboard-header-buttons">
            <a href="my_bookmarks.php" class="button button-secondary">My Bookmarks</a>
            <a href="my_accomplishments.php" class="button button-primary">My Accomplishments</a>
        </div>
    </div>

    <?php if ($success_message): ?><div class="form-message success" role="status"><?php echo $success_message; ?></div><?php endif; ?>

    <div class="dashboard-content">
        <?php if (empty($enrolled_courses)): ?>
            <div class="info-box">
                <p>You are not currently enrolled in any courses.</p>
                <a href="<?php echo $base; ?>/courses.php" class="button button-primary">Browse Courses</a>
            </div>
        <?php else: ?>
            <div class="course-grid">
                <?php foreach ($enrolled_courses as $course): ?>
                    <div class="course-card">
                        <img src="<?php echo e($course['cover_image'] ?? '/assets/images/placeholder_course.jpg'); ?>" alt="Cover image for <?php echo e($course['title']); ?>" class="course-card-image">
                        <div class="course-card-content">
                            <h3><?php echo e($course['title']); ?></h3>
                            <?php
                            $progress = 0;
                            $is_complete = false;
                            if ($course['total_lessons'] > 0) {
                                $progress = round(($course['completed_lessons'] / $course['total_lessons']) * 100);
                                if ($course['completed_lessons'] >= $course['total_lessons']) {
                                    $is_complete = true;
                                }
                            }

                            // Check quiz status
                            $passed_final_quiz = false;
                            if (!empty($course['is_certificate_course']) && !empty($course['final_quiz_lesson_id'])) {
                                $final_lesson_id = (int)$course['final_quiz_lesson_id'];
                                $quiz_id_stmt = $db->prepare("SELECT id FROM quizzes WHERE lesson_id = ?");
                                if ($quiz_id_stmt) {
                                    $quiz_id_stmt->bind_param("i", $final_lesson_id);
                                    $quiz_id_stmt->execute();
                                    $quiz_res = $quiz_id_stmt->get_result();
                                    $quiz = ($quiz_res && $quiz_res->num_rows) ? $quiz_res->fetch_assoc() : null;
                                    $quiz_id_stmt->close();

                                    if ($quiz && !empty($quiz['id'])) {
                                        $quiz_id = (int)$quiz['id'];
                                        $score_stmt = $db->prepare("SELECT MAX(score) as highest_score FROM quiz_attempts WHERE student_id = ? AND quiz_id = ?");
                                        if ($score_stmt) {
                                            $score_stmt->bind_param("ii", $student_id, $quiz_id);
                                            $score_stmt->execute();
                                            $score_res = $score_stmt->get_result();
                                            $attempt = ($score_res && $score_res->num_rows) ? $score_res->fetch_assoc() : null;
                                            $score_stmt->close();

                                            if ($attempt && isset($attempt['highest_score'])) {
                                                $passing_grade = is_numeric($course['passing_grade']) ? (int)$course['passing_grade'] : 0;
                                                if ($attempt['highest_score'] >= $passing_grade) {
                                                    $passed_final_quiz = true;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            ?>
                            <div class="progress-bar-container">
                                <div class="progress-bar" style="width: <?php echo $progress; ?>%;"></div>
                            </div>
                            <small><?php echo $progress; ?>% Complete (<?php echo e($course['completed_lessons']); ?>/<?php echo e($course['total_lessons']); ?> lessons)</small>

                            <div class="course-card-footer dashboard-card-footer button-row">
                                <?php
                                $action_button = '';

                                // Certificate check
                                if (($is_complete || $passed_final_quiz) && !empty($course['is_certificate_course'])) {
                                    $cert_stmt = $db->prepare("SELECT certificate_code FROM certificates WHERE student_id = ? AND course_id = ?");
                                    if ($cert_stmt) {
                                        $cert_stmt->bind_param("ii", $student_id, $course['id']);
                                        $cert_stmt->execute();
                                        $cert_res = $cert_stmt->get_result();
                                        $cert_row = ($cert_res && $cert_res->num_rows) ? $cert_res->fetch_assoc() : null;
                                        $cert_code = $cert_row ? $cert_row['certificate_code'] : null;
                                        $cert_stmt->close();

                                        if ($cert_code) {
                                            // ✅ Show both buttons
                                            $action_button .= '<a href="' . $base . '/certs.php?code=' . e($cert_code) . '" class="button button-success" target="_blank">View Certificate</a> ';
                                        } else {
                                            $action_button .= '<span class="button button-disabled">Processing...</span> ';
                                        }
                                    }
                                }

                                // Always allow to continue learning even after passing
                                $first_lesson_id = 0;
                                $lesson_stmt = $db->prepare("SELECT id FROM lessons WHERE course_id = ? ORDER BY lesson_order ASC LIMIT 1");
                                if ($lesson_stmt) {
                                    $lesson_stmt->bind_param("i", $course['id']);
                                    $lesson_stmt->execute();
                                    $lesson_result = $lesson_stmt->get_result();
                                    if ($lesson_result && $lesson_result->num_rows > 0) {
                                        $first_lesson_id = $lesson_result->fetch_assoc()['id'];
                                    }
                                    $lesson_stmt->close();
                                }
                                $continue_link = ($first_lesson_id > 0) ? $base . "/student/view_lesson.php?id=" . $first_lesson_id : $base . "/view_course.php?id=" . $course['id'];
                                $button_text = ($first_lesson_id > 0) ? "Continue Learning" : "View Course";
                                $action_button .= '<a href="' . $continue_link . '" class="button button-secondary">' . $button_text . '</a>';

                                echo $action_button;
                                ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>