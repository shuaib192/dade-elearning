<?php
/**
 * Course Card Partial
 * Premium reusable component for displaying course cards
 * Expects $course variable to be set
 */

// Include BookmarkController if not already
if (!class_exists('BookmarkController')) {
    require_once APP_ROOT . '/controllers/BookmarkController.php';
}

// Get course image with consistent fallback
$thumbnail = !empty($course['cover_image']) 
    ? url('uploads/courses/' . $course['cover_image'])
    : url('assets/images/course-placeholder.png');

// Get counts
$enrolledCount = $course['enrolled_count'] ?? 0;
$lessonCount = $course['lesson_count'] ?? 0;
$level = $course['level'] ?? 'beginner';

// Check if bookmarked
$isBookmarked = BookmarkController::isBookmarked($course['id']);
?>

<div class="course-card">
    <div class="course-card-image">
        <img src="<?php echo $thumbnail; ?>" alt="<?php echo e($course['title']); ?>"
             onerror="this.src='<?php echo url('assets/images/course-placeholder.png'); ?>'">
        <?php if (!empty($course['category_name'])): ?>
        <span class="course-card-category"><?php echo e($course['category_name']); ?></span>
        <?php endif; ?>
        <span class="course-card-level level-<?php echo $level; ?>">
            <?php echo ucfirst($level); ?>
        </span>
        <!-- Bookmark Button -->
        <button class="bookmark-btn <?php echo $isBookmarked ? 'bookmarked' : ''; ?>" 
                data-course-id="<?php echo $course['id']; ?>"
                title="<?php echo $isBookmarked ? 'Remove bookmark' : 'Bookmark course'; ?>">
            <i class="<?php echo $isBookmarked ? 'fas' : 'far'; ?> fa-heart"></i>
        </button>
    </div>
    <div class="course-card-body">
        <h3 class="course-card-title">
            <a href="<?php echo url('course/' . ($course['slug'] ?? $course['id'])); ?>">
                <?php echo e($course['title']); ?>
            </a>
        </h3>
        <p class="course-card-instructor">
            <i class="fas fa-chalkboard-teacher"></i>
            <?php echo e($course['instructor_name'] ?? 'DADE Learn'); ?>
        </p>
        <div class="course-card-meta">
            <span><i class="fas fa-book"></i> <?php echo $lessonCount; ?> Lessons</span>
            <span><i class="fas fa-users"></i> <?php echo $enrolledCount; ?> Students</span>
        </div>
        <div class="course-card-footer">
            <a href="<?php echo url('course/' . ($course['slug'] ?? $course['id'])); ?>" class="btn btn-primary btn-sm">
                View Course
            </a>
        </div>
    </div>
</div>

<style>
.course-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    border: 1px solid var(--gray-100);
    transition: all 0.3s ease;
}

.course-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 40px rgba(0,0,0,0.12);
}

.course-card-image {
    position: relative;
    height: 180px;
    overflow: hidden;
}

.course-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.course-card:hover .course-card-image img {
    transform: scale(1.05);
}

.course-card-category {
    position: absolute;
    top: var(--space-3);
    left: var(--space-3);
    background: rgba(0,0,0,0.75);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    backdrop-filter: blur(4px);
}

.course-card-level {
    position: absolute;
    top: var(--space-3);
    right: var(--space-3);
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
}

.course-card-level.level-beginner { background: #d1fae5; color: #059669; }
.course-card-level.level-intermediate { background: #fef3c7; color: #d97706; }
.course-card-level.level-advanced { background: #fee2e2; color: #dc2626; }

.course-card-body {
    padding: var(--space-5);
}

.course-card-title {
    font-size: var(--text-lg);
    margin-bottom: var(--space-2);
    line-height: 1.4;
}

.course-card-title a {
    color: var(--text-primary);
    transition: color 0.2s ease;
}

.course-card-title a:hover {
    color: var(--primary);
}

.course-card-instructor {
    color: var(--text-muted);
    font-size: var(--text-sm);
    margin-bottom: var(--space-4);
}

.course-card-instructor i {
    margin-right: 6px;
    color: var(--primary);
}

.course-card-meta {
    display: flex;
    gap: var(--space-4);
    font-size: var(--text-sm);
    color: var(--text-secondary);
    padding: var(--space-4) 0;
    border-top: 1px solid var(--gray-100);
    margin-bottom: var(--space-4);
}

.course-card-meta i {
    margin-right: 4px;
    color: var(--primary);
}

.course-card-footer {
    display: flex;
    justify-content: flex-end;
}

.course-card-footer .btn {
    flex: 1;
    justify-content: center;
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
</style>

<script>
// Bookmark toggle functionality
document.addEventListener('click', function(e) {
    if (e.target.closest('.bookmark-btn')) {
        const btn = e.target.closest('.bookmark-btn');
        const courseId = btn.dataset.courseId;
        const icon = btn.querySelector('i');
        
        fetch('<?php echo url("api/bookmark/toggle"); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
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
