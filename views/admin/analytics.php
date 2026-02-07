<?php
/**
 * DADE Learn - Admin Analytics Dashboard
 * Enhanced with 30+ metrics organized by category tabs with CSV export
 */

$pageTitle = 'Analytics Dashboard';
Auth::requireRole('admin');
$db = getDB();
$user = Auth::user();

// Date range filter
$startDate = $_GET['start'] ?? date('Y-m-d', strtotime('-30 days'));
$endDate = $_GET['end'] ?? date('Y-m-d');
$activeTab = $_GET['tab'] ?? 'overview';

// Safe query helper - returns 0 if table doesn't exist
function safeCount($db, $query) {
    try {
        $result = @$db->query($query);
        if ($result) {
            return $result->fetch_assoc()['c'] ?? 0;
        }
    } catch (Exception $e) {}
    return 0;
}

function safeQuery($db, $query) {
    try {
        return @$db->query($query);
    } catch (Exception $e) {}
    return null;
}

// ============================================================================
// GATHER ALL ANALYTICS DATA (30+ Metrics)
// ============================================================================

$stats = [];

// ---------------------------------------------------------------------------
// 1. USER METRICS
// ---------------------------------------------------------------------------
$stats['users'] = [
    'total' => $db->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c'] ?? 0,
    'students' => $db->query("SELECT COUNT(*) as c FROM users WHERE role = 'student'")->fetch_assoc()['c'] ?? 0,
    'instructors' => $db->query("SELECT COUNT(*) as c FROM users WHERE role = 'instructor'")->fetch_assoc()['c'] ?? 0,
    'admins' => $db->query("SELECT COUNT(*) as c FROM users WHERE role = 'admin'")->fetch_assoc()['c'] ?? 0,
    'verified_emails' => $db->query("SELECT COUNT(*) as c FROM users WHERE email_verified = 1")->fetch_assoc()['c'] ?? 0,
    'unverified_emails' => $db->query("SELECT COUNT(*) as c FROM users WHERE email_verified = 0 OR email_verified IS NULL")->fetch_assoc()['c'] ?? 0,
    'pending_instructors' => $db->query("SELECT COUNT(*) as c FROM users WHERE instructor_pending = 1")->fetch_assoc()['c'] ?? 0,
    'google_logins' => $db->query("SELECT COUNT(*) as c FROM users WHERE google_id IS NOT NULL")->fetch_assoc()['c'] ?? 0,
];

// Period stats
$stmt = $db->prepare("SELECT COUNT(*) as c FROM users WHERE DATE(created_at) BETWEEN ? AND ?");
$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$stats['users']['new_this_period'] = $stmt->get_result()->fetch_assoc()['c'] ?? 0;

