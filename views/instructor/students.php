<?php
/**
 * DADE Learn - Instructor Students
 * Premium view of enrolled students
 */

$pageTitle = 'My Students';
$db = getDB();
$user = Auth::user();
$userId = $user['id'];

// Get all students enrolled in instructor's courses
$students = [];
$stmt = $db->prepare("
    SELECT DISTINCT u.id, u.username, u.email, u.profile_picture, u.created_at,
           COUNT(DISTINCT e.course_id) as courses_enrolled,
           SUM(CASE WHEN e.completed = 1 THEN 1 ELSE 0 END) as courses_completed
    FROM users u
    JOIN enrollments e ON u.id = e.user_id
    JOIN courses c ON e.course_id = c.id
    WHERE c.instructor_id = ?
    GROUP BY u.id
    ORDER BY u.username ASC
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $students[] = $row;
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
            <a href="<?php echo url('instructor/courses'); ?>" class="sidebar-link">
                <i class="fas fa-book"></i>
                <span>My Courses</span>
            </a>
            <a href="<?php echo url('instructor/courses/create'); ?>" class="sidebar-link">
                <i class="fas fa-plus-circle"></i>
                <span>Create Course</span>
            </a>
            <a href="<?php echo url('instructor/students'); ?>" class="sidebar-link active">
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
                <h1>👥 My Students</h1>
                <p><?php echo count($students); ?> students enrolled in your courses</p>
            </div>
        </div>
        
        <?php if (empty($students)): ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-users"></i>
            </div>
            <h2>No students yet</h2>
            <p>Once students enroll in your courses, they'll appear here.</p>
            <a href="<?php echo url('instructor/courses'); ?>" class="btn btn-primary">
                <i class="fas fa-book"></i> View My Courses
            </a>
        </div>
        <?php else: ?>
        <div class="students-grid">
            <?php foreach ($students as $student): ?>
            <div class="student-card">
                <img src="<?php echo avatar($student, 60); ?>" alt="" class="student-avatar">
                <div class="student-info">
                    <h4><?php echo e($student['username']); ?></h4>
                    <p><?php echo e($student['email']); ?></p>
                </div>
                <div class="student-stats">
                    <div class="stat">
                        <span class="stat-value"><?php echo $student['courses_enrolled']; ?></span>
                        <span class="stat-label">Enrolled</span>
                    </div>
                    <div class="stat">
                        <span class="stat-value"><?php echo $student['courses_completed']; ?></span>
                        <span class="stat-label">Completed</span>
                    </div>
                </div>
                <span class="join-date">Joined <?php echo date('M Y', strtotime($student['created_at'])); ?></span>
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

.sidebar-username { margin-bottom: var(--space-2); font-size: var(--text-lg); }

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

.sidebar-nav { display: flex; flex-direction: column; gap: var(--space-1); }

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

.sidebar-link:hover { background: var(--gray-50); color: var(--text-primary); }
.sidebar-link.active { background: linear-gradient(135deg, var(--primary-50), #ede9fe); color: var(--primary); }
.sidebar-link i { width: 20px; text-align: center; }
.sidebar-divider { height: 1px; background: var(--gray-100); margin: var(--space-4) 0; }

.dashboard-main {
    flex: 1;
    padding: var(--space-8);
    max-width: 1200px;
}

.dashboard-header {
    margin-bottom: var(--space-8);
}

.welcome-text h1 { font-size: var(--text-2xl); margin-bottom: var(--space-1); }
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

.empty-icon i { font-size: 48px; color: var(--primary); }
.empty-state h2 { margin-bottom: var(--space-2); }
.empty-state p { color: var(--text-muted); margin-bottom: var(--space-6); }

/* Students Grid */
.students-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: var(--space-5);
}

.student-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: var(--space-5);
    text-align: center;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    border: 1px solid var(--gray-100);
    transition: all var(--transition-fast);
}

.student-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.1);
}

.student-avatar {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: var(--space-3);
    border: 3px solid var(--primary-50);
}

.student-info h4 {
    font-size: var(--text-lg);
    margin-bottom: var(--space-1);
}

.student-info p {
    color: var(--text-muted);
    font-size: var(--text-sm);
    margin-bottom: var(--space-4);
}

.student-stats {
    display: flex;
    justify-content: center;
    gap: var(--space-6);
    padding: var(--space-4) 0;
    border-top: 1px solid var(--gray-100);
    border-bottom: 1px solid var(--gray-100);
    margin-bottom: var(--space-3);
}

.student-stats .stat { text-align: center; }

.student-stats .stat-value {
    display: block;
    font-size: var(--text-xl);
    font-weight: 700;
    color: var(--primary);
}

.student-stats .stat-label {
    font-size: var(--text-xs);
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.join-date {
    font-size: var(--text-sm);
    color: var(--text-muted);
}
</style>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
