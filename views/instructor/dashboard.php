<?php
/**
 * DADE Learn - Instructor Dashboard
 * Premium instructor panel with teaching overview
 */

$pageTitle = 'Instructor Dashboard';
$db = getDB();
$user = Auth::user();
$userId = $user['id'];

// Enforce email verification
if (!Auth::isVerified()) {
    require_once APP_ROOT . '/views/auth/verify-notice.php';
    exit;
}

// Get instructor stats
$stats = [
    'courses' => 0,
    'students' => 0,
    'lessons' => 0,
    'completions' => 0
];

// Total courses
$stmt = $db->prepare("SELECT COUNT(*) as count FROM courses WHERE instructor_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stats['courses'] = $stmt->get_result()->fetch_assoc()['count'];

// Total students enrolled in instructor's courses
$stmt = $db->prepare("
    SELECT COUNT(DISTINCT e.user_id) as count 
    FROM enrollments e 
    JOIN courses c ON e.course_id = c.id 
    WHERE c.instructor_id = ?
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stats['students'] = $stmt->get_result()->fetch_assoc()['count'];

// Total lessons
$stmt = $db->prepare("
    SELECT COUNT(*) as count 
    FROM lessons l 
    JOIN courses c ON l.course_id = c.id 
    WHERE c.instructor_id = ?
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stats['lessons'] = $stmt->get_result()->fetch_assoc()['count'];

// Course completions
$stmt = $db->prepare("
    SELECT COUNT(*) as count 
    FROM enrollments e 
    JOIN courses c ON e.course_id = c.id 
    WHERE c.instructor_id = ? AND e.completed = 1
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stats['completions'] = $stmt->get_result()->fetch_assoc()['count'];

// Get instructor's courses
$courses = [];
$stmt = $db->prepare("
    SELECT c.*, 
           (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id) as enrolled_count,
           (SELECT COUNT(*) FROM lessons WHERE course_id = c.id) as lesson_count
    FROM courses c
    WHERE c.instructor_id = ?
    ORDER BY c.created_at DESC
    LIMIT 5
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $courses[] = $row;
}

// Get recent enrollments
$recentEnrollments = [];
$stmt = $db->prepare("
    SELECT e.*, u.username, u.email, u.profile_picture, c.title as course_title
    FROM enrollments e
    JOIN users u ON e.user_id = u.id
    JOIN courses c ON e.course_id = c.id
    WHERE c.instructor_id = ?
    ORDER BY e.enrolled_at DESC
    LIMIT 5
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $recentEnrollments[] = $row;
}

require_once APP_ROOT . '/views/layouts/header.php';
?>

<div class="dashboard-layout">
    <!-- Sidebar -->
    <aside class="dashboard-sidebar">
        <div class="sidebar-user">
            <img src="<?php echo avatar($user, 80); ?>" alt="<?php echo e($user['username']); ?>" class="sidebar-avatar">
            <h4 class="sidebar-username"><?php echo e($user['username']); ?></h4>
            <span class="sidebar-role instructor-badge">
                <i class="fas fa-chalkboard-teacher"></i> Instructor
            </span>
        </div>
        
        <nav class="sidebar-nav">
            <a href="<?php echo url('instructor'); ?>" class="sidebar-link active">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>
            <a href="<?php echo url('instructor/courses'); ?>" class="sidebar-link">
                <i class="fas fa-book"></i>
                <span>My Courses</span>
            </a>
            <a href="<?php echo url('instructor/courses/create'); ?>" class="sidebar-link">
                <i class="fas fa-plus-circle"></i>
                <span>Create Course</span>
            </a>
            <a href="<?php echo url('instructor/students'); ?>" class="sidebar-link">
                <i class="fas fa-users"></i>
                <span>My Students</span>
            </a>
            <div class="sidebar-divider"></div>
            <a href="<?php echo url('dashboard'); ?>" class="sidebar-link">
                <i class="fas fa-home"></i>
                <span>Student Dashboard</span>
            </a>
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
                <h1>Welcome back, <?php echo e(explode(' ', $user['username'])[0]); ?>! 📚</h1>
                <p>Here's your teaching overview. Track your courses and student progress.</p>
            </div>
            <a href="<?php echo url('instructor/courses/create'); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Create New Course
            </a>
        </div>
        
        <!-- Stats Grid -->
        <section class="stats-section">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon courses">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value"><?php echo number_format($stats['courses']); ?></span>
                        <span class="stat-label">Courses</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon students">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value"><?php echo number_format($stats['students']); ?></span>
                        <span class="stat-label">Total Students</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon lessons">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value"><?php echo number_format($stats['lessons']); ?></span>
                        <span class="stat-label">Lessons</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon completions">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value"><?php echo number_format($stats['completions']); ?></span>
                        <span class="stat-label">Completions</span>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Two Column Layout -->
        <div class="dashboard-grid">
            <!-- My Courses -->
            <section class="dashboard-card">
                <div class="card-header">
                    <h2><i class="fas fa-book"></i> My Courses</h2>
                    <a href="<?php echo url('instructor/courses'); ?>" class="btn btn-sm btn-outline">View All</a>
                </div>
                
                <?php if (empty($courses)): ?>
                <div class="empty-state">
                    <i class="fas fa-book-open"></i>
                    <p>You haven't created any courses yet.</p>
                    <a href="<?php echo url('instructor/courses/create'); ?>" class="btn btn-primary btn-sm">Create Your First Course</a>
                </div>
                <?php else: ?>
                <div class="course-list">
                    <?php foreach ($courses as $course): ?>
                    <div class="course-item">
                        <?php 
                        $thumb = !empty($course['cover_image']) 
                            ? url('uploads/courses/' . $course['cover_image'])
                            : url('assets/images/course-placeholder.png');
                        ?>
                        <img src="<?php echo $thumb; ?>" alt="" class="course-thumb" onerror="this.src='<?php echo url('assets/images/course-placeholder.png'); ?>'">
                        <div class="course-info">
                            <h4><?php echo e($course['title']); ?></h4>
                            <div class="course-meta">
                                <span><i class="fas fa-users"></i> <?php echo $course['enrolled_count']; ?> students</span>
                                <span><i class="fas fa-list"></i> <?php echo $course['lesson_count']; ?> lessons</span>
                            </div>
                        </div>
                        <div class="course-status">
                            <span class="status-badge status-<?php echo $course['status']; ?>">
                                <?php echo ucfirst($course['status']); ?>
                            </span>
                        </div>
                        <div class="course-actions">
                            <a href="<?php echo url('instructor/courses/' . $course['id'] . '/edit'); ?>" class="btn btn-sm btn-ghost" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?php echo url('instructor/courses/' . $course['id'] . '/lessons'); ?>" class="btn btn-sm btn-ghost" title="Lessons">
                                <i class="fas fa-list-alt"></i>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </section>
            
            <!-- Recent Enrollments -->
            <section class="dashboard-card">
                <div class="card-header">
                    <h2><i class="fas fa-user-plus"></i> Recent Enrollments</h2>
                    <a href="<?php echo url('instructor/students'); ?>" class="btn btn-sm btn-outline">View All</a>
                </div>
                
                <?php if (empty($recentEnrollments)): ?>
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <p>No students enrolled yet.</p>
                </div>
                <?php else: ?>
                <div class="enrollment-list">
                    <?php foreach ($recentEnrollments as $enrollment): ?>
                    <div class="enrollment-item">
                        <img src="<?php echo avatar($enrollment, 40); ?>" alt="" class="enrollment-avatar">
                        <div class="enrollment-info">
                            <strong><?php echo e($enrollment['username']); ?></strong>
                            <small>Enrolled in <?php echo e($enrollment['course_title']); ?></small>
                        </div>
                        <span class="enrollment-time"><?php echo date('M d', strtotime($enrollment['enrolled_at'])); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </section>
        </div>
    </div>
</div>

<style>
/* Dashboard Layout */
.dashboard-layout {
    display: flex;
    min-height: calc(100vh - var(--header-height));
    background: var(--gray-50);
}

/* Sidebar */
.dashboard-sidebar {
    width: 280px;
    background: var(--white);
    border-right: 1px solid var(--gray-100);
    padding: var(--space-6);
    position: sticky;
    top: var(--header-height);
    height: calc(100vh - var(--header-height));
    overflow-y: auto;
}

@media (max-width: 1024px) {
    .dashboard-sidebar {
        display: none;
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
    border: 3px solid var(--primary);
    object-fit: cover;
}

.sidebar-username {
    margin-bottom: var(--space-2);
    font-size: var(--text-lg);
    color: var(--text-primary);
}

.sidebar-role {
    display: inline-flex;
    align-items: center;
    gap: var(--space-2);
    font-size: var(--text-sm);
    padding: 6px 14px;
    border-radius: 20px;
}

.instructor-badge {
    background: linear-gradient(135deg, var(--primary), #7c3aed);
    color: white;
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
    background: linear-gradient(135deg, var(--primary-50), #ede9fe);
    color: var(--primary);
}

.sidebar-link i {
    width: 20px;
    text-align: center;
}

.sidebar-divider {
    height: 1px;
    background: var(--gray-100);
    margin: var(--space-4) 0;
}

/* Main Content */
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

/* Header */
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--space-8);
    flex-wrap: wrap;
    gap: var(--space-4);
}

.welcome-text h1 {
    font-size: var(--text-2xl);
    margin-bottom: var(--space-1);
    color: var(--text-primary);
}

.welcome-text p {
    color: var(--text-muted);
}

/* Stats Grid */
.stats-section {
    margin-bottom: var(--space-8);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: var(--space-5);
}

.stat-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: var(--space-5);
    display: flex;
    align-items: center;
    gap: var(--space-4);
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    border: 1px solid var(--gray-100);
    transition: all var(--transition-fast);
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.stat-icon.courses {
    background: linear-gradient(135deg, var(--primary-50), #ede9fe);
    color: var(--primary);
}

.stat-icon.students {
    background: linear-gradient(135deg, #dbeafe, #e0e7ff);
    color: #3b82f6;
}

.stat-icon.lessons {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #d97706;
}

.stat-icon.completions {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #059669;
}

.stat-info {
    display: flex;
    flex-direction: column;
}

.stat-value {
    font-size: var(--text-2xl);
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1.2;
}

.stat-label {
    font-size: var(--text-sm);
    color: var(--text-muted);
}

/* Dashboard Grid */
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: var(--space-6);
}

@media (max-width: 768px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
}

/* Cards */
.dashboard-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: var(--space-6);
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    border: 1px solid var(--gray-100);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--space-5);
    padding-bottom: var(--space-4);
    border-bottom: 1px solid var(--gray-100);
}

.card-header h2 {
    display: flex;
    align-items: center;
    gap: var(--space-2);
    font-size: var(--text-lg);
    color: var(--text-primary);
}

.card-header h2 i {
    color: var(--primary);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: var(--space-8) var(--space-4);
    color: var(--text-muted);
}

.empty-state i {
    font-size: 48px;
    color: var(--gray-200);
    margin-bottom: var(--space-4);
}

.empty-state p {
    margin-bottom: var(--space-4);
}

/* Course List */
.course-list {
    display: flex;
    flex-direction: column;
    gap: var(--space-3);
}

.course-item {
    display: flex;
    align-items: center;
    gap: var(--space-4);
    padding: var(--space-3);
    border-radius: var(--radius-lg);
    transition: all var(--transition-fast);
}

.course-item:hover {
    background: var(--gray-50);
}

.course-thumb {
    width: 60px;
    height: 40px;
    border-radius: var(--radius-md);
    object-fit: cover;
}

.course-info {
    flex: 1;
    min-width: 0;
}

.course-info h4 {
    font-size: var(--text-base);
    margin-bottom: var(--space-1);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.course-meta {
    display: flex;
    gap: var(--space-3);
    font-size: var(--text-sm);
    color: var(--text-muted);
}

.course-meta i {
    margin-right: 4px;
}

.status-badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.status-published {
    background: #d1fae5;
    color: #059669;
}

.status-draft {
    background: #fef3c7;
    color: #d97706;
}

.status-pending {
    background: #dbeafe;
    color: #3b82f6;
}

.course-actions {
    display: flex;
    gap: var(--space-1);
}

/* Enrollment List */
.enrollment-list {
    display: flex;
    flex-direction: column;
}

.enrollment-item {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    padding: var(--space-3) 0;
    border-bottom: 1px solid var(--gray-50);
}

.enrollment-item:last-child {
    border-bottom: none;
}

.enrollment-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.enrollment-info {
    flex: 1;
}

.enrollment-info strong {
    display: block;
    font-size: var(--text-base);
}

.enrollment-info small {
    color: var(--text-muted);
    font-size: var(--text-sm);
}

.enrollment-time {
    font-size: var(--text-sm);
    color: var(--text-muted);
}

/* Buttons */
.btn-ghost {
    background: transparent;
    color: var(--text-secondary);
    padding: var(--space-2);
}

.btn-ghost:hover {
    background: var(--gray-100);
    color: var(--text-primary);
}
</style>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
