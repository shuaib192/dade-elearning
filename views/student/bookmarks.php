<?php
/**
 * DADE Learn - Student Bookmarks
 * Display all bookmarked courses
 */

$pageTitle = 'My Bookmarks';
$db = getDB();
$user = Auth::user();

// Include BookmarkController
require_once APP_ROOT . '/controllers/BookmarkController.php';

// Get bookmarked courses
$bookmarkedCourses = BookmarkController::getUserBookmarks();

require_once APP_ROOT . '/views/layouts/header.php';
?>

<div class="dashboard-layout">
    <!-- Sidebar -->
    <aside class="dashboard-sidebar">
        <div class="sidebar-user">
            <img src="<?php echo avatar($user, 80); ?>" alt="<?php echo e($user['username']); ?>" class="sidebar-avatar">
            <h4 class="sidebar-username"><?php echo e($user['username']); ?></h4>
            <span class="sidebar-role">
                <i class="fas fa-user-graduate"></i> Student
            </span>
        </div>
        
        <nav class="sidebar-nav">
            <a href="<?php echo url('dashboard'); ?>" class="sidebar-link">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="<?php echo url('dashboard/courses'); ?>" class="sidebar-link">
                <i class="fas fa-book"></i>
                <span>My Courses</span>
            </a>
            <a href="<?php echo url('dashboard/bookmarks'); ?>" class="sidebar-link active">
                <i class="fas fa-heart"></i>
                <span>Bookmarks</span>
            </a>
            <a href="<?php echo url('dashboard/certificates'); ?>" class="sidebar-link">
                <i class="fas fa-certificate"></i>
                <span>Certificates</span>
            </a>
            <a href="<?php echo url('profile'); ?>" class="sidebar-link">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>
        </nav>
    </aside>
    
    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="dashboard-header">
            <div class="welcome-text">
                <h1><i class="fas fa-heart text-danger"></i> My Bookmarks</h1>
                <p><?php echo count($bookmarkedCourses); ?> courses saved</p>
            </div>
            <a href="<?php echo url('courses'); ?>" class="btn btn-outline">
                <i class="fas fa-search"></i> Browse More Courses
            </a>
        </div>
        
        <?php if (empty($bookmarkedCourses)): ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="far fa-heart"></i>
            </div>
            <h2>No bookmarks yet</h2>
            <p>Save courses you're interested in by clicking the heart icon on any course card.</p>
            <a href="<?php echo url('courses'); ?>" class="btn btn-primary btn-lg">
                <i class="fas fa-compass"></i> Explore Courses
            </a>
        </div>
        <?php else: ?>
        <div class="bookmark-grid">
            <?php foreach ($bookmarkedCourses as $course): ?>
            <div class="bookmark-card" data-course-id="<?php echo $course['id']; ?>">
                <div class="bookmark-thumb">
                    <?php 
                    $thumbnail = !empty($course['cover_image']) 
                        ? url('uploads/courses/' . $course['cover_image'])
                        : url('assets/images/course-placeholder.png');
                    ?>
                    <img src="<?php echo $thumbnail; ?>" alt="<?php echo e($course['title']); ?>"
                         onerror="this.src='<?php echo url('assets/images/course-placeholder.png'); ?>'">
                    <span class="bookmark-level level-<?php echo $course['level'] ?? 'beginner'; ?>">
                        <?php echo ucfirst($course['level'] ?? 'Beginner'); ?>
                    </span>
                    <button class="remove-bookmark-btn" data-course-id="<?php echo $course['id']; ?>" title="Remove bookmark">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="bookmark-body">
                    <span class="bookmark-category"><?php echo e($course['category_name'] ?? 'General'); ?></span>
                    <h3><a href="<?php echo url('course/' . ($course['slug'] ?? $course['id'])); ?>"><?php echo e($course['title']); ?></a></h3>
                    <p class="bookmark-instructor">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <?php echo e($course['instructor_name'] ?? 'DADE Learn'); ?>
                    </p>
                    <div class="bookmark-meta">
                        <span><i class="fas fa-book"></i> <?php echo $course['lesson_count'] ?? 0; ?> Lessons</span>
                        <span><i class="fas fa-users"></i> <?php echo $course['enrolled_count'] ?? 0; ?> Students</span>
                    </div>
                    <div class="bookmark-footer">
                        <span class="bookmark-date">
                            <i class="far fa-clock"></i> Saved <?php echo date('M j', strtotime($course['bookmarked_at'])); ?>
                        </span>
                        <a href="<?php echo url('course/' . ($course['slug'] ?? $course['id'])); ?>" class="btn btn-primary btn-sm">
                            View Course
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </main>
</div>

<style>
/* Dashboard Layout */
.dashboard-layout {
    display: flex;
    min-height: calc(100vh - var(--header-height));
    background: var(--gray-50);
}

.dashboard-sidebar {
    width: 280px;
    background: var(--white);
    border-right: 1px solid var(--gray-100);
    padding: var(--space-6);
    position: sticky;
    top: var(--header-height);
    height: calc(100vh - var(--header-height));
    overflow-y: auto;
}

@media (max-width: 1024px) {
    .dashboard-sidebar { display: none; }
}

.sidebar-user {
    text-align: center;
    padding-bottom: var(--space-6);
    border-bottom: 1px solid var(--gray-100);
    margin-bottom: var(--space-6);
}

