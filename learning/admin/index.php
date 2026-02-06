<?php
$page_title = 'Admin Dashboard';

// This page should only be accessible by admins.
$allowed_roles = [1]; 
require_once __DIR__ . '/../includes/auth_check.php';

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1>Admin Dashboard</h1>
        <!-- CORRECTED LINK -->
        <a href="<?php echo $base; ?>/instructor/create_course.php" class="button button-primary">Create New Course</a>
    </div>
    <p>Welcome, Administrator <?php echo e($_SESSION['username']); ?>! Use the tools below to manage the platform.</p>

    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h3>Manage Users</h3>
            <p>View, edit roles, and change the status of all users on the platform.</p>
            <a href="manage_users.php" class="button button-secondary">Go to User Management</a>
        </div>
        <div class="dashboard-card">
            <h3>Manage Courses</h3>
            <p>Review, approve, or feature courses submitted by instructors.</p>
            <a href="manage_courses.php" class="button button-secondary">Go to Course Management</a>
        </div>
        <div class="dashboard-card">
            <h3>Site Analytics</h3>
            <p>View reports on user activity, course enrollment, and AI usage.</p>
            <a href="site_analytics.php" class="button button-secondary">View Analytics</a>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>