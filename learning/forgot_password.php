<?php
// --- SETUP ---
$page_title = 'Forgot Password';
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
$email = '';

// --- FORM SUBMISSION ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    
    if (empty($email)) {
        $errors[] = 'Please enter your email address.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    
    if (empty($errors)) {
        // Check if user exists
        $stmt = $db->prepare("SELECT id, first_name FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Generate secure token
            $token = bin2hex(random_bytes(32));
            $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Delete any existing tokens for this user
            $delete_stmt = $db->prepare("DELETE FROM password_resets WHERE user_id = ?");
            $delete_stmt->bind_param("i", $user['id']);
            $delete_stmt->execute();
            $delete_stmt->close();
            
            // Insert new token
            $insert_stmt = $db->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
            $insert_stmt->bind_param("iss", $user['id'], $token, $expires_at);
            $insert_stmt->execute();
            $insert_stmt->close();
            
            require_once __DIR__ . '/includes/mail_helper.php';

            // Build reset link
            $reset_link = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . 
                          ($_SERVER['HTTP_HOST'] ?? 'localhost') . 
                          $base . '/reset_password.php?token=' . $token;
            
            $subject = "Password Reset Request - DADE Foundation";
            $body = "
                <h3>Password Reset Request</h3>
                <p>Hello,</p>
                <p>We received a request to reset your password. Click the link below to set a new password:</p>
                <p><a href='$reset_link'>$reset_link</a></p>
                <p>This link will expire in 1 hour.</p>
                <p>If you didn't ask for this, you can ignore this email.</p>
            ";

            $email_result = send_email($email, $subject, $body, true);
            if ($email_result === true) {
                $success_message = "A password reset link has been sent to your email address.";
            } else {
                $success_message = "Error sending email. Please contact support. (Debug: $email_result)";
                // FOR DEV MODE: Keep showing the link if email fails
                $_SESSION['reset_link'] = $reset_link;
            }
        } else {
            // Don't reveal if email exists or not (security)
            $success_message = "If an account with that email exists, a password reset link has been sent.";
        }
        $stmt->close();
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="form-page-wrapper">
    <div class="container">
        <div class="form-container" style="max-width: 450px; margin: 0 auto;">
            <h2 class="form-title">Forgot Password?</h2>
            <p class="form-subtitle">Enter your email address and we'll send you a link to reset your password.</p>

            <?php if ($success_message): ?>
                <div class="form-message success" role="status">
                    <p><?php echo e($success_message); ?></p>
                    <?php if (isset($_SESSION['reset_link'])): ?>
                        <p style="margin-top: 1rem; font-size: 0.85rem;">
                            <strong>Development Mode:</strong><br>
                            <a href="<?php echo e($_SESSION['reset_link']); ?>" style="word-break: break-all;">
                                <?php echo e($_SESSION['reset_link']); ?>
                            </a>
                        </p>
                        <?php unset($_SESSION['reset_link']); ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="form-message error" role="alert">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo e($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!$success_message): ?>
            <form action="<?php echo $base; ?>/forgot_password.php" method="POST" novalidate>
                <div class="form-group">
                    <label for="email" style="text-align: left;">Email Address</label>
                    <input type="email" id="email" name="email" value="<?php echo e($email); ?>" required 
                           placeholder="Enter your email address">
                </div>
                <button type="submit" class="button button-primary form-button">
                    <i class="fas fa-paper-plane"></i> Send Reset Link
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
