<?php
/**
 * DADE Elearning - Core Functions
 * This file contains essential helper functions for security, validation, and utility.
 */

// Ensure BASE_URL and database connection are available globally
require_once __DIR__ . '/../config/database.php';


/**
 * Escapes HTML output to prevent XSS (Cross-Site Scripting) attacks.
 * A crucial security function.
 *
 * @param string|null $string The string to escape.
 * @return string The escaped string.
 */
function e(?string $string): string {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Redirects the user to a different page and stops script execution.
 * Automatically prepends BASE_URL for root-relative paths.
 *
 * @param string $location The path to redirect to (e.g., '/login.php').
 * @return void
 */
function redirect(string $location): void {
    // If path starts with /, prepend BASE_URL for local development
    if (strpos($location, '/') === 0 && defined('BASE_URL') && BASE_URL !== '') {
        $location = BASE_URL . $location;
    }
    header("Location: {$location}");
    exit();
}

/**
 * Awards a badge to a user based on a trigger event.
 * Checks if the user already has the badge to prevent duplicates.
 *
 * @param int $user_id The ID of the user.
 * @param string $event_name The trigger event (e.g., 'REGISTER').
 * @param mysqli $db The database connection object.
 * @return void
 */
function award_badge(int $user_id, string $event_name, mysqli $db): void {
    if (empty($user_id) || empty($event_name) || !$db) {
        return;
    }

    $badge_stmt = $db->prepare("SELECT id FROM badges WHERE trigger_event = ?");
    $badge_stmt->bind_param("s", $event_name);
    $badge_stmt->execute();
    $badge_result = $badge_stmt->get_result();
    
    if ($badge_result->num_rows > 0) {
        $badge_id = $badge_result->fetch_assoc()['id'];
        
        $check_stmt = $db->prepare("SELECT id FROM user_badges WHERE user_id = ? AND badge_id = ?");
        $check_stmt->bind_param("ii", $user_id, $badge_id);
        $check_stmt->execute();
        
        if ($check_stmt->get_result()->num_rows === 0) {
            $insert_stmt = $db->prepare("INSERT INTO user_badges (user_id, badge_id) VALUES (?, ?)");
            $insert_stmt->bind_param("ii", $user_id, $badge_id);
            $insert_stmt->execute();
            $insert_stmt->close();
            
            $_SESSION['new_badge_id'] = $badge_id;
        }
        $check_stmt->close();
    }
    $badge_stmt->close();
}

/**
 * Generates initials from a username for avatar display.
 * Returns first letter, or first and last letter if there's a space.
 *
 * @param string $username The username to extract initials from.
 * @return string The initials (1-2 characters).
 */
function get_initials(string $username): string {
    $username = trim($username);
    if (empty($username)) {
        return '?';
    }
    
    $parts = explode(' ', $username);
    if (count($parts) >= 2) {
        return strtoupper(substr($parts[0], 0, 1) . substr(end($parts), 0, 1));
    }
    
    return strtoupper(substr($username, 0, 1));
}

/**
 * Gets a consistent color class based on username for avatar.
 *
 * @param string $username The username.
 * @return string The color class (color-1 to color-5).
 */
function get_avatar_color(string $username): string {
    $hash = ord(strtolower(substr($username, 0, 1)));
    $colorNum = ($hash % 5) + 1;
    return 'color-' . $colorNum;
}

/**
 * Renders an avatar - either image or initials fallback.
 *
 * @param string|null $profile_picture The profile picture path or null.
 * @param string $username The username for initials.
 * @param string $size Size class: 'small', 'medium', or 'large'.
 * @return string HTML for the avatar.
 */
function render_avatar(?string $profile_picture, string $username, string $size = 'medium'): string {
    if (!empty($profile_picture) && $profile_picture !== '/assets/images/default_avatar.png') {
        return '<img src="' . e($profile_picture) . '" alt="Profile picture of ' . e($username) . '" class="avatar-img ' . $size . '">';
    }
    
    $initials = get_initials($username);
    $color = get_avatar_color($username);
    return '<div class="avatar-initials ' . $size . ' ' . $color . '">' . e($initials) . '</div>';
}
?>
