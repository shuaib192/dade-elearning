<?php
/**
 * Dade Initiative - Course Catalog
 * Browse and filter all available courses
 */

$pageTitle = 'Browse Courses';

// Include BookmarkController for bookmark check
if (!class_exists('BookmarkController')) {
    require_once APP_ROOT . '/controllers/BookmarkController.php';
}

require_once APP_ROOT . '/views/layouts/header.php';

$db = getDB();

// Get filter parameters
$categoryId = !empty($_GET['category']) ? intval($_GET['category']) : null;
$level = !empty($_GET['level']) ? $_GET['level'] : null;
$sort = !empty($_GET['sort']) ? $_GET['sort'] : 'popular';
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = ITEMS_PER_PAGE;
$offset = ($page - 1) * $perPage;

// Build query
$where = ["c.status = 'published'"];
$params = [];
$types = "";

if ($categoryId) {
    $where[] = "c.category_id = ?";
    $params[] = $categoryId;
    $types .= "i";
}

if ($level && in_array($level, ['beginner', 'intermediate', 'advanced'])) {
    $where[] = "c.level = ?";
    $params[] = $level;
    $types .= "s";
}

$whereClause = implode(' AND ', $where);

// Sort options
$orderBy = match($sort) {
    'newest' => 'c.created_at DESC',
    'rating' => 'avg_rating DESC',
    'title' => 'c.title ASC',
    default => 'enrolled_count DESC'
};

// Get total count
$countQuery = "SELECT COUNT(*) as total FROM courses c WHERE $whereClause";
$stmt = $db->prepare($countQuery);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$totalCourses = $stmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalCourses / $perPage);

// Get courses - simplified query without subqueries for missing tables
$query = "
    SELECT c.*, u.username as instructor_name, u.profile_picture as instructor_avatar,
           cat.name as category_name
    FROM courses c
    LEFT JOIN users u ON c.instructor_id = u.id
    LEFT JOIN categories cat ON c.category_id = cat.id
    WHERE $whereClause
    ORDER BY c.created_at DESC
    LIMIT $perPage OFFSET $offset
";

$courses = [];
try {
    $stmt = $db->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
} catch (Exception $e) {
    error_log("Courses query error: " . $e->getMessage());
}

// Get all categories for filter
$categories = [];
try {
    $result = $db->query("SELECT * FROM categories ORDER BY name");
    if ($result) {
        $categories = $result->fetch_all(MYSQLI_ASSOC);
    }
} catch (Exception $e) {
    error_log("Categories query error: " . $e->getMessage());
}

// Get current category name
$currentCategory = null;
if ($categoryId) {
    foreach ($categories as $cat) {
        if ($cat['id'] == $categoryId) {
            $currentCategory = $cat['name'];
            break;
        }
    }
}
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="page-header-content">
            <nav class="breadcrumb">
                <a href="<?php echo url(); ?>">Home</a>
                <i class="fas fa-chevron-right"></i>
                <span>Courses</span>
                <?php if ($currentCategory): ?>
                <i class="fas fa-chevron-right"></i>
                <span><?php echo e($currentCategory); ?></span>
                <?php endif; ?>
            </nav>
            <h1 class="page-title"><?php echo $currentCategory ? e($currentCategory) . ' Courses' : 'All Courses'; ?></h1>
            <p class="page-subtitle"><?php echo $totalCourses; ?> courses available</p>
        </div>
    </div>
</section>

<style>
.page-header {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
    padding: var(--space-12) 0;
    margin-bottom: var(--space-10);
}
.page-header-content {
    text-align: center;
}
.breadcrumb {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-2);
    margin-bottom: var(--space-4);
    font-size: var(--text-sm);
    color: rgba(255,255,255,0.8);
}
.breadcrumb a {
    color: rgba(255,255,255,0.8);
}
.breadcrumb a:hover {
    color: white;
}
.breadcrumb i {
    font-size: 10px;
}
.page-title {
    color: white;
    margin-bottom: var(--space-2);
}
.page-subtitle {
    color: rgba(255,255,255,0.9);
    margin: 0;
}
</style>

