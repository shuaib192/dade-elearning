<?php
/**
 * DADE Learn - Admin Badges Management
 * Create, edit, and manage platform badges
 */

$pageTitle = 'Badge Management';
Auth::requireRole('admin');
$db = getDB();
$user = Auth::user();

$message = '';
$messageType = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create') {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $icon = trim($_POST['icon'] ?? 'fas fa-award');
        $color = trim($_POST['color'] ?? '#FFD700');
        $criteria = trim($_POST['criteria'] ?? '');
        $criteriaValue = (int)($_POST['criteria_value'] ?? 1);
        $points = (int)($_POST['points'] ?? 10);
        
        if ($name && $criteria) {
            $stmt = $db->prepare("INSERT INTO badges (name, description, icon, color, criteria, criteria_value, points) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssii", $name, $description, $icon, $color, $criteria, $criteriaValue, $points);
            if ($stmt->execute()) {
                $message = 'Badge created successfully!';
                $messageType = 'success';
            } else {
                $message = 'Error creating badge.';
                $messageType = 'error';
            }
        }
    } elseif ($action === 'delete') {
        $badgeId = (int)($_POST['badge_id'] ?? 0);
        if ($badgeId) {
            $stmt = $db->prepare("DELETE FROM badges WHERE id = ?");
            $stmt->bind_param("i", $badgeId);
            $stmt->execute();
            $message = 'Badge deleted.';
            $messageType = 'success';
        }
    }
}

// Get all badges
$badges = [];
$result = $db->query("SELECT b.*, (SELECT COUNT(*) FROM user_badges WHERE badge_id = b.id) as awarded_count FROM badges b ORDER BY b.id ASC");
while ($row = $result->fetch_assoc()) {
    $badges[] = $row;
}

// Get criteria options
$criteriaOptions = [
    'courses_completed' => 'Courses Completed',
    'quizzes_passed' => 'Quizzes Passed',
    'perfect_quiz' => 'Perfect Quiz Score',
    'certificates_earned' => 'Certificates Earned',
    'reviews_written' => 'Reviews Written',
    'bookmarks_created' => 'Bookmarks Created',
    'login_streak' => 'Login Streak (days)'
];

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
            <a href="<?php echo url('admin/analytics'); ?>" class="sidebar-link">
                <i class="fas fa-chart-line"></i>
                <span>Analytics</span>
            </a>
            <a href="<?php echo url('admin/users'); ?>" class="sidebar-link">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </a>
            <a href="<?php echo url('admin/courses'); ?>" class="sidebar-link">
                <i class="fas fa-book"></i>
                <span>Courses</span>
            </a>
            <a href="<?php echo url('admin/badges'); ?>" class="sidebar-link active">
                <i class="fas fa-award"></i>
                <span>Badges</span>
            </a>
            <a href="<?php echo url('admin/certificates'); ?>" class="sidebar-link">
                <i class="fas fa-certificate"></i>
                <span>Certificates</span>
            </a>
        </nav>
    </aside>
    
    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="page-header">
            <div class="header-left">
                <h1><i class="fas fa-award"></i> Badge Management</h1>
                <p>Create and manage achievement badges</p>
            </div>
            <button class="btn btn-primary" onclick="openModal('createBadgeModal')">
                <i class="fas fa-plus"></i> Create Badge
            </button>
        </div>
        
        <?php if ($message): ?>
        <div class="alert alert-<?php echo $messageType; ?>">
            <?php echo e($message); ?>
        </div>
        <?php endif; ?>
        
        <!-- Badges Grid -->
        <div class="badges-grid">
            <?php foreach ($badges as $badge): ?>
            <div class="badge-card">
                <div class="badge-icon" style="background: <?php echo e($badge['color']); ?>20; color: <?php echo e($badge['color']); ?>">
                    <i class="<?php echo e($badge['icon']); ?>"></i>
                </div>
                <div class="badge-info">
                    <h3><?php echo e($badge['name']); ?></h3>
                    <p><?php echo e($badge['description']); ?></p>
                    <div class="badge-meta">
                        <span class="criteria">
                            <i class="fas fa-bullseye"></i>
                            <?php echo e($criteriaOptions[$badge['criteria']] ?? $badge['criteria']); ?> ≥ <?php echo $badge['criteria_value']; ?>
                        </span>
                        <span class="points">
                            <i class="fas fa-star"></i> <?php echo $badge['points']; ?> pts
                        </span>
                    </div>
                    <div class="badge-stats">
                        <span><i class="fas fa-users"></i> <?php echo $badge['awarded_count']; ?> awarded</span>
                    </div>
                </div>
                <div class="badge-actions">
                    <form method="POST" onsubmit="return confirm('Delete this badge?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="badge_id" value="<?php echo $badge['id']; ?>">
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php if (empty($badges)): ?>
            <div class="empty-state">
                <i class="fas fa-trophy"></i>
                <h3>No badges yet</h3>
                <p>Create badges to reward user achievements</p>
            </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<!-- Create Badge Modal -->
