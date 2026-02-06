<?php
// --- DATABASE UPDATE SCRIPT ---
// This is a temporary file to update the database schema.
// After running it ONCE, you should DELETE IT from your server for security.

echo "<h1>Database Update Script</h1>";

// Include the database connection
require_once __DIR__ . '/config/database.php';

if ($db->connect_error) {
    die("<p style='color:red;'><strong>Database Connection Failed:</strong> " . $db->connect_error . "</p>");
}
echo "<p style='color:green;'><strong>Database Connection Successful.</strong></p>";

// --- SQL COMMANDS TO RUN ---
$queries = [
    "ALTER TABLE `courses` ADD `is_certificate_course` BOOLEAN NOT NULL DEFAULT FALSE AFTER `status`",
    "ALTER TABLE `courses` ADD `final_quiz_lesson_id` INT(11) NULL DEFAULT NULL AFTER `is_certificate_course`",
    "ALTER TABLE `courses` ADD `passing_grade` INT(3) NOT NULL DEFAULT 75 AFTER `final_quiz_lesson_id`"
];

echo "<h3>Attempting to run " . count($queries) . " updates...</h3>";
echo "<ol>";

foreach ($queries as $query) {
    echo "<li><p><strong>Executing:</strong> <code>" . htmlspecialchars($query) . "</code></p>";
    if ($db->query($query)) {
        echo "<p style='color:green;'><strong>Success!</strong> The database was updated.</p></li>";
    } else {
        // Check for "Duplicate column name" error, which means it's already been run.
        if ($db->errno === 1060) {
            echo "<p style='color:orange;'><strong>Warning:</strong> This update has already been applied (Duplicate column name). Skipping.</p></li>";
        } else {
            echo "<p style='color:red;'><strong>Error:</strong> " . $db->error . "</p></li>";
        }
    }
}

echo "</ol>";
echo "<h2>Update process complete. You should now DELETE this file (update_database.php) from your server.</h2>";

$db->close();
?>