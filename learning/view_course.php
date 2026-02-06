<?php
// --- SETUP & SECURITY ---
require_once __DIR__ . '/config/database.php';
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/includes/functions.php';

// Validate the course ID from the URL
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $_SESSION['error_message'] = "Invalid course link.";
    redirect('/courses.php');
}
$course_id = $_GET['id'];
$user_id = $_SESSION['user_id'] ?? null;

// --- FORM HANDLING: SUBMIT RATING ---
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'submit_rating') {
    if (!$user_id) {
        $errors[] = "You must be logged in to leave a rating.";
    } else {
        $rating = filter_var($_POST['rating'], FILTER_VALIDATE_INT);
        $review = trim($_POST['review']);

        if (!$rating || $rating < 1 || $rating > 5) $errors[] = "Please select a star rating between 1 and 5.";
        if (empty($review)) $errors[] = "A written review is required.";

        if (empty($errors)) {
            $stmt = $db->prepare("INSERT INTO course_ratings (course_id, user_id, rating, review) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE rating = VALUES(rating), review = VALUES(review), created_at = NOW()");
            $stmt->bind_param("iiis", $course_id, $user_id, $rating, $review);
            
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Thank you! Your review has been submitted successfully.";
            } else {
                $_SESSION['error_message'] = "There was an error submitting your review. Please try again.";
            }
            $stmt->close();
            redirect($_SERVER['REQUEST_URI']);
        }
    }
}

// --- DATA FETCHING ---
// Fetch course details and instructor name
$stmt = $db->prepare(
    "SELECT c.*, u.username AS instructor_name FROM courses c JOIN users u ON c.instructor_id = u.id WHERE c.id = ? AND (c.status = 'published' OR c.status = 'archived')"
);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    $_SESSION['error_message'] = "This course was not found or is not currently available.";
    redirect('/courses.php');
}
$course = $result->fetch_assoc();
$page_title = $course['title'];
$stmt->close();

// Fetch all lessons for the curriculum view
$lessons = [];
$lesson_stmt = $db->prepare("SELECT id, title, lesson_type FROM lessons WHERE course_id = ? ORDER BY lesson_order ASC");
$lesson_stmt->bind_param("i", $course_id);
$lesson_stmt->execute();
$lesson_result = $lesson_stmt->get_result();
while ($row = $lesson_result->fetch_assoc()) {
    $lessons[] = $row;
}
$lesson_stmt->close();

// Check if the current user is enrolled
$is_enrolled = false;
$is_student = isset($_SESSION['role_id']) && $_SESSION['role_id'] == 3;
if ($user_id) {
    $enrollment_stmt = $db->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?");
    $enrollment_stmt->bind_param("ii", $user_id, $course_id);
    $enrollment_stmt->execute();
    if ($enrollment_stmt->get_result()->num_rows > 0) $is_enrolled = true;
    $enrollment_stmt->close();
}

// Fetch all ratings and calculate the average
$ratings = [];
$average_rating = 0;
$total_ratings = 0;
$ratings_stmt = $db->prepare("SELECT r.rating, r.review, r.created_at, u.username, u.profile_picture FROM course_ratings r JOIN users u ON r.user_id = u.id WHERE r.course_id = ? ORDER BY r.created_at DESC");
$ratings_stmt->bind_param("i", $course_id);
$ratings_stmt->execute();
$ratings_result = $ratings_stmt->get_result();
if ($ratings_result->num_rows > 0) {
    $total_ratings = $ratings_result->num_rows;
    $sum_of_ratings = 0;
    while ($row = $ratings_result->fetch_assoc()) {
        $ratings[] = $row;
        $sum_of_ratings += $row['rating'];
    }
    $average_rating = round($sum_of_ratings / $total_ratings, 1);
}
$ratings_stmt->close();


// Handle session messages for display
$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);

// --- PAGE DISPLAY ---
require_once __DIR__ . '/includes/header.php';
?>

<div class="course-view-header" style="background-image: linear-gradient(rgba(0, 95, 115, 0.85), rgba(0, 95, 115, 0.85)), url('<?php echo e($course['cover_image'] ?? '/assets/images/placeholder_course.jpg'); ?>');">
    <div class="container">
        <h1><?php echo e($course['title']); ?></h1>
        <p class="course-tagline"><?php echo e(substr($course['description'], 0, 150)); ?>...</p>
        <p class="course-instructor-info">Created by: <strong><?php echo e($course['instructor_name']); ?></strong></p>
        <div class="stars-display large-stars" data-rating="<?php echo e($average_rating); ?>"></div>
        <span class="total-ratings header-ratings">(<?php echo $total_ratings; ?> ratings)</span>
    </div>
