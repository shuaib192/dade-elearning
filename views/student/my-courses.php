<?php
/**
 * DADE Learn - My Courses Page
 * All enrolled courses with progress tracking
 */

$pageTitle = 'My Courses';
$db = getDB();
$user = Auth::user();
$userId = $user['id'];

// Filter
$filter = $_GET['filter'] ?? 'all';

// Get enrolled courses
$whereClause = "";
if ($filter === 'completed') {
    $whereClause = "AND e.completed = 1";
} elseif ($filter === 'in-progress') {
    $whereClause = "AND e.completed = 0";
}

$enrolledCourses = [];
$result = $db->prepare("
    SELECT c.*, e.enrolled_at, e.progress, e.completed,
           cat.name as category_name,
           u.username as instructor_name,
           (SELECT COUNT(*) FROM lessons WHERE course_id = c.id) as total_lessons,
           (SELECT COUNT(*) FROM lesson_progress lp 
            JOIN lessons l ON lp.lesson_id = l.id 
            WHERE l.course_id = c.id AND lp.user_id = ? AND lp.completed = 1) as completed_lessons
    FROM enrollments e
    JOIN courses c ON e.course_id = c.id
    LEFT JOIN categories cat ON c.category_id = cat.id
    LEFT JOIN users u ON c.instructor_id = u.id
    WHERE e.user_id = ? $whereClause
    ORDER BY e.enrolled_at DESC
");
$result->bind_param("ii", $userId, $userId);
$result->execute();
$res = $result->get_result();
while ($row = $res->fetch_assoc()) {
    $enrolledCourses[] = $row;
}

require_once APP_ROOT . '/views/layouts/header.php';
?>

<div class="dashboard-layout">
    <!-- Sidebar -->
    <aside class="dashboard-sidebar">
        <div class="sidebar-user">
            <img src="<?php echo avatar($user, 80); ?>" alt="<?php echo e($user['username']); ?>" class="sidebar-avatar">
            <h4 class="sidebar-username"><?php echo e($user['username']); ?></h4>
            <span class="sidebar-role"><?php echo ucfirst(str_replace('_', ' ', $user['role'])); ?></span>
        </div>
        
        <nav class="sidebar-nav">
            <a href="<?php echo url('dashboard'); ?>" class="sidebar-link">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="<?php echo url('dashboard/courses'); ?>" class="sidebar-link active">
                <i class="fas fa-book-open"></i>
                <span>My Courses</span>
            </a>
            <a href="<?php echo url('dashboard/certificates'); ?>" class="sidebar-link">
                <i class="fas fa-certificate"></i>
                <span>Certificates</span>
            </a>
            <a href="<?php echo url('dashboard/profile'); ?>" class="sidebar-link">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>
            <div class="sidebar-divider"></div>
            <a href="<?php echo url('courses'); ?>" class="sidebar-link">
                <i class="fas fa-search"></i>
                <span>Browse Courses</span>
            </a>
        </nav>
    </aside>
    
    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="dashboard-header">
            <div class="welcome-text">
                <h1>My Courses</h1>
                <p>Track your enrolled courses and continue learning.</p>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="filter-tabs">
            <a href="<?php echo url('dashboard/courses'); ?>" class="filter-tab <?php echo $filter === 'all' ? 'active' : ''; ?>">
                All Courses
            </a>
            <a href="<?php echo url('dashboard/courses?filter=in-progress'); ?>" class="filter-tab <?php echo $filter === 'in-progress' ? 'active' : ''; ?>">
                In Progress
            </a>
            <a href="<?php echo url('dashboard/courses?filter=completed'); ?>" class="filter-tab <?php echo $filter === 'completed' ? 'active' : ''; ?>">
                Completed
            </a>
        </div>
        
        <?php if (empty($enrolledCourses)): ?>
        <div class="empty-state-card">
            <i class="fas fa-book-reader"></i>
            <h3>
                <?php if ($filter === 'completed'): ?>
                    No completed courses yet
                <?php elseif ($filter === 'in-progress'): ?>
                    No courses in progress
                <?php else: ?>
                    No courses yet
                <?php endif; ?>
            </h3>
            <p>Start your learning journey by exploring our course catalog.</p>
            <a href="<?php echo url('courses'); ?>" class="btn btn-primary">Browse Courses</a>
        </div>
        <?php else: ?>
        <div class="courses-grid">
            <?php foreach ($enrolledCourses as $course): 
                $progress = $course['total_lessons'] > 0 
                    ? round(($course['completed_lessons'] / $course['total_lessons']) * 100) 
                    : 0;
            ?>
            <div class="my-course-card">
                <div class="my-course-thumb">
                    <?php 
                    $thumbnail = !empty($course['cover_image']) 
                        ? url('uploads/courses/' . $course['cover_image'])
                        : url('assets/images/course-placeholder.png');
                    ?>
                    <img src="<?php echo $thumbnail; ?>" alt="<?php echo e($course['title']); ?>"
                         onerror="this.src='<?php echo url('assets/images/course-placeholder.png'); ?>'">
                    <?php if ($course['completed']): ?>
                    <span class="course-badge-complete">
                        <i class="fas fa-check"></i> Completed
                    </span>
                    <?php endif; ?>
                </div>
                <div class="my-course-body">
                    <span class="my-course-cat"><?php echo e($course['category_name'] ?? 'General'); ?></span>
                    <h3 class="my-course-title"><?php echo e($course['title']); ?></h3>
                    <p class="my-course-instructor">
                        <i class="fas fa-user"></i> <?php echo e($course['instructor_name']); ?>
                    </p>
                    
                    <div class="my-course-progress">
                        <div class="progress-header">
                            <span>Progress</span>
                            <span><?php echo $progress; ?>%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill <?php echo $progress >= 100 ? 'complete' : ''; ?>" style="width: <?php echo $progress; ?>%"></div>
                        </div>
                        <span class="progress-detail">
                            <?php echo $course['completed_lessons']; ?> / <?php echo $course['total_lessons']; ?> lessons
                        </span>
                    </div>
                    
                    <a href="<?php echo url('learn/' . ($course['slug'] ?? $course['id'])); ?>" class="btn btn-primary btn-block">
                        <?php echo $course['completed'] ? 'Review Course' : ($progress > 0 ? 'Continue Learning' : 'Start Course'); ?>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </main>
</div>

<style>
.dashboard-layout {
    display: flex;
    min-height: calc(100vh - var(--header-height));
    background: var(--gray-50);
}
.dashboard-sidebar {
    width: 280px;
    background: var(--white);
    border-right: 1px solid var(--gray-100);
    padding: var(--space-6);
    display: none;
}
@media (min-width: 1024px) {
    .dashboard-sidebar {
        display: block;
    }
}
.sidebar-user {
    text-align: center;
    padding-bottom: var(--space-6);
    border-bottom: 1px solid var(--gray-100);
    margin-bottom: var(--space-6);
}
.sidebar-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    margin-bottom: var(--space-3);
    border: 3px solid var(--primary-50);
}
.sidebar-username {
    margin-bottom: var(--space-1);
    font-size: var(--text-lg);
}
.sidebar-role {
    font-size: var(--text-sm);
    color: var(--text-muted);
}
.sidebar-nav {
    display: flex;
    flex-direction: column;
    gap: var(--space-1);
}
.sidebar-link {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    padding: var(--space-3) var(--space-4);
    border-radius: var(--radius-lg);
    color: var(--text-secondary);
    font-weight: 500;
    transition: all var(--transition-fast);
}
.sidebar-link:hover {
    background: var(--gray-50);
    color: var(--text-primary);
}
.sidebar-link.active {
    background: var(--primary-50);
    color: var(--primary);
}
.sidebar-link i {
    width: 20px;
}
.sidebar-divider {
    height: 1px;
    background: var(--gray-100);
    margin: var(--space-4) 0;
}
.dashboard-main {
    flex: 1;
    padding: var(--space-8);
    max-width: 1200px;
}
.dashboard-header {
    margin-bottom: var(--space-6);
}
.welcome-text h1 {
    font-size: var(--text-2xl);
    margin-bottom: var(--space-1);
}
.welcome-text p {
    color: var(--text-secondary);
    margin: 0;
}
.filter-tabs {
    display: flex;
    gap: var(--space-2);
    margin-bottom: var(--space-6);
    flex-wrap: wrap;
}
.filter-tab {
    padding: var(--space-2) var(--space-4);
    border-radius: var(--radius-full);
    color: var(--text-secondary);
    font-weight: 500;
    background: var(--white);
    border: 1px solid var(--gray-200);
    transition: all var(--transition-fast);
}
.filter-tab:hover {
    border-color: var(--primary);
    color: var(--primary);
}
.filter-tab.active {
    background: var(--primary);
    color: var(--white);
    border-color: var(--primary);
}
.empty-state-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: var(--space-12);
    text-align: center;
    box-shadow: var(--shadow-sm);
}
.empty-state-card i {
    font-size: 48px;
    color: var(--gray-300);
    margin-bottom: var(--space-4);
}
.empty-state-card h3 {
    margin-bottom: var(--space-2);
}
.empty-state-card p {
    color: var(--text-muted);
    margin-bottom: var(--space-6);
}
.courses-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: var(--space-6);
}
.my-course-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}
.my-course-thumb {
    position: relative;
}
.my-course-thumb img {
    width: 100%;
    aspect-ratio: 16/9;
    object-fit: cover;
}
.course-badge-complete {
    position: absolute;
    top: var(--space-3);
    right: var(--space-3);
    background: var(--success);
    color: var(--white);
    padding: var(--space-1) var(--space-3);
    border-radius: var(--radius-full);
    font-size: var(--text-xs);
    font-weight: 600;
}
.my-course-body {
    padding: var(--space-5);
}
.my-course-cat {
    font-size: var(--text-xs);
    color: var(--primary);
    font-weight: 600;
    text-transform: uppercase;
}
.my-course-title {
    font-size: var(--text-lg);
    margin: var(--space-2) 0;
}
.my-course-instructor {
    font-size: var(--text-sm);
    color: var(--text-muted);
    margin-bottom: var(--space-4);
}
.my-course-progress {
    margin-bottom: var(--space-4);
}
.progress-header {
    display: flex;
    justify-content: space-between;
    font-size: var(--text-sm);
    margin-bottom: var(--space-2);
}
.progress-bar {
    height: 8px;
    background: var(--gray-200);
    border-radius: var(--radius-full);
    overflow: hidden;
}
.progress-fill {
    height: 100%;
    background: var(--primary);
    border-radius: var(--radius-full);
}
.progress-fill.complete {
    background: var(--success);
}
.progress-detail {
    font-size: var(--text-xs);
    color: var(--text-muted);
    margin-top: var(--space-1);
    display: block;
}
</style>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
