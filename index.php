<?php
/**
 * Dade Initiative - Front Controller
 * All requests are routed through this file
 */

// Define application root
define('APP_ROOT', __DIR__);

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', APP_ROOT . '/logs/error.log');

// Load configuration
require_once APP_ROOT . '/config/config.php';
require_once APP_ROOT . '/config/database.php';

// Load core classes
require_once APP_ROOT . '/core/Router.php';
require_once APP_ROOT . '/core/Session.php';
require_once APP_ROOT . '/core/Auth.php';
require_once APP_ROOT . '/core/Mail.php';
require_once APP_ROOT . '/core/Notification.php';
require_once APP_ROOT . '/core/helpers.php';

// Start session
Session::start();

// ============================================================================
// ROUTE DEFINITIONS
// ============================================================================

// --- Public Pages ---
Router::get('', function() {
    require_once APP_ROOT . '/views/public/home.php';
});

Router::get('courses', function() {
    require_once APP_ROOT . '/views/public/courses.php';
});

Router::get('course/{slug}', function($slug) {
    $_GET['slug'] = $slug;
    require_once APP_ROOT . '/views/public/course-detail.php';
});

Router::get('search', function() {
    require_once APP_ROOT . '/views/public/search.php';
});

Router::get('about', function() {
    require_once APP_ROOT . '/views/public/about.php';
});

Router::get('contact', function() {
    require_once APP_ROOT . '/views/public/contact.php';
});

// --- Authentication ---
Router::get('login', function() {
    if (Auth::check()) Router::redirect('dashboard');
    require_once APP_ROOT . '/views/auth/login.php';
});

Router::post('login', function() {
    require_once APP_ROOT . '/controllers/AuthController.php';
    (new AuthController())->login();
});

Router::get('register', function() {
    if (Auth::check()) Router::redirect('dashboard');
    require_once APP_ROOT . '/views/auth/register.php';
});

Router::post('register', function() {
    require_once APP_ROOT . '/controllers/AuthController.php';
    (new AuthController())->register();
});

Router::get('logout', function() {
    Auth::logout();
    Session::flash('success', 'You have been logged out successfully');
    Router::redirect('');
});

Router::get('forgot-password', function() {
    require_once APP_ROOT . '/views/auth/forgot-password.php';
});

Router::post('forgot-password', function() {
    require_once APP_ROOT . '/controllers/AuthController.php';
    (new AuthController())->forgotPassword();
});

Router::get('reset-password', function() {
    require_once APP_ROOT . '/views/auth/reset-password.php';
});

Router::post('reset-password', function() {
    require_once APP_ROOT . '/controllers/AuthController.php';
    (new AuthController())->resetPassword();
});

// --- Payments (Paystack) ---
Router::post('payment/initialize', function() {
    require_once APP_ROOT . '/controllers/PaymentController.php';
    (new PaymentController())->initialize();
});

Router::get('payment/callback', function() {
    require_once APP_ROOT . '/controllers/PaymentController.php';
    (new PaymentController())->callback();
});

// --- Reviews & Comments ---
Router::post('review/store', function() {
    require_once APP_ROOT . '/controllers/ReviewController.php';
    (new ReviewController())->store();
});

Router::get('verify', function() {
    require_once APP_ROOT . '/controllers/AuthController.php';
    (new AuthController())->verify();
});

// Google OAuth
Router::get('auth/google', function() {
    require_once APP_ROOT . '/controllers/AuthController.php';
    (new AuthController())->googleRedirect();
});

Router::get('auth/google/callback', function() {
    require_once APP_ROOT . '/controllers/AuthController.php';
    (new AuthController())->googleCallback();
});

Router::get('resend-verification', function() {
    require_once APP_ROOT . '/controllers/AuthController.php';
    (new AuthController())->resendVerification();
});

Router::get('verify-email', function() {
    require_once APP_ROOT . '/controllers/AuthController.php';
    (new AuthController())->verifyEmail();
});

// --- Student Dashboard ---
Router::get('dashboard', function() {
    Auth::requireLogin();
    require_once APP_ROOT . '/views/student/dashboard.php';
});

Router::get('dashboard/courses', function() {
    Auth::requireLogin();
    require_once APP_ROOT . '/views/student/my-courses.php';
});

