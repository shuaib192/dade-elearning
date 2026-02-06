<?php
// --- SETUP AND SECURITY ---
$page_title = 'Create New Course';
$allowed_roles = [1, 2];
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// --- DATA FETCHING FOR CATEGORY DROPDOWN ---
$categories = [];
$category_result = $db->query("SELECT id, name FROM categories ORDER BY name ASC");
if ($category_result) {
    while($row = $category_result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// --- INITIALIZE VARIABLES ---
$errors = [];
$title = '';
$description = '';
$category_id = '';

// --- FORM SUBMISSION LOGIC ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category_id = filter_var($_POST['category_id'], FILTER_VALIDATE_INT);
    $instructor_id = $_SESSION['user_id'];

    if (empty($title)) $errors[] = 'Course title is required.';
    if (empty($description)) $errors[] = 'Course description is required.';
    if (empty($category_id)) $errors[] = 'Please select a course category.';

    if (empty($errors)) {
        $stmt = $db->prepare(
            "INSERT INTO courses (title, description, category_id, instructor_id, status) VALUES (?, ?, ?, ?, 'draft')"
        );
        $stmt->bind_param("ssii", $title, $description, $category_id, $instructor_id);

        if ($stmt->execute()) {
            $new_course_id = $stmt->insert_id;
            $_SESSION['success_message'] = "Course '" . e($title) . "' created successfully! You can now add lessons.";
            // CORRECTED REDIRECT PATH
            redirect('/instructor/edit_course.php?id=' . $new_course_id);
        } else {
            $errors[] = "Failed to create the course. Please try again.";
        }
        $stmt->close();
    }
}

// --- PAGE DISPLAY ---
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <div class="form-container-wide">
        <div class="page-header">
            <h1>Create a New Course</h1>
            <!-- THIS IS THE CORRECTED LINK -->
            <a href="<?php echo $base; ?>/dashboard.php" class="button button-secondary">Back to Dashboard</a>
        </div>
        <p class="form-subtitle">Fill out the basic details below to start building your course.</p>

        <?php if (!empty($errors)): ?>
            <div class="form-message error" role="alert">
                <?php foreach ($errors as $error): ?><p><?php echo $error; ?></p><?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo $base; ?>/instructor/create_course.php" method="POST">
            <div class="form-group">
                <label for="title">Course Title</label>
                <input type="text" id="title" name="title" value="<?php echo e($title); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="category_id">Course Category</label>
                <select id="category_id" name="category_id" required>
                    <option value="">-- Select a Category --</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" <?php echo ($category_id == $category['id']) ? 'selected' : ''; ?>>
                            <?php echo e($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Course Description</label>
                <textarea id="description" name="description" rows="8" required><?php echo e($description); ?></textarea>
            </div>

            <button type="submit" class="button button-primary form-button">Create Course and Add Lessons</button>
        </form>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>