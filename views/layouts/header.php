<?php
/**
 * DADE Learn - Header Template
 * Responsive navigation with mobile menu
 */

$currentRoute = Router::currentRoute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo e(SITE_TAGLINE); ?> - Learn new skills and advance your career with Dade Initiative's comprehensive course library.">
    <meta name="keywords" content="e-learning, online courses, education, Dade Initiative, skills, career development">
    <title><?php echo isset($pageTitle) ? e($pageTitle) . ' | ' : ''; ?><?php echo SITE_NAME; ?></title>
    
    <!-- Preconnect to external resources -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles -->
    <link rel="stylesheet" href="<?php echo asset('css/main.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('css/components.css'); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo asset('images/favicon.png'); ?>">
    
    <style>
.logo-img {
    height: 40px;
    width: auto;
    object-fit: contain;
}

@media (max-width: 768px) {
    .logo-img {
        height: 32px;
    }
}

/* Ensure mobile menu is clickable */
.mobile-toggle {
    z-index: 1001;
    position: relative;
}

.user-dropdown-menu {
    display: block;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    z-index: 9999;
    pointer-events: none;
    transition: all var(--transition-normal);
}

.user-dropdown.active .user-dropdown-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
    pointer-events: auto;
}
</style>
    <?php if (isset($pageStyles)): ?>
    <style><?php echo $pageStyles; ?></style>
    <?php endif; ?>
