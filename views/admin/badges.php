<?php
/**
 * DADE Learn - Admin Badges Management
 * Premium badge management with modern design
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

// Count stats
$totalBadges = count($badges);
$totalAwarded = 0;
foreach ($badges as $b) $totalAwarded += $b['awarded_count'];

require_once APP_ROOT . '/views/layouts/header.php';
$activePage = 'badges';
?>

<div class="admin-container">
    <?php require_once APP_ROOT . '/views/admin/partials/sidebar.php'; ?>

    <!-- Main Content -->
    <main class="admin-main">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1><i class="fas fa-award"></i> Badge Management</h1>
                <p>Create and manage achievement badges for your students</p>
            </div>
            <button class="btn-create" onclick="openModal()">
                <i class="fas fa-plus"></i> Create Badge
            </button>
        </div>

        <?php if ($message): ?>
        <div class="alert alert-<?php echo $messageType; ?>">
            <i class="fas fa-<?php echo $messageType === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
            <?php echo e($message); ?>
        </div>
        <?php endif; ?>

        <!-- Stats Cards -->
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-medal"></i></div>
                <div class="stat-content">
                    <span class="stat-number"><?php echo $totalBadges; ?></span>
                    <span class="stat-label">Total Badges</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-trophy"></i></div>
                <div class="stat-content">
                    <span class="stat-number"><?php echo $totalAwarded; ?></span>
                    <span class="stat-label">Badges Awarded</span>
                </div>
            </div>
        </div>

        <!-- Badges Grid -->
        <div class="badges-section">
            <h2><i class="fas fa-th-large"></i> All Badges</h2>
            
            <?php if (empty($badges)): ?>
            <div class="empty-card">
                <div class="empty-icon"><i class="fas fa-trophy"></i></div>
                <h3>No Badges Created</h3>
                <p>Create your first badge to start rewarding students</p>
                <button class="btn-primary" onclick="openModal()">Create Badge</button>
            </div>
            <?php else: ?>
            <div class="badges-grid">
                <?php foreach ($badges as $badge): ?>
                <div class="badge-card">
                    <div class="badge-header">
                        <div class="badge-icon-wrapper" style="background: <?php echo e($badge['color']); ?>">
                            <i class="<?php echo e($badge['icon']); ?>"></i>
                        </div>
                        <form method="POST" class="delete-form" onsubmit="return confirm('Delete this badge?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="badge_id" value="<?php echo $badge['id']; ?>">
                            <button type="submit" class="btn-delete"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                    <div class="badge-body">
                        <h3><?php echo e($badge['name']); ?></h3>
                        <p><?php echo e($badge['description'] ?: 'No description'); ?></p>
                    </div>
                    <div class="badge-footer">
                        <div class="badge-criteria">
                            <i class="fas fa-bullseye"></i>
                            <?php echo e($criteriaOptions[$badge['criteria']] ?? $badge['criteria']); ?> ≥ <?php echo $badge['criteria_value']; ?>
                        </div>
                        <div class="badge-stats-row">
                            <span class="points"><i class="fas fa-star"></i> <?php echo $badge['points']; ?> pts</span>
                            <span class="awarded"><i class="fas fa-users"></i> <?php echo $badge['awarded_count']; ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<!-- Create Badge Modal -->
<div id="badgeModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h2><i class="fas fa-plus-circle"></i> Create New Badge</h2>
            <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" class="badge-form">
            <input type="hidden" name="action" value="create">
            
            <div class="form-group">
                <label><i class="fas fa-tag"></i> Badge Name</label>
                <input type="text" name="name" required placeholder="e.g., Quiz Master">
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-align-left"></i> Description</label>
                <textarea name="description" rows="2" placeholder="What this badge represents"></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-icons"></i> Icon Class</label>
                    <input type="text" name="icon" value="fas fa-award" placeholder="fas fa-award">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-palette"></i> Color</label>
                    <input type="color" name="color" value="#10b981" class="color-picker">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-bullseye"></i> Criteria</label>
                    <select name="criteria" required>
                        <?php foreach ($criteriaOptions as $value => $label): ?>
                        <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-hashtag"></i> Required Value</label>
                    <input type="number" name="criteria_value" value="1" min="1">
                </div>
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-star"></i> Points</label>
                <input type="number" name="points" value="10" min="0">
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-check"></i> Create Badge
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Admin Layout */
.admin-container {
    display: flex;
    min-height: calc(100vh - var(--header-height, 70px));
    background: #f8fafc;
}

.admin-sidebar {
    width: 260px;
    background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
    color: white;
    padding: 24px 16px;
    position: sticky;
    top: var(--header-height, 70px);
    height: calc(100vh - var(--header-height, 70px));
    overflow-y: auto;
}

.sidebar-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding-bottom: 20px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    margin-bottom: 20px;
}

.admin-avatar {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    object-fit: cover;
    border: 2px solid #10b981;
}

