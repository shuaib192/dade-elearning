<?php
// --- SETUP ---
$page_title = 'Verify Certificate';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/header.php';

$verification_result = null;
$error_message = '';

if (isset($_GET['code']) && !empty($_GET['code'])) {
    $certificate_code = trim($_GET['code']);
    
    $sql = "SELECT u.username as student_name, c.title as course_title, cert.issue_date
            FROM certificates cert
            JOIN users u ON cert.student_id = u.id
            JOIN courses c ON cert.course_id = c.id
            WHERE cert.certificate_code = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $certificate_code);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $verification_result = $result->fetch_assoc();
    } else {
        $error_message = "No valid certificate was found for the provided code.";
    }
    $stmt->close();
}
?>

<div class="container">
    <div class="page-header"><h1>Certificate Verification</h1></div>
    
    <div class="form-container-wide">
        <h2>Enter a Certificate ID</h2>
        <form action="verify.php" method="GET">
            <div class="form-group">
                <label for="code">Verification Code</label>
                <input type="text" id="code" name="code" value="<?php echo e($certificate_code ?? ''); ?>" placeholder="e.g., DADE-..." required>
            </div>
            <button type="submit" class="button button-primary form-button">Verify</button>
        </form>
    </div>

    <?php if ($verification_result): ?>
        <div class="management-section verification-success">
            <h2>✔️ Certificate is Valid</h2>
            <div class="verification-details">
                <p><strong>Student:</strong> <?php echo e($verification_result['student_name']); ?></p>
                <p><strong>Course:</strong> <?php echo e($verification_result['course_title']); ?></p>
                <p><strong>Date Issued:</strong> <?php echo date('F j, Y', strtotime($verification_result['issue_date'])); ?></p>
            </div>
        </div>
    <?php elseif ($error_message): ?>
        <div class="management-section verification-error">
            <h2>❌ Verification Failed</h2>
            <p><?php echo e($error_message); ?></p>
        </div>
    <?php endif; ?>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>