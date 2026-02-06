<?php
/**
 * DADE Learn - Search & Browse Courses
 * Premium search page with filters and results
 */

$pageTitle = 'Search Courses';
$query = $_GET['q'] ?? '';
$categoryFilter = $_GET['category'] ?? '';
$levelFilter = $_GET['level'] ?? '';
$db = getDB();

// Get categories for filter
$categories = [];
$catResult = $db->query("SELECT * FROM categories ORDER BY name ASC");
while ($row = $catResult->fetch_assoc()) {
    $categories[] = $row;
}

// Build search query
$whereClause = "WHERE c.status = 'published'";
$params = [];
$types = "";

if (!empty($query)) {
    $searchTerm = "%$query%";
    $whereClause .= " AND (c.title LIKE ? OR c.description LIKE ?)";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= "ss";
}

if (!empty($categoryFilter)) {
    $whereClause .= " AND c.category_id = ?";
    $params[] = intval($categoryFilter);
    $types .= "i";
}

if (!empty($levelFilter)) {
    $whereClause .= " AND c.level = ?";
    $params[] = $levelFilter;
    $types .= "s";
}

// Get results
$results = [];
$sql = "
    SELECT c.*, cat.name as category_name, u.username as instructor_name,
           (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id) as enrolled_count,
           (SELECT COUNT(*) FROM lessons WHERE course_id = c.id) as lesson_count
    FROM courses c
    LEFT JOIN categories cat ON c.category_id = cat.id
    LEFT JOIN users u ON c.instructor_id = u.id
    $whereClause
    ORDER BY enrolled_count DESC
    LIMIT 24
";

$stmt = $db->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $results[] = $row;
}

require_once APP_ROOT . '/views/layouts/header.php';
?>

<div class="search-hero">
    <div class="container">
        <h1><?php echo empty($query) ? 'Explore Our Courses' : 'Search Results'; ?></h1>
        <p>
            <?php if (empty($query)): ?>
                Discover courses that will help you grow and learn new skills.
            <?php else: ?>
                Found <?php echo count($results); ?> results for "<strong><?php echo e($query); ?></strong>"
            <?php endif; ?>
        </p>
        
        <form action="<?php echo url('search'); ?>" method="GET" class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" name="q" value="<?php echo e($query); ?>" placeholder="Search for courses, topics, or skills...">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Search
            </button>
        </form>
    </div>
</div>

<div class="search-content">
    <div class="container">
        <!-- Filters -->
        <div class="filters-section">
            <div class="filters-row">
                <div class="filter-group">
                    <label>Category</label>
                    <select name="category" onchange="applyFilter('category', this.value)">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo $categoryFilter == $cat['id'] ? 'selected' : ''; ?>>
                            <?php echo e($cat['name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label>Level</label>
                    <select name="level" onchange="applyFilter('level', this.value)">
                        <option value="">All Levels</option>
                        <option value="beginner" <?php echo $levelFilter === 'beginner' ? 'selected' : ''; ?>>Beginner</option>
                        <option value="intermediate" <?php echo $levelFilter === 'intermediate' ? 'selected' : ''; ?>>Intermediate</option>
                        <option value="advanced" <?php echo $levelFilter === 'advanced' ? 'selected' : ''; ?>>Advanced</option>
                    </select>
                </div>
                
                <?php if (!empty($query) || !empty($categoryFilter) || !empty($levelFilter)): ?>
                <a href="<?php echo url('search'); ?>" class="clear-filters">
                    <i class="fas fa-times"></i> Clear Filters
                </a>
                <?php endif; ?>
            </div>
            
            <span class="results-count"><?php echo count($results); ?> courses found</span>
        </div>
        
        <!-- Results -->
        <?php if (empty($results)): ?>
        <div class="no-results">
            <div class="no-results-icon">
                <i class="fas fa-search"></i>
            </div>
            <h2>No courses found</h2>
            <p>Try different keywords or adjust your filters.</p>
            <a href="<?php echo url('search'); ?>" class="btn btn-primary">
                <i class="fas fa-redo"></i> Reset Search
            </a>
        </div>
        <?php else: ?>
        <div class="courses-grid">
            <?php foreach ($results as $course): 
                // Use uploaded image or local placeholder
                $thumbnail = !empty($course['cover_image']) 
                    ? url('uploads/courses/' . $course['cover_image'])
                    : url('assets/images/course-placeholder.png');
            ?>
            <div class="course-card">
                <div class="card-image">
                    <img src="<?php echo $thumbnail; ?>" alt="<?php echo e($course['title']); ?>"
                         onerror="this.src='<?php echo url('assets/images/course-placeholder.png'); ?>'">
                    <span class="card-category"><?php echo e($course['category_name'] ?? 'General'); ?></span>
                    <span class="card-level level-<?php echo $course['level'] ?? 'beginner'; ?>">
                        <?php echo ucfirst($course['level'] ?? 'Beginner'); ?>
                    </span>
                </div>
                <div class="card-body">
                    <h3>
                        <a href="<?php echo url('course/' . ($course['slug'] ?? $course['id'])); ?>">
                            <?php echo e($course['title']); ?>
                        </a>
                    </h3>
                    <p class="card-instructor">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <?php echo e($course['instructor_name'] ?? 'DADE Learn'); ?>
                    </p>
                    <div class="card-stats">
                        <span><i class="fas fa-book"></i> <?php echo $course['lesson_count']; ?> Lessons</span>
                        <span><i class="fas fa-users"></i> <?php echo $course['enrolled_count']; ?> Students</span>
                    </div>
                    <a href="<?php echo url('course/' . ($course['slug'] ?? $course['id'])); ?>" class="btn btn-primary btn-block">
                        View Course
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* Search Hero */
.search-hero {
    background: linear-gradient(135deg, var(--primary) 0%, #7c3aed 100%);
    padding: var(--space-16) 0;
    text-align: center;
    color: white;
}

.search-hero h1 {
    font-size: clamp(2rem, 5vw, 3rem);
    margin-bottom: var(--space-3);
    font-weight: 800;
}

.search-hero p {
    font-size: var(--text-lg);
    opacity: 0.9;
    margin-bottom: var(--space-8);
}

.search-hero p strong {
    color: #fbbf24;
}

/* Search Box */
.search-box {
    max-width: 700px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    background: white;
    border-radius: 60px;
    padding: var(--space-2);
    box-shadow: 0 20px 60px rgba(0,0,0,0.2);
}

.search-box i.fa-search {
    color: var(--text-muted);
    padding: 0 var(--space-4);
    font-size: 20px;
}

.search-box input {
    flex: 1;
    border: none;
    font-size: var(--text-lg);
    padding: var(--space-3) 0;
    background: transparent;
    outline: none;
    color: var(--text-primary);
}

.search-box input::placeholder {
    color: var(--text-muted);
}

.search-box .btn {
    border-radius: 50px;
    padding: var(--space-3) var(--space-6);
    font-weight: 600;
}

/* Content Section */
.search-content {
    padding: var(--space-10) 0;
    background: var(--gray-50);
    min-height: 60vh;
}

/* Filters */
.filters-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--space-8);
    flex-wrap: wrap;
    gap: var(--space-4);
}

.filters-row {
    display: flex;
    gap: var(--space-4);
    align-items: center;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    align-items: center;
    gap: var(--space-2);
}

.filter-group label {
    font-weight: 600;
    color: var(--text-secondary);
    font-size: var(--text-sm);
}

.filter-group select {
    padding: var(--space-2) var(--space-4);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-lg);
    background: white;
    font-size: var(--text-sm);
    cursor: pointer;
}

