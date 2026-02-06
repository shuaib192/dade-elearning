<?php
/**
 * DADE Learn - Homepage
 * The main landing page with hero, featured courses, categories, and CTA
 */

$pageTitle = 'Home';
require_once APP_ROOT . '/views/layouts/header.php';

// Get database connection
$db = getDB();

// Fetch statistics (with fallback values if tables don't exist)
$stats = [
    'courses' => 50,
    'students' => 1000,
    'instructors' => 25,
    'certificates' => 500
];

try {
    // Query existing tables individually to avoid issues with missing tables
    $result = $db->query("SELECT COUNT(*) as cnt FROM courses WHERE status = 'published'");
    if ($result) $stats['courses'] = $result->fetch_assoc()['cnt'] ?: 50;
    
    $result = $db->query("SELECT COUNT(*) as cnt FROM users WHERE role = 'volunteer'");
    if ($result) $stats['students'] = $result->fetch_assoc()['cnt'] ?: 1000;
    
    $result = $db->query("SELECT COUNT(*) as cnt FROM users WHERE role = 'mentor'");
    if ($result) $stats['instructors'] = $result->fetch_assoc()['cnt'] ?: 25;
    
    // Certificate query with table existence check
    $tableCheck = $db->query("SHOW TABLES LIKE 'certificates'");
    if ($tableCheck && $tableCheck->num_rows > 0) {
        $result = $db->query("SELECT COUNT(*) as cnt FROM certificates");
        if ($result) $stats['certificates'] = $result->fetch_assoc()['cnt'] ?: 500;
    }
} catch (Exception $e) {
    // Use default values if queries fail
    error_log("Home stats query error: " . $e->getMessage());
}

// Fetch featured courses
$featuredCourses = [];
try {
    $result = $db->query("
        SELECT c.*, u.username as instructor_name, u.profile_picture as instructor_avatar,
               cat.name as category_name
        FROM courses c
        LEFT JOIN users u ON c.instructor_id = u.id
        LEFT JOIN categories cat ON c.category_id = cat.id
        WHERE c.status = 'published'
        ORDER BY c.id DESC
        LIMIT 8
    ");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $featuredCourses[] = $row;
        }
    }
} catch (Exception $e) {
    error_log("Featured courses query error: " . $e->getMessage());
}

// Fetch categories with course counts
$categories = [];
try {
    $result = $db->query("
        SELECT cat.*, COUNT(c.id) as course_count
        FROM categories cat
        LEFT JOIN courses c ON cat.id = c.category_id AND c.status = 'published'
        GROUP BY cat.id
        ORDER BY course_count DESC
        LIMIT 8
    ");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
    }
} catch (Exception $e) {
    error_log("Categories query error: " . $e->getMessage());
}

// Category icons mapping
$categoryIcons = [
    'business' => 'fa-briefcase',
    'technology' => 'fa-laptop-code',
    'design' => 'fa-palette',
    'marketing' => 'fa-bullhorn',
    'personal development' => 'fa-user-graduate',
    'health' => 'fa-heartbeat',
    'finance' => 'fa-chart-line',
    'photography' => 'fa-camera',
    'music' => 'fa-music',
    'default' => 'fa-book'
];

function getCategoryIcon($name, $icons) {
    $key = strtolower($name);
    return $icons[$key] ?? $icons['default'];
}
?>

