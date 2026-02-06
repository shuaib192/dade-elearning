<?php
/**
 * DADE Learn - Forgot Password Page
 * Password reset request form
 */

$pageTitle = 'Forgot Password';
require_once APP_ROOT . '/views/layouts/header.php';
?>

<div class="auth-wrapper">
    <div class="auth-container auth-container-centered">
        <div class="auth-form-section">
            <div class="auth-form-container">
                <div class="auth-header">
                    <div class="auth-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h1 class="auth-title">Forgot Password?</h1>
                    <p class="auth-subtitle">
                        No worries! Enter your email and we'll send you reset instructions.
                    </p>
                </div>
                
                <!-- Flash Messages -->
                <?php if ($error = flash('error')): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle alert-icon"></i>
                    <div class="alert-content"><?php echo e($error); ?></div>
                </div>
                <?php endif; ?>
                
                <?php if ($success = flash('success')): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle alert-icon"></i>
                    <div class="alert-content"><?php echo e($success); ?></div>
                </div>
                <?php endif; ?>
                
                <form action="<?php echo url('forgot-password'); ?>" method="POST" class="auth-form">
                    <?php echo csrf_field(); ?>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="form-input" 
                               placeholder="you@example.com" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        <i class="fas fa-paper-plane"></i>
                        Send Reset Link
                    </button>
                </form>
                
                <div class="auth-footer-link">
                    <a href="<?php echo url('login'); ?>">
                        <i class="fas fa-arrow-left"></i>
                        Back to Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.auth-wrapper { min-height: calc(100vh - var(--header-height)); display: flex; }
.auth-container-centered {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: var(--space-10);
    background: var(--gray-50);
}
.auth-form-section {
    background: var(--white);
    border-radius: var(--radius-2xl);
    box-shadow: var(--shadow-lg);
    padding: var(--space-10);
}
.auth-form-container {
    width: 100%;
    max-width: 400px;
}
.auth-header {
    text-align: center;
    margin-bottom: var(--space-8);
}
.auth-icon {
    width: 64px;
    height: 64px;
    background: var(--primary-50);
    color: var(--primary);
    border-radius: var(--radius-xl);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: var(--text-2xl);
    margin: 0 auto var(--space-5);
}
.auth-title {
    font-size: var(--text-2xl);
    margin-bottom: var(--space-2);
}
.auth-subtitle {
    color: var(--text-secondary);
    margin: 0;
}
.auth-footer-link {
    text-align: center;
    margin-top: var(--space-6);
}
.auth-footer-link a {
    color: var(--text-secondary);
    display: inline-flex;
    align-items: center;
    gap: var(--space-2);
}
.auth-footer-link a:hover {
    color: var(--primary);
}
</style>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
