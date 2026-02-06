<?php
// --- SETUP AND SECURITY ---
$page_title = 'Manage Course';
$allowed_roles = [1, 2];
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// --- VALIDATE COURSE ID & FETCH DATA ---
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $_SESSION['error_message'] = "Invalid course ID.";
    redirect('/instructor/index.php');
}
$course_id = $_GET['id'];
$stmt = $db->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$course_result = $stmt->get_result();
if ($course_result->num_rows === 0) {
    $_SESSION['error_message'] = "Course not found.";
    redirect('/instructor/index.php');
}
$course = $course_result->fetch_assoc();
$stmt->close();

$is_admin = ($_SESSION['role_id'] == 1);
if (!$is_admin && $course['instructor_id'] != $_SESSION['user_id']) {
    $_SESSION['error_message'] = "You do not have permission to manage this course.";
    redirect('/instructor/index.php');
}

// --- FORM HANDLING ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $errors = [];

    // ---------------- UPDATE COURSE ----------------
    if ($action === 'update_course') {
        $new_title = trim($_POST['title']);
        $new_description = trim($_POST['description']);
        $new_category_id = filter_var($_POST['category_id'], FILTER_VALIDATE_INT);
        $new_status = $_POST['status'] ?? $course['status'];
        $new_cover_image = $course['cover_image'];

        if (empty($new_title)) $errors[] = "Title is required.";
        if (empty($new_category_id)) $errors[] = "Category is required.";

        if (!$is_admin && $new_status === 'published') {
            $new_status = $course['status'];
        }

        $is_certificate_course = isset($_POST['is_certificate_course']) ? 1 : 0;
        $final_quiz_lesson_id = filter_var($_POST['final_quiz_lesson_id'], FILTER_VALIDATE_INT);
        $passing_grade = filter_var($_POST['passing_grade'], FILTER_VALIDATE_INT);
        if ($final_quiz_lesson_id == 0) $final_quiz_lesson_id = NULL;
        if ($passing_grade < 0 || $passing_grade > 100) $passing_grade = 75;

        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['cover_image'];
            $target_dir = '/content/course_images/';
            $filename = uniqid() . '-' . basename($file['name']);
            $target_path = __DIR__ . '/..' . $target_dir . $filename;
            if (move_uploaded_file($file['tmp_name'], $target_path)) {
                $new_cover_image = $target_dir . $filename;
            } else {
                $errors[] = "Failed to move uploaded image.";
            }
        }

        if (empty($errors)) {
            $update_stmt = $db->prepare("UPDATE courses SET title = ?, description = ?, category_id = ?, status = ?, cover_image = ?, is_certificate_course = ?, final_quiz_lesson_id = ?, passing_grade = ? WHERE id = ?");
            $update_stmt->bind_param("ssissiiii", $new_title, $new_description, $new_category_id, $new_status, $new_cover_image, $is_certificate_course, $final_quiz_lesson_id, $passing_grade, $course_id);
            if ($update_stmt->execute()) {
                $_SESSION['success_message'] = "Course settings updated successfully.";
            } else {
                $_SESSION['error_message'] = "Failed to update course.";
            }
            $update_stmt->close();
        } else {
            $_SESSION['error_message'] = implode("<br>", $errors);
        }
    }

    // ---------------- UPDATE STATUS ONLY ----------------
    if ($action === 'update_status') {
        $new_status = $_POST['status'] ?? $course['status'];
        if (!$is_admin && $new_status === 'published') {
            $_SESSION['error_message'] = "You do not have permission to publish this course.";
        } else {
            $stmt = $db->prepare("UPDATE courses SET status = ? WHERE id = ?");
            $stmt->bind_param("si", $new_status, $course_id);
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Course status updated successfully.";
            } else {
                $_SESSION['error_message'] = "Failed to update course status.";
            }
            $stmt->close();
        }
    }

    // ---------------- ADD LESSON ----------------
    if ($action === 'add_lesson') {
        $lesson_title = trim($_POST['lesson_title']);
        $lesson_type = $_POST['lesson_type'];
        if (!empty($lesson_title)) {
            $order_query = $db->query("SELECT MAX(lesson_order) as max_order FROM lessons WHERE course_id = $course_id");
            $max_order = $order_query->fetch_assoc()['max_order'] ?? 0;
            $new_order = $max_order + 1;
            $add_stmt = $db->prepare("INSERT INTO lessons (course_id, title, lesson_order, content_type) VALUES (?, ?, ?, ?)");
            $add_stmt->bind_param("isis", $course_id, $lesson_title, $new_order, $lesson_type);
            $add_stmt->execute();
            $_SESSION['success_message'] = "New lesson added.";
        }
    }

    // ---------------- SUBMIT FOR REVIEW ----------------
    if ($action === 'submit_for_review') {
        $stmt = $db->prepare("UPDATE courses SET status = 'pending_review' WHERE id = ?");
        $stmt->bind_param("i", $course_id);
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Course submitted for review.";
        }
    }

    redirect("/instructor/edit_course.php?id=" . $course_id);
}

