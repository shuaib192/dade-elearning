<?php
// --- SETUP ---
$page_title = 'Login';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// If user is already logged in, redirect them away
if (isset($_SESSION['user_id'])) {
    redirect('/dashboard.php');
}

// THIS IS THE CRITICAL FIX: We must include the google_config file
require_once __DIR__ . '/google_config.php'; 
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

// --- Create the Google Auth URL Manually ---
$google_auth_params = [
    'scope' => 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email',
    'access_type' => 'offline',
    'include_granted_scopes' => 'true',
    'response_type' => 'code',
    'redirect_uri' => GOOGLE_REDIRECT_URI,
    'client_id' => GOOGLE_CLIENT_ID
];
$google_auth_url = GOOGLE_AUTH_URL . '?' . http_build_query($google_auth_params);

// --- INITIALIZE VARIABLES & HANDLE MESSAGES ---
$errors = [];
$email = '';
$success_message = $_SESSION['success_message'] ?? null;
unset($_SESSION['success_message']);
$error_message_from_session = $_SESSION['error_message'] ?? null;
unset($_SESSION['error_message']);
if ($error_message_from_session) { $errors[] = $error_message_from_session; }


// --- STANDARD LOGIN FORM SUBMISSION LOGIC ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $errors[] = 'Both email and password are required.';
    }

    if (empty($errors)) {
        $stmt = $db->prepare("SELECT id, username, password, role_id, status FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if ($user['status'] !== 'active') {
                $errors[] = 'Your account is inactive or has been banned. Please contact support.';
            } elseif (password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role_id'] = $user['role_id'];
                redirect('/dashboard.php');
            } else {
                $errors[] = 'Invalid email or password.';
            }
        } else {
            $errors[] = 'Invalid email or password.';
        }
        $stmt->close();
    }
}

// --- PAGE DISPLAY ---
require_once __DIR__ . '/includes/header.php';
?>

<center>
<div class="form-page-wrapper">
    <div class="container">
        <div class="form-container">
            <h2 class="form-title">Welcome Back!</h2>
            <p class="form-subtitle">Log in to access your dashboard and courses.</p>

            <?php if ($success_message): ?><div class="form-message success" role="status"><?php echo e($success_message); ?></div><?php endif; ?>
            <?php if (!empty($errors)): ?>
                <div class="form-message error" role="alert">
                    <?php foreach ($errors as $error): ?><p><?php echo $error; ?></p><?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo $base; ?>/login.php" method="POST" novalidate>
                <div class="form-group">
                    <label for="email" style="text-align: left;">Email Address</label>
                    <input type="email" id="email" name="email" value="<?php echo e($email); ?>" required>
                </div>
                <div class="form-group">
                    <label for="password" style="text-align: left;">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="button button-primary form-button">Login</button>
            </form>
            
            <div class="social-login-divider"><span>OR</span></div>
            
            <a href="<?php echo htmlspecialchars($google_auth_url); ?>" class="button social-login-button google-button">
                <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google logo">
                Sign in with Google
            </a>

            <div class="form-footer-link">
                <p>Don't have an account? <a href="<?php echo $base; ?>/register.php">Register now</a>.</p>
            </div>
        </div>
    </div>
</div>

</center>

<?php
require_once __DIR__ . '/includes/footer.php';
?>