<!-- Main Content -->
<section class="section-sm">
    <div class="container">
        <div class="courses-layout">
            <!-- Sidebar Filters -->
            <aside class="courses-sidebar">
                <div class="filter-section">
                    <h4 class="filter-title">Categories</h4>
                    <ul class="filter-list">
                        <li>
                            <a href="<?php echo url('courses'); ?>" class="filter-link <?php echo !$categoryId ? 'active' : ''; ?>">
                                All Categories
                                <span class="filter-count"><?php echo $totalCourses; ?></span>
                            </a>
                        </li>
                        <?php foreach ($categories as $cat): ?>
                        <li>
                            <a href="<?php echo url('courses?category=' . $cat['id']); ?>" 
                               class="filter-link <?php echo $categoryId == $cat['id'] ? 'active' : ''; ?>">
                                <?php echo e($cat['name']); ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div class="filter-section">
                    <h4 class="filter-title">Level</h4>
                    <ul class="filter-list">
                        <li>
                            <a href="<?php echo url('courses?' . http_build_query(array_merge($_GET, ['level' => '']))); ?>" 
                               class="filter-link <?php echo !$level ? 'active' : ''; ?>">
                                All Levels
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo url('courses?' . http_build_query(array_merge($_GET, ['level' => 'beginner']))); ?>" 
                               class="filter-link <?php echo $level === 'beginner' ? 'active' : ''; ?>">
                                <i class="fas fa-seedling"></i> Beginner
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo url('courses?' . http_build_query(array_merge($_GET, ['level' => 'intermediate']))); ?>" 
                               class="filter-link <?php echo $level === 'intermediate' ? 'active' : ''; ?>">
                                <i class="fas fa-signal"></i> Intermediate
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo url('courses?' . http_build_query(array_merge($_GET, ['level' => 'advanced']))); ?>" 
                               class="filter-link <?php echo $level === 'advanced' ? 'active' : ''; ?>">
                                <i class="fas fa-rocket"></i> Advanced
                            </a>
                        </li>
                    </ul>
                </div>
            </aside>
            
            <!-- Course Grid -->
            <div class="courses-content">
                <!-- Toolbar -->
                <div class="courses-toolbar">
                    <div class="courses-count">
                        Showing <?php echo count($courses); ?> of <?php echo $totalCourses; ?> courses
                    </div>
                    <button class="btn btn-secondary filter-toggle-btn hide-desktop" id="mobileFilterToggle">
                        <i class="fas fa-filter"></i> Filters
                    </button>
                    <div class="courses-sort">
                        <label for="sortSelect">Sort by:</label>
                        <select id="sortSelect" class="form-select" onchange="sortCourses(this.value)">
                            <option value="popular" <?php echo $sort === 'popular' ? 'selected' : ''; ?>>Most Popular</option>
                            <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Newest</option>
                            <option value="rating" <?php echo $sort === 'rating' ? 'selected' : ''; ?>>Highest Rated</option>
                            <option value="title" <?php echo $sort === 'title' ? 'selected' : ''; ?>>Title (A-Z)</option>
                        </select>
                    </div>
                </div>
                
                <!-- Courses Grid -->
                <?php if (empty($courses)): ?>
                <div class="empty-state">
                    <i class="fas fa-search"></i>
                    <h3>No courses found</h3>
                    <p>Try adjusting your filters or browse all courses.</p>
                    <a href="<?php echo url('courses'); ?>" class="btn btn-primary">View All Courses</a>
                </div>
                <?php else: ?>
                <div class="course-grid">
                    <?php foreach ($courses as $course): ?>
                    <article class="card course-card">
                        <div class="card-image">
                            <?php 
                            $thumbnail = !empty($course['cover_image']) 
                                ? url('uploads/courses/' . $course['cover_image'])
                                : url('assets/images/course-placeholder.png');
                            ?>
                            <img src="<?php echo $thumbnail; ?>" alt="<?php echo e($course['title']); ?>">
                            <?php if (!empty($course['level'])): ?>
                            <span class="card-badge"><?php echo ucfirst($course['level']); ?></span>
                            <?php endif; ?>
                            <?php $isBookmarked = BookmarkController::isBookmarked($course['id']); ?>
                            <button class="bookmark-btn <?php echo $isBookmarked ? 'bookmarked' : ''; ?>" 
                                    data-course-id="<?php echo $course['id']; ?>"
                                    title="<?php echo $isBookmarked ? 'Remove bookmark' : 'Bookmark course'; ?>">
                                <i class="<?php echo $isBookmarked ? 'fas' : 'far'; ?> fa-heart"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="card-meta">
                                <span><i class="fas fa-folder"></i> <?php echo e($course['category_name'] ?? 'General'); ?></span>
                            </div>
                            <h3 class="card-title">
                                <a href="<?php echo url('course/' . ($course['slug'] ?? $course['id'])); ?>">
                                    <?php echo e($course['title']); ?>
                                </a>
                            </h3>
                            <p class="card-excerpt">
                                <?php echo e(truncate($course['description'], 80)); ?>
                            </p>
                            <div class="card-instructor">
                                <img src="<?php echo avatar(['username' => $course['instructor_name'], 'profile_picture' => $course['instructor_avatar']]); ?>" 
                                     alt="<?php echo e($course['instructor_name']); ?>">
                                <span><?php echo e($course['instructor_name']); ?></span>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="card-stats-row">
                                <div class="card-rating">
                                    <?php echo starRating($course['avg_rating'] ?? 4.5); ?>
                                    <span class="rating-num"><?php echo number_format($course['avg_rating'] ?? 4.5, 1); ?></span>
                                </div>
                                <span class="card-students">
                                    <i class="fas fa-user-graduate"></i> <?php echo formatNumber($course['enrolled_count'] ?? 0); ?>
                                </span>
                            </div>
                            <div class="card-price-row">
                                <div class="card-price-badge <?php echo ($course['price'] ?? 0) > 0 ? 'price-paid' : 'price-free'; ?>">
                                    <?php if (($course['price'] ?? 0) > 0): ?>
                                        <span class="price-val"><?php echo SITE_CURRENCY_SYMBOL . number_format($course['price'], 2); ?></span>
                                    <?php else: ?>
                                        <span class="price-val">Free</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
                
                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                <nav class="pagination">
                    <?php if ($page > 1): ?>
                    <a href="<?php echo url('courses?' . http_build_query(array_merge($_GET, ['page' => $page - 1]))); ?>" class="pagination-link">
                        <i class="fas fa-chevron-left"></i> Previous
                    </a>
                    <?php endif; ?>
                    
                    <div class="pagination-numbers">
                        <?php for ($i = 1; $i <= min(5, $totalPages); $i++): ?>
                        <a href="<?php echo url('courses?' . http_build_query(array_merge($_GET, ['page' => $i]))); ?>" 
                           class="pagination-num <?php echo $i === $page ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                        <?php endfor; ?>
                    </div>
                    
                    <?php if ($page < $totalPages): ?>
                    <a href="<?php echo url('courses?' . http_build_query(array_merge($_GET, ['page' => $page + 1]))); ?>" class="pagination-link">
                        Next <i class="fas fa-chevron-right"></i>
                    </a>
                    <?php endif; ?>
                </nav>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<style>