</head>
<body>
    <!-- Header -->
    <header class="header" id="header">
        <div class="container">
            <div class="header-inner">
                <!-- Logo -->
                <a href="<?php echo url(); ?>" class="logo">
                    <img src="<?php echo url('assets/images/dade-logo.png'); ?>" alt="Dade Initiative" class="logo-img">
                </a>
                
                <!-- Desktop Navigation -->
                <nav class="nav">
                    <ul class="nav-list">
                        <li>
                            <a href="<?php echo url(); ?>" class="nav-link <?php echo $currentRoute === '' ? 'active' : ''; ?>">
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo url('courses'); ?>" class="nav-link <?php echo strpos($currentRoute, 'course') === 0 ? 'active' : ''; ?>">
                                Courses
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo url('about'); ?>" class="nav-link <?php echo $currentRoute === 'about' ? 'active' : ''; ?>">
                                About
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo url('contact'); ?>" class="nav-link <?php echo $currentRoute === 'contact' ? 'active' : ''; ?>">
                                Contact
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo url('verify-certificate'); ?>" class="nav-link <?php echo $currentRoute === 'verify-certificate' ? 'active' : ''; ?>">
                                Verify Certificate
                            </a>
                        </li>
                    </ul>
                </nav>
                
                <!-- Header Actions -->
                <div class="header-actions">
                    <!-- Search Button -->
                    <a href="<?php echo url('search'); ?>" class="btn btn-icon btn-secondary" title="Search courses">
                        <i class="fas fa-search"></i>
                    </a>
                    
                    <?php if (Auth::check()): ?>
                        <!-- User Dropdown -->
                        <div class="user-dropdown">
                            <button class="user-dropdown-toggle" id="userDropdown">
                                <img src="<?php echo avatar(Auth::user()); ?>" alt="Profile" class="user-avatar">
                                <span class="user-name"><?php echo e(Session::get('user_name')); ?></span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="user-dropdown-menu" id="userDropdownMenu">
                                <a href="<?php echo url('dashboard'); ?>" class="dropdown-item">
                                    <i class="fas fa-tachometer-alt"></i>
                                    Dashboard
                                </a>
                                <a href="<?php echo url('dashboard/courses'); ?>" class="dropdown-item">
                                    <i class="fas fa-book"></i>
                                    My Courses
                                </a>
                                <a href="<?php echo url('dashboard/bookmarks'); ?>" class="dropdown-item">
                                    <i class="fas fa-heart"></i>
                                    My Bookmarks
                                </a>
                                <a href="<?php echo url('dashboard/certificates'); ?>" class="dropdown-item">
                                    <i class="fas fa-certificate"></i>
                                    My Certificates
                                </a>
                                <a href="<?php echo url('dashboard/profile'); ?>" class="dropdown-item">
                                    <i class="fas fa-user"></i>
                                    Profile
                                </a>
                                <?php if (Auth::isInstructor()): ?>
                                <div class="dropdown-divider"></div>
                                <a href="<?php echo url('instructor'); ?>" class="dropdown-item">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                    Instructor Panel
                                </a>
                                <?php endif; ?>
                                <?php if (Auth::isAdmin()): ?>
                                <a href="<?php echo url('admin'); ?>" class="dropdown-item">
                                    <i class="fas fa-cog"></i>
                                    Admin Panel
                                </a>
                                <?php endif; ?>
                                <div class="dropdown-divider"></div>
                                <a href="<?php echo url('logout'); ?>" class="dropdown-item text-error">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Logout
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Auth Buttons -->
                        <a href="<?php echo url('login'); ?>" class="btn btn-secondary btn-sm hide-mobile">
                            Log In
                        </a>
                        <a href="<?php echo url('register'); ?>" class="btn btn-primary btn-sm">
                            Get Started
                        </a>
                    <?php endif; ?>
                    
                    <!-- Mobile Menu Toggle -->
                    <button class="mobile-toggle" id="mobileToggle" aria-label="Open menu">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Mobile Menu Overlay -->
    <div class="menu-overlay" id="menuOverlay"></div>
    
    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-header">
            <a href="<?php echo url(); ?>" class="logo">
                <img src="<?php echo url('assets/images/dade-logo.png'); ?>" alt="Dade Initiative" class="logo-img">
            </a>
            <button class="mobile-menu-close" id="mobileClose" aria-label="Close menu">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <nav class="mobile-menu-nav">
            <a href="<?php echo url(); ?>" class="mobile-nav-link">
                <i class="fas fa-home"></i> Home
            </a>
            <a href="<?php echo url('courses'); ?>" class="mobile-nav-link">
                <i class="fas fa-graduation-cap"></i> Courses
            </a>
            <a href="<?php echo url('about'); ?>" class="mobile-nav-link">
                <i class="fas fa-info-circle"></i> About
            </a>
            <a href="<?php echo url('contact'); ?>" class="mobile-nav-link">
                <i class="fas fa-envelope"></i> Contact
            </a>
            
            <?php if (Auth::check()): ?>
            <div class="mobile-menu-divider"></div>
            <a href="<?php echo url('dashboard'); ?>" class="mobile-nav-link">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="<?php echo url('dashboard/courses'); ?>" class="mobile-nav-link">
                <i class="fas fa-book"></i> My Courses
            </a>
            <a href="<?php echo url('logout'); ?>" class="mobile-nav-link text-error">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
            <?php else: ?>
            <div class="mobile-menu-divider"></div>
            <a href="<?php echo url('login'); ?>" class="mobile-nav-link">
                <i class="fas fa-sign-in-alt"></i> Log In
            </a>
            <a href="<?php echo url('register'); ?>" class="mobile-nav-link">
                <i class="fas fa-user-plus"></i> Get Started
            </a>
            <?php endif; ?>
        </nav>
    </div>
    
    <!-- Flash Messages -->
    <?php $success = flash('success'); $error = flash('error'); ?>
    <?php if ($success || $error): ?>
    <div class="toast-container" id="toastContainer">
        <?php if ($success): ?>
        <div class="toast alert-success">
            <i class="fas fa-check-circle alert-icon"></i>
            <div class="alert-content"><?php echo e($success); ?></div>
            <button class="toast-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <?php endif; ?>
        <?php if ($error): ?>
        <div class="toast alert-error">
            <i class="fas fa-exclamation-circle alert-icon"></i>
            <div class="alert-content"><?php echo e($error); ?></div>
            <button class="toast-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    
    <!-- Main Content -->
    <main class="main-content" id="main-content">
    <!-- Mobile Menu Script Fallback -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileToggle = document.getElementById('mobileToggle');
        const mobileMenu = document.getElementById('mobileMenu');
        const menuOverlay = document.getElementById('menuOverlay');
        const mobileClose = document.getElementById('mobileClose');
        const userDropdown = document.getElementById('userDropdown');
        const userDropdownParent = document.querySelector('.user-dropdown');

        if (mobileToggle && mobileMenu && menuOverlay) {
            mobileToggle.addEventListener('click', function(e) {
                e.preventDefault();
                mobileMenu.classList.add('active');
                menuOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            });

            const closeMenu = function() {
                mobileMenu.classList.remove('active');
                menuOverlay.classList.remove('active');
                document.body.style.overflow = '';
            };

            if (mobileClose) mobileClose.addEventListener('click', closeMenu);
            menuOverlay.addEventListener('click', closeMenu);
        }

        // Use event delegation for dropdowns to be more robust
        document.addEventListener('click', function(e) {
            const toggle = e.target.closest('#userDropdown');
            const menu = document.getElementById('userDropdownMenu');
            const parent = document.querySelector('.user-dropdown');
            
            if (toggle) {
                e.preventDefault();
                e.stopPropagation();
                parent.classList.toggle('active');
            } else if (parent && !parent.contains(e.target)) {
                parent.classList.remove('active');
            }
        });
    });
    </script>
    <style>
    .mobile-toggle {
        z-index: 10001 !important;
    }
    .user-dropdown {
        z-index: 10000;
        position: relative;
    }
    </style>
