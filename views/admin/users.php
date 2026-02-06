<?php
/**
 * DADE Learn - Admin User Management
 * Premium user management with role controls
 */

$pageTitle = 'User Management';
$db = getDB();
$user = Auth::user();

// Pagination
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 20;
$offset = ($page - 1) * $perPage;

// Filter
$roleFilter = $_GET['role'] ?? '';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $targetUserId = intval($_POST['user_id'] ?? 0);
    
    if ($action === 'change_role' && $targetUserId) {
        $newRole = $_POST['role'] ?? 'volunteer';
        $stmt = $db->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param("si", $newRole, $targetUserId);
        if ($stmt->execute()) {
            Session::flash('success', 'User role updated successfully.');
        }
        Router::redirect('admin/users');
        return;
    }
    
    if ($action === 'delete_user' && $targetUserId && $targetUserId != $user['id']) {
        // Delete user's data first
        $db->query("DELETE FROM enrollments WHERE user_id = $targetUserId");
        $db->query("DELETE FROM certificates WHERE user_id = $targetUserId");
        $db->query("DELETE FROM lesson_progress WHERE user_id = $targetUserId");
        
        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $targetUserId);
        if ($stmt->execute()) {
            Session::flash('success', 'User deleted successfully.');
        }
        Router::redirect('admin/users');
        return;
    }
    
    // Approve instructor application
    if ($action === 'approve_instructor' && $targetUserId) {
        $stmt = $db->prepare("
            UPDATE users SET 
                role = 'instructor', 
                instructor_pending = 0, 
                instructor_approved_at = NOW(),
                instructor_approved_by = ?
            WHERE id = ? AND instructor_pending = 1
        ");
        $stmt->bind_param("ii", $user['id'], $targetUserId);
        if ($stmt->execute()) {
            Session::flash('success', 'Instructor application approved!');
        }
        Router::redirect('admin/users?tab=pending');
        return;
    }
    
    // Reject instructor application
    if ($action === 'reject_instructor' && $targetUserId) {
        $stmt = $db->prepare("UPDATE users SET instructor_pending = 0 WHERE id = ?");
        $stmt->bind_param("i", $targetUserId);
        if ($stmt->execute()) {
            Session::flash('success', 'Instructor application rejected.');
        }
        Router::redirect('admin/users?tab=pending');
        return;
    }
}

// Build query
$where = "";
if ($roleFilter) {
    $where = "WHERE role = '" . $db->real_escape_string($roleFilter) . "'";
}

// Get total count
$totalResult = $db->query("SELECT COUNT(*) as count FROM users $where");
$totalUsers = $totalResult->fetch_assoc()['count'];
$totalPages = ceil($totalUsers / $perPage);

// Get users
$users = [];
$result = $db->query("SELECT * FROM users $where ORDER BY created_at DESC LIMIT $perPage OFFSET $offset");
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

// Get pending instructor applications
$pendingInstructors = [];
$pendingResult = $db->query("SELECT * FROM users WHERE instructor_pending = 1 ORDER BY instructor_applied_at DESC");
while ($row = $pendingResult->fetch_assoc()) {
    $pendingInstructors[] = $row;
}
$pendingCount = count($pendingInstructors);

// Current tab
$currentTab = $_GET['tab'] ?? 'all';

require_once APP_ROOT . '/views/layouts/header.php';
$activePage = 'users';
?>

