<?php
/**
 * Dade Initiative - Course Detail Page
 * Shows full course information with curriculum
 */

$slug = $_GET['slug'] ?? '';
$db = getDB();

// Get course by slug or ID
$course = null;
try {
    if (is_numeric($slug)) {
        $stmt = $db->prepare("SELECT c.*, u.username as instructor_name, u.bio as instructor_bio, 
                              u.profile_picture as instructor_avatar, cat.name as category_name
                              FROM courses c 
                              LEFT JOIN users u ON c.instructor_id = u.id
                              LEFT JOIN categories cat ON c.category_id = cat.id
                              WHERE c.id = ?");
    } else {
        $stmt = $db->prepare("SELECT c.*, u.username as instructor_name, u.bio as instructor_bio,
                              u.profile_picture as instructor_avatar, cat.name as category_name
                              FROM courses c 
                              LEFT JOIN users u ON c.instructor_id = u.id
                              LEFT JOIN categories cat ON c.category_id = cat.id
                              WHERE c.slug = ?");
    }
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $course = $stmt->get_result()->fetch_assoc();
} catch (Exception $e) {
    error_log("Course detail query error: " . $e->getMessage());
}

if (!$course) {
    http_response_code(404);
    require_once APP_ROOT . '/views/errors/404.php';
    exit;
}

$pageTitle = $course['title'];

// Get course stats with fallback values
$courseStats = [
    'enrolled' => 0,
    'avg_rating' => 4.5,
    'review_count' => 0,
    'lesson_count' => 0
];

try {
    // Count lessons directly
    $stmt = $db->prepare("SELECT COUNT(*) as cnt FROM lessons WHERE course_id = ?");
    $stmt->bind_param("i", $course['id']);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $courseStats['lesson_count'] = $result['cnt'] ?? 0;
    
    // Count enrollments (may not exist yet)
    $tableCheck = $db->query("SHOW TABLES LIKE 'enrollments'");
    if ($tableCheck && $tableCheck->num_rows > 0) {
        $stmt = $db->prepare("SELECT COUNT(*) as cnt FROM enrollments WHERE course_id = ?");
        $stmt->bind_param("i", $course['id']);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $courseStats['enrolled'] = $result['cnt'] ?? 0;
    }
} catch (Exception $e) {
    error_log("Course stats query error: " . $e->getMessage());
}

// Get lessons with flexible ordering
$courseLessons = [];
try {
    // Try with order_index first, fall back to lesson_order or id
    $lessonQuery = "SELECT * FROM lessons WHERE course_id = ? ORDER BY 
                    CASE WHEN order_index IS NOT NULL AND order_index > 0 THEN order_index 
                         WHEN lesson_order IS NOT NULL THEN lesson_order 
                         ELSE id END ASC";
    $stmt = $db->prepare($lessonQuery);
    $stmt->bind_param("i", $course['id']);
    $stmt->execute();
    $courseLessons = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    error_log("Lessons query error: " . $e->getMessage());
}

// Check if user is enrolled
$isEnrolled = false;
if (Auth::check()) {
    try {
        $tableCheck = $db->query("SHOW TABLES LIKE 'enrollments'");
        if ($tableCheck && $tableCheck->num_rows > 0) {
            $check = $db->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?");
            $userId = Auth::id();
            $check->bind_param("ii", $userId, $course['id']);
            $check->execute();
            $isEnrolled = $check->get_result()->num_rows > 0;
        }
    } catch (Exception $e) {
        error_log("Enrollment check error: " . $e->getMessage());
    }
}

// Get reviews
$reviews = [];
try {
    $tableCheck = $db->query("SHOW TABLES LIKE 'reviews'");
    if ($tableCheck && $tableCheck->num_rows > 0) {
        $reviewsStmt = $db->prepare("SELECT r.*, u.username, u.profile_picture 
                                      FROM reviews r 
                                      LEFT JOIN users u ON r.user_id = u.id 
                                      WHERE r.course_id = ? 
                                      ORDER BY r.created_at DESC LIMIT 5");
        $reviewsStmt->bind_param("i", $course['id']);
        $reviewsStmt->execute();
        $reviews = $reviewsStmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
} catch (Exception $e) {
    error_log("Reviews query error: " . $e->getMessage());
}

require_once APP_ROOT . '/views/layouts/header.php';
?>

<div class="course-detail-page">
    <!-- Course Hero -->
    <section class="course-hero">
        <div class="container">
            <div class="course-hero-grid">
                <div class="course-hero-content">
                    <nav class="breadcrumb">
                        <a href="<?php echo url(); ?>">Home</a>
                        <i class="fas fa-chevron-right"></i>
                        <a href="<?php echo url('courses'); ?>">Courses</a>
                        <i class="fas fa-chevron-right"></i>
                        <span><?php echo e($course['category_name'] ?? 'Course'); ?></span>
                    </nav>
                    
                    <h1 class="course-title"><?php echo e($course['title']); ?></h1>
                    
                    <p class="course-description"><?php echo e(truncate($course['description'], 200)); ?></p>
                    
                    <div class="course-meta">
                        <div class="course-rating">
                            <?php echo starRating($courseStats['avg_rating'] ?? 4.5); ?>
                            <span class="rating-value"><?php echo number_format($courseStats['avg_rating'] ?? 4.5, 1); ?></span>
                            <span class="rating-count">(<?php echo $courseStats['review_count'] ?? 0; ?> reviews)</span>
                        </div>
                        <span class="meta-divider">•</span>
                        <span class="course-students">
                            <i class="fas fa-user-graduate"></i>
                            <?php echo formatNumber($courseStats['enrolled'] ?? 0); ?> students
                        </span>
                    </div>
                    
                    <div class="course-instructor-mini">
                        <img src="<?php echo avatar(['username' => $course['instructor_name'], 'profile_picture' => $course['instructor_avatar']]); ?>" 
                             alt="<?php echo e($course['instructor_name']); ?>">
                        <span>Created by <strong><?php echo e($course['instructor_name']); ?></strong></span>
                    </div>
                    
                    <div class="course-info-tags">
                        <span><i class="fas fa-signal"></i> <?php echo ucfirst($course['level'] ?? 'All Levels'); ?></span>
                        <span><i class="fas fa-book"></i> <?php echo $courseStats['lesson_count'] ?? 0; ?> lessons</span>
                        <span><i class="fas fa-clock"></i> <?php echo $course['duration'] ?? 'Self-paced'; ?></span>
                        <span><i class="fas fa-globe"></i> English</span>
                    </div>
                </div>
                
                <!-- Course Card (Sticky on Desktop) -->
                <div class="course-hero-card">
                    <div class="course-card-inner">
                        <div class="course-thumbnail">
                            <?php 
                            $thumbnail = !empty($course['cover_image']) 
                                ? url('uploads/courses/' . $course['cover_image'])
                                : url('assets/images/course-placeholder.png');
                            ?>
                            <img src="<?php echo $thumbnail; ?>" alt="<?php echo e($course['title']); ?>"
                                 onerror="this.src='<?php echo url('assets/images/course-placeholder.png'); ?>'">
                            <button class="play-preview">
                                <i class="fas fa-play"></i>
                            </button>
                        </div>
                        
                        <div class="course-card-body">
                            <?php if ($isEnrolled): ?>
                            <a href="<?php echo url('learn/' . ($course['slug'] ?? $course['id'])); ?>" class="btn btn-primary btn-lg btn-block">
                                <i class="fas fa-play-circle"></i>
                                Continue Learning
                            </a>
                            <?php else: ?>
                            <?php if (($course['price'] ?? 0) > 0): ?>
                            <div class="course-price-box">
                                <span class="price-amount"><?php echo SITE_CURRENCY_SYMBOL . number_format($course['price'], 2); ?></span>
                                <span class="price-label">Lifetime Access</span>
                            </div>
                            <form action="<?php echo url('payment/initialize'); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                                <button type="submit" class="btn btn-primary btn-lg btn-block">
                                    <i class="fas fa-shopping-cart"></i>
                                    Buy Now
                                </button>
                            </form>
                            <?php else: ?>
                            <form action="<?php echo url('enroll/' . $course['id']); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-accent btn-lg btn-block">
                                    <i class="fas fa-graduation-cap"></i>
                                    Enroll Now - Free
                                </button>
                            </form>
                            <?php endif; ?>
                            <?php endif; ?>
                            
                            <p class="enroll-note">Full lifetime access • Certificate of completion</p>
                            
                            <div class="course-includes">
                                <h4>This course includes:</h4>
                                <ul>
                                    <li><i class="fas fa-video"></i> <?php echo $courseStats['lesson_count'] ?? 0; ?> video lessons</li>
                                    <li><i class="fas fa-file-alt"></i> Downloadable resources</li>
                                    <li><i class="fas fa-mobile-alt"></i> Access on mobile and TV</li>
                                    <li><i class="fas fa-infinity"></i> Lifetime access</li>
                                    <li><i class="fas fa-certificate"></i> Certificate of completion</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Course Content -->
    <section class="course-content-section">
        <div class="container">
            <div class="course-content-grid">
                <div class="course-main-content">
                    <!-- What You'll Learn -->
                    <div class="content-block">
                        <h2>What you'll learn</h2>
                        <div class="learn-grid">
                            <div class="learn-item">
                                <i class="fas fa-check"></i>
                                <span>Master the fundamentals and advanced concepts</span>
                            </div>
                            <div class="learn-item">
                                <i class="fas fa-check"></i>
                                <span>Build real-world projects from scratch</span>
                            </div>
                            <div class="learn-item">
                                <i class="fas fa-check"></i>
                                <span>Learn industry best practices</span>
                            </div>
                            <div class="learn-item">
                                <i class="fas fa-check"></i>
                                <span>Get job-ready skills</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Course Curriculum -->
                    <div class="content-block">
                        <h2>Course Curriculum</h2>
                        <p class="curriculum-info">
                            <?php echo $courseStats['lesson_count'] ?? 0; ?> lessons • 
                            <?php echo $course['duration'] ?? 'Self-paced learning'; ?>
                        </p>
                        
                        <div class="curriculum-accordion">
                            <?php if (empty($courseLessons)): ?>
                            <div class="curriculum-item">
                                <div class="curriculum-header">
                                    <span class="curriculum-title">
                                        <i class="fas fa-play-circle"></i>
                                        Introduction to the Course
                                    </span>
                                    <span class="curriculum-duration">5 min</span>
                                </div>
                            </div>
                            <div class="curriculum-item">
                                <div class="curriculum-header">
                                    <span class="curriculum-title">
                                        <i class="fas fa-play-circle"></i>
                                        Getting Started
                                    </span>
                                    <span class="curriculum-duration">12 min</span>
                                </div>
                            </div>
                            <div class="curriculum-item locked">
                                <div class="curriculum-header">
                                    <span class="curriculum-title">
                                        <i class="fas fa-lock"></i>
                                        Core Concepts
                                    </span>
                                    <span class="curriculum-duration">18 min</span>
                                </div>
                            </div>
                            <?php else: ?>
                            <?php foreach ($courseLessons as $index => $lesson): ?>
                            <div class="curriculum-item <?php echo $index > 1 && !$isEnrolled ? 'locked' : ''; ?>">
                                <div class="curriculum-header">
                                    <span class="curriculum-title">
                                        <i class="fas fa-<?php echo $index > 1 && !$isEnrolled ? 'lock' : 'play-circle'; ?>"></i>
                                        <?php echo e($lesson['title']); ?>
                                    </span>
                                    <span class="curriculum-duration">
                                        <?php echo $lesson['duration'] ?? '10 min'; ?>
                                    </span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="content-block">
                        <h2>Description</h2>
                        <div class="course-description-full">
                            <?php echo nl2br(e($course['description'])); ?>
                        </div>
                    </div>
                    
                    <!-- Instructor -->
                    <div class="content-block">
                        <h2>Your Instructor</h2>
                        <div class="instructor-card">
                            <img src="<?php echo avatar(['username' => $course['instructor_name'], 'profile_picture' => $course['instructor_avatar']], 120); ?>" 
                                 alt="<?php echo e($course['instructor_name']); ?>" class="instructor-avatar">
                            <div class="instructor-info">
                                <h3><?php echo e($course['instructor_name']); ?></h3>
                                <p class="instructor-title">Course Instructor</p>
                                <p class="instructor-bio">
                                    <?php echo e($course['instructor_bio'] ?? 'An experienced instructor passionate about teaching and helping students achieve their goals.'); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Reviews -->
                    <div class="content-block">
                        <h2>Student Reviews</h2>
                        
                        <?php if (Auth::check() && $isEnrolled): ?>
                        <?php 
                        // Check if already reviewed
                        $stmt = $db->prepare("SELECT id FROM reviews WHERE user_id = ? AND course_id = ?");
                        $userId = Auth::id();
                        $stmt->bind_param("ii", $userId, $course['id']);
                        $stmt->execute();
                        $hasReviewed = $stmt->get_result()->num_rows > 0;
                        ?>
                        
                        <?php if (!$hasReviewed): ?>
                        <div class="leave-review">
                            <h3>Leave a Review</h3>
                            <form action="<?php echo url('review/store'); ?>" method="POST" class="review-form">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                                
                                <div class="rating-input">
                                    <label>Rate this course:</label>
                                    <div class="star-rating-input">
                                        <input type="radio" id="star5" name="rating" value="5" required /><label for="star5" title="5 stars"><i class="fas fa-star"></i></label>
                                        <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="4 stars"><i class="fas fa-star"></i></label>
                                        <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="3 stars"><i class="fas fa-star"></i></label>
                                        <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="2 stars"><i class="fas fa-star"></i></label>
                                        <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="1 star"><i class="fas fa-star"></i></label>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <textarea name="comment" class="form-control" rows="3" placeholder="Share your experience with this course..." required></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Submit Review</button>
                            </form>
                        </div>
                        <?php endif; ?>
                        <?php endif; ?>

                        <?php if (empty($reviews)): ?>
                        <p class="no-reviews">No reviews yet. Be the first to review this course!</p>
                        <?php else: ?>
                        <div class="reviews-list">
                            <?php foreach ($reviews as $review): ?>
                            <div class="review-item">
                                <img src="<?php echo avatar(['username' => $review['username'], 'profile_picture' => $review['profile_picture']]); ?>" 
                                     alt="<?php echo e($review['username']); ?>" class="review-avatar">
                                <div class="review-content">
                                    <div class="review-header">
                                        <span class="review-author"><?php echo e($review['username']); ?></span>
                                        <span class="review-date"><?php echo timeAgo($review['created_at']); ?></span>
                                    </div>
                                    <div class="review-rating">
                                        <?php echo starRating($review['rating']); ?>
                                    </div>
                                    <p class="review-text"><?php echo e($review['comment']); ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.course-detail-page {
    background: var(--bg-primary);
}
.course-hero {
    background: linear-gradient(135deg, var(--gray-900) 0%, var(--gray-800) 100%);
    padding: var(--space-12) 0 var(--space-16);
}
.course-hero-grid {
    display: grid;
    gap: var(--space-10);
}
@media (min-width: 1024px) {
    .course-hero-grid {
        grid-template-columns: 1fr 400px;
    }
}
.course-hero-content .breadcrumb {
    margin-bottom: var(--space-4);
}
.course-hero-content .breadcrumb, .course-hero-content .breadcrumb a {
    color: rgba(255,255,255,0.7);
}
.course-title {
    color: var(--white);
    font-size: var(--text-3xl);
    line-height: 1.3;
    margin-bottom: var(--space-4);
}
@media (min-width: 768px) {
    .course-title {
        font-size: var(--text-4xl);
    }
}
.course-hero-content .course-description {
    color: rgba(255,255,255,0.9);
    font-size: var(--text-lg);
    margin-bottom: var(--space-5);
}
.course-meta {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: var(--space-3);
    margin-bottom: var(--space-4);
    color: rgba(255,255,255,0.8);
}
.course-rating {
    display: flex;
    align-items: center;
    gap: var(--space-2);
}
.rating-value {
    font-weight: 700;
    color: var(--accent);
}
.meta-divider {
    color: rgba(255,255,255,0.4);
}
.course-instructor-mini {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    margin-bottom: var(--space-5);
    color: rgba(255,255,255,0.8);
}
.course-instructor-mini img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
}
.course-instructor-mini strong {
    color: var(--white);
}
.course-info-tags {
    display: flex;
    flex-wrap: wrap;
    gap: var(--space-4);
    font-size: var(--text-sm);
    color: rgba(255,255,255,0.7);
}
.course-info-tags i {
    margin-right: var(--space-2);
}
.course-hero-card {
    position: relative;
}
@media (min-width: 1024px) {
    .course-hero-card {
        position: sticky;
        top: calc(var(--header-height) + var(--space-6));
    }
}
.course-card-inner {
    background: var(--white);
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow-2xl);
}
.course-thumbnail {
    position: relative;
}
.course-thumbnail img {
    width: 100%;
    aspect-ratio: 16/9;
    object-fit: cover;
}
.play-preview {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 72px;
    height: 72px;
    background: var(--white);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: var(--text-2xl);
    color: var(--primary);
    box-shadow: var(--shadow-lg);
    transition: all var(--transition-normal);
}
.play-preview:hover {
    transform: translate(-50%, -50%) scale(1.1);
    box-shadow: var(--shadow-xl);
}
.course-card-body {
    padding: var(--space-6);
}
.enroll-note {
    text-align: center;
    font-size: var(--text-sm);
    color: var(--text-muted);
    margin: var(--space-4) 0;
}
.course-includes {
    padding-top: var(--space-5);
    border-top: 1px solid var(--gray-100);
}
.course-includes h4 {
    font-size: var(--text-base);
    margin-bottom: var(--space-4);
}
.course-includes ul {
    display: flex;
    flex-direction: column;
    gap: var(--space-3);
}
.course-includes li {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    font-size: var(--text-sm);
    color: var(--text-secondary);
}
.course-includes i {
    width: 20px;
    color: var(--primary);
}
.course-content-section {
    padding: var(--space-12) 0;
}
.course-content-grid {
    max-width: 800px;
}
.content-block {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: var(--space-8);
    margin-bottom: var(--space-6);
    box-shadow: var(--shadow-sm);
}
.content-block h2 {
    font-size: var(--text-xl);
    margin-bottom: var(--space-5);
    padding-bottom: var(--space-4);
    border-bottom: 1px solid var(--gray-100);
}
.learn-grid {
    display: grid;
    gap: var(--space-4);
}
@media (min-width: 640px) {
    .learn-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
.learn-item {
    display: flex;
    align-items: flex-start;
    gap: var(--space-3);
}
.learn-item i {
    color: var(--success);
    margin-top: 4px;
}
.curriculum-info {
    color: var(--text-muted);
    margin-bottom: var(--space-4);
}
.curriculum-accordion {
    border: 1px solid var(--gray-100);
    border-radius: var(--radius-lg);
    overflow: hidden;
}
.curriculum-item {
    border-bottom: 1px solid var(--gray-100);
}
.curriculum-item:last-child {
    border-bottom: none;
}
.curriculum-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--space-4);
    background: var(--gray-50);
    transition: background var(--transition-fast);
}
.curriculum-item:hover .curriculum-header {
    background: var(--gray-100);
}
.curriculum-title {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    font-weight: 500;
}
.curriculum-title i {
    color: var(--primary);
}
.curriculum-item.locked .curriculum-title i {
    color: var(--gray-400);
}
.curriculum-duration {
    font-size: var(--text-sm);
    color: var(--text-muted);
}
.course-description-full {
    line-height: 1.8;
    color: var(--text-secondary);
}
.instructor-card {
    display: flex;
    gap: var(--space-6);
    align-items: flex-start;
}
.instructor-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}
.instructor-info h3 {
    margin-bottom: var(--space-1);
}
.instructor-title {
    color: var(--text-muted);
    margin-bottom: var(--space-3);
}
.instructor-bio {
    color: var(--text-secondary);
    margin: 0;
}
.no-reviews {
    color: var(--text-muted);
    font-style: italic;
}
.reviews-list {
    display: flex;
    flex-direction: column;
    gap: var(--space-6);
}
.review-item {
    display: flex;
    gap: var(--space-4);
}
.review-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    flex-shrink: 0;
}
.review-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: var(--space-2);
}
.review-author {
    font-weight: 600;
}
.review-date {
    font-size: var(--text-sm);
    color: var(--text-muted);
}
.review-rating {
    margin-bottom: var(--space-2);
}
.review-text {
    color: var(--text-secondary);
    margin: 0;
}

