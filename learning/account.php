<?php
/**
 * DADE Elearning - Account Router
 *
 * This file acts as a simple, clean link for the navigation menu.
 * Its only job is to redirect a logged-in user to their personal profile page.
 */

// We must start the session to access the user's ID.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the functions file to get the redirect() helper.
require_once __DIR__ . '/includes/functions.php';

// First, check if a user is logged in. If not, they can't have a profile page.
if (!isset($_SESSION['user_id'])) {
    // Set an error message and send them to the login page.
    $_SESSION['error_message'] = "You must be logged in to view your profile.";
    redirect('/login.php');
}

// If they are logged in, get their ID from the session...
$user_id = $_SESSION['user_id'];

// ...and redirect them to the correct profile page URL.
redirect("/profile.php?id=" . $user_id);
?>