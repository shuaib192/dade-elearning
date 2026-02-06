<?php
/**
 * DADE Learn - Reset Password Page
 * Set new password form
 */

$pageTitle = 'Reset Password';
$token = $_GET['token'] ?? '';
require_once APP_ROOT . '/views/layouts/header.php';
?>

<div class="auth-wrapper">
    <div class="auth-container auth-container-centered">
        <div class="auth-form-section">
            <div class="auth-form-container">
                <div class="auth-header">
                    <div class="auth-icon">
                        <i class="fas fa-key"></i>
                    </div>
                    <h1 class="auth-title">Set New Password</h1>
                    <p class="auth-subtitle">
                        Your new password must be at least 8 characters long.
                    </p>
                </div>
                
                <!-- Flash Messages -->
                <?php if ($error = flash('error')): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle alert-icon"></i>
                    <div class="alert-content"><?php echo e($error); ?></div>
                </div>
                <?php endif; ?>
                
                <form action="<?php echo url('reset-password'); ?>" method="POST" class="auth-form">
                    <?php echo csrf_field(); ?>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-envelope-open-text"></i>
                        We sent a code to <strong><?php echo e(Session::get('reset_email')); ?></strong>
                    </div>

                    <div class="form-group">
                        <label for="code" class="form-label">Verification Code</label>
                        <input type="text" id="code" name="code" class="form-input text-center text-lg tracking-widest" 
                               placeholder="123456" required maxlength="6" pattern="[0-9]*" inputmode="numeric" 
                               style="letter-spacing: 0.5em; font-size: 1.25rem; font-weight: bold;">
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">New Password</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="password" name="password" class="form-input" 
                                   placeholder="Enter new password" required minlength="8">
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirm" class="form-label">Confirm Password</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="password_confirm" name="password_confirm" class="form-input" 
                                   placeholder="Confirm new password" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('password_confirm')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        <i class="fas fa-check"></i>
                        Reset Password
                    </button>
                </form>
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
.password-input-wrapper {
    position: relative;
}
.password-toggle {
    position: absolute;
    right: var(--space-4);
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-400);
    padding: var(--space-2);
}
.password-toggle:hover {
    color: var(--primary);
}
</style>

<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = input.nextElementSibling.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
