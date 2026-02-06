<?php
/**
 * DADE Learn - Instructor Courses
 * Premium list of all courses taught by instructor
 */

$pageTitle = 'My Courses';
$db = getDB();
$user = Auth::user();
$userId = $user['id'];

// Get all instructor's courses
$courses = [];
$stmt = $db->prepare("
    SELECT c.*, 
           cat.name as category_name,
           (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id) as enrolled_count,
           (SELECT COUNT(*) FROM lessons WHERE course_id = c.id) as lesson_count,
           (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id AND completed = 1) as completion_count
    FROM courses c
    LEFT JOIN categories cat ON c.category_id = cat.id
    WHERE c.instructor_id = ?
    ORDER BY c.created_at DESC
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $courses[] = $row;
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
            <a href="<?php echo url('instructor'); ?>" class="sidebar-link">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>
            <a href="<?php echo url('instructor/courses'); ?>" class="sidebar-link active">
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
        </nav>
    </aside>
    
    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="dashboard-header">
            <div class="welcome-text">
                <h1>My Courses 📚</h1>
                <p><?php echo count($courses); ?> courses created</p>
            </div>
            <a href="<?php echo url('instructor/courses/create'); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create New Course
            </a>
        </div>
        
        <?php if ($success = flash('success')): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo e($success); ?>
        </div>
        <?php endif; ?>
        
        <?php if (empty($courses)): ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-book-open"></i>
            </div>
            <h2>No courses yet</h2>
            <p>Start creating your first course and share your knowledge with the world!</p>
            <a href="<?php echo url('instructor/courses/create'); ?>" class="btn btn-primary btn-lg">
                <i class="fas fa-plus"></i> Create Your First Course
            </a>
        </div>
        <?php else: ?>
        <div class="courses-grid">
            <?php foreach ($courses as $course): ?>
            <div class="course-card">
                <?php 
                $thumb = !empty($course['cover_image']) 
                    ? url('uploads/courses/' . $course['cover_image'])
                    : url('assets/images/course-placeholder.png');
                ?>
                <div class="course-thumb">
                    <img src="<?php echo $thumb; ?>" alt="<?php echo e($course['title']); ?>"
                         onerror="this.src='<?php echo url('assets/images/course-placeholder.png'); ?>'">
                    <span class="status-badge status-<?php echo $course['status']; ?>">
                        <?php echo ucfirst($course['status']); ?>
                    </span>
                </div>
                <div class="course-body">
                    <span class="course-category"><?php echo e($course['category_name'] ?? 'Uncategorized'); ?></span>
                    <h3><?php echo e($course['title']); ?></h3>
                    <div class="course-stats">
                        <span><i class="fas fa-users"></i> <?php echo $course['enrolled_count']; ?> students</span>
                        <span><i class="fas fa-list"></i> <?php echo $course['lesson_count']; ?> lessons</span>
                    </div>
                    <div class="course-footer">
                        <a href="<?php echo url('instructor/courses/' . $course['id'] . '/edit'); ?>" class="btn btn-sm btn-outline">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="<?php echo url('instructor/courses/' . $course['id'] . '/lessons'); ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-list-alt"></i> Lessons
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </main>
</div>

<style>
/* Dashboard Layout */
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
    position: sticky;
    top: var(--header-height);
    height: calc(100vh - var(--header-height));
    overflow-y: auto;
}

@media (max-width: 1024px) {
    .dashboard-sidebar { display: none; }
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

.sidebar-link i { width: 20px; text-align: center; }
.sidebar-divider { height: 1px; background: var(--gray-100); margin: var(--space-4) 0; }

.dashboard-main {
    flex: 1;
    padding: var(--space-8);
    max-width: 1200px;
}

@media (max-width: 768px) {
    .dashboard-main { padding: var(--space-4); }
}

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
}

.welcome-text p { color: var(--text-muted); }

/* Empty State */
.empty-state {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: var(--space-16);
    text-align: center;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    border: 1px solid var(--gray-100);
}

.empty-icon {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, var(--primary-50), #ede9fe);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--space-6);
}

.empty-icon i {
    font-size: 48px;
    color: var(--primary);
}

.empty-state h2 { margin-bottom: var(--space-2); }
.empty-state p { color: var(--text-muted); margin-bottom: var(--space-6); }

/* Courses Grid */
.courses-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: var(--space-6);
}

.course-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    border: 1px solid var(--gray-100);
    transition: all var(--transition-fast);
}

.course-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.12);
}

.course-thumb {
    position: relative;
    height: 160px;
    overflow: hidden;
}

.course-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.status-badge {
    position: absolute;
    top: var(--space-3);
    right: var(--space-3);
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-published { background: #d1fae5; color: #059669; }
.status-draft { background: #fef3c7; color: #d97706; }
.status-pending { background: #dbeafe; color: #3b82f6; }

.course-body { padding: var(--space-5); }

.course-category {
    font-size: var(--text-xs);
    color: var(--primary);
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.course-body h3 {
    margin: var(--space-2) 0 var(--space-3);
    font-size: var(--text-lg);
    line-height: 1.4;
}

.course-stats {
    display: flex;
    gap: var(--space-4);
    font-size: var(--text-sm);
    color: var(--text-muted);
    margin-bottom: var(--space-4);
    padding-bottom: var(--space-4);
    border-bottom: 1px solid var(--gray-100);
}

.course-stats i { margin-right: 4px; }

.course-footer {
    display: flex;
    gap: var(--space-2);
}

.course-footer .btn { flex: 1; justify-content: center; }

/* Alert */
.alert {
    padding: var(--space-4);
    border-radius: var(--radius-lg);
    margin-bottom: var(--space-6);
    display: flex;
    align-items: center;
    gap: var(--space-3);
}

.alert-success {
    background: #d1fae5;
    color: #059669;
    border: 1px solid #a7f3d0;
}
</style>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