</div>

<div class="container course-view-container">
    <?php if ($success_message): ?><div class="form-message success"><?php echo e($success_message); ?></div><?php endif; ?>
    <?php if ($error_message || !empty($errors)): ?>
        <div class="form-message error">
            <?php echo e($error_message); ?>
            <?php foreach ($errors as $error) echo "<p>" . e($error) . "</p>"; ?>
        </div>
    <?php endif; ?>

    <div class="course-main-content">
        <h2>About This Course</h2>
        <p><?php echo nl2br(e($course['description'])); ?></p>
        <hr>
        <h2>Course Curriculum</h2>
        <div class="curriculum-list">
            <?php if (empty($lessons)): ?>
                <p>The curriculum for this course has not been published yet.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($lessons as $index => $lesson): ?>
                        <li>
                            <span class="lesson-number"><?php echo $index + 1; ?></span>
                            <span class="lesson-icon icon-<?php echo e($lesson['lesson_type']); ?>"></span>
                            <?php echo e($lesson['title']); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <hr>
        <!-- RATINGS & REVIEWS SECTION -->
        <div class="management-section">
            <h2>Ratings & Reviews</h2>
            
            <?php if ($is_enrolled): ?>
                <div class="review-form-container">
                    <h3>Leave or update your review</h3>
                    <form action="view_course.php?id=<?php echo $course_id; ?>" method="POST">
                        <input type="hidden" name="action" value="submit_rating">
                        <div class="form-group">
                            <label>Your Rating</label>
                            <div class="star-rating-input">
                                <input type="radio" id="star5" name="rating" value="5" required/><label for="star5" title="5 stars">★</label>
                                <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="4 stars">★</label>
                                <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="3 stars">★</label>
                                <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="2 stars">★</label>
                                <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="1 star">★</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="review">Your Written Review</label>
                            <textarea name="review" id="review" rows="5" placeholder="Share your experience with this course..." required></textarea>
                        </div>
                        <button type="submit" class="button button-primary">Submit Review</button>
                    </form>
                </div>
            <?php endif; ?>

            <div class="review-list">
                <?php if(empty($ratings)): ?>
                    <p>This course has no reviews yet.</p>
                <?php else: ?>
                    <?php foreach($ratings as $rating_data): ?>
                        <div class="review-card">
                            <div class="review-author">
                                <?php echo render_avatar($rating_data['profile_picture'] ?? null, $rating_data['username'], 'medium'); ?>
                                <div>
                                    <strong><?php echo e($rating_data['username']); ?></strong>
                                    <small><?php echo date('F j, Y', strtotime($rating_data['created_at'])); ?></small>
                                </div>
                            </div>
                            <div class="review-content">
                                <div class="stars-display" data-rating="<?php echo e($rating_data['rating']); ?>"></div>
                                <p><?php echo nl2br(e($rating_data['review'])); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <aside class="course-sidebar">
        <div class="sidebar-widget">
            <?php if ($course['status'] === 'archived'): ?>
                <div class="button button-disabled">Enrollment Closed</div>
            <?php elseif ($is_enrolled): ?>
                <a href="<?php echo $base; ?>/student/view_lesson.php?id=<?php echo $lessons[0]['id'] ?? 0; ?>" class="button button-primary button-large">Continue Learning</a>
            <?php elseif (isset($_SESSION['user_id']) && $is_student): ?>
                 <a href="<?php echo $base; ?>/enroll.php?course_id=<?php echo $course_id; ?>" class="button button-primary button-large">Enroll Now</a>
            <?php elseif (isset($_SESSION['user_id']) && !$is_student): ?>
                 <div class="button button-disabled">Enrollment is for Students</div>
            <?php else: ?>
                <a href="<?php echo $base; ?>/logins.php" class="button button-primary button-large">Login to Enroll</a>
            <?php endif; ?>
            
            <h4>Course Features</h4>
            <ul>
                <li>100% Online</li>
                <li>Self-Paced Learning</li>
                <li>Certificate of Completion</li>
                <li>Taught by Experts</li>
            </ul>
        </div>
    </aside>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>