<?php
// --- SETUP & SECURITY ---
$page_title = 'My Profile';
require_once __DIR__ . '/includes/auth_check.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

// Users can only view their own profile.
$profile_id = $_GET['id'] ?? 0;
if ($profile_id != $_SESSION['user_id']) {
    $_SESSION['error_message'] = "You can only view your own profile.";
    redirect('/dashboard.php');
}

// --- DATA FETCHING ---
// Fetch user details
$stmt = $db->prepare("SELECT username, email, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $profile_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// CRITICAL FIX: If user not found (e.g. deleted account with active session), log them out
if (!$user) {
    redirect('/logout.php');
}

// Fetch user's earned badges
$badges = [];
$badge_stmt = $db->prepare("SELECT b.name, b.description, b.icon_class FROM badges b JOIN user_badges ub ON b.id = ub.badge_id WHERE ub.user_id = ? ORDER BY ub.awarded_at DESC");
$badge_stmt->bind_param("i", $profile_id);
$badge_stmt->execute();
$badge_result = $badge_stmt->get_result();
while ($row = $badge_result->fetch_assoc()) {
    $badges[] = $row;
}
$badge_stmt->close();

// --- FORM HANDLING ---
$errors = [];
$success_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Action 1: Update Profile Details
    if ($action === 'update_details') {
        $username = trim($_POST['username']);
        if (empty($username)) {
            $errors[] = "Username cannot be empty.";
        } else {
            $update_stmt = $db->prepare("UPDATE users SET username = ? WHERE id = ?");
            $update_stmt->bind_param("si", $username, $profile_id);
            if ($update_stmt->execute()) {
                $_SESSION['username'] = $username;
                $user['username'] = $username;
                $success_message = "Profile details updated successfully.";
            } else {
                $errors[] = "Failed to update profile.";
            }
            $update_stmt->close();
        }
    }

    // Action 2: Change Password
    if ($action === 'change_password') {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        $pass_stmt = $db->prepare("SELECT password FROM users WHERE id = ?");
        $pass_stmt->bind_param("i", $profile_id);
        $pass_stmt->execute();
        $stored_hash = $pass_stmt->get_result()->fetch_assoc()['password'];
        $pass_stmt->close();

        if (!password_verify($current_password, $stored_hash)) {
            $errors[] = "Your current password is not correct.";
        } elseif (strlen($new_password) < 8) {
            $errors[] = "New password must be at least 8 characters long.";
        } elseif ($new_password !== $confirm_password) {
            $errors[] = "The new passwords do not match.";
        } else {
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_pass_stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update_pass_stmt->bind_param("si", $new_hashed_password, $profile_id);
            if ($update_pass_stmt->execute()) {
                $success_message = "Password changed successfully.";
            } else {
                $errors[] = "Failed to change password.";
            }
            $update_pass_stmt->close();
        }
    }
    
    // Action 3: Update Profile Picture
    if ($action === 'update_picture') {
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['profile_picture'];
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($file['type'], $allowed_types)) {
                $target_dir_path = '/uploads/profile_pictures/';
                $filename = 'user_' . $profile_id . '_' . time() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
                $target_path = __DIR__ . $target_dir_path . $filename;
                
                if (move_uploaded_file($file['tmp_name'], $target_path)) {
                    $db_path = $target_dir_path . $filename;
                    $pic_stmt = $db->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
                    $pic_stmt->bind_param("si", $db_path, $profile_id);
                    if ($pic_stmt->execute()) {
                         $user['profile_picture'] = $db_path;
                         $success_message = "Profile picture updated.";
                    } else {
                        $errors[] = "Failed to save picture path to database.";
                    }
                    $pic_stmt->close();
                } else {
                    $errors[] = "Failed to move uploaded file.";
                }
            } else {
                $errors[] = "Invalid file type. Please upload a JPG, PNG, or GIF.";
            }
        } else {
            $errors[] = "No file was uploaded or an error occurred.";
        }
    }
}

// --- PAGE DISPLAY ---
require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1>My Profile</h1>
    </div>

    <?php if ($success_message): ?><div class="form-message success"><?php echo e($success_message); ?></div><?php endif; ?>
    <?php if (!empty($errors)): ?>
        <div class="form-message error">
            <?php foreach ($errors as $error) echo "<p>" . e($error) . "</p>"; ?>
        </div>
    <?php endif; ?>

    <div class="profile-layout">
        <!-- Profile Picture Section -->
        <div class="management-section">
            <h2>Profile Picture</h2>
            <div class="profile-picture-container">
                <?php echo render_avatar($user['profile_picture'] ?? null, $user['username'], 'large'); ?>
            </div>
            <form action="profile.php?id=<?php echo $profile_id; ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update_picture">
                <div class="form-group">
                    <label for="profile_picture">Upload New Picture</label>
                    <input type="file" id="profile_picture" name="profile_picture" required>
                </div>
                <button type="submit" class="button button-secondary button-small">Update Picture</button>
            </form>
        </div>

        <!-- Edit Details Section -->
        <div class="management-section">
            <h2>Profile Details</h2>
            <form action="profile.php?id=<?php echo $profile_id; ?>" method="POST">
                <input type="hidden" name="action" value="update_details">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?php echo e($user['email']); ?>" disabled>
                    <small class="form-text">Your email address cannot be changed.</small>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo e($user['username']); ?>" required>
                </div>
                <button type="submit" class="button button-primary">Save Details</button>
            </form>
        </div>

        <!-- Change Password Section -->
        <div class="management-section">
            <h2>Change Password</h2>
            <form action="profile.php?id=<?php echo $profile_id; ?>" method="POST">
                <input type="hidden" name="action" value="change_password">
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="button button-primary">Change Password</button>
            </form>
        </div>

        <!-- My Badges Section -->
        <div class="management-section badge-section">
            <h2>My Badges</h2>
            <div class="badge-list">
                <?php if (empty($badges)): ?>
                    <p>You haven't earned any badges yet. Start a course to earn your first one!</p>
                <?php else: ?>
                    <?php foreach($badges as $badge): ?>
                        <div class="badge-item" title="<?php echo e($badge['description']); ?>">
                            <span class="badge-icon"><?php echo e($badge['icon_class']); ?></span>
                            <span class="badge-name"><?php echo e($badge['name']); ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>