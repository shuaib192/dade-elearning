<?php
/**
 * DADE Learn - Verification Notice
 * Page shown to unverified users trying to access restricted features
 */

$pageTitle = 'Verification Required';
require_once APP_ROOT . '/views/layouts/header.php';
?>

<div class="verification-notice-container">
    <div class="notice-card">
        <div class="notice-icon">
            <i class="fas fa-envelope-open-text"></i>
        </div>
        <h1>Email Verification Required</h1>
        <p>You need to verify your email address before you can access this feature. We sent a verification link to your email when you registered.</p>
        
        <div class="notice-actions">
            <a href="<?php echo url('resend-verification'); ?>" class="btn btn-primary">Resend Verification Email</a>
            <a href="<?php echo url('dashboard'); ?>" class="btn btn-secondary">Go to Dashboard</a>
        </div>
        
        <p class="notice-footer">
            Didn't receive the email? Check your spam folder or click the button above to resend.
        </p>
    </div>
</div>

<style>
.verification-notice-container {
    padding: var(--space-20) var(--space-4);
    display: flex;
    justify-content: center;
    background: var(--gray-50);
    min-height: 70vh;
}
.notice-card {
    background: var(--white);
    padding: var(--space-10);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-lg);
    max-width: 500px;
    text-align: center;
}
.notice-icon {
    width: 80px;
    height: 80px;
    background: #fef3c7;
    color: #d97706;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 40px;
    margin: 0 auto var(--space-6);
}
.notice-card h1 {
    font-size: var(--text-2xl);
    margin-bottom: var(--space-4);
    color: var(--text-primary);
}
.notice-card p {
    color: var(--text-secondary);
    line-height: 1.6;
    margin-bottom: var(--space-8);
}
.notice-actions {
    display: flex;
    flex-direction: column;
    gap: var(--space-4);
    margin-bottom: var(--space-8);
}
.notice-footer {
    font-size: 14px;
    color: var(--text-muted) !important;
}
</style>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