<!-- Hero Section -->
<section class="hero" style="background-image: linear-gradient(rgba(0, 95, 115, 0.8), rgba(10, 147, 150, 0.8)), url('<?php echo asset('images/hero-bg.png'); ?>'); background-size: cover; background-position: center; min-height: 80vh; display: flex; align-items: center; text-align: center;">
    <div class="container">
        <div class="hero-content" style="max-width: 800px; margin: 0 auto;" data-animate="fadeInUp">
            <span class="hero-badge">
                <i class="fas fa-rocket"></i>
                Start Learning Today
            </span>
            
            <h1 class="hero-title">
                Unlock Your Potential with 
                <span class="highlight">World-Class</span> Education
            </h1>
            
            <p class="hero-subtitle">
                Join thousands of learners and gain the skills you need to succeed. 
                Expert-led courses in technology, business, design, and more.
            </p>
            
            <!-- Hero Search -->
            <form class="hero-search" action="<?php echo url('search'); ?>" method="GET" style="margin-left: auto; margin-right: auto;">
                <input type="text" name="q" class="search-input" placeholder="What do you want to learn today?">
                <button type="submit" class="btn btn-accent">
                    <i class="fas fa-search"></i>
                    Search
                </button>
            </form>
            
            <div class="hero-actions" style="justify-content: center;">
                <a href="<?php echo url('courses'); ?>" class="btn btn-white btn-lg">
                    <i class="fas fa-play-circle"></i>
                    Browse Courses
                </a>
                <a href="<?php echo url('register'); ?>" class="btn btn-outline btn-lg" style="border-color: var(--white); color: var(--white);">
                    Get Started Free
                </a>
            </div>
            
            <div class="hero-stats" style="justify-content: center;">
                <div class="hero-stat">
                    <div class="hero-stat-value" data-counter="<?php echo $stats['courses'] ?? 50; ?>">0</div>
                    <div class="hero-stat-label">Courses</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-value" data-counter="<?php echo $stats['students'] ?? 1000; ?>">0</div>
                    <div class="hero-stat-label">Students</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-value" data-counter="<?php echo $stats['instructors'] ?? 25; ?>">0</div>
                    <div class="hero-stat-label">Instructors</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Decorative elements -->
    <div class="hero-decoration">
        <svg class="hero-wave" viewBox="0 0 1440 120" preserveAspectRatio="none">
            <path fill="var(--bg-primary)" d="M0,64L60,58.7C120,53,240,43,360,48C480,53,600,75,720,80C840,85,960,75,1080,64C1200,53,1320,43,1380,37.3L1440,32L1440,120L1380,120C1320,120,1200,120,1080,120C960,120,840,120,720,120C600,120,480,120,360,120C240,120,120,120,60,120L0,120Z"></path>
        </svg>
    </div>
</section>

