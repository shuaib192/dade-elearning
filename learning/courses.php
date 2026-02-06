<?php
// --- SETUP ---
$page_title = 'All Courses';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/header.php';

// --- DATA FETCHING ---
$categories = [];
$category_result = $db->query("SELECT * FROM categories ORDER BY name ASC");
if ($category_result) { while($row = $category_result->fetch_assoc()) { $categories[] = $row; } }

$sql = "SELECT c.id, c.title, c.cover_image, u.username AS instructor_name, cat.name as category_name, (SELECT AVG(rating) FROM course_ratings WHERE course_id = c.id) as avg_rating, (SELECT COUNT(id) FROM course_ratings WHERE course_id = c.id) as total_ratings FROM courses c JOIN users u ON c.instructor_id = u.id LEFT JOIN categories cat ON c.category_id = cat.id WHERE c.status = 'published'";
$params = [];
$types = '';

$search_query = trim($_GET['q'] ?? '');
if (!empty($search_query)) {
    $sql .= " AND (c.title LIKE ? OR c.description LIKE ? OR u.username LIKE ?)";
    $search_term = "%" . $search_query . "%";
    array_push($params, $search_term, $search_term, $search_term);
    $types .= 'sss';
}

$category_filter = trim($_GET['category'] ?? '');
if (!empty($category_filter)) {
    $sql .= " AND cat.slug = ?";
    $params[] = $category_filter;
    $types .= 's';
}
$sql .= " ORDER BY c.created_at DESC";
$stmt = $db->prepare($sql);
if (!empty($params)) { $stmt->bind_param($types, ...$params); }
$stmt->execute();
$result = $stmt->get_result();
$courses = [];
if ($result && $result->num_rows > 0) { while ($row = $result->fetch_assoc()) { $courses[] = $row; } }

?>

<div class="container">
    <div class="page-header"><h1>Explore Courses</h1></div>
    <div class="course-filter-bar">
        <div class="search-box">
            <form action="<?php echo $base; ?>/courses.php" method="GET">
                <input type="search" name="q" placeholder="Search courses..." value="<?php echo e($search_query); ?>">
                <button type="submit">Search</button>
            </form>
        </div>
        <div class="category-filters">
            <a href="<?php echo $base; ?>/courses.php" class="<?php echo empty($category_filter) ? 'active' : ''; ?>">All</a>
            <?php foreach ($categories as $category): ?>
                <a href="?category=<?php echo e($category['slug']); ?>" class="<?php echo ($category_filter == $category['slug']) ? 'active' : ''; ?>"><?php echo e($category['name']); ?></a>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if (empty($courses)): ?>
        <div class="info-box" style="margin-top: 2rem;"><p>No courses found matching your criteria.</p></div>
    <?php else: ?>
        <div class="course-grid compact-grid">
            <?php foreach ($courses as $course): ?>
                <div class="course-card compact-card">
                    <a href="view_course.php?id=<?php echo $course['id']; ?>" class="course-card-image-link">
                       <img src="<?php echo e($course['cover_image'] ?? '/assets/images/placeholder_course.jpg'); ?>" alt="Cover image for <?php echo e($course['title']); ?>" class="course-card-image">
                    </a>
                    <div class="course-card-content">
                        <h3><a href="view_course.php?id=<?php echo $course['id']; ?>"><?php echo e($course['title']); ?></a></h3>
                        <p class="course-card-instructor">By <?php echo e($course['instructor_name']); ?></p>
                        <div class="course-card-rating">
                            <?php if ($course['total_ratings'] > 0): ?>
                                <span class="rating-score"><?php echo round($course['avg_rating'], 1); ?></span>
                                <span class="simple-stars">
                                    <?php 
                                    $rounded_rating = round($course['avg_rating']);
                                    for ($i = 1; $i <= 5; $i++):
                                        if ($i <= $rounded_rating): echo '<span class="star-filled">★</span>'; else: echo '<span class="star-empty">★</span>'; endif;
                                    endfor;
                                    ?>
                                </span>
                                <span class="rating-count">(<?php echo e($course['total_ratings']); ?>)</span>
                            <?php else: ?>
                                <span class="rating-score">New</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>