/* Reviews & Comments styling */
.leave-review {
    background: var(--gray-50);
    padding: var(--space-6);
    border-radius: var(--radius-lg);
    margin-bottom: var(--space-8);
}
.leave-review h3 {
    margin-bottom: var(--space-4);
    font-size: var(--text-lg);
}
.star-rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: var(--space-1);
    margin-bottom: var(--space-4);
}
.star-rating-input input {
    display: none;
}
.star-rating-input label {
    font-size: 24px;
    color: var(--gray-300);
    cursor: pointer;
    transition: color 0.2s ease;
}
.star-rating-input label:hover,
.star-rating-input label:hover ~ label,
.star-rating-input input:checked ~ label {
    color: #fbbf24; /* Gold */
}
.review-form textarea {
    width: 100%;
    margin-bottom: var(--space-4);
}

.course-price-box {
    text-align: center;
    margin-bottom: var(--space-4);
    padding: var(--space-4);
    background: var(--gray-50);
    border-radius: var(--radius-lg);
}

.price-amount {
    display: block;
    font-size: var(--text-3xl);
    font-weight: 800;
    color: var(--primary);
}

.price-label {
    font-size: var(--text-sm);
    color: var(--text-muted);
}
@media (max-width: 768px) {
    .course-hero {
        padding: var(--space-8) 0 var(--space-12);
    }
    .instructor-card {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .review-item {
        flex-direction: column;
        align-items: flex-start;
    }
    .review-header {
        flex-direction: column;
        gap: var(--space-1);
    }
    .content-block {
        padding: var(--space-5);
    }
}
</style>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
