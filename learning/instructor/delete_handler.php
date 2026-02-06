<?php
// --- SETUP AND SECURITY ---
$allowed_roles = [1, 2]; // Only Instructors and Admins can delete content
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// --- FORM SUBMISSION VALIDATION ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // If not a POST request, redirect away. This prevents accidental deletion.
    redirect('/instructor/index.php');
}

$action = $_POST['action'] ?? '';
$id = filter_var($_POST['id'], FILTER_VALIDATE_INT);

if (!$id) {
    $_SESSION['error_message'] = "Invalid ID provided.";
    redirect('/instructor/index.php');
}

$user_id = $_SESSION['user_id'];
$is_admin = ($_SESSION['role_id'] == 1);


// --- ACTION ROUTER ---
if ($action === 'delete_lesson') {
    // Security: Verify ownership before deleting
    $stmt = $db->prepare("SELECT c.instructor_id, l.course_id FROM lessons l JOIN courses c ON l.course_id = c.id WHERE l.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $item = $result->fetch_assoc();
        if ($is_admin || $item['instructor_id'] == $user_id) {
            // Authorized, proceed with delete
            $delete_stmt = $db->prepare("DELETE FROM lessons WHERE id = ?");
            $delete_stmt->bind_param("i", $id);
            $delete_stmt->execute();
            $_SESSION['success_message'] = "Lesson deleted successfully.";
            redirect('/instructor/edit_course.php?id=' . $item['course_id']);
        }
    }
    // If we reach here, something went wrong or permission was denied
    $_SESSION['error_message'] = "Could not delete lesson. Permission denied or lesson not found.";
    redirect('/instructor/index.php');

} elseif ($action === 'delete_course') {
    // Security: Verify ownership before deleting
    $stmt = $db->prepare("SELECT instructor_id FROM courses WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $item = $result->fetch_assoc();
        if ($is_admin || $item['instructor_id'] == $user_id) {
            // Authorized, proceed with delete. The DB is set to CASCADE DELETE,
            // which will also delete related lessons, enrollments, progress, etc.
            $delete_stmt = $db->prepare("DELETE FROM courses WHERE id = ?");
            $delete_stmt->bind_param("i", $id);
            $delete_stmt->execute();
            $_SESSION['success_message'] = "Course and all its related content have been deleted.";
            redirect('/instructor/index.php');
        }
    }
    // If we reach here, something went wrong
    $_SESSION['error_message'] = "Could not delete course. Permission denied or course not found.";
    redirect('/instructor/index.php');
}

// Fallback for unknown action
$_SESSION['error_message'] = "Unknown delete action.";
redirect('/instructor/index.php');
?>