// Active users (logged in within 30 days) - approximate via last login or created_at
$stats['users']['active_30d'] = $db->query("SELECT COUNT(*) as c FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetch_assoc()['c'] ?? 0;

// ---------------------------------------------------------------------------
// 2. COURSE METRICS
// ---------------------------------------------------------------------------
$stats['courses'] = [
    'total' => $db->query("SELECT COUNT(*) as c FROM courses")->fetch_assoc()['c'] ?? 0,
    'published' => $db->query("SELECT COUNT(*) as c FROM courses WHERE status = 'published'")->fetch_assoc()['c'] ?? 0,
    'draft' => $db->query("SELECT COUNT(*) as c FROM courses WHERE status = 'draft' OR status IS NULL")->fetch_assoc()['c'] ?? 0,
    'with_certificates' => $db->query("SELECT COUNT(*) as c FROM courses WHERE is_certificate_course = 1")->fetch_assoc()['c'] ?? 0,
    'free_courses' => $db->query("SELECT COUNT(*) as c FROM courses WHERE price = 0 OR price IS NULL")->fetch_assoc()['c'] ?? 0,
    'paid_courses' => $db->query("SELECT COUNT(*) as c FROM courses WHERE price > 0")->fetch_assoc()['c'] ?? 0,
];

$stats['courses']['total_lessons'] = $db->query("SELECT COUNT(*) as c FROM lessons")->fetch_assoc()['c'] ?? 0;
$stats['courses']['avg_lessons_per_course'] = $stats['courses']['total'] > 0 
    ? round($stats['courses']['total_lessons'] / $stats['courses']['total'], 1) 
    : 0;

// Courses with quizzes (safe - table may not exist)
$stats['courses']['with_quizzes'] = safeCount($db, "SELECT COUNT(DISTINCT course_id) as c FROM quizzes");

// ---------------------------------------------------------------------------
// 3. ENROLLMENT & COMPLETION METRICS
// ---------------------------------------------------------------------------
$stats['enrollments'] = [
    'total' => $db->query("SELECT COUNT(*) as c FROM enrollments")->fetch_assoc()['c'] ?? 0,
    'completed' => $db->query("SELECT COUNT(*) as c FROM enrollments WHERE completed = 1")->fetch_assoc()['c'] ?? 0,
    'in_progress' => $db->query("SELECT COUNT(*) as c FROM enrollments WHERE completed = 0 OR completed IS NULL")->fetch_assoc()['c'] ?? 0,
];

$stats['enrollments']['completion_rate'] = $stats['enrollments']['total'] > 0 
    ? round(($stats['enrollments']['completed'] / $stats['enrollments']['total']) * 100, 1) 
    : 0;

// Period enrollments
$stmt = $db->prepare("SELECT COUNT(*) as c FROM enrollments WHERE DATE(enrolled_at) BETWEEN ? AND ?");
$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$stats['enrollments']['new_this_period'] = $stmt->get_result()->fetch_assoc()['c'] ?? 0;

$stmt = $db->prepare("SELECT COUNT(*) as c FROM enrollments WHERE completed = 1 AND DATE(completed_at) BETWEEN ? AND ?");
$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$stats['enrollments']['completed_this_period'] = $stmt->get_result()->fetch_assoc()['c'] ?? 0;

// ---------------------------------------------------------------------------
// 4. QUIZ & ASSESSMENT METRICS (safe - tables may not exist)
// ---------------------------------------------------------------------------
$quizResult = safeQuery($db, "
    SELECT 
        COUNT(*) as total_attempts,
        AVG(score) as avg_score,
        MAX(score) as max_score,
        MIN(score) as min_score,
        SUM(CASE WHEN passed = 1 THEN 1 ELSE 0 END) as passed,
        SUM(CASE WHEN passed = 0 THEN 1 ELSE 0 END) as failed
    FROM quiz_attempts
");
$quizStats = $quizResult ? $quizResult->fetch_assoc() : [];

$stats['quizzes'] = [
    'total_quizzes' => safeCount($db, "SELECT COUNT(*) as c FROM quizzes"),
    'total_attempts' => $quizStats['total_attempts'] ?? 0,
    'avg_score' => round($quizStats['avg_score'] ?? 0, 1),
    'max_score' => $quizStats['max_score'] ?? 0,
    'min_score' => $quizStats['min_score'] ?? 0,
    'passed' => $quizStats['passed'] ?? 0,
    'failed' => $quizStats['failed'] ?? 0,
];
$stats['quizzes']['pass_rate'] = $stats['quizzes']['total_attempts'] > 0 
    ? round(($stats['quizzes']['passed'] / $stats['quizzes']['total_attempts']) * 100, 1) 
    : 0;

// ---------------------------------------------------------------------------
// 5. CERTIFICATE METRICS (safe - tables may not exist)
// ---------------------------------------------------------------------------
$stats['certificates'] = [
    'total' => safeCount($db, "SELECT COUNT(*) as c FROM certificates"),
    'approved' => safeCount($db, "SELECT COUNT(*) as c FROM certificates WHERE status = 'approved'"),
    'pending' => safeCount($db, "SELECT COUNT(*) as c FROM certificates WHERE status = 'pending'"),
    'rejected' => safeCount($db, "SELECT COUNT(*) as c FROM certificates WHERE status = 'rejected'"),
];

// ---------------------------------------------------------------------------
// 6. BADGE & GAMIFICATION METRICS (safe - tables may not exist)
// ---------------------------------------------------------------------------
$stats['badges'] = [
    'total_badges' => safeCount($db, "SELECT COUNT(*) as c FROM badges"),
    'total_earned' => safeCount($db, "SELECT COUNT(*) as c FROM user_badges"),
    'unique_earners' => safeCount($db, "SELECT COUNT(DISTINCT user_id) as c FROM user_badges"),
];

// ---------------------------------------------------------------------------
// 7. ENGAGEMENT METRICS (safe - tables may not exist)
// ---------------------------------------------------------------------------
$stats['engagement'] = [
    'total_bookmarks' => safeCount($db, "SELECT COUNT(*) as c FROM bookmarks"),
    'users_with_bookmarks' => safeCount($db, "SELECT COUNT(DISTINCT user_id) as c FROM bookmarks"),
    'total_reviews' => safeCount($db, "SELECT COUNT(*) as c FROM reviews"),
    'avg_rating' => 0,
    'forum_posts' => safeCount($db, "SELECT COUNT(*) as c FROM forum_posts"),
];

// Get avg_rating safely
$avgRatingResult = safeQuery($db, "SELECT AVG(rating) as avg FROM reviews");
if ($avgRatingResult) {
    $stats['engagement']['avg_rating'] = round($avgRatingResult->fetch_assoc()['avg'] ?? 0, 1);
}

// ---------------------------------------------------------------------------
// 8. REVENUE METRICS (safe - payments table may not exist)
// ---------------------------------------------------------------------------
$revenueResult = safeQuery($db, "
    SELECT 
        COALESCE(SUM(amount), 0) as total_revenue,
        COUNT(*) as total_transactions
    FROM payments WHERE status = 'success'
");
$revenueData = $revenueResult ? $revenueResult->fetch_assoc() : [];

$stats['revenue'] = [
    'total' => $revenueData['total_revenue'] ?? 0,
    'total_transactions' => $revenueData['total_transactions'] ?? 0,
];

// This month's revenue
$thisMonth = date('Y-m');
$monthRevenueResult = safeQuery($db, "
    SELECT COALESCE(SUM(amount), 0) as monthly 
    FROM payments 
    WHERE status = 'success' AND DATE_FORMAT(created_at, '%Y-%m') = '$thisMonth'
");
$stats['revenue']['this_month'] = $monthRevenueResult ? ($monthRevenueResult->fetch_assoc()['monthly'] ?? 0) : 0;

$stats['revenue']['avg_transaction'] = $stats['revenue']['total_transactions'] > 0 
    ? round($stats['revenue']['total'] / $stats['revenue']['total_transactions'], 2) 
    : 0;

// ---------------------------------------------------------------------------
// CHART DATA
// ---------------------------------------------------------------------------

// User Growth
$userGrowth = [];
$stmt = $db->prepare("
    SELECT DATE(created_at) as date, COUNT(*) as count 
    FROM users 
    WHERE DATE(created_at) BETWEEN ? AND ?
    GROUP BY DATE(created_at) 
    ORDER BY date ASC
");
$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) $userGrowth[] = $row;

// Enrollment Trends
$enrollmentTrends = [];
$stmt = $db->prepare("
    SELECT DATE(enrolled_at) as date, COUNT(*) as count 
    FROM enrollments 
    WHERE DATE(enrolled_at) BETWEEN ? AND ?
    GROUP BY DATE(enrolled_at) 
    ORDER BY date ASC
");
$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) $enrollmentTrends[] = $row;

// Role breakdown
$roleBreakdown = [];
$result = $db->query("SELECT role, COUNT(*) as count FROM users GROUP BY role ORDER BY count DESC");
while ($row = $result->fetch_assoc()) $roleBreakdown[] = $row;

// Top Courses
$topCourses = [];
$result = $db->query("
    SELECT c.title, COUNT(e.id) as enrollments, 
           SUM(CASE WHEN e.completed = 1 THEN 1 ELSE 0 END) as completions
    FROM courses c
    LEFT JOIN enrollments e ON c.id = e.course_id
    GROUP BY c.id
    ORDER BY enrollments DESC
    LIMIT 10
");
while ($row = $result->fetch_assoc()) $topCourses[] = $row;

// Categories
$categories = [];
$result = $db->query("
    SELECT COALESCE(cat.name, 'Uncategorized') as category, COUNT(*) as count 
    FROM courses c 
    LEFT JOIN categories cat ON c.category_id = cat.id 
    GROUP BY c.category_id 
    ORDER BY count DESC
");
while ($row = $result->fetch_assoc()) $categories[] = $row;

// ---------------------------------------------------------------------------
// CSV EXPORT
// ---------------------------------------------------------------------------
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="dade-analytics-' . date('Y-m-d') . '.csv"');
    
    $output = fopen('php://output', 'w');
    
    // Header
    fputcsv($output, ['DADE Learn Analytics Report', date('Y-m-d H:i:s')]);
    fputcsv($output, ['Date Range:', $startDate . ' to ' . $endDate]);
    fputcsv($output, []);
    
    // User Metrics
    fputcsv($output, ['USER METRICS']);
    foreach ($stats['users'] as $key => $value) {
        fputcsv($output, [ucwords(str_replace('_', ' ', $key)), $value]);
    }
    fputcsv($output, []);
    
    // Course Metrics
    fputcsv($output, ['COURSE METRICS']);
    foreach ($stats['courses'] as $key => $value) {
        fputcsv($output, [ucwords(str_replace('_', ' ', $key)), $value]);
    }
    fputcsv($output, []);
    
    // Enrollment Metrics
    fputcsv($output, ['ENROLLMENT METRICS']);
    foreach ($stats['enrollments'] as $key => $value) {
        fputcsv($output, [ucwords(str_replace('_', ' ', $key)), $value]);
    }
    fputcsv($output, []);
    
    // Quiz Metrics
    fputcsv($output, ['QUIZ METRICS']);
    foreach ($stats['quizzes'] as $key => $value) {
        fputcsv($output, [ucwords(str_replace('_', ' ', $key)), $value]);
    }
    fputcsv($output, []);
    
    // Certificate Metrics
    fputcsv($output, ['CERTIFICATE METRICS']);
    foreach ($stats['certificates'] as $key => $value) {
        fputcsv($output, [ucwords(str_replace('_', ' ', $key)), $value]);
    }
    fputcsv($output, []);
    
    // Badge Metrics
    fputcsv($output, ['BADGE METRICS']);
    foreach ($stats['badges'] as $key => $value) {
        fputcsv($output, [ucwords(str_replace('_', ' ', $key)), $value]);
    }
    fputcsv($output, []);
    
    // Engagement Metrics
    fputcsv($output, ['ENGAGEMENT METRICS']);
    foreach ($stats['engagement'] as $key => $value) {
        fputcsv($output, [ucwords(str_replace('_', ' ', $key)), $value]);
    }
    fputcsv($output, []);
    
    // Revenue Metrics
    fputcsv($output, ['REVENUE METRICS']);
    foreach ($stats['revenue'] as $key => $value) {
        fputcsv($output, [ucwords(str_replace('_', ' ', $key)), $value]);
    }
    fputcsv($output, []);
    
    // Top Courses
    fputcsv($output, ['TOP COURSES BY ENROLLMENT']);
    fputcsv($output, ['Course Title', 'Enrollments', 'Completions']);
    foreach ($topCourses as $course) {
        fputcsv($output, [$course['title'], $course['enrollments'], $course['completions']]);
    }
    
    fclose($output);
    exit;
}

require_once APP_ROOT . '/views/layouts/header.php';
$activePage = 'analytics';
?>

<div class="admin-container">
    <?php require_once APP_ROOT . '/views/admin/partials/sidebar.php'; ?>
    
    <!-- Main Content -->
    <main class="admin-main">
        <div class="analytics-header">
            <div class="header-left">
                <h1><i class="fas fa-chart-line"></i> Analytics Dashboard</h1>
                <p>Comprehensive platform insights with 30+ metrics</p>
            </div>
            <div class="header-right">
                <form class="date-filter" method="GET">
                    <input type="hidden" name="tab" value="<?php echo $activeTab; ?>">
                    <input type="date" name="start" value="<?php echo $startDate; ?>">
                    <span>to</span>
                    <input type="date" name="end" value="<?php echo $endDate; ?>">
                    <button type="submit" class="btn btn-outline btn-sm">Apply</button>
                </form>
                <a href="<?php echo url('admin/analytics?export=csv&start=' . $startDate . '&end=' . $endDate); ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-download"></i> Export CSV
                </a>
            </div>
        </div>
        
        <!-- Category Tabs -->
        <div class="analytics-tabs">
            <a href="<?php echo url('admin/analytics?tab=overview'); ?>" class="analytics-tab <?php echo $activeTab === 'overview' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i> Overview
            </a>
            <a href="<?php echo url('admin/analytics?tab=users'); ?>" class="analytics-tab <?php echo $activeTab === 'users' ? 'active' : ''; ?>">
                <i class="fas fa-users"></i> Users
            </a>
            <a href="<?php echo url('admin/analytics?tab=courses'); ?>" class="analytics-tab <?php echo $activeTab === 'courses' ? 'active' : ''; ?>">
                <i class="fas fa-book"></i> Courses
            </a>
            <a href="<?php echo url('admin/analytics?tab=engagement'); ?>" class="analytics-tab <?php echo $activeTab === 'engagement' ? 'active' : ''; ?>">
                <i class="fas fa-heart"></i> Engagement
            </a>
            <a href="<?php echo url('admin/analytics?tab=revenue'); ?>" class="analytics-tab <?php echo $activeTab === 'revenue' ? 'active' : ''; ?>">
                <i class="fas fa-money-bill-wave"></i> Revenue
            </a>
        </div>
        
        <?php if ($activeTab === 'overview'): ?>
        <!-- OVERVIEW TAB -->
        <div class="stats-grid three-cols">
            <div class="stat-card">
                <div class="stat-icon users"><i class="fas fa-users"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['users']['total']); ?></span>
                    <span class="stat-label">Total Users</span>
                    <span class="stat-change positive">+<?php echo $stats['users']['new_this_period']; ?> this period</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon courses"><i class="fas fa-book"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['courses']['total']); ?></span>
                    <span class="stat-label">Total Courses</span>
                    <span class="stat-change"><?php echo $stats['courses']['total_lessons']; ?> lessons</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon enrollments"><i class="fas fa-user-graduate"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['enrollments']['total']); ?></span>
                    <span class="stat-label">Enrollments</span>
                    <span class="stat-change positive">+<?php echo $stats['enrollments']['new_this_period']; ?> this period</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon completions"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo $stats['enrollments']['completion_rate']; ?>%</span>
                    <span class="stat-label">Completion Rate</span>
                    <span class="stat-change"><?php echo number_format($stats['enrollments']['completed']); ?> completed</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon certificates"><i class="fas fa-certificate"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['certificates']['total']); ?></span>
                    <span class="stat-label">Certificates</span>
                    <span class="stat-change"><?php echo $stats['certificates']['pending']; ?> pending</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon quiz"><i class="fas fa-question-circle"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo $stats['quizzes']['avg_score']; ?>%</span>
                    <span class="stat-label">Avg Quiz Score</span>
                    <span class="stat-change"><?php echo number_format($stats['quizzes']['total_attempts']); ?> attempts</span>
                </div>
            </div>
        </div>
        
        <!-- Charts Row -->
        <div class="charts-row">
            <div class="chart-card">
                <h3>User Growth</h3>
                <canvas id="userGrowthChart"></canvas>
            </div>
            <div class="chart-card">
                <h3>Enrollment Trends</h3>
                <canvas id="enrollmentChart"></canvas>
            </div>
        </div>
        
        <div class="charts-row three-cols">
            <div class="chart-card small">
                <h3>Users by Role</h3>
                <canvas id="roleChart"></canvas>
            </div>
            <div class="chart-card small">
                <h3>Courses by Category</h3>
                <canvas id="categoryChart"></canvas>
            </div>
            <div class="chart-card small">
                <h3>Quiz Performance</h3>
                <canvas id="quizChart"></canvas>
            </div>
        </div>
        
        <?php elseif ($activeTab === 'users'): ?>
        <!-- USERS TAB -->
        <div class="stats-grid four-cols">
            <div class="stat-card">
                <div class="stat-icon users"><i class="fas fa-users"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['users']['total']); ?></span>
                    <span class="stat-label">Total Users</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon students"><i class="fas fa-user-graduate"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['users']['students']); ?></span>
                    <span class="stat-label">Students</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon instructors"><i class="fas fa-chalkboard-teacher"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['users']['instructors']); ?></span>
                    <span class="stat-label">Instructors</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon admins"><i class="fas fa-user-shield"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['users']['admins']); ?></span>
                    <span class="stat-label">Admins</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon verified"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['users']['verified_emails']); ?></span>
                    <span class="stat-label">Verified Emails</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon unverified"><i class="fas fa-exclamation-circle"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['users']['unverified_emails']); ?></span>
                    <span class="stat-label">Unverified</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon pending"><i class="fas fa-hourglass-half"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['users']['pending_instructors']); ?></span>
                    <span class="stat-label">Pending Instructors</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon google"><i class="fab fa-google"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['users']['google_logins']); ?></span>
                    <span class="stat-label">Google Logins</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon active"><i class="fas fa-user-clock"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['users']['active_30d']); ?></span>
                    <span class="stat-label">Active (30 days)</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon new"><i class="fas fa-user-plus"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['users']['new_this_period']); ?></span>
                    <span class="stat-label">New This Period</span>
                </div>
            </div>
        </div>
        
        <?php elseif ($activeTab === 'courses'): ?>
        <!-- COURSES TAB -->
        <div class="stats-grid four-cols">
            <div class="stat-card">
                <div class="stat-icon courses"><i class="fas fa-book"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['courses']['total']); ?></span>
                    <span class="stat-label">Total Courses</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon published"><i class="fas fa-check"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['courses']['published']); ?></span>
                    <span class="stat-label">Published</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon draft"><i class="fas fa-pencil-alt"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['courses']['draft']); ?></span>
                    <span class="stat-label">Drafts</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon lessons"><i class="fas fa-play-circle"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['courses']['total_lessons']); ?></span>
                    <span class="stat-label">Total Lessons</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon avg"><i class="fas fa-layer-group"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo $stats['courses']['avg_lessons_per_course']; ?></span>
                    <span class="stat-label">Avg Lessons/Course</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon quizzes"><i class="fas fa-question"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['courses']['with_quizzes']); ?></span>
                    <span class="stat-label">With Quizzes</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon certificates"><i class="fas fa-certificate"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['courses']['with_certificates']); ?></span>
                    <span class="stat-label">With Certificates</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon free"><i class="fas fa-gift"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['courses']['free_courses']); ?></span>
                    <span class="stat-label">Free Courses</span>
                </div>
            </div>
        </div>
        
        <!-- Top Courses Table -->
        <div class="data-card">
            <div class="data-header">
                <h3>Top Courses by Enrollment</h3>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Course Title</th>
                        <th>Enrollments</th>
                        <th>Completions</th>
                        <th>Rate</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($topCourses as $i => $course): ?>
                    <tr>
                        <td><?php echo $i + 1; ?></td>
                        <td><?php echo e($course['title']); ?></td>
                        <td><?php echo number_format($course['enrollments']); ?></td>
                        <td><?php echo number_format($course['completions']); ?></td>
                        <td>
                            <?php $rate = $course['enrollments'] > 0 ? round(($course['completions'] / $course['enrollments']) * 100, 1) : 0; ?>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?php echo $rate; ?>%"></div>
                                <span><?php echo $rate; ?>%</span>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php elseif ($activeTab === 'engagement'): ?>
        <!-- ENGAGEMENT TAB -->
        <div class="stats-grid four-cols">
            <div class="stat-card">
                <div class="stat-icon enrollments"><i class="fas fa-user-graduate"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['enrollments']['total']); ?></span>
                    <span class="stat-label">Total Enrollments</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon completions"><i class="fas fa-check-double"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['enrollments']['completed']); ?></span>
                    <span class="stat-label">Completed</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon in-progress"><i class="fas fa-spinner"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['enrollments']['in_progress']); ?></span>
                    <span class="stat-label">In Progress</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon rate"><i class="fas fa-percentage"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo $stats['enrollments']['completion_rate']; ?>%</span>
                    <span class="stat-label">Completion Rate</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bookmarks"><i class="fas fa-bookmark"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['engagement']['total_bookmarks']); ?></span>
                    <span class="stat-label">Total Bookmarks</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon reviews"><i class="fas fa-star"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['engagement']['total_reviews']); ?></span>
                    <span class="stat-label">Reviews</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon rating"><i class="fas fa-star-half-alt"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo round($stats['engagement']['avg_rating'], 1); ?></span>
                    <span class="stat-label">Avg Rating</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon badges"><i class="fas fa-award"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['badges']['total_earned']); ?></span>
                    <span class="stat-label">Badges Earned</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon quiz"><i class="fas fa-question-circle"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['quizzes']['total_attempts']); ?></span>
                    <span class="stat-label">Quiz Attempts</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon pass"><i class="fas fa-check"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo $stats['quizzes']['pass_rate']; ?>%</span>
                    <span class="stat-label">Quiz Pass Rate</span>
                </div>
            </div>
        </div>
        
        <?php elseif ($activeTab === 'revenue'): ?>
        <!-- REVENUE TAB -->
        <div class="stats-grid four-cols">
            <div class="stat-card large">
                <div class="stat-icon revenue"><i class="fas fa-money-bill-wave"></i></div>
                <div class="stat-info">
                    <span class="stat-value">₦<?php echo number_format($stats['revenue']['total'], 2); ?></span>
                    <span class="stat-label">Total Revenue</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon monthly"><i class="fas fa-calendar-alt"></i></div>
                <div class="stat-info">
                    <span class="stat-value">₦<?php echo number_format($stats['revenue']['this_month'], 2); ?></span>
                    <span class="stat-label">This Month</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon transactions"><i class="fas fa-receipt"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['revenue']['total_transactions']); ?></span>
                    <span class="stat-label">Transactions</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon avg"><i class="fas fa-calculator"></i></div>
                <div class="stat-info">
                    <span class="stat-value">₦<?php echo number_format($stats['revenue']['avg_transaction'], 2); ?></span>
                    <span class="stat-label">Avg Transaction</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon paid"><i class="fas fa-shopping-cart"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['courses']['paid_courses']); ?></span>
                    <span class="stat-label">Paid Courses</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon free"><i class="fas fa-gift"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['courses']['free_courses']); ?></span>
                    <span class="stat-label">Free Courses</span>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </main>
</div>

<style>
/* Analytics Specific Styles */
.analytics-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: var(--space-4);
    margin-bottom: var(--space-6);
}
.header-left h1 { font-size: var(--text-2xl); margin-bottom: var(--space-1); }
.header-left h1 i { color: var(--primary); margin-right: var(--space-2); }
.header-left p { color: var(--text-muted); }
.header-right { display: flex; gap: var(--space-4); align-items: center; flex-wrap: wrap; }
.date-filter { display: flex; gap: var(--space-2); align-items: center; }
.date-filter input { padding: var(--space-2) var(--space-3); border: 1px solid var(--gray-200); border-radius: var(--radius); font-size: var(--text-sm); }
.date-filter span { color: var(--text-muted); }

