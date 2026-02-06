<?php
// Start the session to access it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the helper functions and database config.
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

// 1. Unset all of the session variables
$_SESSION = [];

// 2. Destroy the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Finally, destroy the session
session_destroy();

// 4. Redirect to the login page with a logged-out message (optional but good UX)
session_start(); // Start a new, clean session for the message
$_SESSION['success_message'] = "You have been logged out successfully.";
redirect('/logins.php');
?>