<?php
/**
 * DADE Learn - Admin Course Management
 * Premium course moderation with status controls
 */

$pageTitle = 'Course Management';
$db = getDB();
$user = Auth::user();

// Pagination
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 20;
$offset = ($page - 1) * $perPage;

// Filter
$statusFilter = $_GET['status'] ?? '';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $courseId = intval($_POST['course_id'] ?? 0);
    
    if ($action === 'change_status' && $courseId) {
        $newStatus = $_POST['status'] ?? 'draft';
        $stmt = $db->prepare("UPDATE courses SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $newStatus, $courseId);
        if ($stmt->execute()) {
            Session::flash('success', 'Course status updated.');
        }
        Router::redirect('admin/courses');
        return;
    }
    
    if ($action === 'delete_course' && $courseId) {
        // Delete related data
        $db->query("DELETE FROM lessons WHERE course_id = $courseId");
        $db->query("DELETE FROM enrollments WHERE course_id = $courseId");
        $db->query("DELETE FROM certificates WHERE course_id = $courseId");
        $db->query("DELETE FROM lesson_progress WHERE lesson_id IN (SELECT id FROM lessons WHERE course_id = $courseId)");
        
        $stmt = $db->prepare("DELETE FROM courses WHERE id = ?");
        $stmt->bind_param("i", $courseId);
        if ($stmt->execute()) {
            Session::flash('success', 'Course deleted successfully.');
        }
        Router::redirect('admin/courses');
        return;
    }
}

// Build query
$where = "";
if ($statusFilter) {
    $where = "WHERE c.status = '" . $db->real_escape_string($statusFilter) . "'";
}

// Get total count
$totalResult = $db->query("SELECT COUNT(*) as count FROM courses c $where");
$totalCourses = $totalResult->fetch_assoc()['count'];
$totalPages = ceil($totalCourses / $perPage);