/* Tabs */
.analytics-tabs {
    display: flex;
    gap: var(--space-2);
    margin-bottom: var(--space-6);
    flex-wrap: wrap;
    background: var(--white);
    padding: var(--space-2);
    border-radius: var(--radius-lg);
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
.analytics-tab {
    padding: var(--space-3) var(--space-5);
    border-radius: var(--radius-md);
    font-weight: 500;
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    gap: var(--space-2);
    transition: all var(--transition-fast);
}
.analytics-tab:hover { background: var(--gray-50); }
.analytics-tab.active { background: var(--primary); color: white; }

/* Stats Grid */
.stats-grid { display: grid; gap: var(--space-5); margin-bottom: var(--space-8); width: 100%; }
.stats-grid.six-cols { grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); }
.stats-grid.four-cols { grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); }
.stats-grid.three-cols { grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); }

@media (max-width: 768px) { .stats-grid.six-cols, .stats-grid.four-cols, .stats-grid.three-cols { grid-template-columns: repeat(2, 1fr); } }

.stat-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: var(--space-5);
    display: flex;
    align-items: center;
    gap: var(--space-4);
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    border: 1px solid var(--gray-100);
}
.stat-card.large { grid-column: span 2; }
.stat-icon { width: 50px; height: 50px; border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; font-size: 20px; }
.stat-icon.users { background: #dbeafe; color: #2563eb; }
.stat-icon.courses, .stat-icon.published { background: #d1fae5; color: #059669; }
.stat-icon.enrollments { background: #fef3c7; color: #d97706; }
.stat-icon.completions, .stat-icon.verified, .stat-icon.pass { background: #ede9fe; color: #7c3aed; }
.stat-icon.certificates { background: #fed7d7; color: #c53030; }
.stat-icon.quiz, .stat-icon.quizzes { background: #fce7f3; color: #db2777; }
.stat-icon.students { background: #e0f2fe; color: #0284c7; }
.stat-icon.instructors { background: #fef9c3; color: #ca8a04; }
.stat-icon.admins { background: #fee2e2; color: #dc2626; }
.stat-icon.pending { background: #ffedd5; color: #ea580c; }
.stat-icon.google { background: #fef3c7; color: #f59e0b; }
.stat-icon.active, .stat-icon.new { background: #ccfbf1; color: #0d9488; }
.stat-icon.draft { background: #f3f4f6; color: #6b7280; }
.stat-icon.lessons, .stat-icon.avg { background: #e0e7ff; color: #4f46e5; }
.stat-icon.free, .stat-icon.gift { background: #dcfce7; color: #16a34a; }
.stat-icon.bookmarks { background: #fce7f3; color: #ec4899; }
.stat-icon.reviews, .stat-icon.rating { background: #fef3c7; color: #f59e0b; }
.stat-icon.badges { background: #fef08a; color: #ca8a04; }
.stat-icon.in-progress { background: #e0f2fe; color: #0284c7; }
.stat-icon.rate { background: #f3e8ff; color: #9333ea; }
.stat-icon.revenue, .stat-icon.monthly { background: #dcfce7; color: #16a34a; }
.stat-icon.transactions { background: #e0e7ff; color: #4f46e5; }
.stat-icon.paid { background: #fef3c7; color: #d97706; }
.stat-icon.unverified { background: #fee2e2; color: #dc2626; }

.stat-info { display: flex; flex-direction: column; }
.stat-value { font-size: var(--text-xl); font-weight: 700; color: var(--text-primary); }
.stat-label { font-size: var(--text-sm); color: var(--text-muted); }
.stat-change { font-size: var(--text-xs); color: var(--text-secondary); margin-top: var(--space-1); }
.stat-change.positive { color: #059669; }

/* Charts */
.charts-row { display: grid; grid-template-columns: repeat(2, 1fr); gap: var(--space-6); margin-bottom: var(--space-6); }
.charts-row.three-cols { grid-template-columns: repeat(3, 1fr); }
@media (max-width: 1200px) { .charts-row, .charts-row.three-cols { grid-template-columns: 1fr; } }

.chart-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: var(--space-6);
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    border: 1px solid var(--gray-100);
}
.chart-card h3 { font-size: var(--text-lg); margin-bottom: var(--space-5); color: var(--text-secondary); }
.chart-card.small canvas { max-height: 200px; }

/* Data Table */
.data-card { background: var(--white); border-radius: var(--radius-xl); padding: var(--space-6); box-shadow: 0 2px 8px rgba(0,0,0,0.04); border: 1px solid var(--gray-100); }
.data-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-5); }
.data-header h3 { font-size: var(--text-lg); color: var(--text-secondary); }
.data-table { width: 100%; border-collapse: collapse; }
.data-table th, .data-table td { padding: var(--space-3) var(--space-4); text-align: left; border-bottom: 1px solid var(--gray-100); }
.data-table th { font-weight: 600; color: var(--text-secondary); font-size: var(--text-sm); }
.data-table tr:hover { background: var(--gray-50); }
.progress-bar { display: flex; align-items: center; gap: var(--space-3); background: var(--gray-100); border-radius: 20px; height: 20px; width: 120px; position: relative; overflow: hidden; }
.progress-fill { height: 100%; background: linear-gradient(90deg, var(--primary), #3b82f6); border-radius: 20px; }
.progress-bar span { position: absolute; width: 100%; text-align: center; font-size: var(--text-xs); font-weight: 600; color: var(--text-primary); }

/* Dashboard Layout (duplicated for standalone) */
.dashboard-layout { display: flex; min-height: calc(100vh - var(--header-height)); background: var(--gray-50); }
.dashboard-sidebar { width: 280px; background: var(--white); border-right: 1px solid var(--gray-100); padding: var(--space-6); position: sticky; top: var(--header-height); height: calc(100vh - var(--header-height)); overflow-y: auto; }
@media (max-width: 1024px) { .dashboard-sidebar { display: none; } }
.sidebar-user { text-align: center; padding-bottom: var(--space-6); border-bottom: 1px solid var(--gray-100); margin-bottom: var(--space-6); }
.sidebar-avatar { width: 80px; height: 80px; border-radius: 50%; margin-bottom: var(--space-3); border: 3px solid #dc2626; object-fit: cover; }
.sidebar-username { margin-bottom: var(--space-2); font-size: var(--text-lg); }
.sidebar-role { display: inline-flex; align-items: center; gap: var(--space-2); font-size: var(--text-sm); padding: 6px 14px; border-radius: 20px; }
.admin-badge { background: linear-gradient(135deg, #dc2626, #b91c1c); color: white; }
.sidebar-nav { display: flex; flex-direction: column; gap: var(--space-1); }
.sidebar-link { display: flex; align-items: center; gap: var(--space-3); padding: var(--space-3) var(--space-4); border-radius: var(--radius-lg); color: var(--text-secondary); font-weight: 500; transition: all var(--transition-fast); }
.sidebar-link:hover { background: var(--gray-50); color: var(--text-primary); }
.sidebar-link.active { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #dc2626; }
.sidebar-link i { width: 20px; text-align: center; }
.dashboard-main { flex: 1; padding: var(--space-8); max-width: 1400px; }
</style>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
<?php if ($activeTab === 'overview'): ?>
// User Growth Chart
const userGrowthData = <?php echo json_encode($userGrowth); ?>;
new Chart(document.getElementById('userGrowthChart'), {
    type: 'line',
    data: {
        labels: userGrowthData.map(d => d.date),
        datasets: [{
            label: 'New Users',
            data: userGrowthData.map(d => d.count),
            borderColor: '#2563eb',
            backgroundColor: 'rgba(37, 99, 235, 0.1)',
            fill: true,
            tension: 0.4
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});

// Enrollment Trends Chart
const enrollmentData = <?php echo json_encode($enrollmentTrends); ?>;
new Chart(document.getElementById('enrollmentChart'), {
    type: 'line',
    data: {
        labels: enrollmentData.map(d => d.date),
        datasets: [{
            label: 'Enrollments',
            data: enrollmentData.map(d => d.count),
            borderColor: '#059669',
            backgroundColor: 'rgba(5, 150, 105, 0.1)',
            fill: true,
            tension: 0.4
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});

// Role Breakdown Chart
const roleData = <?php echo json_encode($roleBreakdown); ?>;
new Chart(document.getElementById('roleChart'), {
    type: 'doughnut',
    data: {
        labels: roleData.map(d => d.role),
        datasets: [{ data: roleData.map(d => d.count), backgroundColor: ['#2563eb', '#059669', '#d97706', '#7c3aed'] }]
    },
    options: { responsive: true }
});

// Category Breakdown Chart
const categoryData = <?php echo json_encode($categories); ?>;
new Chart(document.getElementById('categoryChart'), {
    type: 'doughnut',
    data: {
        labels: categoryData.map(d => d.category),
        datasets: [{ data: categoryData.map(d => d.count), backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'] }]
    },
    options: { responsive: true }
});

// Quiz Performance Chart
const quizData = <?php echo json_encode($stats['quizzes']); ?>;
new Chart(document.getElementById('quizChart'), {
    type: 'doughnut',
    data: {
        labels: ['Passed', 'Failed'],
        datasets: [{ data: [quizData.passed || 0, quizData.failed || 0], backgroundColor: ['#10b981', '#ef4444'] }]
    },
    options: { responsive: true }
});
<?php endif; ?>
</script>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
