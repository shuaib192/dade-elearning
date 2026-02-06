    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <!-- Brand Column -->
                <div class="footer-brand">
                    <a href="<?php echo url(); ?>" class="logo">
                        <img src="<?php echo url('assets/images/dade-logo.png'); ?>" alt="Dade Initiative" class="logo-img">
                    </a>
                    <p class="footer-tagline"><?php echo e(SITE_TAGLINE); ?></p>
                    <p class="footer-description">
                        Empowering learners worldwide with quality education and inclusive innovation.
                    </p>
                    <div class="footer-social">
                        <a href="#" class="social-link" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link" title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-link" title="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="social-link" title="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="#" class="social-link" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="footer-links">
                    <h4 class="footer-title">Explore</h4>
                    <ul>
                        <li><a href="<?php echo url('courses'); ?>">All Courses</a></li>
                        <li><a href="<?php echo url('courses?category=business'); ?>">Business</a></li>
                        <li><a href="<?php echo url('courses?category=technology'); ?>">Technology</a></li>
                        <li><a href="<?php echo url('courses?category=design'); ?>">Design</a></li>
                        <li><a href="<?php echo url('courses?category=personal-development'); ?>">Personal Development</a></li>
                        <li><a href="<?php echo url('verify-certificate'); ?>">Verify Certificate</a></li>
                    </ul>
                </div>
                
                <!-- Company Links -->
                <div class="footer-links">
                    <h4 class="footer-title">Company</h4>
                    <ul>
                        <li><a href="<?php echo url('about'); ?>">About Us</a></li>
                        <li><a href="<?php echo url('contact'); ?>">Contact Us</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Become an Instructor</a></li>
                    </ul>
                </div>
                
                <!-- Support Links -->
                <div class="footer-links">
                    <h4 class="footer-title">Support</h4>
                    <ul>
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">FAQs</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Newsletter -->
            <div class="footer-newsletter">
                <div class="newsletter-content">
                    <h4>Stay Updated</h4>
                    <p>Subscribe to our newsletter for the latest courses and learning tips.</p>
                </div>
                <form class="newsletter-form" action="#" method="POST">
                    <input type="email" class="form-input" placeholder="Enter your email" required>
                    <button type="submit" class="btn btn-accent">
                        Subscribe
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>
            </div>
            
            <!-- Copyright -->
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Dade Initiative. All rights reserved.</p>
                <p>A product of <a href="#" target="_blank">Dade Initiative</a></p>
            </div>
        </div>
    </footer>
    
    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop" aria-label="Back to top">
        <i class="fas fa-chevron-up"></i>
    </button>
    
    <!-- Scripts -->
    <script src="<?php echo asset('js/main.js'); ?>"></script>
    <script src="<?php echo asset('js/accessibility.js'); ?>"></script>
    <?php if (Auth::check()): ?>
    <script src="<?php echo asset('js/ai-assistant.js'); ?>"></script>
    <?php endif; ?>
    <?php if (isset($pageScripts)): ?>
    <script><?php echo $pageScripts; ?></script>
    <?php endif; ?>
    
    <script>
        // Auto-dismiss toasts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.toast').forEach(toast => {
                toast.style.animation = 'slideIn 0.3s ease reverse forwards';
                setTimeout(() => toast.remove(), 300);
            });
        }, 5000);
    </script>
</body>
</html>
