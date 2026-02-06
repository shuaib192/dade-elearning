<?php
// --- SETUP & SECURITY ---
// We start the session first to access session variables.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Now, we define the role(s) allowed to perform this action.
// Only students should be able to enroll in courses.
$allowed_roles = [3];

// Now we run the authentication check. It will use the $allowed_roles array we just defined.
require_once __DIR__ . '/includes/auth_check.php';

// Include other necessary files
require_once __DIR__ . '/config/database.php';
// functions.php is already included by auth_check.php

// --- ENROLLMENT PROCESS ---
// Validate the course ID from the URL
if (!isset($_GET['course_id']) || !filter_var($_GET['course_id'], FILTER_VALIDATE_INT)) {
    $_SESSION['error_message'] = "Invalid enrollment link.";
    redirect('/courses.php');
}
$course_id = $_GET['course_id'];
$student_id = $_SESSION['user_id'];

// First, check if the user is already enrolled to prevent duplicate entries
$check_stmt = $db->prepare("SELECT id FROM enrollments WHERE student_id = ? AND course_id = ?");
$check_stmt->bind_param("ii", $student_id, $course_id);
$check_stmt->execute();
$is_enrolled = $check_stmt->get_result()->num_rows > 0;
$check_stmt->close();

if ($is_enrolled) {
    // If they are already enrolled for some reason, just confirm it.
    $_SESSION['success_message'] = "You are already enrolled in this course.";
} else {
    // Not enrolled, so proceed with inserting into the enrollments table.
    $enroll_stmt = $db->prepare("INSERT INTO enrollments (student_id, course_id) VALUES (?, ?)");
    $enroll_stmt->bind_param("ii", $student_id, $course_id);

    if ($enroll_stmt->execute()) {
        $_SESSION['success_message'] = "You have successfully enrolled in the course!";
    } else {
        $_SESSION['error_message'] = "There was an error enrolling you in the course. Please try again.";
    }
    $enroll_stmt->close();
}

// After processing, always redirect back to the course page.
// The view_course.php page will then display the appropriate session message.
redirect('/view_course.php?id=' . $course_id);
?>