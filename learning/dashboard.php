<?php
/**
 * DADE Elearning - Central Dashboard Router
 * FINAL, COMPLETE, AND CORRECTED VERSION
 * This file's only job is to check a logged-in user's role
 * and redirect them to the correct dashboard (admin, instructor, or student)
 * using the correct paths for a live server.
 */

// We must start the session to check the user's login status and role.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the helper functions and database config.
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

// --- SECURITY CHECK ---
// If the 'user_id' is not set in the session, it means the user is not logged in.
// In this case, we send them to the login page immediately.
if (!isset($_SESSION['user_id'])) {
    redirect('/login.php');
}


// --- ROLE-BASED REDIRECTION ---
// Get the logged-in user's role ID from the session variable we created during login.
// CRITICAL FIX: Do NOT default to 3. If role_id is missing, the session is invalid -> Logout.
$role_id = $_SESSION['role_id'] ?? null; 

if ($role_id === null) {
    redirect('/logout.php');
}

// Use a switch statement to check the role_id and redirect to the correct dashboard.
// These are root-relative paths (starting with /) which are correct for your live server.
switch ($role_id) {
    case 1:
        // Role ID 1 is the Administrator.
        redirect('/admin/index.php');
        break;
    
    case 2:
        // Role ID 2 is the Instructor.
        redirect('/instructor/index.php');
        break;
    
    case 3:
        // Role ID 3 is the Student.
        redirect('/student/index.php');
        break;
    
    default:
        // If for any reason the role_id is something else, it's a security risk.
        // The safest action is to log the user out and send them to the login page.
        redirect('/logout.php');
        break;
}
?>