.courses-layout {
    display: grid;
    gap: var(--space-8);
}
@media (min-width: 1024px) {
    .courses-layout {
        grid-template-columns: 280px 1fr;
    }
}
.courses-sidebar {
    display: none;
}
@media (min-width: 1024px) {
    .courses-sidebar {
        display: block;
    }
}
.filter-section {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: var(--space-5);
    margin-bottom: var(--space-5);
    box-shadow: var(--shadow-sm);
}
.filter-title {
    font-size: var(--text-base);
    margin-bottom: var(--space-4);
    padding-bottom: var(--space-3);
    border-bottom: 1px solid var(--gray-100);
}
.filter-list {
    display: flex;
    flex-direction: column;
    gap: var(--space-1);
}
.filter-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: var(--space-2) var(--space-3);
    border-radius: var(--radius-md);
    color: var(--text-secondary);
    font-size: var(--text-sm);
    transition: all var(--transition-fast);
}
.filter-link:hover, .filter-link.active {
    background: var(--primary-50);
    color: var(--primary);
}
.filter-link i {
    margin-right: var(--space-2);
    width: 16px;
}
.filter-count {
    background: var(--gray-100);
    padding: 2px 8px;
    border-radius: var(--radius-full);
    font-size: var(--text-xs);
}
.courses-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--space-6);
    padding: var(--space-4);
    background: var(--white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
}
.courses-count {
    color: var(--text-secondary);
    font-size: var(--text-sm);
}
.courses-sort {
    display: flex;
    align-items: center;
    gap: var(--space-2);
}
.courses-sort label {
    font-size: var(--text-sm);
    color: var(--text-secondary);
}
.courses-sort .form-select {
    width: auto;
    padding: var(--space-2) var(--space-4);
    font-size: var(--text-sm);
}
.card-instructor {
    display: flex;
    align-items: center;
    gap: var(--space-2);
    margin-top: auto;
    padding-top: var(--space-3);
    font-size: var(--text-sm);
    color: var(--text-muted);
}
.card-instructor img {
    width: 24px;
    height: 24px;
    border-radius: 50%;
}
.empty-state {
    text-align: center;
    padding: var(--space-16);
    background: var(--white);
    border-radius: var(--radius-xl);
}
.empty-state i {
    font-size: 64px;
    color: var(--gray-300);
    margin-bottom: var(--space-6);
}
.empty-state h3 {
    margin-bottom: var(--space-2);
}
.empty-state p {
    margin-bottom: var(--space-6);
}
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: var(--space-4);
    margin-top: var(--space-10);
}
.pagination-link {
    display: flex;
    align-items: center;
    gap: var(--space-2);
    padding: var(--space-3) var(--space-5);
    background: var(--white);
    border-radius: var(--radius-lg);
    color: var(--text-primary);
    font-weight: 500;
    box-shadow: var(--shadow-sm);
    transition: all var(--transition-fast);
}
.pagination-link:hover {
    background: var(--primary);
    color: var(--white);
}
.pagination-numbers {
    display: flex;
    gap: var(--space-2);
}
.pagination-num {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--white);
    border-radius: var(--radius-lg);
    color: var(--text-primary);
    font-weight: 500;
    box-shadow: var(--shadow-sm);
    transition: all var(--transition-fast);
}
.pagination-num:hover, .pagination-num.active {
    background: var(--primary);
    color: var(--white);
}

