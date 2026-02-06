<?php
// --- SETUP ---
$page_title = 'Verify Certificate';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/header.php';

$verification_result = null;
$error_message = '';
$search_code = '';

if (isset($_GET['code']) && !empty($_GET['code'])) {
    $search_code = trim($_GET['code']);
    
    $sql = "SELECT u.username as student_name, c.title as course_title, cert.issue_date, cert.certificate_code
            FROM certificates cert
            JOIN users u ON cert.student_id = u.id
            JOIN courses c ON cert.course_id = c.id
            WHERE cert.certificate_code = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $search_code);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $verification_result = $result->fetch_assoc();
    } else {
        $error_message = "No valid certificate was found for the ID: " . e($search_code);
    }
    $stmt->close();
}
?>

<div class="container">
    <div class="page-header"><h1>Certificate Verification</h1></div>
    
    <div class="form-container-wide">
        <h2>Enter a Certificate ID</h2>
        <p>Enter the unique verification code found on the certificate to confirm its authenticity.</p>
        <form action="verify_certificate.php" method="GET">
            <div class="form-group">
                <label for="code" class="sr-only">Verification Code</label>
                <input type="text" id="code" name="code" value="<?php echo e($search_code); ?>" placeholder="e.g., DADE-..." required>
            </div>
            <button type="submit" class="button button-primary form-button">Verify</button>
        </form>
    </div>

    <?php if ($verification_result): ?>
        <div class="management-section verification-success">
            <h2><span class="icon">✔️</span> Certificate is Valid</h2>
            <div class="verification-details">
                <p><strong>Student:</strong> <?php echo e($verification_result['student_name']); ?></p>
                <p><strong>Course:</strong> <?php echo e($verification_result['course_title']); ?></p>
                <p><strong>Date Issued:</strong> <?php echo date('F j, Y', strtotime($verification_result['issue_date'])); ?></p>
                <p><strong>Certificate ID:</strong> <?php echo e($verification_result['certificate_code']); ?></p>
            </div>
        </div>
    <?php elseif (!empty($search_code)): ?>
        <div class="management-section verification-error">
            <h2><span class="icon">❌</span> Verification Failed</h2>
            <p><?php echo e($error_message); ?></p>
        </div>
    <?php endif; ?>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>