<div id="createBadgeModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Create New Badge</h2>
            <button class="modal-close" onclick="closeModal('createBadgeModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" class="modal-form">
            <input type="hidden" name="action" value="create">
            
            <div class="form-group">
                <label>Badge Name</label>
                <input type="text" name="name" required placeholder="e.g., Quiz Master">
            </div>
            
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="2" placeholder="What this badge represents"></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Icon (Font Awesome)</label>
                    <input type="text" name="icon" value="fas fa-award" placeholder="fas fa-award">
                </div>
                <div class="form-group">
                    <label>Color</label>
                    <input type="color" name="color" value="#FFD700">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Criteria</label>
                    <select name="criteria" required>
                        <?php foreach ($criteriaOptions as $value => $label): ?>
                        <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Required Value</label>
                    <input type="number" name="criteria_value" value="1" min="1">
                </div>
            </div>
            
            <div class="form-group">
                <label>Points</label>
                <input type="number" name="points" value="10" min="0">
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('createBadgeModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Badge</button>
            </div>
        </form>
    </div>
</div>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--space-8);
}

.header-left h1 {
    font-size: var(--text-2xl);
    margin-bottom: var(--space-1);
}

.header-left h1 i {
    color: var(--primary);
    margin-right: var(--space-2);
}

.header-left p {
    color: var(--text-muted);
}

.badges-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: var(--space-6);
}

.badge-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: var(--space-6);
    display: flex;
    gap: var(--space-5);
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    border: 1px solid var(--gray-100);
    transition: all var(--transition);
}

.badge-card:hover {
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    transform: translateY(-2px);
}

.badge-icon {
    width: 64px;
    height: 64px;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    flex-shrink: 0;
}

.badge-info {
    flex: 1;
}

.badge-info h3 {
    font-size: var(--text-lg);
    margin-bottom: var(--space-2);
}

.badge-info p {
    color: var(--text-muted);
    font-size: var(--text-sm);
    margin-bottom: var(--space-3);
}

.badge-meta {
    display: flex;
    gap: var(--space-4);
    font-size: var(--text-xs);
    color: var(--text-secondary);
    margin-bottom: var(--space-2);
}

.badge-meta i {
    margin-right: var(--space-1);
}

.badge-stats {
    font-size: var(--text-sm);
    color: var(--text-secondary);
}

.badge-stats i {
    color: var(--primary);
    margin-right: var(--space-1);
}

.badge-actions {
    display: flex;
    flex-direction: column;
    gap: var(--space-2);
}

.alert {
    padding: var(--space-4);
    border-radius: var(--radius-lg);
    margin-bottom: var(--space-6);
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #10b981;
}

.alert-error {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #ef4444;
}

.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: var(--space-16);
    background: var(--white);
    border-radius: var(--radius-xl);
}

.empty-state i {
    font-size: 64px;
    color: var(--gray-300);
    margin-bottom: var(--space-4);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--space-4);
}

.modal-form {
    display: flex;
    flex-direction: column;
    gap: var(--space-4);
}

/* Modal styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}

.modal.active {
    display: flex;
}

.modal-content {
    background: var(--white);
    border-radius: var(--radius-2xl);
    max-width: 500px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--space-6);
    border-bottom: 1px solid var(--gray-100);
}

.modal-header h2 {
    font-size: var(--text-xl);
}

.modal-close {
    background: none;
    border: none;
    cursor: pointer;
    color: var(--text-muted);
    font-size: 20px;
}

.modal-form {
    padding: var(--space-6);
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: var(--space-3);
    padding-top: var(--space-4);
    border-top: 1px solid var(--gray-100);
}
</style>

<script>
function openModal(id) {
    document.getElementById(id).classList.add('active');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('active');
}
</script>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
