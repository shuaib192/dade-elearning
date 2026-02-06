<?php
// --- SETUP ---
$page_title = 'Register';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$errors = [];
$username = '';
$email = '';

// --- FORM SUBMISSION LOGIC ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username)) $errors[] = 'Username is required.';
    if (empty($email)) $errors[] = 'Email is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email address.';
    if (empty($password)) $errors[] = 'Password is required.';
    if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters long.';
    if ($password !== $confirm_password) $errors[] = 'The two passwords do not match.';

    if (empty($errors)) {
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = 'An account with this email address already exists. Please <a href="login.php">log in</a>.';
        }
        $stmt->close();
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            $new_user_id = $stmt->insert_id;
            
            // Award the "First Steps" badge upon successful registration.
            award_badge($new_user_id, 'REGISTER', $db);
            
            $_SESSION['success_message'] = 'Registration successful! You can now log in.';
            redirect('/login.php');
        } else {
            $errors[] = 'Registration failed due to a system error. Please try again later.';
        }
        $stmt->close();
    }
}

// --- PAGE DISPLAY ---
require_once __DIR__ . '/includes/header.php';
?>

<div class="form-page-wrapper">
    <div class="container" style="display: flex; justify-content:center;">
        <div class="form-container">
            <h2 class="form-title">Create Your Account</h2>
            <p class="form-subtitle">Join our community and start your learning journey today.</p>

            <?php if (!empty($errors)): ?>
                <div class="form-message error" role="alert">
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?php echo $base; ?>/register.php" method="POST" novalidate>
                <div class="form-group">
                    <label for="username">Full Name</label>
                    <input type="text" id="username" name="username" value="<?php echo e($username); ?>" required aria-describedby="username-help">
                    <small id="username-help" class="form-text">Your public name on the platform.</small>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?php echo e($email); ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required aria-describedby="password-help">
                    <small id="password-help" class="form-text">Must be at least 8 characters long.</small>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="button button-primary form-button">Register</button>
            </form>

            <div class="form-footer-link">
                <p>Already have an account? <a href="<?php echo $base; ?>/logins.php">Log in here</a>.</p>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>