Router::get('dashboard/bookmarks', function() {
    Auth::requireLogin();
    require_once APP_ROOT . '/views/student/bookmarks.php';
});

// Bookmark API
Router::post('api/bookmark/toggle', function() {
    require_once APP_ROOT . '/controllers/BookmarkController.php';
    BookmarkController::toggle();
});

// AI Study Assistant API
Router::post('api/ai/chat', function() {
    require_once APP_ROOT . '/controllers/AiController.php';
    (new AiController())->chat();
});

// Specific route MUST come first (with lesson ID)
Router::get('learn/{slug}/{lesson}', function($slug, $lesson) {
    Auth::requireLogin();
    $_GET['slug'] = $slug;
    $_GET['lesson'] = $lesson;
    require_once APP_ROOT . '/views/student/learn.php';
});

// POST route for lesson completion with lesson ID
Router::post('learn/{slug}/{lesson}', function($slug, $lesson) {
    Auth::requireLogin();
    $_GET['slug'] = $slug;
    $_GET['lesson'] = $lesson;
    require_once APP_ROOT . '/views/student/learn.php';
});

// Less specific route (without lesson ID) comes second
Router::get('learn/{slug}', function($slug) {
    Auth::requireLogin();
    $_GET['slug'] = $slug;
    require_once APP_ROOT . '/views/student/learn.php';
});

// POST route for lesson completion without lesson ID
Router::post('learn/{slug}', function($slug) {
    Auth::requireLogin();
    $_GET['slug'] = $slug;
    require_once APP_ROOT . '/views/student/learn.php';
});

Router::get('dashboard/certificates', function() {
    Auth::requireLogin();
    require_once APP_ROOT . '/views/student/certificates.php';
});

// --- Certificate Verification & View ---
Router::get('certificate/{id}', function($id) {
    Auth::requireLogin();
    require_once APP_ROOT . '/controllers/CertificateController.php';
    (new CertificateController())->view($id);
});

Router::get('certificate/{id}/download', function($id) {
    // Auth::requireLogin(); // Allow public download if they have the link? 
    // Usually download requires auth if sensitive. Let's keep it auth for now as per controller logic.
    Auth::requireLogin(); 
    require_once APP_ROOT . '/controllers/CertificateController.php';
    (new CertificateController())->download($id);
});

Router::get('verify-certificate', function() {
    require_once APP_ROOT . '/controllers/CertificateController.php';
    (new CertificateController())->verify();
});

Router::get('dashboard/profile', function() {
    Auth::requireLogin();
    require_once APP_ROOT . '/views/student/profile.php';
});

Router::post('dashboard/profile', function() {
    Auth::requireLogin();
    require_once APP_ROOT . '/controllers/ProfileController.php';
    (new ProfileController())->update();
});

// Enrollment
Router::post('enroll/{id}', function($id) {
    Auth::requireLogin();
    require_once APP_ROOT . '/controllers/CourseController.php';
    (new CourseController())->enroll($id);
});

// --- Instructor Panel ---
Router::get('instructor', function() {
    Auth::requireRole(ROLE_INSTRUCTOR);
    require_once APP_ROOT . '/views/instructor/dashboard.php';
});

Router::get('instructor/courses', function() {
    Auth::requireRole(ROLE_INSTRUCTOR);
    require_once APP_ROOT . '/views/instructor/courses.php';
});

Router::get('instructor/courses/create', function() {
    Auth::requireRole(ROLE_INSTRUCTOR);
    require_once APP_ROOT . '/views/instructor/course-edit.php';
});

Router::post('instructor/courses/create', function() {
    Auth::requireRole(ROLE_INSTRUCTOR);
    require_once APP_ROOT . '/views/instructor/course-edit.php';
});

Router::get('instructor/courses/{id}/edit', function($id) {
    Auth::requireRole(ROLE_INSTRUCTOR);
    $_GET['id'] = $id;
    require_once APP_ROOT . '/views/instructor/course-edit.php';
});

Router::post('instructor/courses/{id}/edit', function($id) {
    Auth::requireRole(ROLE_INSTRUCTOR);
    $_GET['id'] = $id;
    require_once APP_ROOT . '/views/instructor/course-edit.php';
});

