<?php
// --- SETUP AND SECURITY ---
$page_title = 'Take Quiz';
$allowed_roles = [3]; // Only students can take quizzes
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// --- VALIDATE LESSON ID & FETCH QUIZ DATA ---
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $_SESSION['error_message'] = "Invalid quiz link.";
    redirect('/student/index.php');
}
$lesson_id = $_GET['id'];
$student_id = $_SESSION['user_id'];

// Fetch the quiz ID and course ID associated with this lesson
$stmt = $db->prepare("SELECT q.id as quiz_id, l.title as quiz_title, c.id as course_id
                      FROM quizzes q
                      JOIN lessons l ON q.lesson_id = l.id
                      JOIN courses c ON l.course_id = c.id
                      WHERE q.lesson_id = ?");
$stmt->bind_param("i", $lesson_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    $_SESSION['error_message'] = "Quiz not found for this lesson.";
    redirect('/student/index.php');
}
$quiz_data = $result->fetch_assoc();
$quiz_id = $quiz_data['quiz_id'];
$course_id = $quiz_data['course_id'];
$page_title = $quiz_data['quiz_title'];
$stmt->close();

// Security Check: Verify the student is enrolled in this course
$enroll_stmt = $db->prepare("SELECT id FROM enrollments WHERE student_id = ? AND course_id = ?");
$enroll_stmt->bind_param("ii", $student_id, $course_id);
$enroll_stmt->execute();
if ($enroll_stmt->get_result()->num_rows === 0) {
    $_SESSION['error_message'] = "You are not enrolled in this course.";
    redirect('/courses.php');
}
$enroll_stmt->close();

// --- FORM SUBMISSION: GRADING LOGIC ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitted_answers = $_POST['answers'] ?? [];
    $total_questions = 0;
    $correct_answers = 0;

    // Get all correct options for this quiz in one query for efficiency
    $correct_options_query = $db->query("SELECT question_id, id FROM question_options WHERE is_correct = 1 AND question_id IN (SELECT id FROM quiz_questions WHERE quiz_id = $quiz_id)");
    $correct_answers_map = [];
    while($row = $correct_options_query->fetch_assoc()) {
        $correct_answers_map[$row['question_id']] = $row['id'];
    }
    
    $total_questions = count($correct_answers_map);

    // Grade the submitted answers
    foreach ($submitted_answers as $question_id => $selected_option_id) {
        if (isset($correct_answers_map[$question_id]) && $correct_answers_map[$question_id] == $selected_option_id) {
            $correct_answers++;
        }
    }

    $score = ($total_questions > 0) ? ($correct_answers / $total_questions) * 100 : 0;
    $score = round($score, 2);

    // Save the attempt to the database
    $attempt_stmt = $db->prepare("INSERT INTO quiz_attempts (student_id, quiz_id, score) VALUES (?, ?, ?)");
    $attempt_stmt->bind_param("iid", $student_id, $quiz_id, $score);
    $attempt_stmt->execute();
    $attempt_id = $attempt_stmt->insert_id;
    $attempt_stmt->close();

    // Redirect to the results page
    redirect("quiz_result.php?attempt_id=" . $attempt_id);
}

// --- DATA FETCHING FOR DISPLAY ---
// Fetch all questions and their options for this quiz
$questions = [];
$q_stmt = $db->prepare("SELECT id, question_text FROM quiz_questions WHERE quiz_id = ?");
$q_stmt->bind_param("i", $quiz_id);
$q_stmt->execute();
$q_result = $q_stmt->get_result();
while ($question = $q_result->fetch_assoc()) {
    $options = [];
    $opt_stmt = $db->prepare("SELECT id, option_text FROM question_options WHERE question_id = ?");
    $opt_stmt->bind_param("i", $question['id']);
    $opt_stmt->execute();
    $opt_result = $opt_stmt->get_result();
    while ($option = $opt_result->fetch_assoc()) {
        $options[] = $option;
    }
    $opt_stmt->close();
    $question['options'] = $options;
    $questions[] = $question;
}
$q_stmt->close();

// --- PAGE DISPLAY ---
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1>Quiz: <?php echo e($quiz_data['quiz_title']); ?></h1>
        <a href="view_lesson.php?id=<?php echo $lesson_id; ?>" class="button button-secondary">Back to Lesson View</a>
    </div>

    <?php if(empty($questions)): ?>
        <div class="info-box"><p>This quiz has no questions yet. Please check back later.</p></div>
    <?php else: ?>
        <form action="take_quiz.php?id=<?php echo $lesson_id; ?>" method="POST" class="quiz-form">
            <?php foreach ($questions as $index => $question): ?>
                <div class="quiz-question-block">
                    <p class="question-text"><strong>Question <?php echo $index + 1; ?>:</strong> <?php echo e($question['question_text']); ?></p>
                    <div class="question-options">
                        <?php foreach ($question['options'] as $option): ?>
                            <div class="option-choice">
                                <input type="radio" name="answers[<?php echo $question['id']; ?>]" value="<?php echo $option['id']; ?>" id="option_<?php echo $option['id']; ?>" required>
                                <label for="option_<?php echo $option['id']; ?>"><?php echo e($option['option_text']); ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <button type="submit" class="button button-primary form-button">Submit Quiz</button>
        </form>
    <?php endif; ?>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>