/* Bookmark Button */
.bookmark-btn {
    position: absolute;
    bottom: var(--space-3);
    right: var(--space-3);
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(255,255,255,0.95);
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
    font-size: 16px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    z-index: 5;
}
.bookmark-btn:hover {
    transform: scale(1.1);
    color: #ef4444;
}
.bookmark-btn.bookmarked {
    color: #ef4444;
    background: #fef2f2;
}
.bookmark-btn.bookmarked i {
    animation: heartPulse 0.3s ease;
}
@keyframes heartPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.3); }
    100% { transform: scale(1); }
}

.card-footer {
    display: flex;
    flex-direction: column;
    gap: var(--space-4);
    padding: var(--space-5);
    border-top: 1px solid var(--gray-100);
}

.card-stats-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-price-row {
    display: flex;
    justify-content: flex-end;
}

.card-price-badge {
    padding: var(--space-2) var(--space-4);
    border-radius: var(--radius-lg);
    font-weight: 800;
    font-size: var(--text-lg);
    box-shadow: var(--shadow-sm);
}

.price-paid {
    background: #fef2f2;
    color: var(--primary);
    border: 1px solid #fee2e2;
}

.price-free {
    color: #059669; /* Green */
    background: #ecfdf5;
}
@media (max-width: 1023px) {
    .courses-sidebar {
        position: fixed;
        top: 0;
        left: -100%;
        width: 80%;
        max-width: 300px;
        height: 100vh;
        z-index: 1002;
        background: var(--white);
        padding: var(--space-6);
        overflow-y: auto;
        transition: left 0.3s ease;
        display: block;
    }
    .courses-sidebar.active {
        left: 0;
    }
    .filter-toggle-btn {
        display: flex;
    }
    .courses-toolbar {
        flex-direction: column;
        gap: var(--space-4);
        align-items: stretch;
    }
    .courses-sort {
        justify-content: space-between;
    }
}

.hide-desktop {
    display: none;
}
@media (max-width: 1023px) {
    .hide-desktop {
        display: flex;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterToggle = document.getElementById('mobileFilterToggle');
    const sidebar = document.querySelector('.courses-sidebar');
    const overlay = document.getElementById('menuOverlay');

    if (filterToggle && sidebar) {
        filterToggle.addEventListener('click', function() {
            sidebar.classList.add('active');
            if (overlay) overlay.classList.add('active');
        });

        if (overlay) {
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
            });
        }
    }
});

function sortCourses(value) {
    const url = new URL(window.location);
    url.searchParams.set('sort', value);
    window.location = url;
}

// Bookmark toggle functionality
document.addEventListener('click', function(e) {
    if (e.target.closest('.bookmark-btn')) {
        const btn = e.target.closest('.bookmark-btn');
        const courseId = btn.dataset.courseId;
        const icon = btn.querySelector('i');
        
        fetch('<?php echo url("api/bookmark/toggle"); ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'course_id=' + courseId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.bookmarked) {
                    btn.classList.add('bookmarked');
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    btn.title = 'Remove bookmark';
                } else {
                    btn.classList.remove('bookmarked');
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    btn.title = 'Bookmark course';
                }
            } else {
                alert(data.message || 'Please login to bookmark courses');
            }
        })
        .catch(err => console.error('Bookmark error:', err));
    }
});
</script>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