// --- DATA FETCHING FOR DISPLAY ---
$lessons = [];
$lesson_stmt = $db->prepare("SELECT * FROM lessons WHERE course_id = ? ORDER BY lesson_order ASC");
$lesson_stmt->bind_param("i", $course_id);
$lesson_stmt->execute();
$lesson_result = $lesson_stmt->get_result();
while ($row = $lesson_result->fetch_assoc()) {
    $lessons[] = $row;
}
$lesson_stmt->close();

$categories = [];
$category_result = $db->query("SELECT id, name FROM categories ORDER BY name ASC");
if ($category_result) {
    while ($row = $category_result->fetch_assoc()) {
        $categories[] = $row;
    }
}

$quiz_lessons = [];
$quiz_stmt = $db->prepare("SELECT id, title FROM lessons WHERE course_id = ? AND content_type = 'quiz' ORDER BY lesson_order ASC");
$quiz_stmt->bind_param("i", $course_id);
$quiz_stmt->execute();
$quiz_result = $quiz_stmt->get_result();
while ($row = $quiz_result->fetch_assoc()) {
    $quiz_lessons[] = $row;
}
$quiz_stmt->close();

$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);

require_once __DIR__ . '/../includes/header.php';
?>

<style>
    .edit-course-layout {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    .main-course-column,
    .sidebar-course-column {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .form-group-checkbox {
        display: flex;
        align-items: center;
        gap: .75rem;
        background-color: var(--color-background-light);
        padding: 1rem;
        border-radius: var(--border-radius-md);
        border: 1px solid var(--color-border);
    }

    .form-group-checkbox input[type=checkbox] {
        width: 1.5em;
        height: 1.5em;
        flex-shrink: 0;
    }

    .form-group-checkbox label {
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 0;
        cursor: pointer;
    }

    #assessment-rules-container {
        transition: all .4s ease-in-out;
        overflow: hidden;
        max-height: 1000px;
    }

    #assessment-rules-container.hidden {
        max-height: 0;
        opacity: 0;
        margin-top: -1rem;
        padding: 0;
        border: none;
    }

    .quiz-selection-list {
        display: flex;
        flex-direction: column;
        gap: .5rem;
        margin-top: 1rem;
    }

    .quiz-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
        padding: .75rem 1rem;
        border-radius: var(--border-radius-sm);
        border: 1px solid var(--color-border);
        transition: all var(--transition-speed);
    }

    .quiz-item.selected {
        border-left: 5px solid var(--color-success);
        background-color: var(--color-success-bg);
    }

    .quiz-item span {
        font-weight: 500;
    }

    @media (min-width:1024px) {
        .edit-course-layout {
            grid-template-columns: 2fr 1fr;
        }
    }
</style>

