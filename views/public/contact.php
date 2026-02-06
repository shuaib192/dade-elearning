<?php
/**
 * DADE Learn - Contact Page
 */

$pageTitle = 'Contact Us';
require_once APP_ROOT . '/views/layouts/header.php';
?>

<section class="page-header">
    <div class="container">
        <div class="page-header-content">
            <nav class="breadcrumb">
                <a href="<?php echo url(); ?>">Home</a>
                <i class="fas fa-chevron-right"></i>
                <span>Contact</span>
            </nav>
            <h1 class="page-title">Contact Us</h1>
            <p class="page-subtitle">We'd love to hear from you</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="contact-layout">
            <div class="contact-info">
                <h2>Get in Touch</h2>
                <p>Have questions about our courses, accessibility features, or partnership opportunities? Reach out to us!</p>
                
                <div class="contact-cards">
                    <div class="contact-card">
                        <div class="info-card" data-animate="fadeInUp">
                            <div class="info-icon"><i class="fas fa-envelope"></i></div>
                            <div class="info-content">
                                <h3>Email Us</h3>
                                <p>Admin@dadeinitiative.org</p>
                                <p>Support@dadeinitiative.org</p>
                            </div>
                        </div>
                    </div>
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <h4>Phone</h4>
                            <p>+234 800 DADE LEARN</p>
                        </div>
                    </div>
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h4>Location</h4>
                            <p>Lagos, Nigeria</p>
                        </div>
                    </div>
                </div>
                
                <div class="social-links">
                    <h4>Follow Us</h4>
                    <div class="social-icons">
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="contact-form-section">
                <h2>Send us a Message</h2>
                
                <?php if ($success = flash('success')): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo e($success); ?>
                </div>
                <?php endif; ?>
                
                <?php if ($error = flash('error')): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo e($error); ?>
                </div>
                <?php endif; ?>
                
                <form action="<?php echo url('contact'); ?>" method="POST" class="contact-form">
                    <?php echo csrf_field(); ?>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" id="name" name="name" class="form-input" placeholder="Your name" required>
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" id="email" name="email" class="form-input" placeholder="you@example.com" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" id="subject" name="subject" class="form-input" placeholder="What is this about?" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="message" class="form-label">Message</label>
                        <textarea id="message" name="message" class="form-input form-textarea" rows="5" placeholder="Your message..." required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-paper-plane"></i>
                        Send Message
                    </button>
                </form>
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
.contact-layout {
    display: grid;
    gap: var(--space-12);
}
@media (min-width: 1024px) {
    .contact-layout {
        grid-template-columns: 1fr 1fr;
    }
}
.contact-info h2 {
    margin-bottom: var(--space-4);
}
.contact-info > p {
    color: var(--text-secondary);
    margin-bottom: var(--space-8);
}
.contact-cards {
    display: flex;
    flex-direction: column;
    gap: var(--space-4);
    margin-bottom: var(--space-8);
}
.contact-card {
    display: flex;
    align-items: center;
    gap: var(--space-4);
    padding: var(--space-4);
    background: var(--white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
}
.contact-icon {
    width: 50px;
    height: 50px;
    background: var(--primary-50);
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.contact-icon i {
    font-size: var(--text-xl);
    color: var(--primary);
}
.contact-card h4 {
    margin-bottom: var(--space-1);
    font-size: var(--text-sm);
    color: var(--text-muted);
}
.contact-card p {
    margin: 0;
    font-weight: 500;
}
.social-links h4 {
    margin-bottom: var(--space-3);
}
.social-icons {
    display: flex;
    gap: var(--space-3);
}
.social-icon {
    width: 44px;
    height: 44px;
    background: var(--primary);
    color: var(--white);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all var(--transition-fast);
}
.social-icon:hover {
    background: var(--primary-light);
    transform: translateY(-2px);
}
.contact-form-section {
    background: var(--white);
    padding: var(--space-8);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-md);
}
.contact-form-section h2 {
    margin-bottom: var(--space-6);
}
.form-row {
    display: grid;
    gap: var(--space-4);
}
@media (min-width: 640px) {
    .form-row {
        grid-template-columns: 1fr 1fr;
    }
}
.form-textarea {
    resize: vertical;
    min-height: 120px;
}
</style>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