// Get courses
$courses = [];
$result = $db->query("
    SELECT c.*, u.username as instructor_name, cat.name as category_name,
           (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id) as enrolled_count,
           (SELECT COUNT(*) FROM lessons WHERE course_id = c.id) as lesson_count
    FROM courses c
    LEFT JOIN users u ON c.instructor_id = u.id
    LEFT JOIN categories cat ON c.category_id = cat.id
    $where
    ORDER BY c.created_at DESC 
    LIMIT $perPage OFFSET $offset
");
while ($row = $result->fetch_assoc()) {
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
            <span class="sidebar-role admin-badge">
                <i class="fas fa-shield-alt"></i> Administrator
            </span>
        </div>
        
        <nav class="sidebar-nav">
            <a href="<?php echo url('admin'); ?>" class="sidebar-link">
                <i class="fas fa-chart-pie"></i>
                <span>Dashboard</span>
            </a>
            <a href="<?php echo url('admin/users'); ?>" class="sidebar-link">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </a>
            <a href="<?php echo url('admin/courses'); ?>" class="sidebar-link active">
                <i class="fas fa-book"></i>
                <span>Courses</span>
            </a>
            <a href="<?php echo url('admin/categories'); ?>" class="sidebar-link">
                <i class="fas fa-folder"></i>
                <span>Categories</span>
            </a>
            <a href="<?php echo url('admin/analytics'); ?>" class="sidebar-link">
                <i class="fas fa-chart-line"></i>
                <span>Analytics</span>
            </a>
            <a href="<?php echo url('admin/badges'); ?>" class="sidebar-link">
                <i class="fas fa-award"></i>
                <span>Badges</span>
            </a>
            <div class="sidebar-divider"></div>
            <a href="<?php echo url('dashboard'); ?>" class="sidebar-link">
                <i class="fas fa-home"></i>
                <span>My Dashboard</span>
            </a>
        </nav>
    </aside>
    
    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="dashboard-header">
            <div class="welcome-text">
                <h1>📚 Course Management</h1>
                <p><?php echo number_format($totalCourses); ?> total courses</p>
            </div>
        </div>
        
        <?php if ($success = flash('success')): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo e($success); ?>
        </div>
        <?php endif; ?>
        
        <!-- Filters -->
        <div class="filters-bar">
            <a href="<?php echo url('admin/courses'); ?>" class="filter-btn <?php echo !$statusFilter ? 'active' : ''; ?>">All</a>
            <a href="<?php echo url('admin/courses?status=published'); ?>" class="filter-btn <?php echo $statusFilter === 'published' ? 'active' : ''; ?>">Published</a>
            <a href="<?php echo url('admin/courses?status=draft'); ?>" class="filter-btn <?php echo $statusFilter === 'draft' ? 'active' : ''; ?>">Draft</a>
        </div>
        
        <!-- Courses Grid -->
        <div class="courses-grid">
            <?php foreach ($courses as $course): ?>
            <div class="course-card">
                <?php 
                $thumb = !empty($course['cover_image']) 
                    ? url('uploads/courses/' . $course['cover_image'])
                    : url('assets/images/course-placeholder.png');
                ?>
                <div class="course-thumb">
                    <img src="<?php echo $thumb; ?>" alt="<?php echo e($course['title']); ?>">
                </div>
                <div class="course-body">
                    <div class="course-header">
                        <span class="course-category"><?php echo e($course['category_name'] ?? 'Uncategorized'); ?></span>
                        <form action="" method="POST" class="status-form">
                            <input type="hidden" name="action" value="change_status">
                            <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                            <select name="status" class="status-select status-<?php echo $course['status']; ?>" onchange="this.form.submit()">
                                <option value="draft" <?php echo $course['status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
                                <option value="published" <?php echo $course['status'] === 'published' ? 'selected' : ''; ?>>Published</option>
                            </select>
                        </form>
                    </div>
                    <h3><?php echo e($course['title']); ?></h3>
                    <p class="course-instructor">
                        <i class="fas fa-user"></i> <?php echo e($course['instructor_name'] ?? 'Unknown'); ?>
                    </p>
                    <div class="course-stats">
                        <span><i class="fas fa-users"></i> <?php echo $course['enrolled_count']; ?></span>
                        <span><i class="fas fa-list"></i> <?php echo $course['lesson_count']; ?> lessons</span>
                    </div>
                    <div class="course-footer">
                        <a href="<?php echo url('course/' . ($course['slug'] ?? $course['id'])); ?>" class="btn btn-sm btn-outline" target="_blank">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <button type="button" class="btn btn-sm btn-danger" onclick="showDeleteModal('delete_course', <?php echo $course['id']; ?>, 'Delete this course and all its data? This cannot be undone.')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
            <a href="<?php echo url('admin/courses?page=' . $p . ($statusFilter ? '&status=' . $statusFilter : '')); ?>" 
               class="page-btn <?php echo $p === $page ? 'active' : ''; ?>">
                <?php echo $p; ?>
            </a>
            <?php endfor; ?>
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
    border: 3px solid #dc2626;
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

.admin-badge {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
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
.sidebar-link.active { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #dc2626; }
.sidebar-link i { width: 20px; text-align: center; }
.sidebar-divider { height: 1px; background: var(--gray-100); margin: var(--space-4) 0; }

.dashboard-main {
    flex: 1;
    padding: var(--space-8);
    max-width: 1400px;
}

.dashboard-header { margin-bottom: var(--space-6); }

.welcome-text h1 { font-size: var(--text-2xl); margin-bottom: var(--space-1); }
.welcome-text p { color: var(--text-muted); }

/* Filters Bar */
.filters-bar {
    display: flex;
    gap: var(--space-2);
    margin-bottom: var(--space-6);
}

.filter-btn {
    padding: var(--space-2) var(--space-4);
    border-radius: 20px;
    font-size: var(--text-sm);
    font-weight: 500;
    background: var(--white);
    color: var(--text-secondary);
    border: 1px solid var(--gray-200);
    transition: all var(--transition-fast);
}

.filter-btn:hover { border-color: #dc2626; color: #dc2626; }
.filter-btn.active { background: #dc2626; color: white; border-color: #dc2626; }

/* Courses Grid */
.courses-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
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
    height: 140px;
    overflow: hidden;
}

.course-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.course-body { padding: var(--space-4); }

.course-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--space-2);
}

.course-category {
    font-size: var(--text-xs);
    color: var(--primary);
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.status-form { margin: 0; }

.status-select {
    padding: 4px 10px;
    border-radius: 16px;
    font-size: 11px;
    font-weight: 600;
    border: none;
    cursor: pointer;
}

.status-select.status-published { background: #d1fae5; color: #059669; }
.status-select.status-draft { background: #fef3c7; color: #d97706; }

.course-body h3 {
    font-size: var(--text-base);
    margin-bottom: var(--space-2);
    line-height: 1.4;
}

.course-instructor {
    font-size: var(--text-sm);
    color: var(--text-muted);
    margin-bottom: var(--space-3);
}

.course-instructor i { margin-right: 4px; }

.course-stats {
    display: flex;
    gap: var(--space-4);
    font-size: var(--text-sm);
    color: var(--text-muted);
    padding: var(--space-3) 0;
    border-top: 1px solid var(--gray-100);
    border-bottom: 1px solid var(--gray-100);
    margin-bottom: var(--space-3);
}

.course-stats i { margin-right: 4px; }

.course-footer {
    display: flex;
    gap: var(--space-2);
}

.course-footer .btn { flex: 1; justify-content: center; }

.btn-danger {
    background: #fee2e2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.btn-danger:hover { background: #dc2626; color: white; }

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    gap: var(--space-2);
    margin-top: var(--space-8);
}

.page-btn {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--radius-md);
    background: var(--white);
    border: 1px solid var(--gray-200);
    font-weight: 500;
    transition: all var(--transition-fast);
}

.page-btn:hover { border-color: #dc2626; color: #dc2626; }
.page-btn.active { background: #dc2626; color: white; border-color: #dc2626; }

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

<!-- Delete Confirmation Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <div class="modal-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3 class="modal-title">Confirm Delete</h3>
        <p class="modal-message" id="modalMessage">Are you sure?</p>
        <div class="modal-buttons">
            <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
            <form id="deleteForm" action="" method="POST" style="display:inline;">
                <input type="hidden" name="action" id="deleteAction" value="">
                <input type="hidden" name="course_id" id="deleteId" value="">
                <button type="submit" class="btn btn-danger">Yes, Delete</button>
            </form>
        </div>
    </div>
</div>

<style>
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(4px);
}
.modal-overlay.active { display: flex; }
.modal-box {
    background: white;
    border-radius: var(--radius-xl);
    padding: var(--space-8);
    max-width: 400px;
    width: 90%;
    text-align: center;
    box-shadow: 0 20px 50px rgba(0,0,0,0.3);
    animation: modalIn 0.2s ease-out;
}
@keyframes modalIn {
    from { transform: scale(0.9); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
.modal-icon { font-size: 48px; color: #dc2626; margin-bottom: var(--space-4); }
.modal-title { font-size: var(--text-xl); margin-bottom: var(--space-2); }
.modal-message { color: var(--text-muted); margin-bottom: var(--space-6); }
.modal-buttons { display: flex; gap: var(--space-3); justify-content: center; }
.modal-buttons .btn { min-width: 100px; }
</style>

<script>
function showDeleteModal(action, id, message) {
    document.getElementById('deleteAction').value = action;
    document.getElementById('deleteId').value = id;
    document.getElementById('modalMessage').textContent = message;
    document.getElementById('deleteModal').classList.add('active');
}
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
}
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeDeleteModal();
});
</script>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