<div class="container">
    <div class="page-header">
        <h1>Manage Course</h1>
        <a href="<?php echo $base; ?>/dashboard.php" class="button button-secondary">Back to Dashboard</a>
    </div>

    <?php if ($success_message): ?><div class="form-message success"><?php echo $success_message; ?></div><?php endif; ?>
    <?php if ($error_message): ?><div class="form-message error"><?php echo $error_message; ?></div><?php endif; ?>

    <div class="edit-course-layout">
        <!-- MAIN COLUMN -->
        <div class="main-course-column">
            <form action="edit_course.php?id=<?php echo $course_id; ?>" method="POST" enctype="multipart/form-data" id="main-course-form">
                <input type="hidden" name="action" value="update_course">

                <div class="management-section">
                    <h2>Course Details</h2>
                    <div class="form-group">
                        <label for="cover_image">Course Cover Image</label>
                        <?php if ($course['cover_image']): ?>
                            <img src="<?php echo e($course['cover_image']); ?>" alt="Current cover image" class="current-cover-image">
                        <?php endif; ?>
                        <input type="file" id="cover_image" name="cover_image">
                    </div>

                    <div class="form-group">
                        <label for="title">Course Title</label>
                        <input type="text" id="title" name="title" value="<?php echo e($course['title']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="category_id">Course Category</label>
                        <select id="category_id" name="category_id" required>
                            <option value="">-- Select a Category --</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>" <?php echo ($course['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                    <?php echo e($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description">Course Description</label>
                        <textarea id="description" name="description" rows="6" required><?php echo e($course['description']); ?></textarea>
                    </div>
                </div>

                <div class="management-section">
                    <h2>Certificate & Assessment Rules</h2>
                    <div class="form-group form-group-checkbox">
                        <input type="checkbox" id="is_certificate_course" name="is_certificate_course" value="1" <?php echo ($course['is_certificate_course']) ? 'checked' : ''; ?>>
                        <label for="is_certificate_course">Enable Certificate for this Course</label>
                    </div>

                    <div id="assessment-rules-container" class="<?php echo ($course['is_certificate_course']) ? '' : 'hidden'; ?>">
                        <hr>
                        <h4>Required Final Assessment</h4>
                        <p>Select a quiz to act as the final exam.</p>

                        <div class="quiz-selection-list">
                            <?php if (empty($quiz_lessons)): ?>
                                <p>No quiz lessons found in this course.</p>
                            <?php else: ?>
                                <?php foreach ($quiz_lessons as $quiz): ?>
                                    <div class="quiz-item <?php echo ($course['final_quiz_lesson_id'] == $quiz['id']) ? 'selected' : ''; ?>">
                                        <span><?php echo e($quiz['title']); ?></span>
                                        <button type="button" class="button button-small <?php echo ($course['final_quiz_lesson_id'] == $quiz['id']) ? 'button-success' : 'button-secondary'; ?> set-final-exam-btn" data-lesson-id="<?php echo $quiz['id']; ?>">
                                            <?php echo ($course['final_quiz_lesson_id'] == $quiz['id']) ? '✔️ Selected' : 'Set as Final Exam'; ?>
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <input type="hidden" id="final_quiz_lesson_id" name="final_quiz_lesson_id" value="<?php echo e($course['final_quiz_lesson_id'] ?? '0'); ?>">

                        <div class="form-group" style="margin-top: 1.5rem;">
                            <label for="passing_grade">Minimum Passing Grade (%)</label>
                            <input type="number" id="passing_grade" name="passing_grade" min="0" max="100" value="<?php echo e($course['passing_grade']); ?>">
                        </div>
                    </div>
                </div>

                <button type="submit" class="button button-primary button-large button-fullwidth">Save All Course Settings</button>
            </form>
        </div>

        <!-- SIDEBAR COLUMN -->
        <div class="sidebar-course-column">
            <div class="management-section">
                <h2>Course Status</h2>
                <form action="edit_course.php?id=<?php echo $course_id; ?>" method="POST">
                    <input type="hidden" name="action" value="update_status">
                    <div class="form-group">
                        <label for="status">Change Status</label>
                        <?php if ($is_admin): ?>
                            <select id="status" name="status" onchange="this.form.submit()">
                                <option value="draft" <?php echo ($course['status'] === 'draft') ? 'selected' : ''; ?>>Draft</option>
                                <option value="pending_review" <?php echo ($course['status'] === 'pending_review') ? 'selected' : ''; ?>>Pending</option>
                                <option value="published" <?php echo ($course['status'] === 'published') ? 'selected' : ''; ?>>Published</option>
                                <option value="archived" <?php echo ($course['status'] === 'archived') ? 'selected' : ''; ?>>Archived</option>
                            </select>
                        <?php elseif ($course['status'] === 'published' || $course['status'] === 'archived'): ?>
                            <select id="status" name="status" onchange="this.form.submit()">
                                <option value="published" <?php echo ($course['status'] === 'published') ? 'selected' : ''; ?>>Published</option>
                                <option value="draft">Revert to Draft</option>
                                <option value="archived" <?php echo ($course['status'] === 'archived') ? 'selected' : ''; ?>>Archived</option>
                            </select>
                        <?php else: ?>
                            <p>This course is currently a <strong><?php echo e(ucfirst(str_replace('_', ' ', $course['status']))); ?></strong>.</p>
                        <?php endif; ?>
                    </div>
                </form>

                <?php if (!$is_admin && in_array($course['status'], ['draft', 'archived'])): ?>
                    <form action="edit_course.php?id=<?php echo $course_id; ?>" method="POST" class="delete-form">
                        <input type="hidden" name="action" value="submit_for_review">
                        <button type="submit" class="button button-success button-fullwidth">Submit for Review</button>
                    </form>
                <?php endif; ?>
            </div>

            <div class="management-section">
                <h2>Course Lessons</h2>
                <div class="lesson-list">
                    <?php if (empty($lessons)): ?>
                        <p>No lessons yet. Add one below.</p>
                    <?php else: ?>
                        <ol>
                            <?php foreach ($lessons as $lesson): ?>
                                <li>
                                    <span class="lesson-title"><?php echo e($lesson['title']); ?> (<?php echo e($lesson['content_type']); ?>)</span>
                                    <span class="lesson-actions">
                                        <a href="edit_lesson.php?id=<?php echo $lesson['id']; ?>" class="button button-secondary button-small">Edit</a>
                                        <form action="delete_handler.php" method="POST" class="inline-form delete-form">
                                            <input type="hidden" name="action" value="delete_lesson">
                                            <input type="hidden" name="id" value="<?php echo $lesson['id']; ?>">
                                            <button type="submit" class="button button-danger button-small">Del</button>
                                        </form>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                    <?php endif; ?>
                </div>
                <hr>
                <h3>Add New Lesson</h3>
                <form action="edit_course.php?id=<?php echo $course_id; ?>" method="POST" class="form-inline">
                    <input type="hidden" name="action" value="add_lesson">
                    <div class="form-group">
                        <label for="lesson_title">Title</label>
                        <input type="text" id="lesson_title" name="lesson_title" required>
                    </div>
                    <div class="form-group">
                        <label for="lesson_type">Type</label>
                        <select id="lesson_type" name="lesson_type">
                            <option value="video">Video</option>
                            <option value="text">Text</option>
                            <option value="quiz">Quiz</option>
                        </select>
                    </div>
                    <button type="submit" class="button button-primary">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('is_certificate_course').addEventListener('change', function() {
        const container = document.getElementById('assessment-rules-container');
        container.classList.toggle('hidden', !this.checked);
    });

    document.querySelectorAll('.set-final-exam-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('final_quiz_lesson_id').value = this.dataset.lessonId;
            document.querySelectorAll('.quiz-item').forEach(i => i.classList.remove('selected'));
            this.closest('.quiz-item').classList.add('selected');
            document.querySelectorAll('.set-final-exam-btn').forEach(b => {
                b.textContent = 'Set as Final Exam';
                b.classList.remove('button-success');
            });
            this.textContent = '✔️ Selected';
            this.classList.add('button-success');
        });
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>