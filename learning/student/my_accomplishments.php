<?php
// --- SETUP AND SECURITY ---
$page_title = 'My Accomplishments';
$allowed_roles = [3]; // Primarily for students, but others can view if they have accomplishments
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// --- DATA FETCHING ---
$student_id = $_SESSION['user_id'];

// 1. Fetch all certificates this user has earned
$certificates = [];
$cert_sql = "SELECT c.title as course_title, cert.issue_date, cert.certificate_code
             FROM certificates cert
             JOIN courses c ON cert.course_id = c.id
             WHERE cert.student_id = ?
             ORDER BY cert.issue_date DESC";
$cert_stmt = $db->prepare($cert_sql);
$cert_stmt->bind_param("i", $student_id);
$cert_stmt->execute();
$cert_result = $cert_stmt->get_result();
if ($cert_result) {
    while ($row = $cert_result->fetch_assoc()) {
        $certificates[] = $row;
    }
}
$cert_stmt->close();


// 2. Fetch all badges this user has earned
$badges = [];
$badge_sql = "SELECT b.name, b.description, b.icon_class, ub.awarded_at
              FROM badges b
              JOIN user_badges ub ON b.id = ub.badge_id
              WHERE ub.user_id = ?
              ORDER BY ub.awarded_at DESC";
$badge_stmt = $db->prepare($badge_sql);
$badge_stmt->bind_param("i", $student_id);
$badge_stmt->execute();
$badge_result = $badge_stmt->get_result();
if ($badge_result) {
    while ($row = $badge_result->fetch_assoc()) {
        $badges[] = $row;
    }
}
$badge_stmt->close();


// --- PAGE DISPLAY ---
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1>My Accomplishments</h1>
        <a href="<?php echo $base; ?>/student/index.php" class="button button-secondary">Back to Dashboard</a>
    </div>
    <p>A record of all the certificates and badges you have earned on your learning journey.</p>

    <!-- CERTIFICATES SECTION -->
    <div class="management-section">
        <h2>My Certificates</h2>
        <?php if (empty($certificates)): ?>
            <p>You have not earned any certificates yet. Complete a course to earn your first one!</p>
        <?php else: ?>
            <div class="accomplishment-grid">
                <?php foreach ($certificates as $cert): ?>
                    <div class="certificate-card">
                        <div class="cert-card-icon">🎓</div>
                        <div class="cert-card-details">
                            <h4><?php echo e($cert['course_title']); ?></h4>
                            <small>Issued on: <?php echo date('F j, Y', strtotime($cert['issue_date'])); ?></small>
                        </div>
                        <a href="<?php echo $base; ?>/certs.php?code=<?php echo e($cert['certificate_code']); ?>" class="button button-primary button-small" target="_blank">View</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- BADGES SECTION -->
    <div class="management-section">
        <h2>My Badges</h2>
        <?php if (empty($badges)): ?>
            <p>You haven't earned any badges yet. Enroll in a course to get started!</p>
        <?php else: ?>
            <div class="badge-list">
                <?php foreach($badges as $badge): ?>
                    <div class="badge-item" title="<?php echo e($badge['description']); ?> (Awarded: <?php echo date('M j, Y', strtotime($badge['awarded_at'])); ?>)">
                        <span class="badge-icon"><?php echo e($badge['icon_class']); ?></span>
                        <span class="badge-name"><?php echo e($badge['name']); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>