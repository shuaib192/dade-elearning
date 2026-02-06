<?php
// --- SETUP AND SECURITY ---
$allowed_roles = [1]; // Only Admins can download user data
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
// THIS IS THE NEW LIBRARY
require_once __DIR__ . '/../includes/SimpleXLSXGen.php';

use Shuchkin\SimpleXLSXGen;

// --- DATA FETCHING ---
$users_data = [];
// Add the header row first
$users_data[] = ['User ID', 'Username', 'Email', 'Role', 'Status', 'Registration Date'];

$sql = "SELECT u.id, u.username, u.email, r.role_name, u.status, u.created_at 
        FROM users u 
        JOIN roles r ON u.role_id = r.id 
        ORDER BY u.id ASC";
$result = $db->query($sql);

if ($result) {
    while($row = $result->fetch_assoc()) {
        // Add each user's data as a new row
        $users_data[] = [
            $row['id'],
            $row['username'],
            $row['email'],
            ucfirst($row['role_name']),
            ucfirst($row['status']),
            $row['created_at']
        ];
    }
}

// --- EXCEL GENERATION ---
$filename = "dade_foundation_users_" . date('Y-m-d') . ".xlsx";

// Use the library to create and download the Excel file
try {
    $xlsx = SimpleXLSXGen::fromArray($users_data);
    $xlsx->downloadAs($filename); // This handles all the complex headers for you
} catch (Exception $e) {
    // Handle error if something goes wrong
    die('Error creating Excel file: ' . $e->getMessage());
}

exit();
?>