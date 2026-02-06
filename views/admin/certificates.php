<?php
/**
 * DADE Learn - Admin Certificate Approvals
 * Review and approve/reject certificate course requests
 */

$pageTitle = 'Certificate Approvals';
$db = getDB();
$user = Auth::user();

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $courseId = intval($_POST['course_id'] ?? 0);
    
    if (in_array($action, ['approve', 'reject']) && $courseId) {
        $newStatus = $action === 'approve' ? 'approved' : 'rejected';
        $stmt = $db->prepare("UPDATE courses SET certificate_status = ? WHERE id = ?");
        $stmt->bind_param("si", $newStatus, $courseId);
        
        if ($stmt->execute()) {
            $message = $action === 'approve' 
                ? 'Certificate course approved! Students can now earn certificates.' 
                : 'Certificate request rejected.';
            Session::flash('success', $message);
        }
        Router::redirect('admin/certificates');
        return;
    }
}

// Get pending certificates
$pending = [];
$result = $db->query("
    SELECT c.*, u.username as instructor_name, cat.name as category_name,
           (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id) as enrolled_count,
           (SELECT COUNT(*) FROM lessons WHERE course_id = c.id) as lesson_count
    FROM courses c
    LEFT JOIN users u ON c.instructor_id = u.id
    LEFT JOIN categories cat ON c.category_id = cat.id
    WHERE c.certificate_status = 'pending'
    ORDER BY c.created_at DESC
");
while ($row = $result->fetch_assoc()) {
    $pending[] = $row;
}

// Get approved certificates
$approved = [];
$result = $db->query("
    SELECT c.*, u.username as instructor_name, cat.name as category_name,
           (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id) as enrolled_count,
           (SELECT COUNT(*) FROM certificates WHERE course_id = c.id) as certificates_issued
    FROM courses c
    LEFT JOIN users u ON c.instructor_id = u.id
    LEFT JOIN categories cat ON c.category_id = cat.id
    WHERE c.certificate_status = 'approved'
    ORDER BY c.created_at DESC
    LIMIT 10
");
while ($row = $result->fetch_assoc()) {
    $approved[] = $row;
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
            <a href="<?php echo url('admin/courses'); ?>" class="sidebar-link">
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
            <a href="<?php echo url('admin/certificates'); ?>" class="sidebar-link active">
                <i class="fas fa-certificate"></i>
                <span>Certificates</span>
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
                <h1>🏆 Certificate Approvals</h1>
                <p>Review and approve certificate course requests</p>
            </div>
        </div>
        
        <?php if ($success = flash('success')): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo e($success); ?>
        </div>
        <?php endif; ?>
        
        <!-- Pending Requests -->
        <div class="section">
            <h2 class="section-title">
                <i class="fas fa-clock text-warning"></i> 
                Pending Requests
                <?php if (count($pending) > 0): ?>
                <span class="badge"><?php echo count($pending); ?></span>
                <?php endif; ?>
            </h2>
            
            <?php if (empty($pending)): ?>
            <div class="empty-card">
                <i class="fas fa-check-circle text-success"></i>
                <p>No pending certificate requests!</p>
            </div>
            <?php else: ?>
            <div class="cert-requests">
                <?php foreach ($pending as $course): ?>
                <div class="request-card">
                    <div class="request-info">
                        <h3><?php echo e($course['title']); ?></h3>
                        <div class="request-meta">
                            <span><i class="fas fa-user"></i> <?php echo e($course['instructor_name']); ?></span>
                            <span><i class="fas fa-folder"></i> <?php echo e($course['category_name'] ?? 'Uncategorized'); ?></span>
                            <span><i class="fas fa-list"></i> <?php echo $course['lesson_count']; ?> lessons</span>
                            <span><i class="fas fa-users"></i> <?php echo $course['enrolled_count']; ?> enrolled</span>
                        </div>
                        <div class="pass-grade">
                            <i class="fas fa-trophy"></i> Passing Grade: <strong><?php echo $course['passing_grade']; ?>%</strong>
                        </div>
                    </div>
                    <div class="request-actions">
                        <a href="<?php echo url('course/' . ($course['slug'] ?? $course['id'])); ?>" class="btn btn-outline btn-sm" target="_blank">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <form action="" method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="approve">
                            <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fas fa-check"></i> Approve
                            </button>
                        </form>
                        <button type="button" class="btn btn-danger btn-sm" onclick="showConfirmModal('reject', <?php echo $course['id']; ?>, 'Reject this certificate request?')">
                            <i class="fas fa-times"></i> Reject
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Approved Certificate Courses -->
        <div class="section">
            <h2 class="section-title">
                <i class="fas fa-check-circle text-success"></i>
                Approved Certificate Courses
            </h2>
            
            <?php if (empty($approved)): ?>
            <div class="empty-card">
                <i class="fas fa-certificate text-muted"></i>
                <p>No approved certificate courses yet.</p>
            </div>
            <?php else: ?>
            <div class="approved-table">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Instructor</th>
                            <th>Passing Grade</th>
                            <th>Certificates Issued</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($approved as $course): ?>
                        <tr>
                            <td>
                                <strong><?php echo e($course['title']); ?></strong>
                                <br><small class="text-muted"><?php echo e($course['category_name'] ?? ''); ?></small>
                            </td>
                            <td><?php echo e($course['instructor_name']); ?></td>
                            <td><span class="grade-badge"><?php echo $course['passing_grade']; ?>%</span></td>
                            <td><?php echo $course['certificates_issued']; ?></td>
                            <td>
                                <button type="button" class="btn btn-ghost btn-sm text-danger" onclick="showConfirmModal('reject', <?php echo $course['id']; ?>, 'Revoke this certificate approval?')">
                                    <i class="fas fa-ban"></i> Revoke
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
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
    max-width: 1100px;
}

.dashboard-header { margin-bottom: var(--space-8); }

.welcome-text h1 { font-size: var(--text-2xl); margin-bottom: var(--space-1); }
.welcome-text p { color: var(--text-muted); }

/* Sections */
.section { margin-bottom: var(--space-10); }

.section-title {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    font-size: var(--text-xl);
    margin-bottom: var(--space-5);
}

.section-title .badge {
    background: #dc2626;
    color: white;
    padding: 2px 10px;
    border-radius: 12px;
    font-size: var(--text-sm);
    font-weight: 600;
}

.text-warning { color: #d97706; }
.text-success { color: #059669; }
.text-muted { color: var(--text-muted); }
.text-danger { color: #dc2626; }

/* Empty Card */
.empty-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: var(--space-10);
    text-align: center;
    border: 1px solid var(--gray-100);
}

.empty-card i { font-size: 48px; margin-bottom: var(--space-3); }
.empty-card p { color: var(--text-muted); margin: 0; }

/* Request Cards */
.cert-requests {
    display: flex;
    flex-direction: column;
    gap: var(--space-4);
}

.request-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: var(--space-5);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: var(--space-4);
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    border: 1px solid var(--gray-100);
    border-left: 4px solid #d97706;
}

.request-info h3 {
    font-size: var(--text-lg);
    margin-bottom: var(--space-2);
}

.request-meta {
    display: flex;
    gap: var(--space-4);
    font-size: var(--text-sm);
    color: var(--text-muted);
    margin-bottom: var(--space-2);
    flex-wrap: wrap;
}

.request-meta i { margin-right: 4px; }

.pass-grade {
    font-size: var(--text-sm);
    color: var(--primary);
}

.pass-grade i { margin-right: 4px; }

.request-actions {
    display: flex;
    gap: var(--space-2);
    flex-shrink: 0;
}

.btn-success {
    background: #059669;
    color: white;
    border: none;
}

.btn-success:hover { background: #047857; }

.btn-danger {
    background: #fee2e2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.btn-danger:hover { background: #dc2626; color: white; }

.btn-ghost {
    background: transparent;
    border: none;
}

/* Table */
.approved-table {
    background: var(--white);
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    border: 1px solid var(--gray-100);
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
}

.data-table td {
    padding: var(--space-4);
    border-top: 1px solid var(--gray-100);
}

.grade-badge {
    background: var(--primary-50);
    color: var(--primary);
    padding: 4px 12px;
    border-radius: 16px;
    font-weight: 600;
    font-size: var(--text-sm);
}

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

@media (max-width: 768px) {
    .request-card { flex-direction: column; align-items: flex-start; }
    .request-actions { width: 100%; }
}

/* Confirmation Modal */
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

.modal-overlay.active {
    display: flex;
}

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

.modal-icon {
    font-size: 48px;
    color: #dc2626;
    margin-bottom: var(--space-4);
}

.modal-title {
    font-size: var(--text-xl);
    margin-bottom: var(--space-2);
}

.modal-message {
    color: var(--text-muted);
    margin-bottom: var(--space-6);
}

.modal-buttons {
    display: flex;
    gap: var(--space-3);
    justify-content: center;
}

.modal-buttons .btn {
    min-width: 100px;
}
</style>

<!-- Confirmation Modal -->
<div class="modal-overlay" id="confirmModal">
    <div class="modal-box">
        <div class="modal-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3 class="modal-title">Confirm Action</h3>
        <p class="modal-message" id="modalMessage">Are you sure?</p>
        <div class="modal-buttons">
            <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
            <form id="confirmForm" action="" method="POST" style="display:inline;">
                <input type="hidden" name="action" id="modalAction" value="">
                <input type="hidden" name="course_id" id="modalCourseId" value="">
                <button type="submit" class="btn btn-danger">Yes, Continue</button>
            </form>
        </div>
    </div>
</div>

<script>
function showConfirmModal(action, courseId, message) {
    document.getElementById('modalAction').value = action;
    document.getElementById('modalCourseId').value = courseId;
    document.getElementById('modalMessage').textContent = message;
    document.getElementById('confirmModal').classList.add('active');
}

function closeModal() {
    document.getElementById('confirmModal').classList.remove('active');
}

// Close modal when clicking outside
document.getElementById('confirmModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});
</script>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
