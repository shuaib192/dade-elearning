<?php
// --- SETUP AND SECURITY ---
$page_title = 'Assignment';
$allowed_roles = [3]; // Only students can view/submit assignments
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// --- VALIDATE LESSON ID & FETCH DATA ---
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $_SESSION['error_message'] = "Invalid assignment link.";
    redirect('/student/index.php');
}
$lesson_id = $_GET['id'];
$student_id = $_SESSION['user_id'];

// Fetch lesson details and verify it's an assignment type
$stmt = $db->prepare("SELECT l.*, c.id as course_id FROM lessons l JOIN courses c ON l.course_id = c.id WHERE l.id = ? AND l.content_type = 'assignment'");
$stmt->bind_param("i", $lesson_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    $_SESSION['error_message'] = "Assignment not found.";
    redirect('/student/index.php');
}
$assignment = $result->fetch_assoc();
$page_title = $assignment['title'];
$course_id = $assignment['course_id'];
$stmt->close();

// --- HANDLE FILE UPLOAD ---
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['assignment_file'])) {
    if ($_FILES['assignment_file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['assignment_file'];
        
        $target_dir = '/uploads/assignments/';
        $filename = 'course' . $course_id . '_lesson' . $lesson_id . '_student' . $student_id . '_' . time() . '_' . basename($file['name']);
        $target_path = __DIR__ . '/..' . $target_dir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $target_path)) {
            $db_path = $target_dir . $filename;
            
            // We use INSERT...ON DUPLICATE KEY UPDATE to handle re-submissions
            // This requires a UNIQUE key on (assignment_id, student_id) in the DB table.
            $sub_stmt = $db->prepare("INSERT INTO assignment_submissions (assignment_id, student_id, file_path) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE file_path = VALUES(file_path), submitted_at = NOW()");
            $sub_stmt->bind_param("iis", $lesson_id, $student_id, $db_path);
            if ($sub_stmt->execute()) {
                $_SESSION['success_message'] = "Your assignment was submitted successfully!";
            } else {
                 $_SESSION['error_message'] = "Database error while saving submission.";
            }
            $sub_stmt->close();

        } else {
            $_SESSION['error_message'] = "Sorry, there was an error uploading your file.";
        }
    } else {
        $_SESSION['error_message'] = "No file was uploaded or an error occurred during upload.";
    }
    redirect($_SERVER['REQUEST_URI']);
}

// --- DATA FETCHING FOR DISPLAY ---
// Check for a previous submission
$submission = null;
$sub_fetch_stmt = $db->prepare("SELECT * FROM assignment_submissions WHERE assignment_id = ? AND student_id = ?");
$sub_fetch_stmt->bind_param("ii", $lesson_id, $student_id);
$sub_fetch_stmt->execute();
$sub_result = $sub_fetch_stmt->get_result();
if ($sub_result->num_rows > 0) {
    $submission = $sub_result->fetch_assoc();
}
$sub_fetch_stmt->close();

// Handle session messages
$success_message = $_SESSION['success_message'] ?? null;
unset($_SESSION['success_message']);

// --- PAGE DISPLAY ---
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1>Assignment: <?php echo e($assignment['title']); ?></h1>
        <a href="view_lesson.php?id=<?php echo $lesson_id; ?>" class="button button-secondary">Back to Lesson View</a>
    </div>
    
    <?php if ($success_message): ?><div class="form-message success"><?php echo e($success_message); ?></div><?php endif; ?>
    <?php if (!empty($errors)): ?>
        <div class="form-message error"><?php foreach ($errors as $error) echo "<p>" . e($error) . "</p>"; ?></div>
    <?php endif; ?>

    <div class="assignment-layout">
        <div class="assignment-instructions">
            <div class="management-section">
                <h2>Instructions</h2>
                <div class="text-content">
                    <?php echo nl2br(e($assignment['content_text'])); ?>
                </div>
            </div>
        </div>
        <div class="assignment-submission">
            <div class="management-section">
                <h2>Your Submission</h2>
                <form action="view_assignment.php?id=<?php echo $lesson_id; ?>" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="assignment_file">Upload your file</label>
                        <input type="file" id="assignment_file" name="assignment_file" required>
                        <small class="form-text">Accepted formats: PDF, DOC, DOCX, ZIP</small>
                    </div>
                    <button type="submit" class="button button-primary form-button">Submit Assignment</button>
                </form>
                <hr>
                <h4>Submission Status</h4>
                <?php if ($submission): ?>
                    <div class="submission-status submitted">
                        <p><strong>Status:</strong> Submitted</p>
                        <p><strong>File:</strong> <?php echo e(basename($submission['file_path'])); ?></p>
                        <p><strong>Date:</strong> <?php echo date('F j, Y, g:i a', strtotime($submission['submitted_at'])); ?></p>
                        <p><strong>Grade:</strong> <?php echo $submission['grade'] ? e($submission['grade']) . '%' : 'Not graded yet'; ?></p>
                    </div>
                <?php else: ?>
                    <div class="submission-status not-submitted">
                        <p>You have not submitted this assignment yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>