<div class="admin-container">
    <?php require_once APP_ROOT . '/views/admin/partials/sidebar.php'; ?>
    
    <!-- Main Content -->
    <main class="admin-main">
        <div class="dashboard-header">
            <div class="welcome-text">
                <h1>👤 User Management</h1>
                <p><?php echo number_format($totalUsers); ?> total users</p>
            </div>
        </div>
        
        <?php if ($success = flash('success')): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo e($success); ?>
        </div>
        <?php endif; ?>
        
        <!-- Filters -->
        <div class="filters-bar">
            <a href="<?php echo url('admin/users'); ?>" class="filter-btn <?php echo $currentTab === 'all' && !$roleFilter ? 'active' : ''; ?>">All</a>
            <a href="<?php echo url('admin/users?tab=pending'); ?>" class="filter-btn <?php echo $currentTab === 'pending' ? 'active' : ''; ?>">
                <i class="fas fa-hourglass-half"></i> Pending Instructors
                <?php if ($pendingCount > 0): ?>
                <span class="badge-count"><?php echo $pendingCount; ?></span>
                <?php endif; ?>
            </a>
            <a href="<?php echo url('admin/users?role=volunteer'); ?>" class="filter-btn <?php echo $roleFilter === 'volunteer' ? 'active' : ''; ?>">Students</a>
            <a href="<?php echo url('admin/users?role=instructor'); ?>" class="filter-btn <?php echo $roleFilter === 'instructor' ? 'active' : ''; ?>">Instructors</a>
            <a href="<?php echo url('admin/users?role=admin'); ?>" class="filter-btn <?php echo $roleFilter === 'admin' ? 'active' : ''; ?>">Admins</a>
        </div>
        
        <?php if ($currentTab === 'pending'): ?>
        <!-- Pending Instructor Applications -->
        <div class="pending-section">
            <div class="section-header">
                <h2><i class="fas fa-user-clock"></i> Pending Instructor Applications</h2>
                <p>Users who have applied to become instructors</p>
            </div>
            
            <?php if (empty($pendingInstructors)): ?>
            <div class="empty-state">
                <i class="fas fa-check-circle"></i>
                <h3>No Pending Applications</h3>
                <p>All instructor applications have been processed.</p>
            </div>
            <?php else: ?>
            <div class="pending-grid">
                <?php foreach ($pendingInstructors as $pi): ?>
                <div class="pending-card">
                    <div class="pending-user">
                        <img src="<?php echo avatar($pi, 60); ?>" alt="" class="pending-avatar">
                        <div class="pending-info">
                            <h4><?php echo e($pi['username'] ?? $pi['first_name'] . ' ' . $pi['last_name']); ?></h4>
                            <span><?php echo e($pi['email']); ?></span>
                            <small>Applied: <?php echo date('M d, Y', strtotime($pi['instructor_applied_at'] ?? $pi['created_at'])); ?></small>
                        </div>
                    </div>
                    <div class="pending-actions">
                        <form action="" method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="approve_instructor">
                            <input type="hidden" name="user_id" value="<?php echo $pi['id']; ?>">
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fas fa-check"></i> Approve
                            </button>
                        </form>
                        <form action="" method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="reject_instructor">
                            <input type="hidden" name="user_id" value="<?php echo $pi['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php else: ?>
        
        <!-- Users Table -->
        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td>
                            <div class="user-cell">
                                <img src="<?php echo avatar($u, 40); ?>" alt="" class="user-avatar">
                                <span><?php echo e($u['username'] ?? $u['first_name'] . ' ' . $u['last_name']); ?></span>
                            </div>
                        </td>
                        <td><?php echo e($u['email']); ?></td>
                        <td>
                            <form action="" method="POST" class="role-form">
                                <input type="hidden" name="action" value="change_role">
                                <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                <select name="role" class="role-select role-<?php echo $u['role']; ?>" onchange="this.form.submit()">
                                    <option value="volunteer" <?php echo $u['role'] === 'volunteer' ? 'selected' : ''; ?>>Student</option>
                                    <option value="mentor" <?php echo $u['role'] === 'mentor' ? 'selected' : ''; ?>>Instructor</option>
                                    <option value="admin" <?php echo $u['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                </select>
                            </form>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($u['created_at'])); ?></td>
                        <td>
                            <?php if ($u['id'] != $user['id']): ?>
                            <button type="button" class="btn btn-sm btn-danger" onclick="showDeleteModal('delete_user', <?php echo $u['id']; ?>, 'Delete this user? This cannot be undone.')">
                                <i class="fas fa-trash"></i>
                            </button>
                            <?php else: ?>
                            <span class="text-muted">You</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
            <a href="<?php echo url('admin/users?page=' . $p . ($roleFilter ? '&role=' . $roleFilter : '')); ?>" 
               class="page-btn <?php echo $p === $page ? 'active' : ''; ?>">
                <?php echo $p; ?>
            </a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
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
    max-width: 1200px;
}

