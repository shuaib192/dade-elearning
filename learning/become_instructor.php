<?php
// --- SETUP ---
$page_title = 'Become an Instructor';
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$user_id = $_SESSION['user_id'] ?? null;
$user_status = '';
$user_role_id = 0;

if ($user_id) {
    $stmt = $db->prepare("SELECT status, role_id FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $user_status = $result['status'];
    $user_role_id = $result['role_id'];
    $stmt->close();
}

$errors = [];
$success_message = '';

// --- FORM HANDLING ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$user_id) {
        $_SESSION['error_message'] = "You must be logged in to apply.";
        redirect('login.php');
    }

    $expertise = trim($_POST['expertise']);
    $motivation = trim($_POST['motivation']);

    if (empty($expertise)) $errors[] = "Please describe your area of expertise.";
    if (empty($motivation)) $errors[] = "Please tell us why you want to become an instructor.";

    if (empty($errors)) {
        // Save the application text to the new columns
        $stmt = $db->prepare("UPDATE users SET status = 'pending_instructor', expertise = ?, motivation = ? WHERE id = ?");
        $stmt->bind_param("ssi", $expertise, $motivation, $user_id);
        
        if ($stmt->execute()) {
            $success_message = "Thank you! Your application has been submitted for review. Our team will review it shortly.";
            $user_status = 'pending_instructor';
        } else {
            $errors[] = "There was a system error submitting your application.";
        }
        $stmt->close();
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1>Become an Instructor</h1>
    </div>
    
    <?php if ($success_message): ?>
        <div class="form-message success" role="status">
            <h2>Application Submitted!</h2>
            <p><?php echo e($success_message); ?></p>
            <a href="<?php echo $base; ?>/dashboard.php" class="button button-primary" style="margin-top: 1rem;">Go to My Dashboard</a>
        </div>
    <?php else: ?>
        <p class="section-subtitle">Share your knowledge and passion with our community. Join us in our mission to provide accessible education for all.</p>
        
        <?php if (!$user_id): ?>
            <div class="info-box">
                <p>To apply, you first need to have an account on our platform.</p>
                <div class="actions-cell" style="justify-content: center;">
                    <a href="register.php" class="button button-primary">Create an Account</a>
                    <a href="login.php" class="button button-secondary">Login to Your Account</a>
                </div>
            </div>
        <?php elseif ($user_role_id == 2): ?>
             <div class="info-box">
                <h2>You are already an Instructor!</h2>
                <p>You can start creating courses from your dashboard.</p>
                <a href="<?php echo $base; ?>/instructor/index.php" class="button button-primary" style="margin-top: 1rem;">Go to Instructor Dashboard</a>
            </div>
        <?php elseif ($user_status === 'pending_instructor'): ?>
            <div class="info-box">
                <h2>Your Application is Under Review</h2>
                <p>Thank you for your submission. Our team is currently reviewing your application. We appreciate your patience!</p>
            </div>
        <?php else: ?>
            <div class="form-container-wide">
                <?php if (!empty($errors)): ?>
                    <div class="form-message error">
                        <?php foreach ($errors as $error) echo "<p>" . e($error) . "</p>"; ?>
                    </div>
                <?php endif; ?>
                <h3>Application Form</h3>
                <form action="become_instructor.php" method="POST">
                    <div class="form-group">
                        <label for="expertise">Your Area of Expertise</label>
                        <input type="text" id="expertise" name="expertise" placeholder="e.g., Web Accessibility, Digital Marketing, Graphic Design" required>
                    </div>
                    <div class="form-group">
                        <label for="motivation">Why do you want to teach on DADE Elearning?</label>
                        <textarea id="motivation" name="motivation" rows="6" placeholder="Tell us about your passion for teaching and inclusion." required></textarea>
                    </div>
                    <button type="submit" class="button button-primary form-button">Submit Application for Review</button>
                </form>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>