<style>
.hero-decoration {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    line-height: 0;
}
.hero-wave {
    width: 100%;
    height: 80px;
@media (max-width: 768px) {
    .hero {
        padding: var(--space-12) 0;
        min-height: auto !important;
    }
    .hero-title {
        font-size: var(--text-3xl) !important;
    }
    .hero-subtitle {
        font-size: var(--text-base) !important;
    }
    .hero-actions {
        flex-direction: column;
        gap: var(--space-4);
    }
    .hero-actions .btn {
        width: 100%;
    }
    .hero-stats {
        flex-direction: column;
        gap: var(--space-6);
    }
}
</style>

<!-- Stats Section -->
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card" data-animate="fadeInUp" data-delay="0">
                <div class="stat-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="stat-value" data-counter="<?php echo $stats['courses'] ?? 50; ?>">0</div>
                <div class="stat-label">Expert-Led Courses</div>
            </div>
            <div class="stat-card" data-animate="fadeInUp" data-delay="100">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-value" data-counter="<?php echo $stats['students'] ?? 1000; ?>">0</div>
                <div class="stat-label">Active Learners</div>
            </div>
            <div class="stat-card" data-animate="fadeInUp" data-delay="200">
                <div class="stat-icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="stat-value" data-counter="<?php echo $stats['instructors'] ?? 25; ?>">0</div>
                <div class="stat-label">Expert Instructors</div>
            </div>
            <div class="stat-card" data-animate="fadeInUp" data-delay="300">
                <div class="stat-icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <div class="stat-value" data-counter="<?php echo $stats['certificates'] ?? 500; ?>">0</div>
                <div class="stat-label">Certificates Issued</div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Courses Section -->
<section class="section" id="courses">
    <div class="container">
        <div class="section-header">
            <span class="section-subtitle">
                <i class="fas fa-fire"></i>
                Popular Courses
            </span>
            <h2 class="section-title">Explore Our Featured Courses</h2>
            <p class="section-description">
                Hand-picked courses to help you get started on your learning journey. 
                Learn from industry experts and grow your skills.
            </p>
        </div>
        
        <div class="course-grid">
            <?php if (empty($featuredCourses)): ?>
                <!-- Placeholder courses for demo -->
                <?php for ($i = 0; $i < 4; $i++): ?>
                <article class="card course-card" data-animate="fadeInUp" data-delay="<?php echo $i * 100; ?>">
                    <div class="card-image">
                        <img src="<?php echo url('assets/images/course-placeholder.png'); ?>" alt="Course thumbnail">
                        <span class="card-badge">Featured</span>
                    </div>
                    <div class="card-body">
                        <div class="card-meta">
                            <span><i class="fas fa-folder"></i> Category</span>
                            <span><i class="fas fa-clock"></i> 4h 30m</span>
                        </div>
                        <h3 class="card-title">
                            <a href="#">Complete Web Development Bootcamp</a>
                        </h3>
                        <p class="card-excerpt">
                            Learn web development from scratch with this comprehensive course covering HTML, CSS, JavaScript, and more.
                        </p>
                    </div>
                    <div class="card-footer">
                        <div class="card-rating">
                            <span class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </span>
                            <span class="rating-num">4.8</span>
                            <span class="rating-count">(125)</span>
                        </div>
                        <span class="card-students">
                            <i class="fas fa-user-graduate"></i> 2.5K
                        </span>
                    </div>
                </article>
                <?php endfor; ?>
            <?php else: ?>
                <?php foreach ($featuredCourses as $index => $course): ?>
                <article class="card course-card" data-animate="fadeInUp" data-delay="<?php echo $index * 100; ?>">
                    <div class="card-image">
                        <?php 
                        $thumbnail = !empty($course['cover_image']) 
                            ? url('uploads/courses/' . $course['cover_image'])
                            : url('assets/images/course-placeholder.png');
                        ?>
                        <img src="<?php echo $thumbnail; ?>" alt="<?php echo e($course['title']); ?>"
                             onerror="this.src='<?php echo url('assets/images/course-placeholder.png'); ?>'">
                        <?php if ($index < 4): ?>
                        <span class="card-badge">Featured</span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <div class="card-meta">
                            <span><i class="fas fa-folder"></i> <?php echo e($course['category_name'] ?? 'General'); ?></span>
                            <span><i class="fas fa-signal"></i> <?php echo e(ucfirst($course['level'] ?? 'Beginner')); ?></span>
                        </div>
                        <h3 class="card-title">
                            <a href="<?php echo url('course/' . ($course['slug'] ?? $course['id'])); ?>">
                                <?php echo e($course['title']); ?>
                            </a>
                        </h3>
                        <p class="card-excerpt">
                            <?php echo e(truncate($course['description'], 100)); ?>
                        </p>
                    </div>
                    <div class="card-footer">
                        <div class="card-rating">
                            <span class="stars">
                                <?php echo starRating($course['avg_rating'] ?? 4.5); ?>
                            </span>
                            <span class="rating-num"><?php echo number_format($course['avg_rating'] ?? 4.5, 1); ?></span>
                            <span class="rating-count">(<?php echo $course['review_count'] ?? 0; ?>)</span>
                        </div>
                        <span class="card-students">
                            <i class="fas fa-user-graduate"></i> <?php echo formatNumber($course['enrolled_count'] ?? 0); ?>
                        </span>
                    </div>
                </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="text-center" style="margin-top: var(--space-10);">
            <a href="<?php echo url('courses'); ?>" class="btn btn-primary btn-lg">
                View All Courses
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="section" style="background: var(--gray-50);">
    <div class="container">
        <div class="section-header">
            <span class="section-subtitle">
                <i class="fas fa-th-large"></i>
                Browse Categories
            </span>
            <h2 class="section-title">Explore By Category</h2>
            <p class="section-description">
                Find courses in your field of interest. We offer a wide range of categories 
                to help you achieve your goals.
            </p>
        </div>
        
        <div class="grid gap-6" style="grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));">
            <?php if (empty($categories)): ?>
                <!-- Placeholder categories -->
                <?php 
                $defaultCategories = [
                    ['name' => 'Technology', 'icon' => 'fa-laptop-code', 'count' => 45],
                    ['name' => 'Business', 'icon' => 'fa-briefcase', 'count' => 32],
                    ['name' => 'Design', 'icon' => 'fa-palette', 'count' => 28],
                    ['name' => 'Marketing', 'icon' => 'fa-bullhorn', 'count' => 21],
                    ['name' => 'Personal Development', 'icon' => 'fa-user-graduate', 'count' => 19],
                    ['name' => 'Finance', 'icon' => 'fa-chart-line', 'count' => 15],
                    ['name' => 'Health', 'icon' => 'fa-heartbeat', 'count' => 12],
                    ['name' => 'Photography', 'icon' => 'fa-camera', 'count' => 8],
                ];
                foreach ($defaultCategories as $index => $cat): 
                ?>
                <a href="<?php echo url('courses?category=' . strtolower($cat['name'])); ?>" 
                   class="category-card" data-animate="fadeInUp" data-delay="<?php echo $index * 50; ?>">
                    <div class="category-icon">
                        <i class="fas <?php echo $cat['icon']; ?>"></i>
                    </div>
                    <h3 class="category-name"><?php echo $cat['name']; ?></h3>
                    <span class="category-count"><?php echo $cat['count']; ?> Courses</span>
                </a>
                <?php endforeach; ?>
            <?php else: ?>
                <?php foreach ($categories as $index => $category): ?>
                <a href="<?php echo url('courses?category=' . $category['id']); ?>" 
                   class="category-card" data-animate="fadeInUp" data-delay="<?php echo $index * 50; ?>">
                    <div class="category-icon">
                        <i class="fas <?php echo getCategoryIcon($category['name'], $categoryIcons); ?>"></i>
                    </div>
                    <h3 class="category-name"><?php echo e($category['name']); ?></h3>
                    <span class="category-count"><?php echo $category['course_count']; ?> Courses</span>
                </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-subtitle">
                <i class="fas fa-check-circle"></i>
                Why Choose Us
            </span>
            <h2 class="section-title">Why Learn With Dade Initiative?</h2>
            <p class="section-description">
                We're committed to providing the best learning experience with 
                expert instructors, flexible learning, and industry-recognized certificates.
            </p>
        </div>
        
        <div class="grid gap-8" style="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));">
            <!-- Feature 1 -->
            <div class="card" data-animate="fadeInUp" data-delay="0">
                <div class="card-body text-center" style="padding: var(--space-8);">
                    <div class="stat-icon" style="margin-bottom: var(--space-5);">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h4 style="margin-bottom: var(--space-3);">Expert Instructors</h4>
                    <p style="margin: 0;">
                        Learn from industry professionals with years of real-world experience in their fields.
                    </p>
                </div>
            </div>
            
            <!-- Feature 2 -->
            <div class="card" data-animate="fadeInUp" data-delay="100">
                <div class="card-body text-center" style="padding: var(--space-8);">
                    <div class="stat-icon" style="margin-bottom: var(--space-5);">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h4 style="margin-bottom: var(--space-3);">Learn at Your Pace</h4>
                    <p style="margin: 0;">
                        Flexible learning schedules. Access courses anytime, anywhere, on any device.
                    </p>
                </div>
            </div>
            
            <!-- Feature 3 -->
            <div class="card" data-animate="fadeInUp" data-delay="200">
                <div class="card-body text-center" style="padding: var(--space-8);">
                    <div class="stat-icon" style="margin-bottom: var(--space-5);">
                        <i class="fas fa-award"></i>
                    </div>
                    <h4 style="margin-bottom: var(--space-3);">Earn Certificates</h4>
                    <p style="margin: 0;">
                        Get recognized certificates upon completion to showcase your new skills.
                    </p>
                </div>
            </div>
            
            <!-- Feature 4 -->
            <div class="card" data-animate="fadeInUp" data-delay="300">
                <div class="card-body text-center" style="padding: var(--space-8);">
                    <div class="stat-icon" style="margin-bottom: var(--space-5);">
                        <i class="fas fa-infinity"></i>
                    </div>
                    <h4 style="margin-bottom: var(--space-3);">Lifetime Access</h4>
                    <p style="margin: 0;">
                        Once enrolled, get unlimited lifetime access to course materials and updates.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="section" style="background: linear-gradient(135deg, var(--primary-50) 0%, rgba(10, 147, 150, 0.05) 100%);">
    <div class="container">
        <div class="section-header">
            <span class="section-subtitle">
                <i class="fas fa-quote-left"></i>
                Testimonials
            </span>
            <h2 class="section-title">What Our Students Say</h2>
            <p class="section-description">
                Hear from our community of learners who have transformed their careers through our platform.
            </p>
        </div>
        
        <div class="grid gap-8" style="grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));">
            <!-- Testimonial 1 -->
            <div class="testimonial-card" data-animate="fadeInUp" data-delay="0">
                <div class="testimonial-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="testimonial-text">
                    "DADE Learn has been instrumental in my career transition. The courses are well-structured 
                    and the instructors are incredibly knowledgeable. Highly recommended!"
                </p>
                <div class="testimonial-author">
                    <img src="https://ui-avatars.com/api/?name=Sarah+Johnson&background=005f73&color=fff" 
                         alt="Sarah Johnson" class="testimonial-avatar">
                    <div>
                        <div class="testimonial-name">Sarah Johnson</div>
                        <div class="testimonial-role">Software Developer</div>
                    </div>
                </div>
            </div>
            
            <!-- Testimonial 2 -->
            <div class="testimonial-card" data-animate="fadeInUp" data-delay="100">
                <div class="testimonial-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="testimonial-text">
                    "The quality of education here rivals that of top universities. I learned more in 3 months 
                    than I did in years of self-study. The certificates really helped my resume stand out."
                </p>
                <div class="testimonial-author">
                    <img src="https://ui-avatars.com/api/?name=Michael+Chen&background=0a9396&color=fff" 
                         alt="Michael Chen" class="testimonial-avatar">
                    <div>
                        <div class="testimonial-name">Michael Chen</div>
                        <div class="testimonial-role">Product Manager</div>
                    </div>
                </div>
            </div>
            
            <!-- Testimonial 3 -->
            <div class="testimonial-card" data-animate="fadeInUp" data-delay="200">
                <div class="testimonial-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <p class="testimonial-text">
                    "I love how I can learn at my own pace. The mobile app is amazing for learning on the go. 
                    The discussion forums helped me connect with fellow learners worldwide."
                </p>
                <div class="testimonial-author">
                    <img src="https://ui-avatars.com/api/?name=Emily+Davis&background=ffb703&color=1e293b" 
                         alt="Emily Davis" class="testimonial-avatar">
                    <div>
                        <div class="testimonial-name">Emily Davis</div>
                        <div class="testimonial-role">UX Designer</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content" data-animate="fadeInUp">
            <h2 class="cta-title">Ready to Start Your Learning Journey?</h2>
            <p class="cta-text">
                Join thousands of learners who are already transforming their careers. 
                Sign up today and get access to world-class education.
            </p>
            <div class="cta-actions">
                <a href="<?php echo url('register'); ?>" class="btn btn-accent btn-lg">
                    Get Started for Free
                    <i class="fas fa-arrow-right"></i>
                </a>
                <a href="<?php echo url('courses'); ?>" class="btn btn-white btn-lg">
                    Browse Courses
                </a>
            </div>
        </div>
    </div>
</section>

<script>
    // Initialize counters when visible
    document.addEventListener('DOMContentLoaded', function() {
        initCounters();
    });
</script>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
