<?php
/**
 * DADE Learn - Student Dashboard
 * Main student dashboard with overview and enrolled courses
 */

$pageTitle = 'My Dashboard';
$db = getDB();
$user = Auth::user();
$userId = $user['id'];

// Get enrolled courses with progress
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
    WHERE e.user_id = ?
    ORDER BY e.enrolled_at DESC
    LIMIT 6
");
$result->bind_param("ii", $userId, $userId);
$result->execute();
$res = $result->get_result();
while ($row = $res->fetch_assoc()) {
    $enrolledCourses[] = $row;
}

// Get user stats
$stats = [
    'enrolled' => 0,
    'completed' => 0,
    'certificates' => 0,
    'learning_hours' => 0
];

$stmt = $db->prepare("SELECT COUNT(*) as count FROM enrollments WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stats['enrolled'] = $stmt->get_result()->fetch_assoc()['count'];

$stmt = $db->prepare("SELECT COUNT(*) as count FROM enrollments WHERE user_id = ? AND completed = 1");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stats['completed'] = $stmt->get_result()->fetch_assoc()['count'];

$stmt = $db->prepare("SELECT COUNT(*) as count FROM certificates WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stats['certificates'] = $stmt->get_result()->fetch_assoc()['count'];

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
            <a href="<?php echo url('dashboard'); ?>" class="sidebar-link active">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="<?php echo url('dashboard/courses'); ?>" class="sidebar-link">
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
            <?php if (Auth::hasRole('mentor')): ?>
            <a href="<?php echo url('instructor'); ?>" class="sidebar-link">
                <i class="fas fa-chalkboard-teacher"></i>
                <span>Instructor Panel</span>
            </a>
            <?php endif; ?>
            <?php if (Auth::isAdmin()): ?>
            <a href="<?php echo url('admin'); ?>" class="sidebar-link">
                <i class="fas fa-cog"></i>
                <span>Admin Panel</span>
            </a>
            <?php endif; ?>
        </nav>
    </aside>
    
    <!-- Main Content -->
    <div class="dashboard-main">
        <!-- Welcome Header -->
        <div class="dashboard-header">
            <div class="welcome-text">
                <h1>Welcome back, <?php echo e(explode(' ', $user['username'])[0]); ?>! 👋</h1>
                <p>Track your progress and continue learning from where you left off.</p>
            </div>
            <a href="<?php echo url('courses'); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Browse Courses
            </a>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-row">
            <div class="stat-item">
                <div class="stat-icon" style="background: var(--primary-50); color: var(--primary);">
                    <i class="fas fa-book-open"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo $stats['enrolled']; ?></span>
                    <span class="stat-label">Enrolled Courses</span>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo $stats['completed']; ?></span>
                    <span class="stat-label">Completed</span>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--accent);">
                    <i class="fas fa-certificate"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo $stats['certificates']; ?></span>
                    <span class="stat-label">Certificates</span>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo $stats['learning_hours']; ?>h</span>
                    <span class="stat-label">Learning Time</span>
                </div>
            </div>
        </div>
        
        <!-- Continue Learning -->
        <section class="dashboard-section">
            <div class="section-header-row">
                <h2>Continue Learning</h2>
                <a href="<?php echo url('dashboard/courses'); ?>" class="link-more">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            <?php if (empty($enrolledCourses)): ?>
            <div class="empty-state-card">
                <i class="fas fa-book-reader"></i>
                <h3>No courses yet</h3>
                <p>Start your learning journey by exploring our course catalog.</p>
                <a href="<?php echo url('courses'); ?>" class="btn btn-primary">Browse Courses</a>
            </div>
            <?php else: ?>
            <div class="course-list">
                <?php foreach ($enrolledCourses as $course): 
                    $progress = $course['total_lessons'] > 0 
                        ? round(($course['completed_lessons'] / $course['total_lessons']) * 100) 
                        : 0;
                ?>
                <div class="course-list-item">
                    <div class="course-list-thumb">
                        <?php 
                        $thumbnail = !empty($course['cover_image']) 
                            ? url('uploads/courses/' . $course['cover_image'])
                            : url('assets/images/course-placeholder.png');
                        ?>
                        <img src="<?php echo $thumbnail; ?>" alt="<?php echo e($course['title']); ?>"
                             onerror="this.src='<?php echo url('assets/images/course-placeholder.png'); ?>'">
                    </div>
                    <div class="course-list-info">
                        <span class="course-list-category"><?php echo e($course['category_name'] ?? 'General'); ?></span>
                        <h4 class="course-list-title">
                            <a href="<?php echo url('learn/' . ($course['slug'] ?? $course['id'])); ?>">
                                <?php echo e($course['title']); ?>
                            </a>
                        </h4>
                        <div class="course-list-progress">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?php echo $progress; ?>%"></div>
                            </div>
                            <span class="progress-text"><?php echo $progress; ?>% complete</span>
                        </div>
                    </div>
                    <div class="course-list-action">
                        <a href="<?php echo url('learn/' . ($course['slug'] ?? $course['id'])); ?>" class="btn btn-sm btn-primary">
                            <?php echo $progress > 0 ? 'Continue' : 'Start'; ?>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </section>
        
        <!-- Recommended Courses -->
        <section class="dashboard-section">
            <div class="section-header-row">
                <h2>Recommended for You</h2>
                <a href="<?php echo url('courses'); ?>" class="link-more">
                    Explore <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            <div class="recommended-grid">
                <?php
                // Get recommended courses (popular courses not enrolled in)
                $recommended = $db->query("
                    SELECT c.*, cat.name as category_name, u.username as instructor_name,
                           (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id) as enrolled_count
                    FROM courses c
                    LEFT JOIN categories cat ON c.category_id = cat.id
                    LEFT JOIN users u ON c.instructor_id = u.id
                    WHERE c.status = 'published'
                    AND c.id NOT IN (SELECT course_id FROM enrollments WHERE user_id = $userId)
                    ORDER BY enrolled_count DESC
                    LIMIT 3
                ");
                
                if ($recommended->num_rows === 0):
                ?>
                <p class="text-muted">Check back later for personalized recommendations!</p>
                <?php else:
                while ($course = $recommended->fetch_assoc()):
                ?>
                <div class="recommended-card">
                    <?php 
                    $thumbnail = !empty($course['cover_image']) 
                        ? url('uploads/courses/' . $course['cover_image'])
                        : url('assets/images/course-placeholder.png');
                    ?>
                    <img src="<?php echo $thumbnail; ?>" alt="<?php echo e($course['title']); ?>" class="recommended-thumb"
                         onerror="this.src='<?php echo url('assets/images/course-placeholder.png'); ?>'">
                    <div class="recommended-info">
                        <span class="recommended-cat"><?php echo e($course['category_name'] ?? 'General'); ?></span>
                        <h4><a href="<?php echo url('course/' . ($course['slug'] ?? $course['id'])); ?>"><?php echo e($course['title']); ?></a></h4>
                        <p class="recommended-instructor">
                            <i class="fas fa-user"></i> <?php echo e($course['instructor_name']); ?>
                        </p>
                    </div>
                </div>
                <?php endwhile; endif; ?>
            </div>
        </section>
    </div>
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
    text-transform: capitalize;
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
@media (max-width: 768px) {
    .dashboard-main {
        padding: var(--space-4);
    }
}
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: var(--space-4);
    margin-bottom: var(--space-8);
}
.welcome-text h1 {
    font-size: var(--text-2xl);
    margin-bottom: var(--space-1);
}
.welcome-text p {
    color: var(--text-secondary);
    margin: 0;
}
.stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--space-5);
    margin-bottom: var(--space-10);
}
.stat-item {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: var(--space-5);
    display: flex;
    align-items: center;
    gap: var(--space-4);
    box-shadow: var(--shadow-sm);
}
.stat-icon {
    width: 52px;
    height: 52px;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: var(--text-xl);
}
.stat-info {
    display: flex;
    flex-direction: column;
}
.stat-value {
    font-size: var(--text-2xl);
    font-weight: 700;
    color: var(--text-primary);
}
.stat-label {
    font-size: var(--text-sm);
    color: var(--text-muted);
}
.dashboard-section {
    margin-bottom: var(--space-10);
}
.section-header-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--space-5);
}
.section-header-row h2 {
    font-size: var(--text-xl);
    margin: 0;
}
.link-more {
    color: var(--primary);
    font-weight: 500;
    font-size: var(--text-sm);
    display: flex;
    align-items: center;
    gap: var(--space-2);
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
.course-list {
    background: var(--white);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}
.course-list-item {
    display: flex;
    align-items: center;
    gap: var(--space-4);
    padding: var(--space-4);
    border-bottom: 1px solid var(--gray-100);
}
.course-list-item:last-child {
    border-bottom: none;
}
.course-list-thumb {
    width: 120px;
    flex-shrink: 0;
}
.course-list-thumb img {
    width: 100%;
    aspect-ratio: 16/10;
    object-fit: cover;
    border-radius: var(--radius-md);
}
.course-list-info {
    flex: 1;
    min-width: 0;
}
.course-list-category {
    font-size: var(--text-xs);
    color: var(--primary);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.course-list-title {
    margin: var(--space-1) 0 var(--space-2);
    font-size: var(--text-base);
}
.course-list-title a {
    color: var(--text-primary);
}
.course-list-title a:hover {
    color: var(--primary);
}
.course-list-progress {
    display: flex;
    align-items: center;
    gap: var(--space-3);
}
.progress-bar {
    flex: 1;
    height: 6px;
    background: var(--gray-200);
    border-radius: var(--radius-full);
    overflow: hidden;
}
.progress-fill {
    height: 100%;
    background: var(--primary);
    border-radius: var(--radius-full);
    transition: width 0.3s ease;
}
.progress-text {
    font-size: var(--text-xs);
    color: var(--text-muted);
    white-space: nowrap;
}
.course-list-action {
    flex-shrink: 0;
}
.recommended-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: var(--space-5);
}
.recommended-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: transform var(--transition-fast), box-shadow var(--transition-fast);
}
.recommended-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
}
.recommended-thumb {
    width: 100%;
    aspect-ratio: 16/9;
    object-fit: cover;
}
.recommended-info {
    padding: var(--space-4);
}
.recommended-cat {
    font-size: var(--text-xs);
    color: var(--primary);
    font-weight: 600;
    text-transform: uppercase;
}
.recommended-info h4 {
    margin: var(--space-2) 0;
    font-size: var(--text-base);
}
.recommended-info h4 a {
    color: var(--text-primary);
}
.recommended-info h4 a:hover {
    color: var(--primary);
}
.recommended-instructor {
    font-size: var(--text-sm);
    color: var(--text-muted);
    margin: 0;
    display: flex;
    align-items: center;
    gap: var(--space-2);
}
@media (max-width: 768px) {
    .course-list-item {
        flex-direction: column;
        align-items: stretch;
        gap: var(--space-3);
        padding: var(--space-4);
    }
    .course-list-thumb {
        width: 100%;
        margin-bottom: var(--space-2);
    }
    .course-list-thumb img {
        aspect-ratio: 16/9;
        width: 100%;
    }
    .course-list-info {
        margin-bottom: var(--space-3);
    }
    .course-list-action .btn {
        width: 100%;
    }
    .stats-row {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--space-3);
    }
    .stat-item {
        padding: var(--space-3);
        flex-direction: column;
        text-align: center;
        gap: var(--space-2);
    }
    .stat-icon {
        width: 40px;
        height: 40px;
        font-size: var(--text-base);
    }
    .stat-value {
        font-size: var(--text-lg);
    }
    .dashboard-header {
        text-align: center;
        flex-direction: column;
        margin-bottom: var(--space-6);
    }
    .dashboard-header h1 {
        font-size: var(--text-xl);
    }
    .dashboard-header .btn {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .stats-row {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
