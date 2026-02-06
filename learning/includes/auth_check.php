<?php
/**
 * DADE Elearning - Authentication & Role Check (Improved)
 *
 * This script checks if a user is logged in.
 * If an array named $allowed_roles is defined BEFORE this script is included,
 * it will ALSO check if the user's role is in that array.
 * If $allowed_roles is NOT defined, it only checks for a valid login.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

// 1. Check if the user is logged in at all. This check always runs.
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    $_SESSION['error_message'] = 'You must be logged in to view that page.';
    redirect('/logins.php');
}

// 2. Check for specific roles ONLY if the $allowed_roles array is set by the calling page.
if (isset($allowed_roles) && is_array($allowed_roles)) {
    if (!in_array($_SESSION['role_id'], $allowed_roles)) {
        // User is logged in but has the wrong role.
        $_SESSION['error_message'] = 'You do not have permission to access that page.';
        redirect('/dashboard.php');
    }
}
// If $allowed_roles is not set, we do nothing and the script finishes, allowing access.
?>