<?php
// --- SETUP AND SECURITY ---
$page_title = 'Manage Users';
$allowed_roles = [1];
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// --- FORM HANDLING ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $user_id = filter_var($_POST['user_id'] ?? 0, FILTER_VALIDATE_INT);
    
    if (!$user_id || $user_id == $_SESSION['user_id']) {
        $_SESSION['error_message'] = "Invalid user or cannot modify your own account.";
        redirect('/admin/manage_users.php');
    }

    // Update user role and status
    if ($action === 'update_user') {
        $role_id = filter_var($_POST['role_id'] ?? 3, FILTER_VALIDATE_INT);
        $status = $_POST['status'] ?? 'active';
        $allowed_statuses = ['active', 'inactive', 'banned'];
        
        if (!in_array($status, $allowed_statuses)) { $status = 'active'; }
        if ($role_id < 1 || $role_id > 3) { $role_id = 3; }

        $stmt = $db->prepare("UPDATE users SET role_id = ?, status = ? WHERE id = ?");
        $stmt->bind_param("isi", $role_id, $status, $user_id);
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "User updated successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to update user.";
        }
        $stmt->close();
    }

    // Approve instructor application
    if ($action === 'approve_instructor') {
        $stmt = $db->prepare("UPDATE users SET role_id = 2, status = 'active' WHERE id = ? AND status = 'pending_instructor'");
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            $_SESSION['success_message'] = "Instructor approved successfully!";
        } else {
            $_SESSION['error_message'] = "Failed to approve instructor.";
        }
        $stmt->close();
    }

    // Delete user
    if ($action === 'delete_user') {
        // Try to delete related records (ignore errors if tables don't exist)
        @$db->query("DELETE FROM enrollments WHERE student_id = " . intval($user_id));
        @$db->query("DELETE FROM lesson_progress WHERE student_id = " . intval($user_id));
        @$db->query("DELETE FROM user_badges WHERE user_id = " . intval($user_id));
        @$db->query("DELETE FROM course_ratings WHERE student_id = " . intval($user_id));
        @$db->query("DELETE FROM bookmarks WHERE student_id = " . intval($user_id));
        @$db->query("DELETE FROM lesson_notes WHERE student_id = " . intval($user_id));
        
        // Delete the user
        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $user_id);
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "User deleted successfully.";
            } else {
                $_SESSION['error_message'] = "Failed to delete user: " . $db->error;
            }
            $stmt->close();
        } else {
            $_SESSION['error_message'] = "Database error: " . $db->error;
        }
    }

    redirect('/admin/manage_users.php');
}

// --- DATA FETCHING WITH SEARCH ---
$search_query = trim($_GET['search'] ?? '');
$users = [];

$sql = "SELECT u.id, u.username, u.email, u.status, u.created_at, r.role_name, r.id as role_id
        FROM users u
        JOIN roles r ON u.role_id = r.id";

if (!empty($search_query)) {
    $search_term = "%" . $search_query . "%";
    $sql .= " WHERE (u.username LIKE ? OR u.email LIKE ?)";
}
$sql .= " ORDER BY u.created_at DESC";

$stmt = $db->prepare($sql);
if (!empty($search_query)) {
    $stmt->bind_param("ss", $search_term, $search_term);
}
$stmt->execute();
$result = $stmt->get_result();
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1>Manage Users</h1>
        <div class="header-actions">
            <a href="download_users.php" class="button button-success">Download (CSV)</a>
            <a href="index.php" class="button button-secondary">Back to Dashboard</a>
        </div>
    </div>

    <!-- NEW SEARCH BAR -->
    <div class="search-filter-box">
        <form action="manage_users.php" method="GET">
            <input type="search" name="search" placeholder="Search by username or email..." value="<?php echo e($search_query); ?>">
            <button type="submit" class="button button-primary">Search</button>
        </form>
    </div>

    <?php if ($success_message): ?><div class="form-message success"><?php echo e($success_message); ?></div><?php endif; ?>
    <?php if ($error_message): ?><div class="form-message error"><?php echo e($error_message); ?></div><?php endif; ?>

    <!-- NEW USER LIST DESIGN -->
    <div class="user-card-list">
        <?php if (empty($users)): ?>
            <div class="info-box">
                <p>No users found matching your criteria.</p>
            </div>
        <?php else: ?>
            <?php foreach ($users as $user): ?>
                <div class="user-card <?php echo ($user['status'] === 'pending_instructor') ? 'pending' : ''; ?>">
                    <div class="user-card-info">
                        <img src="<?php echo $base; ?>/<?php echo e($user['profile_picture'] ?? 'assets/images/default_avatar.png'); ?>" alt="Profile picture" class="user-avatar">
                        <div>
                            <h3 class="user-name"><?php echo e($user['username']); ?></h3>
                            <p class="user-email"><?php echo e($user['email']); ?></p>
                            <div class="user-badges">
                                <span class="status-badge role-<?php echo $user['role_id']; ?>"><?php echo e(ucfirst($user['role_name'])); ?></span>
                                <span class="status-badge status-<?php echo e($user['status']); ?>"><?php echo e(ucfirst(str_replace('_', ' ', $user['status']))); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="user-card-actions">
                        <form action="manage_users.php" method="POST">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <?php if ($user['status'] === 'pending_instructor'): ?>
                                <button type="submit" name="action" value="approve_instructor" class="button button-success">Approve Instructor</button>
                            <?php else: ?>
                                <div class="action-row">
                                    <select name="role_id" <?php if ($user['id'] == $_SESSION['user_id']) echo 'disabled'; ?>>
                                        <option value="3" <?php if ($user['role_id'] == 3) echo 'selected'; ?>>Student</option>
                                        <option value="2" <?php if ($user['role_id'] == 2) echo 'selected'; ?>>Instructor</option>
                                        <option value="1" <?php if ($user['role_id'] == 1) echo 'selected'; ?>>Admin</option>
                                    </select>
                                    <select name="status" <?php if ($user['id'] == $_SESSION['user_id']) echo 'disabled'; ?>>
                                        <option value="active" <?php if ($user['status'] == 'active') echo 'selected'; ?>>Active</option>
                                        <option value="inactive" <?php if ($user['status'] == 'inactive') echo 'selected'; ?>>Inactive</option>
                                        <option value="banned" <?php if ($user['status'] == 'banned') echo 'selected'; ?>>Banned</option>
                                    </select>
                                </div>
                                <button type="submit" name="action" value="update_user" class="button button-primary button-fullwidth" <?php if ($user['id'] == $_SESSION['user_id']) echo 'disabled'; ?>>Save Changes</button>
                            <?php endif; ?>
                        </form>
                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                            <form action="manage_users.php" method="POST" class="delete-form">
                                <input type="hidden" name="action" value="delete_user">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <button type="submit" class="button-link-danger">Delete User</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>