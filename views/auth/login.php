<?php
/**
 * DADE Learn - Login Page
 * Beautiful login with email and Google OAuth
 */

$pageTitle = 'Login';
$pageStyles = '
.auth-wrapper { min-height: calc(100vh - var(--header-height)); display: flex; }
';

require_once APP_ROOT . '/views/layouts/header.php';
?>

<div class="auth-wrapper">
    <div class="auth-container">
        <!-- Left Side - Branding -->
        <div class="auth-branding">
            <div class="auth-branding-content">
                <h2 class="auth-branding-title">Welcome Back</h2>
                <p class="auth-branding-text">
                    Continue your learning journey with thousands of expert-led courses.
                </p>
                <div class="auth-features">
                    <div class="auth-feature">
                        <i class="fas fa-check-circle"></i>
                        <span>Access all your enrolled courses</span>
                    </div>
                    <div class="auth-feature">
                        <i class="fas fa-check-circle"></i>
                        <span>Track your learning progress</span>
                    </div>
                    <div class="auth-feature">
                        <i class="fas fa-check-circle"></i>
                        <span>Earn certificates on completion</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Login Form -->
        <div class="auth-form-section">
            <div class="auth-form-container">
                <div class="auth-header">
                    <h1 class="auth-title">Sign In</h1>
                    <p class="auth-subtitle">
                        Don't have an account? 
                        <a href="<?php echo url('register'); ?>">Create one free</a>
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
                
                <!-- Google Login -->
                <a href="<?php echo url('auth/google'); ?>" class="btn-google">
                    <svg width="24" height="24" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Continue with Google
                </a>
                
                <div class="auth-divider">
                    <span>or sign in with email</span>
                </div>
                
                <!-- Login Form -->
                <form action="<?php echo url('login'); ?>" method="POST" class="auth-form" id="loginForm">
                    <?php echo csrf_field(); ?>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="form-input" 
                               placeholder="you@example.com" required 
                               value="<?php echo e($_POST['email'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <div class="form-label-row">
                            <label for="password" class="form-label">Password</label>
                            <a href="<?php echo url('forgot-password'); ?>" class="form-link">Forgot password?</a>
                        </div>
                        <div class="password-input-wrapper">
                            <input type="password" id="password" name="password" class="form-input" 
                                   placeholder="Enter your password" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember" value="1">
                            <span class="checkbox-custom"></span>
                            Remember me for 30 days
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        Sign In
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.auth-container {
    display: flex;
    width: 100%;
    min-height: calc(100vh - var(--header-height));
}
.auth-branding {
    display: none;
    width: 45%;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
    padding: var(--space-12);
    position: relative;
    overflow: hidden;
}
.auth-branding::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -30%;
    width: 100%;
    height: 200%;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
}
@media (min-width: 1024px) {
    .auth-branding {
        display: flex;
        align-items: center;
        justify-content: center;
    }
}
.auth-branding-content {
    position: relative;
    z-index: 1;
    color: var(--white);
    max-width: 400px;
}
.auth-branding-title {
    font-size: var(--text-4xl);
    margin-bottom: var(--space-4);
    color: var(--white);
}
.auth-branding-text {
    font-size: var(--text-lg);
    color: rgba(255,255,255,0.9);
    margin-bottom: var(--space-8);
}
.auth-features {
    display: flex;
    flex-direction: column;
    gap: var(--space-4);
}
.auth-feature {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    font-size: var(--text-base);
    color: rgba(255,255,255,0.9);
}
.auth-feature i {
    color: var(--accent);
}
.auth-form-section {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: var(--space-10);
    background: var(--white);
}
.auth-form-container {
    width: 100%;
    max-width: 420px;
}
.auth-header {
    text-align: center;
    margin-bottom: var(--space-8);
}
.auth-title {
    font-size: var(--text-3xl);
    margin-bottom: var(--space-2);
}
.auth-subtitle {
    color: var(--text-secondary);
    margin: 0;
}
.auth-subtitle a {
    color: var(--primary);
    font-weight: 600;
}
.btn-google {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-3);
    width: 100%;
    padding: var(--space-4);
    background: var(--white);
    border: 2px solid var(--gray-200);
    border-radius: var(--radius-lg);
    font-weight: 600;
    color: var(--text-primary);
    transition: all var(--transition-fast);
}
.btn-google:hover {
    border-color: var(--gray-300);
    background: var(--gray-50);
    color: var(--text-primary);
}
.auth-divider {
    display: flex;
    align-items: center;
    margin: var(--space-6) 0;
    color: var(--text-muted);
    font-size: var(--text-sm);
}
.auth-divider::before,
.auth-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--gray-200);
}
.auth-divider span {
    padding: 0 var(--space-4);
}
.form-label-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--space-2);
}
.form-link {
    font-size: var(--text-sm);
    color: var(--primary);
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
.checkbox-label {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    cursor: pointer;
    font-size: var(--text-sm);
    color: var(--text-secondary);
}
.checkbox-label input {
    display: none;
}
.checkbox-custom {
    width: 20px;
    height: 20px;
    border: 2px solid var(--gray-300);
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all var(--transition-fast);
}
.checkbox-label input:checked + .checkbox-custom {
    background: var(--primary);
    border-color: var(--primary);
}
.checkbox-label input:checked + .checkbox-custom::after {
    content: '✓';
    color: var(--white);
    font-size: 12px;
}
@media (max-width: 768px) {
    .auth-form-section {
        padding: var(--space-6) var(--space-4);
    }
    .auth-title {
        font-size: var(--text-2xl);
    }
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
