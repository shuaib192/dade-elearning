<?php
// --- SETUP AND SECURITY ---
$page_title = 'View Thread';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// --- HELPER FUNCTIONS (DEFINED AT THE TOP) ---
function get_course_info_for_thread($thread_id, $course_id, $db) {
    if ($thread_id) {
        $stmt = $db->prepare("SELECT c.id, c.instructor_id, c.title FROM courses c JOIN forum_threads t ON c.id = t.course_id WHERE t.id = ?");
        $stmt->bind_param("i", $thread_id);
    } elseif ($course_id) {
        $stmt = $db->prepare("SELECT id, instructor_id, title FROM courses WHERE id = ?");
        $stmt->bind_param("i", $course_id);
    } else {
        return null;
    }
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function verify_thread_access($user_id, $role_id, $course_id, $instructor_id, $db) {
    if ($role_id == 1 || ($role_id == 2 && $user_id == $instructor_id)) return true;
    if ($role_id == 3) {
        $stmt = $db->prepare("SELECT id FROM enrollments WHERE student_id = ? AND course_id = ?");
        $stmt->bind_param("ii", $user_id, $course_id);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) return true;
    }
    $_SESSION['error_message'] = "You do not have permission to access this discussion.";
    redirect('/student/index.php');
}

// --- DETERMINE ACTION & VALIDATE IDs ---
$action = $_GET['action'] ?? 'view';
$thread_id = filter_var($_GET['thread_id'] ?? null, FILTER_VALIDATE_INT);
$course_id_from_url = filter_var($_GET['course_id'] ?? null, FILTER_VALIDATE_INT);
$user_id = $_SESSION['user_id'];
$is_instructor_or_admin = in_array($_SESSION['role_id'], [1, 2]);

// --- GET COURSE CONTEXT & VERIFY PERMISSIONS ---
$course_info = get_course_info_for_thread($thread_id, $course_id_from_url, $db);
if (!$course_info) {
    $_SESSION['error_message'] = 'Course context for this thread could not be found.';
    redirect('/student/index.php');
}
verify_thread_access($user_id, $_SESSION['role_id'], $course_info['id'], $course_info['instructor_id'], $db);

// --- FORM HANDLING ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_action = $_POST['action'] ?? '';

    // Action: Create a New Thread
    if ($post_action === 'create_thread') {
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        $posted_course_id = filter_var($_POST['course_id'], FILTER_VALIDATE_INT);
        if (empty($title) || empty($content) || !$posted_course_id) {
            $_SESSION['error_message'] = "Title and content are required.";
            redirect("view_thread.php?action=new&course_id=" . $posted_course_id);
        }
        $stmt = $db->prepare("INSERT INTO forum_threads (course_id, student_id, title, content) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $posted_course_id, $user_id, $title, $content);
        $stmt->execute();
        $new_thread_id = $stmt->insert_id;
        redirect("view_thread.php?thread_id=" . $new_thread_id);
    }

    // Action: Post a Reply
    if ($post_action === 'post_reply' && $thread_id) {
        $content = trim($_POST['content']);
        if (empty($content)) {
            $_SESSION['error_message'] = "Reply cannot be empty.";
        } else {
            $stmt = $db->prepare("INSERT INTO forum_replies (thread_id, user_id, content) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $thread_id, $user_id, $content);
            $stmt->execute();
        }
        redirect("view_thread.php?thread_id=" . $thread_id);
    }

    // Actions for Instructor/Admin
    if ($is_instructor_or_admin) {
        if ($post_action === 'delete_thread' && $thread_id) {
            $course_id_for_redirect = $_POST['course_id'];
            $stmt = $db->prepare("DELETE FROM forum_threads WHERE id = ?");
            $stmt->bind_param("i", $thread_id);
            $stmt->execute();
            $_SESSION['success_message'] = "Thread deleted successfully.";
            redirect("forum.php?course_id=" . $course_id_for_redirect);
        }
        if ($post_action === 'toggle_pin' && $thread_id) {
            $new_pin_status = filter_var($_POST['is_pinned'], FILTER_VALIDATE_INT) == 1 ? 0 : 1;
            $stmt = $db->prepare("UPDATE forum_threads SET is_pinned = ? WHERE id = ?");
            $stmt->bind_param("ii", $new_pin_status, $thread_id);
            $stmt->execute();
            redirect("view_thread.php?thread_id=" . $thread_id);
        }
    }
}


// --- DATA FETCHING FOR PAGE DISPLAY ---
if ($action === 'view') {
    $thread_sql = "SELECT t.*, u.username as author, u.profile_picture, u.role_id FROM forum_threads t JOIN users u ON t.student_id = u.id WHERE t.id = ?";
    $stmt = $db->prepare($thread_sql);
    $stmt->bind_param("i", $thread_id);
    $stmt->execute();
    $thread = $stmt->get_result()->fetch_assoc();
    if (!$thread) {
        $_SESSION['error_message'] = "Thread not found.";
        redirect('forum.php?course_id=' . $course_info['id']);
    }
    $page_title = e($thread['title']);

    $replies = [];
    $reply_sql = "SELECT r.content, r.created_at, u.username as author, u.profile_picture, u.role_id FROM forum_replies r JOIN users u ON r.user_id = u.id WHERE r.thread_id = ? ORDER BY r.created_at ASC";
    $stmt = $db->prepare($reply_sql);
    $stmt->bind_param("i", $thread_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
        $replies[] = $row;
    }
} else {
    $page_title = 'Start New Thread';
}

$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['error_message']);

