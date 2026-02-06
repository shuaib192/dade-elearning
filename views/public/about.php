<?php
/**
 * DADE Learn - About Page
 */

$pageTitle = 'About Us';
require_once APP_ROOT . '/views/layouts/header.php';
?>

<section class="page-header">
    <div class="container">
        <div class="page-header-content">
            <nav class="breadcrumb">
                <a href="<?php echo url(); ?>">Home</a>
                <i class="fas fa-chevron-right"></i>
                <span>About</span>
            </nav>
            <h1 class="page-title">About DADE Learn</h1>
            <p class="page-subtitle">Empowering communities through inclusive education</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="about-content">
            <div class="about-intro">
                <h2>Our Mission</h2>
                <p>Dade Initiative is our flagship e-learning platform, dedicated to providing accessible, high-quality education to individuals with disabilities and marginalized communities.</p>
                <p>A product of <a href="#" target="_blank">Dade Initiative</a></p>
                <p>We believe that education is a fundamental right, and we're committed to breaking down barriers that prevent people from accessing learning opportunities.</p>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <i class="fas fa-users"></i>
                    <div class="stat-value">5,000+</div>
                    <div class="stat-label">Active Learners</div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-book"></i>
                    <div class="stat-value">100+</div>
                    <div class="stat-label">Free Courses</div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <div class="stat-value">50+</div>
                    <div class="stat-label">Expert Instructors</div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-certificate"></i>
                    <div class="stat-value">2,500+</div>
                    <div class="stat-label">Certificates Issued</div>
                </div>
            </div>
            
            <div class="about-section">
                <h2>What Makes Us Different</h2>
                <div class="feature-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-universal-access"></i>
                        </div>
                        <h3>Accessibility First</h3>
                        <p>All our courses are designed with accessibility in mind, ensuring everyone can learn regardless of ability.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-hand-holding-heart"></i>
                        </div>
                        <h1>About Dade Initiative</h1>
                        <p>We believe quality education should be free. All our courses are available at no cost.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <h3>Verified Certificates</h3>
                        <p>Earn recognized certificates upon course completion to boost your career prospects.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3>Community Support</h3>
                        <p>Join a supportive community of learners and mentors who help each other succeed.</p>
                    </div>
                </div>
            </div>
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
.about-content {
    max-width: 900px;
    margin: 0 auto;
}
.about-intro {
    text-align: center;
    margin-bottom: var(--space-12);
}
.about-intro h2 {
    margin-bottom: var(--space-4);
}
.about-intro p {
    color: var(--text-secondary);
    font-size: var(--text-lg);
    margin-bottom: var(--space-4);
}
.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--space-6);
    margin-bottom: var(--space-12);
}
@media (min-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}
.stat-card {
    background: var(--white);
    padding: var(--space-6);
    border-radius: var(--radius-xl);
    text-align: center;
    box-shadow: var(--shadow-md);
}
.stat-card i {
    font-size: var(--text-3xl);
    color: var(--primary);
    margin-bottom: var(--space-3);
}
.stat-value {
    font-size: var(--text-3xl);
    font-weight: 700;
    color: var(--text-primary);
}
.stat-label {
    color: var(--text-muted);
    font-size: var(--text-sm);
}
.about-section {
    margin-bottom: var(--space-12);
}
.about-section h2 {
    text-align: center;
    margin-bottom: var(--space-8);
}
.feature-grid {
    display: grid;
    gap: var(--space-6);
}
@media (min-width: 768px) {
    .feature-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
.feature-card {
    background: var(--white);
    padding: var(--space-6);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-sm);
}
.feature-icon {
    width: 60px;
    height: 60px;
    background: var(--primary-50);
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: var(--space-4);
}
.feature-icon i {
    font-size: var(--text-2xl);
    color: var(--primary);
}
.feature-card h3 {
    margin-bottom: var(--space-2);
}
.feature-card p {
    color: var(--text-secondary);
    margin: 0;
}
@media (max-width: 768px) {
    .page-header {
        padding: var(--space-8) 0;
    }
    .about-intro p {
        font-size: var(--text-base);
    }
    .stat-value {
        font-size: var(--text-2xl);
    }
    .section {
        padding: var(--space-10) 0;
    }
}
</style>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
