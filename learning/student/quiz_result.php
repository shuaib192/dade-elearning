<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// --- SETUP AND SECURITY ---
$page_title = 'Quiz Result';
$allowed_roles = [3];
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// --- VALIDATE ATTEMPT ID & FETCH DATA ---
if (!isset($_GET['attempt_id']) || !filter_var($_GET['attempt_id'], FILTER_VALIDATE_INT)) {
    redirect('/student/index.php');
}
$attempt_id = $_GET['attempt_id'];
$student_id = $_SESSION['user_id'];

// Fetch the attempt details, ensuring it belongs to the logged-in student
$stmt = $db->prepare("
    SELECT qa.score, qa.quiz_id, q.lesson_id, l.title AS quiz_title, c.id AS course_id, c.passing_grade, c.is_certificate_course
    FROM quiz_attempts qa
    JOIN quizzes q ON qa.quiz_id = q.id
    JOIN lessons l ON q.lesson_id = l.id
    JOIN courses c ON l.course_id = c.id
    WHERE qa.id = ? AND qa.student_id = ?
");
$stmt->bind_param("ii", $attempt_id, $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error_message'] = "Quiz result not found or you do not have permission to view it.";
    redirect('/student/index.php');
}
$attempt_data = $result->fetch_assoc();
$stmt->close();

// --- CERTIFICATE CHECK & AUTO-GENERATION ---
$has_certificate = false;
$certificate_code = null;

if ($attempt_data['score'] >= $attempt_data['passing_grade'] && $attempt_data['is_certificate_course']) {
    // Check if certificate already exists
    $cert_stmt = $db->prepare("SELECT certificate_code FROM certificates WHERE student_id = ? AND course_id = ?");
    $cert_stmt->bind_param("ii", $student_id, $attempt_data['course_id']);
    $cert_stmt->execute();
    $cert_result = $cert_stmt->get_result();

    if ($cert_result->num_rows > 0) {
        $certificate_code = $cert_result->fetch_assoc()['certificate_code'];
        $has_certificate = true;
    } else {
        // Generate a new certificate code
        $certificate_code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 12));

        // Insert certificate (no created_at column)
        $insert_cert = $db->prepare("INSERT INTO certificates (student_id, course_id, certificate_code) VALUES (?, ?, ?)");
        $insert_cert->bind_param("iis", $student_id, $attempt_data['course_id'], $certificate_code);
        if ($insert_cert->execute()) {
            $has_certificate = true;
            $_SESSION['success_message'] = "🎉 Congratulations! You have been awarded a certificate for completing this course.";
        }
        $insert_cert->close();
    }

    $cert_stmt->close();
}

// --- PAGE DISPLAY ---
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <div class="quiz-result-box">
        <h1>Results for: <?php echo e($attempt_data['quiz_title']); ?></h1>
        <p class="score-display">Your Score:</p>
        <div class="score-circle">
            <?php echo e($attempt_data['score']); ?>%
        </div>

        <?php if ($attempt_data['score'] >= $attempt_data['passing_grade']): ?>
            <p class="result-message success">Congratulations, you passed!</p>
        <?php else: ?>
            <p class="result-message error">You did not pass this time. Please review the material and try again.</p>
        <?php endif; ?>

        <div class="result-actions">
            <a href="take_quiz.php?id=<?php echo e($attempt_data['lesson_id']); ?>" class="button button-secondary">Retake Quiz</a>

            <?php if ($has_certificate): ?>
                <a href="<?php echo $base; ?>/certs.php?code=<?php echo e($certificate_code); ?>" class="button button-success" target="_blank">View Certificate</a>
            <?php elseif ($attempt_data['score'] >= $attempt_data['passing_grade'] && $attempt_data['is_certificate_course']): ?>
                <span class="button button-disabled">Processing Certificate...</span>
            <?php else: ?>
                <a href="#" class="button button-primary">Continue to Next Lesson</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>