<?php
// --- SETUP ---
$page_title = 'Home - DADE Elearning for Inclusive Innovation';
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

// --- DATA FETCHING ---
$base = defined('BASE_URL') ? BASE_URL : '';

// Categories
$categories = [];
$category_result = $db->query("SELECT * FROM categories ORDER BY name ASC");
if ($category_result) { while($row = $category_result->fetch_assoc()) { $categories[] = $row; } }

// Featured courses
$featured_courses = [];
$sql = "SELECT c.id, c.title, c.description, c.cover_image, u.username AS instructor_name, 
        (SELECT AVG(rating) FROM course_ratings WHERE course_id = c.id) as avg_rating, 
        (SELECT COUNT(id) FROM course_ratings WHERE course_id = c.id) as total_ratings,
        (SELECT COUNT(id) FROM enrollments WHERE course_id = c.id) as enrolled_count
        FROM courses c 
        JOIN users u ON c.instructor_id = u.id 
        WHERE c.status = 'published' 
        ORDER BY c.created_at DESC LIMIT 6";
$result = $db->query($sql);
if ($result && $result->num_rows > 0) { while ($row = $result->fetch_assoc()) { $featured_courses[] = $row; } }

// Stats
$total_courses = $db->query("SELECT COUNT(*) as cnt FROM courses WHERE status='published'")->fetch_assoc()['cnt'];
$total_students = $db->query("SELECT COUNT(*) as cnt FROM users WHERE role='volunteer'")->fetch_assoc()['cnt'];

require_once __DIR__ . '/includes/header.php';
?>

<!-- HERO SECTION - Modern Gradient Style -->
<section class="hero-modern">
    <div class="hero-bg-overlay"></div>
    <div class="container hero-content-modern">
        <div class="hero-badge"><i class="fas fa-graduation-cap"></i> Free Learning Platform</div>
        <h1 class="hero-title-modern">Inclusive Innovation<br>that <span class="highlight">Births Change</span></h1>
        <p class="hero-subtitle-modern">Empowering Youth & Persons with Disabilities through Technology, Advocacy, and Skills Development.</p>
        
        <div class="hero-search-modern">
            <form action="<?php echo $base; ?>/search.php" method="GET">
                <div class="search-input-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="search" name="q" placeholder="What do you want to learn today?" aria-label="Search for courses">
                </div>
                <button type="submit" class="search-btn">Search</button>
            </form>
        </div>
        
        <div class="hero-stats">
            <div class="stat-item">
                <span class="stat-number"><?php echo $total_courses; ?>+</span>
                <span class="stat-label">Courses</span>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <span class="stat-number"><?php echo $total_students; ?>+</span>
                <span class="stat-label">Learners</span>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <span class="stat-number">100%</span>
                <span class="stat-label">Free Access</span>
            </div>
        </div>

        <!-- TRUST SECTION (NEW) -->
        <div class="trust-labels">
            <div class="trust-item"><i class="fas fa-check-circle"></i> ISO Certified</div>
            <div class="trust-item"><i class="fas fa-university"></i> University Partners</div>
            <div class="trust-item"><i class="fas fa-hands-helping"></i> DADE Community</div>
            <div class="trust-item"><i class="fas fa-shield-alt"></i> Secure Learning</div>
        </div>
    </div>
</section>

<!-- CATEGORIES SECTION -->
<section class="categories-section">
    <div class="container">
        <h2 class="section-heading">Browse by <span class="text-accent">Category</span></h2>
        <div class="category-grid">
            <?php 
            $icons = ['<i class="fas fa-laptop-code"></i>', '<i class="fas fa-balance-scale"></i>', '<i class="fas fa-rocket"></i>', '<i class="fas fa-bullseye"></i>', '<i class="fas fa-universal-access"></i>'];
            foreach ($categories as $index => $category): 
                $icon = $icons[$index] ?? '<i class="fas fa-book"></i>';
            ?>
            <a href="<?php echo $base; ?>/courses.php?category=<?php echo e($category['slug']); ?>" class="category-card">
                <div class="category-icon"><?php echo $icon; ?></div>
                <h3><?php echo e($category['name']); ?></h3>
                <span class="category-arrow">→</span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- FEATURED COURSES SECTION (with Carousel) -->
