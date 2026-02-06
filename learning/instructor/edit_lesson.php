<?php
// --- SETUP AND SECURITY ---
$page_title = 'Manage Lesson Content';
$allowed_roles = [1, 2];
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// --- VALIDATE LESSON ID & FETCH DATA ---
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $_SESSION['error_message'] = "Invalid lesson ID.";
    redirect('/instructor/index.php');
}
$lesson_id = $_GET['id'];
$stmt = $db->prepare("SELECT l.*, c.instructor_id, c.id as course_id FROM lessons l JOIN courses c ON l.course_id = c.id WHERE l.id = ?");
$stmt->bind_param("i", $lesson_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    $_SESSION['error_message'] = "Lesson not found.";
    redirect('/instructor/index.php');
}
$lesson = $result->fetch_assoc();
$stmt->close();

if ($_SESSION['role_id'] != 1 && $lesson['instructor_id'] != $_SESSION['user_id']) {
    $_SESSION['error_message'] = "You do not have permission to manage this lesson.";
    redirect('/instructor/index.php');
}

// --- FORM HANDLING ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lesson_title = trim($_POST['lesson_title']);
    $content_text = $_POST['content_text'] ?? '';

    if (empty($lesson_title)) {
        $_SESSION['error_message'] = "Lesson title cannot be empty.";
    } else {
        $update_stmt = $db->prepare("UPDATE lessons SET title = ?, content_text = ? WHERE id = ?");
        $update_stmt->bind_param("ssi", $lesson_title, $content_text, $lesson_id);
        $update_stmt->execute();
        $update_stmt->close();
        $_SESSION['success_message'] = "Lesson details saved successfully.";
    }

    if ($lesson['content_type'] === 'video') {
        $upload_type = $_POST['video_upload_type'] ?? 'file';

        if ($upload_type === 'file' && isset($_FILES['content_file']) && $_FILES['content_file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['content_file'];
            $target_dir_name = 'course_videos';
            $target_dir = __DIR__ . '/../content/' . $target_dir_name . '/';
            $filename = uniqid() . '-' . basename($file['name']);
            $target_path = $target_dir . $filename;
            if (move_uploaded_file($file['tmp_name'], $target_path)) {
                $db_path = '/content/' . $target_dir_name . '/' . $filename;
                $db->query("DELETE FROM lesson_media WHERE lesson_id = $lesson_id");
                $media_stmt = $db->prepare("INSERT INTO lesson_media (lesson_id, file_path, original_filename) VALUES (?, ?, ?)");
                $media_stmt->bind_param("iss", $lesson_id, $db_path, $file['name']);
                $media_stmt->execute();
                $media_stmt->close();
                $_SESSION['success_message'] = "Video file uploaded successfully.";
            } else {
                $_SESSION['error_message'] = "Failed to move uploaded file.";
            }
        }
        
        elseif ($upload_type === 'youtube') {
            $youtube_url = trim($_POST['youtube_url']);
            if (!empty($youtube_url)) {
                if (filter_var($youtube_url, FILTER_VALIDATE_URL)) {
                    preg_match('/(youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $youtube_url, $matches);
                    $video_id = $matches[2] ?? null;
                    if ($video_id) {
                        $embed_url = 'https://www.youtube.com/embed/' . $video_id;
                        
                        $media_check = $db->query("SELECT id FROM lesson_media WHERE lesson_id = $lesson_id");
                        if ($media_check->num_rows > 0) {
                            $media_stmt = $db->prepare("UPDATE lesson_media SET youtube_url = ?, file_path = NULL, original_filename = NULL, subtitle_path = NULL WHERE lesson_id = ?");
                            $media_stmt->bind_param("si", $embed_url, $lesson_id);
                        } else {
                            $media_stmt = $db->prepare("INSERT INTO lesson_media (lesson_id, youtube_url) VALUES (?, ?)");
                            $media_stmt->bind_param("is", $lesson_id, $embed_url);
                        }
                        $media_stmt->execute();
                        $media_stmt->close();
                        $_SESSION['success_message'] = "YouTube video embedded successfully.";
                    } else {
                        $_SESSION['error_message'] = "Invalid YouTube URL format.";
                    }
                } else {
                    $_SESSION['error_message'] = "The provided URL is not valid.";
                }
            } else {
                $db->query("UPDATE lesson_media SET youtube_url = NULL WHERE lesson_id = $lesson_id");
            }
        }
    }
    redirect("/instructor/edit_lesson.php?id=" . $lesson_id);
}

// --- DATA FETCHING FOR DISPLAY ---
$media_file = null;
if (in_array($lesson['content_type'], ['video', 'pdf'])) {
    $media_result = $db->query("SELECT * FROM lesson_media WHERE lesson_id = $lesson_id LIMIT 1");
    if ($media_result && $media_result->num_rows > 0) {
        $media_file = $media_result->fetch_assoc();
    }
}
$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1>Manage Lesson: <?php echo e($lesson['title']); ?></h1>
        <a href="edit_course.php?id=<?php echo $lesson['course_id']; ?>" class="button button-secondary">Back to Course</a>
    </div>

    <?php if ($success_message): ?><div class="form-message success"><?php echo e($success_message); ?></div><?php endif; ?>
    <?php if ($error_message): ?><div class="form-message error"><?php echo e($error_message); ?></div><?php endif; ?>

    <div class="management-section">
        <form action="edit_lesson.php?id=<?php echo $lesson_id; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="lesson_title">Lesson Title</label>
                <input type="text" id="lesson_title" name="lesson_title" value="<?php echo e($lesson['title']); ?>" required>
            </div>
            <hr>
            <div class="form-group">
                <label for="content_text">Lesson Description / Text Content</label>
                <textarea id="content_text" name="content_text" rows="5"><?php echo e($lesson['content_text']); ?></textarea>
                <small class="form-text">For Text/Assignment lessons, this is the main content. For Video lessons, this provides context for students and the AI subtitle generator.</small>
            </div>
            <hr>
            <div class="form-group">
                <label>Lesson Content (Type: <?php echo e(ucfirst($lesson['content_type'])); ?>)</label>
                
                <?php if ($lesson['content_type'] === 'video'): ?>
                    <div class="upload-type-tabs">
                        <button type="button" class="tab-button active" data-tab="file_upload">Upload Video File</button>
                        <button type="button" class="tab-button" data-tab="youtube_embed">Embed YouTube Video</button>
                    </div>

                    <div id="file_upload" class="tab-content active">
                        <input type="radio" name="video_upload_type" value="file" checked style="display:none;">
                        <div class="file-upload-area">
                            <?php if ($media_file && !empty($media_file['file_path'])): ?>
                                <p class="current-file"><strong>Current File:</strong> <?php echo e($media_file['original_filename']); ?></p>
                                <div class="ai-generator inline-generator">
                                    <h4>Accurate Subtitle Generation</h4>
                                    <p>Generate real, word-for-word subtitles from your video's audio. This process can take several minutes.</p>
                                    <button type="button" id="generate-subtitles-ai" class="button button-primary" data-lesson-id="<?php echo $lesson_id; ?>">🤖 Generate Accurate Subtitles</button>
                                    <div id="ai-subtitle-status" class="ai-status"></div>
                                    <?php if ($media_file && !empty($media_file['subtitle_path'])): ?>
                                        <p class="success-text">✔️ Subtitles are currently active for this video.</p>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <p>No video file has been uploaded for this lesson yet.</p>
                            <?php endif; ?>
                            <hr style="margin: 1.5rem 0;">
                            <label for="content_file">Upload New VIDEO File</label>
                            <input type="file" id="content_file" name="content_file" data-upload-type="file">
                        </div>
                    </div>

                    <div id="youtube_embed" class="tab-content">
                        <input type="radio" name="video_upload_type" value="youtube" style="display:none;">
                        <div class="form-group">
                            <label for="youtube_url">YouTube Video URL</label>
                            <input type="url" id="youtube_url" name="youtube_url" placeholder="e.g., https://www.youtube.com/watch?v=..." value="<?php echo e($media_file['youtube_url'] ?? ''); ?>" data-upload-type="youtube">
                            <small class="form-text">Paste the full URL of the YouTube video you want to embed. Uploading a file in the other tab will clear this.</small>
                        </div>
                    </div>
                <?php elseif (in_array($lesson['content_type'], ['text', 'assignment'])): ?>
                     <div class="info-box"><p>You are editing a <?php echo e($lesson['content_type']); ?> lesson. The main content is managed in the text area above.</p></div>
                <?php elseif ($lesson['content_type'] === 'quiz'): ?>
                     <div class="info-box">
                        <p>This is a quiz lesson. You can add questions to it using the Quiz Manager.</p>
                        <a href="manage_quiz.php?lesson_id=<?php echo $lesson_id; ?>" class="button button-secondary">Manage Quiz Questions</a>
                    </div>
                <?php endif; ?>
            </div>
            <button type="submit" class="button button-primary form-button">Save Lesson Changes</button>
        </form>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>