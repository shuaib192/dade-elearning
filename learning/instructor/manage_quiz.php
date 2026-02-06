<?php
// --- SETUP AND SECURITY ---
$page_title = 'Manage Quiz';
$allowed_roles = [1, 2]; // Admin & Instructor
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// --- VALIDATE LESSON ID & FETCH DATA ---
if (!isset($_GET['lesson_id']) || !filter_var($_GET['lesson_id'], FILTER_VALIDATE_INT)) {
    $_SESSION['error_message'] = "Invalid lesson ID.";
    redirect('/instructor/index.php');
}
$lesson_id = $_GET['lesson_id'];

// Fetch lesson and course details to verify ownership and content type
$stmt = $db->prepare(
    "SELECT l.id as lesson_id, l.title as lesson_title, l.content_type, c.id as course_id, c.instructor_id
     FROM lessons l
     JOIN courses c ON l.course_id = c.id
     WHERE l.id = ? AND l.content_type = 'quiz'"
);
$stmt->bind_param("i", $lesson_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error_message'] = "Quiz lesson not found or the lesson is not a quiz type.";
    redirect('/instructor/index.php');
}
$lesson = $result->fetch_assoc();
$stmt->close();

// Security Check: User must be an admin or the instructor who owns the course
if ($_SESSION['role_id'] != 1 && $lesson['instructor_id'] != $_SESSION['user_id']) {
    $_SESSION['error_message'] = "You do not have permission to manage this quiz.";
    redirect('/instructor/index.php');
}

// --- CHECK IF QUIZ EXISTS, IF NOT, CREATE IT ---
$quiz_id = null;
$quiz_stmt = $db->prepare("SELECT id FROM quizzes WHERE lesson_id = ?");
$quiz_stmt->bind_param("i", $lesson_id);
$quiz_stmt->execute();
$quiz_result = $quiz_stmt->get_result();
if ($quiz_result->num_rows > 0) {
    $quiz = $quiz_result->fetch_assoc();
    $quiz_id = $quiz['id'];
} else {
    // No quiz entry exists for this lesson yet, so create one.
    $insert_quiz_stmt = $db->prepare("INSERT INTO quizzes (lesson_id, title) VALUES (?, ?)");
    $insert_quiz_stmt->bind_param("is", $lesson_id, $lesson['lesson_title']);
    $insert_quiz_stmt->execute();
    $quiz_id = $insert_quiz_stmt->insert_id;
    $insert_quiz_stmt->close();
}
$quiz_stmt->close();


// --- FORM HANDLING: ADD NEW QUESTION ---
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_question') {
    $question_text = trim($_POST['question_text']);
    $options = $_POST['options'] ?? [];
    $correct_option_index = $_POST['correct_option'] ?? -1;

    // Validation
    if (empty($question_text)) $errors[] = "Question text cannot be empty.";
    if (count($options) < 2) $errors[] = "You must provide at least two options.";
    if (empty(trim($options[0])) || empty(trim($options[1]))) $errors[] = "The first two options cannot be empty.";
    if ($correct_option_index == -1) $errors[] = "You must select a correct answer.";

    if (empty($errors)) {
        $db->begin_transaction(); // Start transaction for safe multi-table insert
        try {
            // 1. Insert the question
            $q_stmt = $db->prepare("INSERT INTO quiz_questions (quiz_id, question_text, question_type) VALUES (?, ?, 'mcq')");
            $q_stmt->bind_param("is", $quiz_id, $question_text);
            $q_stmt->execute();
            $question_id = $q_stmt->insert_id;
            $q_stmt->close();

            // 2. Insert the options
            $opt_stmt = $db->prepare("INSERT INTO question_options (question_id, option_text, is_correct) VALUES (?, ?, ?)");
            foreach ($options as $index => $option_text) {
                if (!empty(trim($option_text))) {
                    $is_correct = ($index == $correct_option_index) ? 1 : 0;
                    $opt_stmt->bind_param("isi", $question_id, $option_text, $is_correct);
                    $opt_stmt->execute();
                }
            }
            $opt_stmt->close();

            $db->commit(); // All good, commit the changes
            $_SESSION['success_message'] = "New question added successfully.";

        } catch (Exception $e) {
            $db->rollback(); // Something went wrong, rollback
            $errors[] = "Database error: Failed to add question.";
        }
        redirect($_SERVER['REQUEST_URI']);
    }
}