.dashboard-header {
    margin-bottom: var(--space-6);
}

.welcome-text h1 { font-size: var(--text-2xl); margin-bottom: var(--space-1); }
.welcome-text p { color: var(--text-muted); }

/* Filters Bar */
.filters-bar {
    display: flex;
    gap: var(--space-2);
    margin-bottom: var(--space-6);
    flex-wrap: wrap;
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

/* Table Card */
.table-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    border: 1px solid var(--gray-100);
    overflow: hidden;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th {
    background: var(--gray-50);
    padding: var(--space-4);
    text-align: left;
    font-weight: 600;
    font-size: var(--text-sm);
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.data-table td {
    padding: var(--space-4);
    border-top: 1px solid var(--gray-100);
}

.data-table tr:hover td { background: var(--gray-50); }

.user-cell {
    display: flex;
    align-items: center;
    gap: var(--space-3);
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.role-form { margin: 0; }

.role-select {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    border: none;
    cursor: pointer;
}

.role-select.role-volunteer { background: #dbeafe; color: #2563eb; }
.role-select.role-mentor { background: var(--primary-50); color: var(--primary); }
.role-select.role-admin { background: #fee2e2; color: #dc2626; }

.btn-danger {
    background: #fee2e2;
    color: #dc2626;
    border: none;
    padding: var(--space-2);
    border-radius: var(--radius-md);
    cursor: pointer;
}

.btn-danger:hover { background: #dc2626; color: white; }

.text-muted { color: var(--text-muted); font-size: var(--text-sm); }

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    gap: var(--space-2);
    margin-top: var(--space-6);
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

/* Pending Instructors Section */
.pending-section { margin-bottom: var(--space-6); }
.section-header { margin-bottom: var(--space-6); }
.section-header h2 { font-size: var(--text-xl); display: flex; align-items: center; gap: var(--space-3); }
.section-header h2 i { color: #f59e0b; }
.section-header p { color: var(--text-muted); margin-top: var(--space-2); }

.pending-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: var(--space-5);
}

.pending-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: var(--space-5);
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    border: 1px solid var(--gray-100);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: var(--space-4);
}

.pending-user { display: flex; align-items: center; gap: var(--space-4); }
.pending-avatar { width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 2px solid #f59e0b; }
.pending-info h4 { margin-bottom: var(--space-1); }
.pending-info span { font-size: var(--text-sm); color: var(--text-muted); display: block; }
.pending-info small { font-size: var(--text-xs); color: var(--text-muted); margin-top: var(--space-1); display: block; }

.pending-actions { display: flex; gap: var(--space-2); }
.btn-success { background: #10b981; color: white; border: none; padding: var(--space-2) var(--space-4); border-radius: var(--radius-md); cursor: pointer; display: inline-flex; align-items: center; gap: var(--space-2); }
.btn-success:hover { background: #059669; }

.badge-count {
    background: #dc2626;
    color: white;
    font-size: 11px;
    padding: 2px 6px;
    border-radius: 10px;
    margin-left: var(--space-2);
}

.empty-state {
    text-align: center;
    padding: var(--space-12);
    background: var(--white);
    border-radius: var(--radius-xl);
    border: 1px solid var(--gray-100);
}
.empty-state i { font-size: 48px; color: #10b981; margin-bottom: var(--space-4); }
.empty-state h3 { margin-bottom: var(--space-2); }
.empty-state p { color: var(--text-muted); }

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
                <input type="hidden" name="user_id" id="deleteId" value="">
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
