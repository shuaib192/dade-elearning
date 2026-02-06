<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Include database config for BASE_URL constant
require_once __DIR__ . '/../config/database.php';
// Include functions file using a server-relative path for reliability
require_once __DIR__ . '/functions.php';

// Helper for base URL
$base = defined('BASE_URL') ? BASE_URL : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? e($page_title) . ' - DADE Elearning' : 'DADE Elearning'; ?></title>
    <meta name="description" content="Empowering Youth & Persons with Disabilities through Innovation and Inclusion.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Teko:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/open-dyslexic" rel="stylesheet">
    <link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css"/>

    
    <!-- Stylesheet -->
    <link rel="stylesheet" href="<?php echo defined('BASE_URL') ? BASE_URL : ''; ?>/assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body>

    <!-- ACCESSIBILITY WIDGET -->
    <div class="accessibility-widget">
        <button id="accessibility-toggle" class="widget-toggle-button" aria-label="Open Accessibility Menu" aria-expanded="false">
            <i class="fas fa-universal-access" style="color:white; font-size: 40px;"></i>
        </button>
        <div id="accessibility-menu" class="widget-menu" aria-hidden="true">
            <div class="widget-header">
                <h3>Accessibility</h3>
                <button id="accessibility-reset" class="widget-reset-button">Reset All</button>
            </div>
            <div class="widget-content">
                <div class="widget-group">
                    <h4>Content Scaling</h4>
                    <div class="widget-buttons">
                        <button id="acc-font-decrease" aria-label="Decrease text size">A-</button>
                        <span id="acc-font-indicator">100%</span>
                        <button id="acc-font-increase" aria-label="Increase text size">A+</button>
                    </div>
                </div>
                <div class="widget-group"><h4>Display</h4><button class="widget-feature-button" id="acc-high-contrast">High Contrast</button><button class="widget-feature-button" id="acc-negative-contrast">Negative Contrast</button><button class="widget-feature-button" id="acc-grayscale">Grayscale</button></div>
                <div class="widget-group"><h4>Reading</h4><button class="widget-feature-button" id="acc-dyslexia-font">Dyslexia Font</button><button class="widget-feature-button" id="acc-highlight-links">Highlight Links</button><button class="widget-feature-button" id="acc-highlight-headings">Highlight Headings</button><button class="widget-feature-button" id="acc-reading-mask">Reading Mask</button><button class="widget-feature-button" id="acc-text-to-speech">Text to Speech</button></div>
                <div class="widget-group"><h4>Other</h4><button class="widget-feature-button" id="acc-stop-animations">Stop Animations</button><button class="widget-feature-button" id="acc-enlarge-cursor">Enlarge Cursor</button></div>
            </div>
        </div>
    </div>
    <div id="reading-mask-top" class="reading-mask"></div>
    <div id="reading-mask-bottom" class="reading-mask"></div>
    <div id="tts-popup" class="tts-popup" role="toolbar" aria-hidden="true"><button id="tts-play" aria-label="Read selected text">▶️ Read Text</button></div>
    <!-- END WIDGET -->
    
    <a href="#main-content" class="skip-link">Skip to Main Content</a>
    <header class="site-header">
        <div class="header-container">
            <a href="<?php echo defined('BASE_URL') ? BASE_URL : ''; ?>/index.php" class="logo">
                <img src="<?php echo defined('BASE_URL') ? BASE_URL : ''; ?>/assets/images/logo.png" alt="DADE Foundation Logo">
            </a>
            <nav class="main-nav" aria-label="Main Navigation">
                <button class="nav-toggle" aria-label="Toggle navigation menu" aria-expanded="false"><span class="hamburger"></span></button>
                <ul class="nav-menu">
                    <li><a href="<?php echo defined('BASE_URL') ? BASE_URL : ''; ?>/index.php">Home</a></li>
                    <li><a href="<?php echo defined('BASE_URL') ? BASE_URL : ''; ?>/courses.php">Courses</a></li>
                    <li><a href="<?php echo defined('BASE_URL') ? BASE_URL : ''; ?>/about.php">About</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="<?php echo defined('BASE_URL') ? BASE_URL : ''; ?>/dashboard.php">Dashboard</a></li>
                        <li><a href="<?php echo defined('BASE_URL') ? BASE_URL : ''; ?>/account.php">My Profile</a></li>
                        <li><a href="<?php echo defined('BASE_URL') ? BASE_URL : ''; ?>/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo defined('BASE_URL') ? BASE_URL : ''; ?>/logins.php">Login</a></li>
                        <li><a href="<?php echo defined('BASE_URL') ? BASE_URL : ''; ?>/register.php" class="button button-primary">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main id="main-content">