Router::get('instructor/courses/{id}/lessons', function($id) {
    Auth::requireRole(ROLE_INSTRUCTOR);
    $_GET['id'] = $id;
    require_once APP_ROOT . '/views/instructor/lessons.php';
});

Router::post('instructor/courses/{id}/lessons', function($id) {
    Auth::requireRole(ROLE_INSTRUCTOR);
    $_GET['id'] = $id;
    require_once APP_ROOT . '/views/instructor/lessons.php';
});

Router::get('instructor/courses/{courseId}/lessons/{lessonId}/quiz', function($courseId, $lessonId) {
    Auth::requireRole(ROLE_INSTRUCTOR);
    $_GET['courseId'] = $courseId;
    $_GET['lessonId'] = $lessonId;
    require_once APP_ROOT . '/views/instructor/quiz-builder.php';
});

Router::post('instructor/courses/{courseId}/lessons/{lessonId}/quiz', function($courseId, $lessonId) {
    Auth::requireRole(ROLE_INSTRUCTOR);
    $_GET['courseId'] = $courseId;
    $_GET['lessonId'] = $lessonId;
    require_once APP_ROOT . '/views/instructor/quiz-builder.php';
});

Router::get('instructor/students', function() {
    Auth::requireRole(ROLE_INSTRUCTOR);
    require_once APP_ROOT . '/views/instructor/students.php';
});

// --- Admin Panel ---
Router::get('admin', function() {
    Auth::requireRole(ROLE_ADMIN);
    require_once APP_ROOT . '/views/admin/dashboard.php';
});

Router::get('admin/users', function() {
    Auth::requireRole(ROLE_ADMIN);
    require_once APP_ROOT . '/views/admin/users.php';
});

Router::post('admin/users', function() {
    Auth::requireRole(ROLE_ADMIN);
    require_once APP_ROOT . '/views/admin/users.php';
});

Router::get('admin/courses', function() {
    Auth::requireRole(ROLE_ADMIN);
    require_once APP_ROOT . '/views/admin/courses.php';
});

Router::post('admin/courses', function() {
    Auth::requireRole(ROLE_ADMIN);
    require_once APP_ROOT . '/views/admin/courses.php';
});

Router::get('admin/categories', function() {
    Auth::requireRole(ROLE_ADMIN);
    require_once APP_ROOT . '/views/admin/categories.php';
});

Router::get('admin/analytics', function() {
    Auth::requireRole(ROLE_ADMIN);
    require_once APP_ROOT . '/views/admin/analytics.php';
});

Router::get('admin/badges', function() {
    Auth::requireRole(ROLE_ADMIN);
    require_once APP_ROOT . '/views/admin/badges.php';
});

Router::post('admin/badges', function() {
    Auth::requireRole(ROLE_ADMIN);
    require_once APP_ROOT . '/views/admin/badges.php';
});

Router::post('admin/categories', function() {
    Auth::requireRole(ROLE_ADMIN);
    require_once APP_ROOT . '/views/admin/categories.php';
});

Router::get('admin/certificates', function() {
    Auth::requireRole(ROLE_ADMIN);
    require_once APP_ROOT . '/views/admin/certificates.php';
});

Router::post('admin/certificates', function() {
    Auth::requireRole(ROLE_ADMIN);
    require_once APP_ROOT . '/views/admin/certificates.php';
});

// --- Certificate Routes ---
Router::get('certificate/{id}', function($id) {
    require_once APP_ROOT . '/controllers/CertificateController.php';
    (new CertificateController())->view($id);
});

Router::get('certificate/{id}/download', function($id) {
    Auth::requireLogin();
    require_once APP_ROOT . '/controllers/CertificateController.php';
    (new CertificateController())->download($id);
});

Router::get('verify-certificate', function() {
    require_once APP_ROOT . '/controllers/CertificateController.php';
    (new CertificateController())->verify();
});

// --- Notification Routes ---
Router::get('notifications/mark-all-read', function() {
    Auth::requireLogin();
    Notification::markAllAsRead(Auth::id());
    if (isset($_SERVER['HTTP_REFERER'])) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        Router::redirect('dashboard');
    }
});

// ============================================================================
// DISPATCH REQUEST
// ============================================================================

Router::dispatch();
