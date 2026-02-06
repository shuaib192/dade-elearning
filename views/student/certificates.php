<?php
/**
 * DADE Learn - Certificates Page
 * Display all earned certificates
 */

$pageTitle = 'My Certificates';
$db = getDB();
$user = Auth::user();
$userId = $user['id'];

// Get user certificates
$certificates = [];
$stmt = $db->prepare("
    SELECT cert.*, c.title as course_title, c.cover_image as course_thumbnail
    FROM certificates cert
    JOIN courses c ON cert.course_id = c.id
    WHERE cert.user_id = ?
    ORDER BY cert.issued_at DESC
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $certificates[] = $row;
}

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
            <a href="<?php echo url('dashboard'); ?>" class="sidebar-link">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="<?php echo url('dashboard/courses'); ?>" class="sidebar-link">
                <i class="fas fa-book-open"></i>
                <span>My Courses</span>
            </a>
            <a href="<?php echo url('dashboard/certificates'); ?>" class="sidebar-link active">
                <i class="fas fa-certificate"></i>
                <span>Certificates</span>
            </a>
            <a href="<?php echo url('dashboard/profile'); ?>" class="sidebar-link">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>
        </nav>
    </aside>
    
    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="dashboard-header">
            <div class="welcome-text">
                <h1>My Certificates</h1>
                <p>View and download your earned certificates.</p>
            </div>
        </div>
        
        <?php if (empty($certificates)): ?>
        <div class="empty-state-card">
            <i class="fas fa-award"></i>
            <h3>No certificates yet</h3>
            <p>Complete courses to earn certificates that showcase your achievements.</p>
            <a href="<?php echo url('courses'); ?>" class="btn btn-primary">Browse Courses</a>
        </div>
        <?php else: ?>
        <div class="certificates-grid">
            <?php foreach ($certificates as $cert): ?>
            <div class="certificate-entry shadow-sm">
                <div class="cert-icon-box">
                    <i class="fas fa-certificate"></i>
                </div>
                <div class="cert-info">
                    <h3 class="cert-title"><?php echo e($cert['course_title']); ?></h3>
                    <div class="cert-meta">
                        <span><i class="far fa-calendar-alt"></i> Issued: <?php echo date('M d, Y', strtotime($cert['issued_at'])); ?></span>
                        <span><i class="fas fa-fingerprint"></i> ID: <?php echo e($cert['certificate_number'] ?? substr($cert['id'], 0, 8)); ?></span>
                    </div>
                </div>
                <div class="cert-actions">
                    <a href="<?php echo url('certificate/' . $cert['id']); ?>" class="btn btn-sm btn-outline" target="_blank">
                        <i class="fas fa-eye"></i> View
                    </a>
                    <a href="<?php echo url('certificate/' . $cert['id'] . '/download'); ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-download"></i> Download
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </main>
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
}
.sidebar-username {
    margin-bottom: var(--space-1);
    font-size: var(--text-lg);
}
.sidebar-role {
    font-size: var(--text-sm);
    color: var(--text-muted);
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
.dashboard-main {
    flex: 1;
    padding: var(--space-8);
    max-width: 1200px;
}
.dashboard-header {
    margin-bottom: var(--space-6);
}
.welcome-text h1 {
    font-size: var(--text-2xl);
    margin-bottom: var(--space-1);
}
.welcome-text p {
    color: var(--text-secondary);
    margin: 0;
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
.certificates-grid {
    display: flex;
    flex-direction: column;
    gap: var(--space-4);
}
.certificate-entry {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: var(--space-4);
    display: flex;
    align-items: center;
    gap: var(--space-4);
    border: 1px solid var(--gray-100);
}
.cert-icon-box {
    width: 60px;
    height: 60px;
    background: var(--primary-50);
    color: var(--primary);
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}
.cert-info {
    flex: 1;
}
.cert-title {
    font-size: var(--text-lg);
    margin-bottom: var(--space-1);
    color: var(--text-primary);
}
.cert-meta {
    display: flex;
    gap: var(--space-4);
    font-size: var(--text-sm);
    color: var(--text-muted);
}
.cert-meta span {
    display: flex;
    align-items: center;
    gap: var(--space-1);
}
.cert-actions {
    display: flex;
    gap: var(--space-2);
}

@media (max-width: 640px) {
    .certificate-entry {
        flex-direction: column;
        align-items: flex-start;
        gap: var(--space-4);
    }
    .cert-actions {
        width: 100%;
        justify-content: space-between;
    }
}
</style>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
