<?php
// --- SETUP ---
$page_title = 'Search Results';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/header.php';

// --- SEARCH LOGIC ---
$search_query = trim($_GET['q'] ?? '');
$search_results = [];

if (!empty($search_query)) {
    // Sanitize the search query and prepare it for a LIKE search
    $search_term = "%" . $search_query . "%";
    
    // The query searches the course title, description, and the instructor's username.
    $sql = "SELECT c.id, c.title, c.description, c.cover_image, u.username AS instructor_name,
                   (SELECT AVG(rating) FROM course_ratings WHERE course_id = c.id) as avg_rating,
                   (SELECT COUNT(id) FROM course_ratings WHERE course_id = c.id) as total_ratings
            FROM courses c
            JOIN users u ON c.instructor_id = u.id
            WHERE (c.title LIKE ? OR c.description LIKE ? OR u.username LIKE ?) AND c.status = 'published'
            ORDER BY c.created_at DESC";

    $stmt = $db->prepare($sql);
    $stmt->bind_param("sss", $search_term, $search_term, $search_term);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $search_results[] = $row;
        }
    }
    $stmt->close();
}

// --- PAGE DISPLAY ---
?>

<div class="container">
    <div class="page-header">
        <h1>Search Results for "<?php echo e($search_query); ?>"</h1>
    </div>
    <p class="section-subtitle"><?php echo count($search_results); ?> courses found.</p>

    <?php if (empty($search_results) && !empty($search_query)): ?>
        <div class="info-box">
            <p>Sorry, we couldn't find any courses matching your search. Please try a different keyword.</p>
        </div>
    <?php elseif (!empty($search_results)): ?>
        <div class="course-grid">
            <?php foreach ($search_results as $course): ?>
                <div class="course-card">
                    <a href="<?php echo $base; ?>/view_course.php?id=<?php echo $course['id']; ?>" class="course-card-image-link">
                       <img src="<?php echo e($course['cover_image'] ?? '/assets/images/placeholder_course.jpg'); ?>" alt="Cover image for <?php echo e($course['title']); ?>" class="course-card-image">
                    </a>
                    <div class="course-card-content">
                        <h3><a href="<?php echo $base; ?>/view_course.php?id=<?php echo $course['id']; ?>"><?php echo e($course['title']); ?></a></h3>
                        <p class="course-card-instructor">By <?php echo e($course['instructor_name']); ?></p>
                        <div class="course-card-rating">
                            <?php if ($course['total_ratings'] > 0): ?>
                                <span class="rating-score"><?php echo round($course['avg_rating'], 1); ?></span>
                                <div class="stars-display" data-rating="<?php echo e($course['avg_rating']); ?>"></div>
                                <span class="rating-count">(<?php echo e($course['total_ratings']); ?>)</span>
                            <?php else: ?>
                                <span class="rating-score">New</span>
                            <?php endif; ?>
                        </div>
                        <p class="course-card-description"><?php echo e(substr($course['description'], 0, 80)); ?>...</p>
                    </div>
                     <div class="course-card-footer">
                        <a href="<?php echo $base; ?>/view_course.php?id=<?php echo $course['id']; ?>" class="button button-primary button-fullwidth">View Course</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
         <div class="info-box"><p>Please enter a keyword in the search bar to find courses.</p></div>
    <?php endif; ?>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>