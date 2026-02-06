<?php
// --- SETUP ---
$page_title = 'Reset Password';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include configs
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$base = defined('BASE_URL') ? BASE_URL : '';

// If user is already logged in, redirect them
if (isset($_SESSION['user_id'])) {
    redirect('/dashboard.php');
}

$errors = [];
$success_message = '';
$token = $_GET['token'] ?? '';
$valid_token = false;
$user_id = null;

// Validate token
if (!empty($token)) {
    $stmt = $db->prepare("SELECT pr.user_id, pr.expires_at, pr.used, u.email 
                          FROM password_resets pr 
                          JOIN users u ON pr.user_id = u.id 
                          WHERE pr.token = ? LIMIT 1");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $reset = $result->fetch_assoc();
        
        if ($reset['used']) {
            $errors[] = 'This reset link has already been used.';
        } elseif (strtotime($reset['expires_at']) < time()) {
            $errors[] = 'This reset link has expired. Please request a new one.';
        } else {
            $valid_token = true;
            $user_id = $reset['user_id'];
        }
    } else {
        $errors[] = 'Invalid reset link. Please request a new one.';
    }
    $stmt->close();
} else {
    $errors[] = 'No reset token provided.';
}

// --- FORM SUBMISSION ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $valid_token) {
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    
    if (empty($password)) {
        $errors[] = 'Please enter a new password.';
    } elseif (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long.';
    }
    
    if ($password !== $password_confirm) {
        $errors[] = 'Passwords do not match.';
    }
    
    if (empty($errors)) {
        // Hash the new password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Update user password
        $update_stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
        $update_stmt->bind_param("si", $hashed_password, $user_id);
        $update_stmt->execute();
        $update_stmt->close();
        
        // Mark token as used
        $mark_stmt = $db->prepare("UPDATE password_resets SET used = 1 WHERE token = ?");
        $mark_stmt->bind_param("s", $token);
        $mark_stmt->execute();
        $mark_stmt->close();
        
        $success_message = 'Your password has been successfully reset!';
        $valid_token = false; // Hide the form
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="form-page-wrapper">
    <div class="container">
        <div class="form-container" style="max-width: 450px; margin: 0 auto;">
            <h2 class="form-title">Reset Your Password</h2>
            <p class="form-subtitle">Enter your new password below.</p>

            <?php if ($success_message): ?>
                <div class="form-message success" role="status">
                    <p><?php echo e($success_message); ?></p>
                    <p style="margin-top: 1rem;">
                        <a href="<?php echo $base; ?>/logins.php" class="button button-primary" style="display: inline-block; margin-top: 0.5rem;">
                            <i class="fas fa-sign-in-alt"></i> Login Now
                        </a>
                    </p>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors) && !$valid_token): ?>
                <div class="form-message error" role="alert">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo e($error); ?></p>
                    <?php endforeach; ?>
                </div>
                <div class="form-footer-link" style="margin-top: 2rem;">
                    <a href="<?php echo $base; ?>/forgot_password.php" class="button button-secondary">
                        <i class="fas fa-redo"></i> Request New Reset Link
                    </a>
                </div>
            <?php endif; ?>

            <?php if ($valid_token): ?>
                <?php if (!empty($errors)): ?>
                    <div class="form-message error" role="alert">
                        <?php foreach ($errors as $error): ?>
                            <p><?php echo e($error); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo $base; ?>/reset_password.php?token=<?php echo e($token); ?>" method="POST" novalidate>
                    <div class="form-group">
                        <label for="password" style="text-align: left;">New Password</label>
                        <input type="password" id="password" name="password" required 
                               placeholder="Enter new password (min 8 characters)" minlength="8">
                    </div>
                    <div class="form-group">
                        <label for="password_confirm" style="text-align: left;">Confirm New Password</label>
                        <input type="password" id="password_confirm" name="password_confirm" required 
                               placeholder="Confirm your new password">
                    </div>
                    <button type="submit" class="button button-primary form-button">
                        <i class="fas fa-key"></i> Reset Password
                    </button>
                </form>
            <?php endif; ?>

            <div class="form-footer-link" style="margin-top: 2rem;">
                <p><a href="<?php echo $base; ?>/logins.php"><i class="fas fa-arrow-left"></i> Back to Login</a></p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