// --- DATA FETCHING FOR DISPLAY ---
$questions = [];
$q_fetch_stmt = $db->prepare("SELECT * FROM quiz_questions WHERE quiz_id = ?");
$q_fetch_stmt->bind_param("i", $quiz_id);
$q_fetch_stmt->execute();
$q_result = $q_fetch_stmt->get_result();
while ($row = $q_result->fetch_assoc()) {
    $questions[] = $row;
}
$q_fetch_stmt->close();

$success_message = $_SESSION['success_message'] ?? null;
unset($_SESSION['success_message']);

// --- PAGE DISPLAY ---
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1>Manage Quiz: <?php echo e($lesson['lesson_title']); ?></h1>
        <a href="edit_course.php?id=<?php echo $lesson['course_id']; ?>" class="button button-secondary">Back to Course</a>
    </div>

    <?php if ($success_message): ?><div class="form-message success"><?php echo e($success_message); ?></div><?php endif; ?>
    <?php if (!empty($errors)): ?>
        <div class="form-message error">
            <?php foreach ($errors as $error) echo "<p>" . e($error) . "</p>"; ?>
        </div>
    <?php endif; ?>

    <!-- List Existing Questions -->
    <div class="management-section">
        <h2>Existing Questions</h2>
        <?php if (empty($questions)): ?>
            <p>This quiz has no questions yet. Add one below.</p>
        <?php else: ?>
            <ol class="question-list">
                <?php foreach ($questions as $question): ?>
                    <li><?php echo e($question['question_text']); ?></li>
                <?php endforeach; ?>
            </ol>
        <?php endif; ?>
    </div>

    <!-- Add New Question Form -->
    <div class="management-section">
        <h2>Add New Multiple Choice Question</h2>
        <form action="manage_quiz.php?lesson_id=<?php echo $lesson_id; ?>" method="POST" class="form-container-wide">
            <input type="hidden" name="action" value="add_question">
            <div class="form-group">
                <label for="question_text">Question Text</label>
                <textarea id="question_text" name="question_text" rows="3" required></textarea>
            </div>
            
            <div class="form-group options-group">
                <label>Answer Options</label>
                <!-- Option 1 -->
                <div class="option-input">
                    <input type="radio" name="correct_option" value="0" id="correct_0" required>
                    <label for="correct_0" class="radio-label">Correct</label>
                    <input type="text" name="options[]" placeholder="Option 1 (Required)">
                </div>
                <!-- Option 2 -->
                <div class="option-input">
                    <input type="radio" name="correct_option" value="1" id="correct_1">
                    <label for="correct_1" class="radio-label">Correct</label>
                    <input type="text" name="options[]" placeholder="Option 2 (Required)">
                </div>
                <!-- Option 3 -->
                <div class="option-input">
                    <input type="radio" name="correct_option" value="2" id="correct_2">
                    <label for="correct_2" class="radio-label">Correct</label>
                    <input type="text" name="options[]" placeholder="Option 3 (Optional)">
                </div>
                <!-- Option 4 -->
                <div class="option-input">
                    <input type="radio" name="correct_option" value="3" id="correct_3">
                    <label for="correct_3" class="radio-label">Correct</label>
                    <input type="text" name="options[]" placeholder="Option 4 (Optional)">
                </div>
            </div>

            <!-- ... just after the Existing Questions section ... -->
                    <hr>

                    <!-- AI QUESTION GENERATION -->
                    <div class="management-section ai-generator">
                        <h2>AI Question Generator</h2>
                        <p>Generate questions automatically based on the text content from other lessons in this course.</p>
                        <button id="generate-quiz-ai" class="button button-primary" data-course-id="<?php echo $lesson['course_id']; ?>" data-quiz-id="<?php echo $quiz_id; ?>">
                            ✨ Generate 3 Questions with AI
                        </button>
                        <div id="ai-spinner" class="spinner" style="display: none;"></div>
                    </div>

                    <!-- Add New Question Form -->
                    <div class="management-section">
                        <h2>Add New Multiple Choice Question (Manually)</h2>
                        <!-- ... the manual form follows ... -->

            <button type="submit" class="button button-primary form-button">Add Question</button>
        </form>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>