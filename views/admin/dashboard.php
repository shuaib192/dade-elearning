<?php
/**
 * DADE Learn - Admin Dashboard
 * Premium admin panel with platform overview
 */

$pageTitle = 'Admin Dashboard';
$db = getDB();
$user = Auth::user();

// Get platform stats
$stats = [
    'users' => 0,
    'courses' => 0,
    'enrollments' => 0,
    'completions' => 0
];

$stats['users'] = $db->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$stats['courses'] = $db->query("SELECT COUNT(*) as count FROM courses")->fetch_assoc()['count'];
$stats['enrollments'] = $db->query("SELECT COUNT(*) as count FROM enrollments")->fetch_assoc()['count'];
$stats['completions'] = $db->query("SELECT COUNT(*) as count FROM enrollments WHERE completed = 1")->fetch_assoc()['count'];

// Role breakdown
$roleStats = [];
$result = $db->query("SELECT role, COUNT(*) as count FROM users GROUP BY role");
while ($row = $result->fetch_assoc()) {
    $roleStats[$row['role']] = $row['count'];
}

// Recent users
$recentUsers = [];
$result = $db->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 5");
while ($row = $result->fetch_assoc()) {
    $recentUsers[] = $row;
}

// Recent courses
$recentCourses = [];
$result = $db->query("
    SELECT c.*, u.username as instructor_name 
    FROM courses c
    LEFT JOIN users u ON c.instructor_id = u.id
    ORDER BY c.created_at DESC 
    LIMIT 5
");
while ($row = $result->fetch_assoc()) {
    $recentCourses[] = $row;
}

require_once APP_ROOT . '/views/layouts/header.php';
$activePage = 'dashboard';
?>

<div class="admin-container">
    <?php require_once APP_ROOT . '/views/admin/partials/sidebar.php'; ?>
    
    <!-- Main Content -->
    <div class="admin-main">
        <!-- Welcome Header -->
        <div class="dashboard-header">
            <div class="welcome-text">
                <h1>Admin Dashboard 🛡️</h1>
                <p>Platform overview and management. Monitor users, courses, and activity.</p>
            </div>
        </div>
        
        <!-- Stats Grid -->
        <section class="stats-section">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon users">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value"><?php echo number_format($stats['users']); ?></span>
                        <span class="stat-label">Total Users</span>
                    </div>
                </div>
                
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
                    <div class="stat-icon enrollments">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-value"><?php echo number_format($stats['enrollments']); ?></span>
                        <span class="stat-label">Enrollments</span>
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
        
        <!-- Role Breakdown -->
        <section class="role-section">
            <div class="role-cards">
                <div class="role-card">
                    <div class="role-icon student">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="role-info">
                        <strong><?php echo number_format($roleStats['volunteer'] ?? 0); ?></strong>
                        <span>Students</span>
                    </div>
                </div>
                <div class="role-card">
                    <div class="role-icon instructor">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="role-info">
                        <strong><?php echo number_format($roleStats['mentor'] ?? 0); ?></strong>
                        <span>Instructors</span>
                    </div>
                </div>
                <div class="role-card">
                    <div class="role-icon admin">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="role-info">
                        <strong><?php echo number_format($roleStats['admin'] ?? 0); ?></strong>
                        <span>Admins</span>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Two Column Layout -->
        <div class="dashboard-grid">
            <!-- Recent Users -->
            <section class="dashboard-card">
                <div class="card-header">
                    <h2><i class="fas fa-users"></i> Recent Users</h2>
                    <a href="<?php echo url('admin/users'); ?>" class="btn btn-sm btn-outline">View All</a>
                </div>
                
                <div class="user-list">
                    <?php foreach ($recentUsers as $u): ?>
                    <div class="user-item">
                        <img src="<?php echo avatar($u, 40); ?>" alt="" class="user-avatar">
                        <div class="user-info">
                            <strong><?php echo e($u['username']); ?></strong>
                            <small><?php echo e($u['email']); ?></small>
                        </div>
                        <span class="role-badge role-<?php echo $u['role']; ?>">
                            <?php echo ucfirst($u['role']); ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
            
            <!-- Recent Courses -->
            <section class="dashboard-card">
                <div class="card-header">
                    <h2><i class="fas fa-book"></i> Recent Courses</h2>
                    <a href="<?php echo url('admin/courses'); ?>" class="btn btn-sm btn-outline">View All</a>
                </div>
                
                <div class="course-list">
                    <?php foreach ($recentCourses as $course): ?>
                    <div class="course-item">
                        <div class="course-info">
                            <strong><?php echo e($course['title']); ?></strong>
                            <small>by <?php echo e($course['instructor_name'] ?? 'Unknown'); ?></small>
                        </div>
                        <span class="status-badge status-<?php echo $course['status']; ?>">
                            <?php echo ucfirst($course['status']); ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>
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
    border: 3px solid #dc2626;
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

.admin-badge {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
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
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: #dc2626;
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
    margin-bottom: var(--space-8);
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
    margin-bottom: var(--space-6);
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

.stat-icon.users {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #2563eb;
}

.stat-icon.courses {
    background: linear-gradient(135deg, var(--primary-50), #ede9fe);
    color: var(--primary);
}

.stat-icon.enrollments {
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

/* Role Section */
.role-section {
    margin-bottom: var(--space-8);
}

.role-cards {
    display: flex;
    gap: var(--space-4);
    flex-wrap: wrap;
}

.role-card {
    flex: 1;
    min-width: 150px;
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: var(--space-4);
    display: flex;
    align-items: center;
    gap: var(--space-3);
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    border: 1px solid var(--gray-100);
}

.role-icon {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.role-icon.student {
    background: #dbeafe;
    color: #2563eb;
}

.role-icon.instructor {
    background: var(--primary-50);
    color: var(--primary);
}

.role-icon.admin {
    background: #fee2e2;
    color: #dc2626;
}

.role-info {
    display: flex;
    flex-direction: column;
}

.role-info strong {
    font-size: var(--text-xl);
    line-height: 1.2;
}

.role-info span {
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
    color: #dc2626;
}

/* User List */
.user-list {
    display: flex;
    flex-direction: column;
}

.user-item {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    padding: var(--space-3) 0;
    border-bottom: 1px solid var(--gray-50);
}

.user-item:last-child {
    border-bottom: none;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.user-info {
    flex: 1;
}

.user-info strong {
    display: block;
    font-size: var(--text-base);
}

.user-info small {
    color: var(--text-muted);
    font-size: var(--text-sm);
}

.role-badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.role-volunteer {
    background: #dbeafe;
    color: #2563eb;
}

.role-mentor {
    background: var(--primary-50);
    color: var(--primary);
}

.role-admin {
    background: #fee2e2;
    color: #dc2626;
}

/* Course List */
.course-list {
    display: flex;
    flex-direction: column;
}

.course-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: var(--space-3) 0;
    border-bottom: 1px solid var(--gray-50);
}

.course-item:last-child {
    border-bottom: none;
}

.course-info {
    flex: 1;
}

.course-info strong {
    display: block;
    font-size: var(--text-base);
}

.course-info small {
    color: var(--text-muted);
    font-size: var(--text-sm);
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
</style>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
