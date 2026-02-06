<?php
// --- SETUP AND SECURITY ---
$page_title = 'Site Analytics';
$allowed_roles = [1]; // Only Admins can access this page
require_once __DIR__ . '/../includes/auth_check.php';

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// --- DATA FETCHING FOR ANALYTICS ---

// Simple counts for totals
$total_users = $db->query("SELECT COUNT(id) as count FROM users")->fetch_assoc()['count'];
$total_students = $db->query("SELECT COUNT(id) as count FROM users WHERE role_id = 3")->fetch_assoc()['count'];
$total_instructors = $db->query("SELECT COUNT(id) as count FROM users WHERE role_id = 2")->fetch_assoc()['count'];
$total_courses = $db->query("SELECT COUNT(id) as count FROM courses")->fetch_assoc()['count'];
$total_enrollments = $db->query("SELECT COUNT(id) as count FROM enrollments")->fetch_assoc()['count'];
$total_course_completions = $db->query("SELECT COUNT(id) as count FROM certificates")->fetch_assoc()['count'];
$total_forum_threads = $db->query("SELECT COUNT(id) as count FROM forum_threads")->fetch_assoc()['count'];
$total_forum_replies = $db->query("SELECT COUNT(id) as count FROM forum_replies")->fetch_assoc()['count'];

// More complex queries for popular courses
$most_enrolled_courses = [];
$enrolled_result = $db->query("
    SELECT c.title, COUNT(e.id) as enrollment_count
    FROM enrollments e
    JOIN courses c ON e.course_id = c.id
    GROUP BY c.id
    ORDER BY enrollment_count DESC
    LIMIT 5
");
if ($enrolled_result) {
    while ($row = $enrolled_result->fetch_assoc()) {
        $most_enrolled_courses[] = $row;
    }
}

$highest_rated_courses = [];
$rated_result = $db->query("
    SELECT c.title, AVG(cr.rating) as avg_rating
    FROM course_ratings cr
    JOIN courses c ON cr.course_id = c.id
    GROUP BY c.id
    ORDER BY avg_rating DESC
    LIMIT 5
");
if ($rated_result) {
    while ($row = $rated_result->fetch_assoc()) {
        $highest_rated_courses[] = $row;
    }
}

// --- PAGE DISPLAY ---
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1>Site-Wide Analytics</h1>
        <a href="index.php" class="button button-secondary">Back to Admin Dashboard</a>
    </div>
    <p>An overview of platform activity and key metrics.</p>

    <!-- Stat Cards Section -->
    <div class="analytics-grid">
        <div class="stat-card">
            <div class="stat-value"><?php echo $total_users; ?></div>
            <div class="stat-label">Total Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?php echo $total_students; ?></div>
            <div class="stat-label">Students</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?php echo $total_instructors; ?></div>
            <div class="stat-label">Instructors</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?php echo $total_courses; ?></div>
            <div class="stat-label">Total Courses</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?php echo $total_enrollments; ?></div>
            <div class="stat-label">Total Enrollments</div>
        </div>
         <div class="stat-card">
            <div class="stat-value"><?php echo $total_course_completions; ?></div>
            <div class="stat-label">Course Completions</div>
        </div>
    </div>
    
    <hr>

    <div class="analytics-columns">
        <!-- Most Popular Courses -->
        <div class="management-section">
            <h2>Most Popular Courses (By Enrollment)</h2>
            <?php if (empty($most_enrolled_courses)): ?>
                <p>No enrollment data available yet.</p>
            <?php else: ?>
                <ol class="analytics-list">
                    <?php foreach ($most_enrolled_courses as $course): ?>
                        <li>
                            <span><?php echo e($course['title']); ?></span>
                            <span class="list-value"><?php echo e($course['enrollment_count']); ?> Enrollments</span>
                        </li>
                    <?php endforeach; ?>
                </ol>
            <?php endif; ?>
        </div>

        <!-- Highest Rated Courses -->
        <div class="management-section">
            <h2>Highest-Rated Courses</h2>
             <?php if (empty($highest_rated_courses)): ?>
                <p>No rating data available yet.</p>
            <?php else: ?>
                <ol class="analytics-list">
                    <?php foreach ($highest_rated_courses as $course): ?>
                        <li>
                            <span><?php echo e($course['title']); ?></span>
                            <span class="list-value">
                                <div class="stars-display" data-rating="<?php echo $course['avg_rating']; ?>"></div>
                                (<?php echo round($course['avg_rating'], 1); ?>)
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ol>
            <?php endif; ?>
        </div>
    </div>

     <div class="management-section">
        <h2>Forum Activity</h2>
        <div class="analytics-grid forum-stats">
             <div class="stat-card">
                <div class="stat-value"><?php echo $total_forum_threads; ?></div>
                <div class="stat-label">Total Threads Started</div>
            </div>
             <div class="stat-card">
                <div class="stat-value"><?php echo $total_forum_replies; ?></div>
                <div class="stat-label">Total Replies Posted</div>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>