// --- PAGE DISPLAY ---
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1><?php echo $page_title; ?></h1>
        <a href="forum.php?course_id=<?php echo $course_info['id']; ?>" class="button button-secondary">Back to Forum</a>
    </div>

    <?php if ($error_message): ?>
        <div class="form-message error"><?php echo e($error_message); ?></div>
    <?php endif; ?>

    <?php if ($action === 'view' && isset($thread)): ?>
        <div class="thread-post original-post">
            <div class="post-author">
                <img src="<?php echo e($thread['profile_picture'] ? $base . $thread['profile_picture'] : $base . '/assets/images/default_avatar.png'); ?>" alt="Profile picture">
                <strong><?php echo e($thread['author']); ?></strong>
                <span class="author-role role-<?php echo $thread['role_id']; ?>"><?php echo ($thread['role_id'] == 2) ? 'Instructor' : 'Student'; ?></span>
            </div>
            <div class="post-content">
                <div class="post-meta">
                    <span>Posted on <?php echo date('F j, Y, g:i a', strtotime($thread['created_at'])); ?></span>
                    <?php if ($is_instructor_or_admin || $user_id == $thread['student_id']): ?>
                    <div class="thread-actions">
                        <?php if ($is_instructor_or_admin): ?>
                        <form action="view_thread.php?thread_id=<?php echo $thread_id; ?>" method="POST" class="inline-form">
                            <input type="hidden" name="action" value="toggle_pin">
                            <input type="hidden" name="is_pinned" value="<?php echo $thread['is_pinned']; ?>">
                            <button type="submit" class="button-link"><?php echo $thread['is_pinned'] ? '📌 Unpin' : '📌 Pin'; ?></button>
                        </form>
                        <?php endif; ?>
                        <form action="view_thread.php?thread_id=<?php echo $thread_id; ?>" method="POST" class="inline-form delete-form">
                            <input type="hidden" name="action" value="delete_thread">
                            <input type="hidden" name="course_id" value="<?php echo $course_info['id']; ?>">
                            <button type="submit" class="button-link-danger">Delete Thread</button>
                        </form>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="post-body"><?php echo nl2br(e($thread['content'])); ?></div>
            </div>
        </div>

        <?php foreach ($replies as $reply): ?>
            <div class="thread-post reply-post">
                <div class="post-author">
                    <img src="<?php echo e($reply['profile_picture'] ? $base . $reply['profile_picture'] : $base . '/assets/images/default_avatar.png'); ?>" alt="Profile picture">
                    <strong><?php echo e($reply['author']); ?></strong>
                     <span class="author-role role-<?php echo $reply['role_id']; ?>"><?php echo ($reply['role_id'] == 2) ? 'Instructor' : 'Student'; ?></span>
                </div>
                <div class="post-content">
                    <div class="post-meta">Posted on <?php echo date('F j, Y, g:i a', strtotime($reply['created_at'])); ?></div>
                    <div class="post-body"><?php echo nl2br(e($reply['content'])); ?></div>
                </div>
            </div>
        <?php endforeach; ?>

        <hr>
        <div class="management-section">
            <h3>Post a Reply</h3>
            <form action="view_thread.php?thread_id=<?php echo $thread_id; ?>" method="POST">
                <input type="hidden" name="action" value="post_reply">
                <div class="form-group">
                    <textarea name="content" rows="7" placeholder="Write your reply here..." required></textarea>
                </div>
                <button type="submit" class="button button-primary">Post Reply</button>
            </form>
        </div>

    <?php elseif ($action === 'new'): ?>
        <div class="management-section">
            <form action="view_thread.php?action=new&course_id=<?php echo $course_id_from_url; ?>" method="POST">
                <input type="hidden" name="action" value="create_thread">
                <input type="hidden" name="course_id" value="<?php echo $course_id_from_url; ?>">
                <div class="form-group">
                    <label for="title">Thread Title</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="content">Your Question or Comment</label>
                    <textarea id="content" name="content" rows="10" required></textarea>
                </div>
                <button type="submit" class="button button-primary">Create Thread</button>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>