.filter-group select:focus {
    outline: none;
    border-color: var(--primary);
}

.clear-filters {
    color: var(--primary);
    font-size: var(--text-sm);
    font-weight: 500;
}

.clear-filters:hover {
    text-decoration: underline;
}

.results-count {
    color: var(--text-muted);
    font-size: var(--text-sm);
}

/* No Results */
.no-results {
    background: white;
    border-radius: var(--radius-xl);
    padding: var(--space-16);
    text-align: center;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.no-results-icon {
    width: 100px;
    height: 100px;
    background: var(--gray-100);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--space-6);
}

.no-results-icon i {
    font-size: 48px;
    color: var(--gray-300);
}

.no-results h2 {
    margin-bottom: var(--space-2);
}

.no-results p {
    color: var(--text-muted);
    margin-bottom: var(--space-6);
}

/* Courses Grid */
.courses-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: var(--space-6);
}

/* Course Card */
.course-card {
    background: white;
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    border: 1px solid var(--gray-100);
    transition: all 0.3s ease;
}

.course-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.12);
}

.card-image {
    position: relative;
    height: 180px;
    overflow: hidden;
}

.card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.course-card:hover .card-image img {
    transform: scale(1.05);
}

.card-category {
    position: absolute;
    top: var(--space-3);
    left: var(--space-3);
    background: rgba(0,0,0,0.7);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    backdrop-filter: blur(4px);
}

.card-level {
    position: absolute;
    top: var(--space-3);
    right: var(--space-3);
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
}

.level-beginner { background: #d1fae5; color: #059669; }
.level-intermediate { background: #fef3c7; color: #d97706; }
.level-advanced { background: #fee2e2; color: #dc2626; }

.card-body {
    padding: var(--space-5);
}

.card-body h3 {
    font-size: var(--text-lg);
    margin-bottom: var(--space-2);
    line-height: 1.4;
}

.card-body h3 a {
    color: var(--text-primary);
    transition: color 0.2s ease;
}

.card-body h3 a:hover {
    color: var(--primary);
}

.card-instructor {
    color: var(--text-muted);
    font-size: var(--text-sm);
    margin-bottom: var(--space-4);
}

.card-instructor i {
    margin-right: 6px;
    color: var(--primary);
}

.card-stats {
    display: flex;
    gap: var(--space-4);
    font-size: var(--text-sm);
    color: var(--text-secondary);
    padding: var(--space-4) 0;
    border-top: 1px solid var(--gray-100);
    margin-bottom: var(--space-4);
}

.card-stats i {
    margin-right: 4px;
    color: var(--primary);
}

.btn-block {
    width: 100%;
    justify-content: center;
}

@media (max-width: 768px) {
    .search-hero {
        padding: var(--space-10) var(--space-4);
    }
    
    .search-box {
        flex-direction: column;
        border-radius: var(--radius-xl);
        padding: var(--space-4);
    }
    
    .search-box i.fa-search {
        display: none;
    }
    
    .search-box input {
        width: 100%;
        margin-bottom: var(--space-3);
    }
    
    .search-box .btn {
        width: 100%;
    }
    
    .filters-row {
        width: 100%;
    }
    
    .filter-group {
        flex: 1;
        min-width: 120px;
    }
    
    .filter-group select {
        width: 100%;
    }
}
</style>

<script>
function applyFilter(name, value) {
    const url = new URL(window.location.href);
    if (value) {
        url.searchParams.set(name, value);
    } else {
        url.searchParams.delete(name);
    }
    window.location.href = url.toString();
}
</script>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