.sidebar-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    margin-bottom: var(--space-3);
    border: 3px solid var(--primary);
    object-fit: cover;
}

.sidebar-username {
    margin-bottom: var(--space-2);
    font-size: var(--text-lg);
}

.sidebar-role {
    display: inline-flex;
    align-items: center;
    gap: var(--space-2);
    font-size: var(--text-sm);
    padding: 6px 14px;
    border-radius: 20px;
    background: linear-gradient(135deg, var(--primary-50), #ede9fe);
    color: var(--primary);
}

.sidebar-nav {
    display: flex;
    flex-direction: column;
    gap: var(--space-1);
}

.sidebar-link {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    padding: var(--space-3) var(--space-4);
    border-radius: var(--radius-lg);
    color: var(--text-secondary);
    font-weight: 500;
    transition: all var(--transition-fast);
}

.sidebar-link:hover {
    background: var(--gray-50);
    color: var(--text-primary);
}

.sidebar-link.active {
    background: linear-gradient(135deg, var(--primary-50), #ede9fe);
    color: var(--primary);
}

.sidebar-link i { width: 20px; text-align: center; }

.dashboard-main {
    flex: 1;
    padding: var(--space-8);
    max-width: 1200px;
}

@media (max-width: 768px) {
    .dashboard-main { padding: var(--space-4); }
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--space-8);
    flex-wrap: wrap;
    gap: var(--space-4);
}

.welcome-text h1 {
    font-size: var(--text-2xl);
    margin-bottom: var(--space-1);
}

.welcome-text p { color: var(--text-muted); }

/* Empty State */
.empty-state {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: var(--space-16);
    text-align: center;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    border: 1px solid var(--gray-100);
}

.empty-icon {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--space-6);
}

.empty-icon i {
    font-size: 48px;
    color: #ef4444;
}

.empty-state h2 { margin-bottom: var(--space-2); }
.empty-state p { color: var(--text-muted); margin-bottom: var(--space-6); }

/* Bookmark Grid */
.bookmark-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: var(--space-6);
}

.bookmark-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    border: 1px solid var(--gray-100);
    transition: all var(--transition-fast);
}

.bookmark-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.12);
}

.bookmark-thumb {
    position: relative;
    height: 180px;
    overflow: hidden;
}

.bookmark-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.bookmark-card:hover .bookmark-thumb img {
    transform: scale(1.05);
}

.bookmark-level {
    position: absolute;
    top: var(--space-3);
    left: var(--space-3);
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
}

.bookmark-level.level-beginner { background: #d1fae5; color: #059669; }
.bookmark-level.level-intermediate { background: #fef3c7; color: #d97706; }
.bookmark-level.level-advanced { background: #fee2e2; color: #dc2626; }

.remove-bookmark-btn {
    position: absolute;
    top: var(--space-3);
    right: var(--space-3);
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(255,255,255,0.9);
    border: none;
    cursor: pointer;
    color: #9ca3af;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.remove-bookmark-btn:hover {
    background: #fee2e2;
    color: #dc2626;
}

.bookmark-body {
    padding: var(--space-5);
}

.bookmark-category {
    font-size: var(--text-xs);
    color: var(--primary);
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.bookmark-body h3 {
    margin: var(--space-2) 0 var(--space-3);
    font-size: var(--text-lg);
    line-height: 1.4;
}

.bookmark-body h3 a {
    color: var(--text-primary);
    transition: color 0.2s ease;
}

.bookmark-body h3 a:hover {
    color: var(--primary);
}

.bookmark-instructor {
    color: var(--text-muted);
    font-size: var(--text-sm);
    margin-bottom: var(--space-3);
}

.bookmark-instructor i {
    margin-right: 6px;
    color: var(--primary);
}

.bookmark-meta {
    display: flex;
    gap: var(--space-4);
    font-size: var(--text-sm);
    color: var(--text-secondary);
    padding: var(--space-3) 0;
    border-top: 1px solid var(--gray-100);
    margin-bottom: var(--space-3);
}

.bookmark-meta i {
    margin-right: 4px;
    color: var(--primary);
}

.bookmark-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.bookmark-date {
    font-size: var(--text-xs);
    color: var(--text-muted);
}

.bookmark-date i {
    margin-right: 4px;
}
</style>

<script>
// Remove bookmark functionality
document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-bookmark-btn')) {
        const btn = e.target.closest('.remove-bookmark-btn');
        const card = btn.closest('.bookmark-card');
        const courseId = btn.dataset.courseId;
        
        if (confirm('Remove this course from bookmarks?')) {
            fetch('<?php echo url("api/bookmark/toggle"); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'course_id=' + courseId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && !data.bookmarked) {
                    card.style.animation = 'fadeOut 0.3s ease';
                    setTimeout(() => card.remove(), 300);
                    
                    // Update count
                    const countEl = document.querySelector('.welcome-text p');
                    const remaining = document.querySelectorAll('.bookmark-card').length - 1;
                    countEl.textContent = remaining + ' courses saved';
                    
                    if (remaining === 0) {
                        location.reload();
                    }
                }
            });
        }
    }
});

// Fade out animation
const style = document.createElement('style');
style.textContent = '@keyframes fadeOut { to { opacity: 0; transform: translateY(-10px); } }';
document.head.appendChild(style);
</script>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