.admin-info h4 {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 2px;
}

.admin-role {
    font-size: 11px;
    color: #10b981;
    display: flex;
    align-items: center;
    gap: 4px;
}

.sidebar-menu {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.menu-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border-radius: 10px;
    color: #94a3b8;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s;
}

.menu-item:hover {
    background: rgba(255,255,255,0.05);
    color: white;
}

.menu-item.active {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.menu-item i {
    width: 20px;
    text-align: center;
}

/* Main Content */
.admin-main {
    flex: 1;
    padding: 32px;
    max-width: 1200px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
}

.page-header h1 {
    font-size: 28px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 4px;
}

.page-header h1 i {
    color: #10b981;
    margin-right: 10px;
}

.page-header p {
    color: #64748b;
    font-size: 14px;
}

.btn-create {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.btn-create:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
}

/* Alert */
.alert {
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 500;
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

/* Stats Row */
.stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
}

.stat-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    border: 1px solid #e2e8f0;
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.stat-icon.blue { background: #dbeafe; color: #2563eb; }
.stat-icon.green { background: #d1fae5; color: #059669; }

.stat-number {
    font-size: 28px;
    font-weight: 700;
    color: #1e293b;
    display: block;
}

.stat-label {
    font-size: 13px;
    color: #64748b;
}

/* Badges Section */
.badges-section h2 {
    font-size: 18px;
    color: #1e293b;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.badges-section h2 i {
    color: #10b981;
}

.badges-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.badge-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    border: 1px solid #e2e8f0;
    transition: all 0.3s;
}

.badge-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.1);
}

.badge-header {
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.badge-icon-wrapper {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: white;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn-delete {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    border: none;
    background: #fee2e2;
    color: #dc2626;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-delete:hover {
    background: #dc2626;
    color: white;
}

.badge-body {
    padding: 0 20px 16px;
}

.badge-body h3 {
    font-size: 18px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 4px;
}

.badge-body p {
    font-size: 13px;
    color: #64748b;
    line-height: 1.5;
}

.badge-footer {
    padding: 16px 20px;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
}

.badge-criteria {
    font-size: 12px;
    color: #64748b;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.badge-criteria i { color: #10b981; }

.badge-stats-row {
    display: flex;
    justify-content: space-between;
    font-size: 13px;
    font-weight: 600;
}

.badge-stats-row .points { color: #f59e0b; }
.badge-stats-row .awarded { color: #3b82f6; }
.badge-stats-row i { margin-right: 4px; }

/* Empty State */
.empty-card {
    text-align: center;
    padding: 60px 40px;
    background: white;
    border-radius: 16px;
    border: 2px dashed #e2e8f0;
}

.empty-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    color: #059669;
}

.empty-card h3 {
    font-size: 20px;
    color: #1e293b;
    margin-bottom: 8px;
}

.empty-card p {
    color: #64748b;
    margin-bottom: 24px;
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
}

/* Modal */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(15, 23, 42, 0.7);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(4px);
}

.modal-overlay.active { display: flex; }

.modal-box {
    background: white;
    border-radius: 20px;
    width: 100%;
    max-width: 480px;
    max-height: 90vh;
    overflow-y: auto;
    animation: modalSlide 0.3s ease;
}

@keyframes modalSlide {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 24px;
    border-bottom: 1px solid #e2e8f0;
}

.modal-header h2 {
    font-size: 20px;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 10px;
}

.modal-header h2 i { color: #10b981; }

.modal-close {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    border: none;
    background: #f1f5f9;
    color: #64748b;
    cursor: pointer;
    transition: all 0.2s;
}

.modal-close:hover { background: #e2e8f0; color: #1e293b; }

.badge-form {
    padding: 24px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
    color: #475569;
    margin-bottom: 8px;
}

.form-group label i { color: #10b981; font-size: 12px; }

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
    transition: all 0.2s;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: #10b981;
    outline: none;
    box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
}

.color-picker {
    height: 44px;
    cursor: pointer;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding-top: 16px;
    border-top: 1px solid #e2e8f0;
    margin-top: 8px;
}

.btn-cancel {
    padding: 12px 24px;
    background: #f1f5f9;
    color: #64748b;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-cancel:hover { background: #e2e8f0; color: #1e293b; }

.btn-submit {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-submit:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

/* Responsive */
@media (max-width: 1024px) {
    .admin-sidebar { display: none; }
    .admin-main { padding: 20px; }
}

@media (max-width: 640px) {
    .page-header { flex-direction: column; align-items: flex-start; gap: 16px; }
    .form-row { grid-template-columns: 1fr; }
}
</style>

<script>
function openModal() {
    document.getElementById('badgeModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('badgeModal').classList.remove('active');
    document.body.style.overflow = '';
}

// Close on overlay click
document.getElementById('badgeModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

// Close on Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});
</script>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
