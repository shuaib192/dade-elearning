<?php
/**
 * DADE Learn - Registration Page
 * Beautiful registration with role selection and Google OAuth
 */

$pageTitle = 'Create Account';
require_once APP_ROOT . '/views/layouts/header.php';
?>

<div class="auth-wrapper">
    <div class="auth-container">
        <!-- Left Side - Branding -->
        <div class="auth-branding">
            <div class="auth-branding-content">
                <h2 class="auth-branding-title">Start Learning Today</h2>
                <p class="auth-branding-text">
                    Join thousands of learners and unlock access to world-class education.
                </p>
                <div class="auth-features">
                    <div class="auth-feature">
                        <i class="fas fa-check-circle"></i>
                        <span>Access 1000+ expert-led courses</span>
                    </div>
                    <div class="auth-feature">
                        <i class="fas fa-check-circle"></i>
                        <span>Learn at your own pace, anywhere</span>
                    </div>
                    <div class="auth-feature">
                        <i class="fas fa-check-circle"></i>
                        <span>Earn recognized certificates</span>
                    </div>
                    <div class="auth-feature">
                        <i class="fas fa-check-circle"></i>
                        <span>Join a global community of learners</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Register Form -->
        <div class="auth-form-section">
            <div class="auth-form-container">
                <div class="auth-header">
                    <h1 class="auth-title">Create Account</h1>
                    <p class="auth-subtitle">
                        Already have an account? 
                        <a href="<?php echo url('login'); ?>">Sign in</a>
                    </p>
                </div>
                
                <!-- Flash Messages -->
                <?php if ($error = flash('error')): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle alert-icon"></i>
                    <div class="alert-content"><?php echo e($error); ?></div>
                </div>
                <?php endif; ?>
                
                <!-- Google Signup -->
                <a href="<?php echo url('auth/google'); ?>" class="btn-google">
                    <svg width="24" height="24" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Sign up with Google
                </a>
                
                <div class="auth-divider">
                    <span>or sign up with email</span>
                </div>
                
                <!-- Register Form -->
                <form action="<?php echo url('register'); ?>" method="POST" class="auth-form" id="registerForm">
                    <?php echo csrf_field(); ?>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="username" class="form-label">Full Name</label>
                            <input type="text" id="username" name="username" class="form-input" 
                                   placeholder="John Doe" required
                                   value="<?php echo e($_POST['username'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="form-input" 
                               placeholder="you@example.com" required
                               value="<?php echo e($_POST['email'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="password" name="password" class="form-input" 
                                   placeholder="Create a strong password" required minlength="8">
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-strength" id="passwordStrength">
                            <div class="strength-bar"></div>
                        </div>
                        <span class="form-hint">Must be at least 8 characters</span>
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirm" class="form-label">Confirm Password</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="password_confirm" name="password_confirm" class="form-input" 
                                   placeholder="Confirm your password" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('password_confirm')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Role Selection -->
                    <div class="form-group">
                        <label class="form-label">I want to:</label>
                        <div class="role-selector">
                            <label class="role-option">
                                <input type="radio" name="role" value="volunteer" checked>
                                <div class="role-card">
                                    <i class="fas fa-user-graduate"></i>
                                    <span class="role-title">Learn</span>
                                    <span class="role-desc">Enroll in courses</span>
                                </div>
                            </label>
                            <label class="role-option">
                                <input type="radio" name="role" value="mentor">
                                <div class="role-card">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                    <span class="role-title">Teach</span>
                                    <span class="role-desc">Create courses</span>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="terms" required>
                            <span class="checkbox-custom"></span>
                            I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        Create Account
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.auth-wrapper { min-height: calc(100vh - var(--header-height)); display: flex; }
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
.form-row {
    display: grid;
    gap: var(--space-4);
}
@media (min-width: 480px) {
    .form-row {
        grid-template-columns: repeat(2, 1fr);
    }
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
.password-strength {
    margin-top: var(--space-2);
    height: 4px;
    background: var(--gray-200);
    border-radius: var(--radius-full);
    overflow: hidden;
}
.strength-bar {
    height: 100%;
    width: 0;
    background: var(--error);
    transition: all var(--transition-fast);
}
.password-strength.weak .strength-bar { width: 33%; background: var(--error); }
.password-strength.medium .strength-bar { width: 66%; background: var(--warning); }
.password-strength.strong .strength-bar { width: 100%; background: var(--success); }
.role-selector {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--space-4);
}
.role-option input {
    display: none;
}
.role-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: var(--space-5);
    border: 2px solid var(--gray-200);
    border-radius: var(--radius-lg);
    cursor: pointer;
    transition: all var(--transition-fast);
    text-align: center;
}
.role-card i {
    font-size: var(--text-2xl);
    color: var(--gray-400);
    margin-bottom: var(--space-2);
    transition: color var(--transition-fast);
}
.role-title {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: var(--space-1);
}
.role-desc {
    font-size: var(--text-sm);
    color: var(--text-muted);
}
.role-option input:checked + .role-card {
    border-color: var(--primary);
    background: var(--primary-50);
}
.role-option input:checked + .role-card i {
    color: var(--primary);
}
.checkbox-label {
    display: flex;
    align-items: flex-start;
    gap: var(--space-3);
    cursor: pointer;
    font-size: var(--text-sm);
    color: var(--text-secondary);
    line-height: 1.5;
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
    flex-shrink: 0;
    margin-top: 2px;
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
.checkbox-label a {
    color: var(--primary);
}
@media (max-width: 768px) {
    .auth-form-section {
        padding: var(--space-6) var(--space-4);
    }
    .auth-title {
        font-size: var(--text-2xl);
    }
    .role-selector {
        grid-template-columns: 1fr;
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

// Password strength indicator
document.getElementById('password').addEventListener('input', function(e) {
    const password = e.target.value;
    const strength = document.getElementById('passwordStrength');
    
    strength.classList.remove('weak', 'medium', 'strong');
    
    if (password.length === 0) {
        return;
    } else if (password.length < 6) {
        strength.classList.add('weak');
    } else if (password.length < 10 || !/[A-Z]/.test(password) || !/[0-9]/.test(password)) {
        strength.classList.add('medium');
    } else {
        strength.classList.add('strong');
    }
});
</script>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