<section class="courses-section">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-heading">Featured <span class="text-accent">Courses</span></h2>
                <p class="section-desc">Start learning today with our most popular programs</p>
            </div>
            <div class="carousel-controls">
                <button id="prevBtn" class="carousel-control-btn" aria-label="Previous courses"><i class="fas fa-chevron-left"></i></button>
                <button id="nextBtn" class="carousel-control-btn" aria-label="Next courses"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
        
        <?php if (empty($featured_courses)): ?>
            <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-book-open"></i></div>
                <h3>Courses Coming Soon</h3>
                <p>We're preparing amazing content for you. Check back soon!</p>
            </div>
        <?php else: ?>
            <div class="carousel-container">
                <div class="carousel-track" id="carouselTrack">
                    <?php foreach ($featured_courses as $course): ?>
                    <div class="course-card-modern">
                        <a href="<?php echo $base; ?>/view_course.php?id=<?php echo $course['id']; ?>" class="course-image-link">
                            <img src="<?php echo $base; ?>/assets/images/placeholder_course.jpg" alt="<?php echo e($course['title']); ?>" class="course-image" loading="lazy">
                            <div class="course-badge">Free</div>
                        </a>
                        <div class="course-body">
                            <div class="course-meta">
                                <span class="instructor"><i class="fas fa-user-circle"></i> <?php echo e($course['instructor_name']); ?></span>
                            </div>
                            <h3 class="course-title">
                                <a href="<?php echo $base; ?>/view_course.php?id=<?php echo $course['id']; ?>"><?php echo e($course['title']); ?></a>
                            </h3>
                            <p class="course-excerpt"><?php echo e(substr($course['description'] ?? '', 0, 80)); ?>...</p>
                            <div class="course-footer">
                                <div class="course-rating">
                                    <?php if ($course['total_ratings'] > 0): ?>
                                        <span class="stars">★</span>
                                        <span class="rating-num"><?php echo round($course['avg_rating'], 1); ?></span>
                                        <span class="rating-count">(<?php echo $course['total_ratings']; ?>)</span>
                                    <?php else: ?>
                                        <span class="new-badge">New</span>
                                    <?php endif; ?>
                                </div>
                                <span class="enrolled"><i class="fas fa-users"></i> <?php echo $course['enrolled_count'] ?? 0; ?> learners</span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <a href="<?php echo $base; ?>/courses.php" class="view-all-btn text-center mt-20" style="display:block; margin: 20px auto 0; width: max-content;">View All Courses →</a>
        <?php endif; ?>
    </div>
</section>

<!-- TESTIMONIALS SECTION (NEW) -->
<section class="testimonials-section">
    <div class="container">
        <h2 class="section-heading text-center">Voices of <span class="text-accent">Success</span></h2>
        <div class="testimonial-grid">
            <div class="testimonial-card">
                <p class="testimonial-quote">"DADE has completely transformed how I learn. The courses are accessible and the community is so supportive."</p>
                <div class="testimonial-author">
                    <img src="<?php echo $base; ?>/assets/images/default_avatar.png" alt="Sarah J." class="testimonial-avatar">
                    <div>
                        <strong>Sarah J.</strong><br>
                        <small>UI/UX Student</small>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <p class="testimonial-quote">"As a volunteer instructor, I've seen firsthand the impact of inclusive education. DADE is at the forefront."</p>
                <div class="testimonial-author">
                    <img src="<?php echo $base; ?>/assets/images/default_avatar.png" alt="Dr. Mike R." class="testimonial-avatar">
                    <div>
                        <strong>Dr. Mike R.</strong><br>
                        <small>Guest Lecturer</small>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <p class="testimonial-quote">"The certification helped me land my first job in tech. I'm forever grateful for this free platform."</p>
                <div class="testimonial-author">
                    <img src="<?php echo $base; ?>/assets/images/default_avatar.png" alt="Blessing E." class="testimonial-avatar">
                    <div>
                        <strong>Blessing E.</strong><br>
                        <small>Web Developer</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- WHY US SECTION -->
<section class="why-section">
    <div class="container">
        <h2 class="section-heading text-center">Why Learn with <span class="text-accent">DADE</span>?</h2>
        <div class="features-grid-modern">
            <div class="feature-box">
                <div class="feature-icon-modern"><i class="fas fa-globe-africa"></i></div>
                <h3>Inclusive by Design</h3>
                <p>All our courses are designed with accessibility in mind, ensuring everyone can learn.</p>
            </div>
            <div class="feature-box">
                <div class="feature-icon-modern"><i class="fas fa-hand-holding-usd"></i></div>
                <h3>100% Free Forever</h3>
                <p>No hidden costs. All courses are completely free for everyone.</p>
            </div>
            <div class="feature-box">
                <div class="feature-icon-modern"><i class="fas fa-certificate"></i></div>
                <h3>Earn Certificates</h3>
                <p>Complete courses and receive certificates to showcase your achievements.</p>
            </div>
            <div class="feature-box">
                <div class="feature-icon-modern"><i class="fas fa-chalkboard-teacher"></i></div>
                <h3>Expert Instructors</h3>
                <p>Learn from professionals who understand diverse learning needs.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA SECTION -->
<section class="cta-section">
    <div class="container">
        <div class="cta-box">
            <h2>Ready to Start Learning?</h2>
            <p>Join thousands of learners and begin your journey today.</p>
            <div class="cta-buttons">
                <a href="<?php echo $base; ?>/register.php" class="btn-primary-lg">Get Started Free</a>
                <a href="<?php echo $base; ?>/courses.php" class="btn-outline-lg">Browse Courses</a>
            </div>
        </div>
    </div>
</section>

</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const track = document.getElementById('carouselTrack');
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    
    if (!track || !nextBtn || !prevBtn) return;

    let index = 0;
    const cards = track.querySelectorAll('.course-card-modern');
    const cardWidth = cards[0].offsetWidth + 24; // Width + gap
    
    function updateCarousel() {
        const maxIndex = cards.length - 3; // Show 3 cards
        if (index > maxIndex) index = 0;
        if (index < 0) index = maxIndex;
        track.style.transform = `translateX(-${index * cardWidth}px)`;
    }

    nextBtn.addEventListener('click', () => {
        index++;
        updateCarousel();
    });

    prevBtn.addEventListener('click', () => {
        index--;
        updateCarousel();
    });
    
    // Auto-slide every 5 seconds
    setInterval(() => {
        index++;
        updateCarousel();
    }, 5000);
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>