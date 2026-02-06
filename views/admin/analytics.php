<?php
/**
 * DADE Learn - Admin Analytics Dashboard
 * Detailed platform metrics with charts and CSV/XLS export
 */

$pageTitle = 'Analytics Dashboard';
Auth::requireRole('admin');
$db = getDB();
$user = Auth::user();

// Date range filter
$startDate = $_GET['start'] ?? date('Y-m-d', strtotime('-30 days'));
$endDate = $_GET['end'] ?? date('Y-m-d');

// ============================================================================
// GATHER ANALYTICS DATA
// ============================================================================

// 1. Overview Stats
$stats = [];

// Total counts
$stats['total_users'] = $db->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c'];
$stats['total_courses'] = $db->query("SELECT COUNT(*) as c FROM courses")->fetch_assoc()['c'];
$stats['total_enrollments'] = $db->query("SELECT COUNT(*) as c FROM enrollments")->fetch_assoc()['c'];
$stats['total_completions'] = $db->query("SELECT COUNT(*) as c FROM enrollments WHERE completed = 1")->fetch_assoc()['c'];
$stats['total_certificates'] = $db->query("SELECT COUNT(*) as c FROM certificates")->fetch_assoc()['c'];
$stats['total_lessons'] = $db->query("SELECT COUNT(*) as c FROM lessons")->fetch_assoc()['c'];

// Period stats
$stmt = $db->prepare("SELECT COUNT(*) as c FROM users WHERE DATE(created_at) BETWEEN ? AND ?");
$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$stats['period_users'] = $stmt->get_result()->fetch_assoc()['c'];

$stmt = $db->prepare("SELECT COUNT(*) as c FROM enrollments WHERE DATE(enrolled_at) BETWEEN ? AND ?");
$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$stats['period_enrollments'] = $stmt->get_result()->fetch_assoc()['c'];

$stmt = $db->prepare("SELECT COUNT(*) as c FROM enrollments WHERE completed = 1 AND DATE(completed_at) BETWEEN ? AND ?");
$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();
$stats['period_completions'] = $stmt->get_result()->fetch_assoc()['c'];

// 2. User Growth Data (for chart)
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
while ($row = $result->fetch_assoc()) {
    $userGrowth[] = $row;
}

// 3. Enrollment Trends
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
while ($row = $result->fetch_assoc()) {
    $enrollmentTrends[] = $row;
}

// 4. User Breakdown by Role
$roleBreakdown = [];
$result = $db->query("SELECT role, COUNT(*) as count FROM users GROUP BY role ORDER BY count DESC");
while ($row = $result->fetch_assoc()) {
    $roleBreakdown[] = $row;
}

// 5. Course Breakdown by Category
$coursesByCategory = [];
$result = $db->query("
    SELECT COALESCE(cat.name, 'Uncategorized') as category, COUNT(*) as count 
    FROM courses c 
    LEFT JOIN categories cat ON c.category_id = cat.id 
    GROUP BY c.category_id 
    ORDER BY count DESC
");
while ($row = $result->fetch_assoc()) {
    $coursesByCategory[] = $row;
}

// 6. Top Courses by Enrollment
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
while ($row = $result->fetch_assoc()) {
    $topCourses[] = $row;
}

// 7. Quiz Performance
$quizStats = [];
$result = $db->query("
    SELECT 
        COUNT(*) as total_attempts,
        AVG(score) as avg_score,
        SUM(CASE WHEN passed = 1 THEN 1 ELSE 0 END) as passed,
        SUM(CASE WHEN passed = 0 THEN 1 ELSE 0 END) as failed
    FROM quiz_attempts
");
$quizStats = $result->fetch_assoc() ?: ['total_attempts' => 0, 'avg_score' => 0, 'passed' => 0, 'failed' => 0];

// 8. Recent Activity
$recentActivity = [];
$result = $db->query("
    (SELECT 'user' as type, username as title, created_at FROM users ORDER BY created_at DESC LIMIT 5)
    UNION ALL
    (SELECT 'enrollment' as type, CONCAT('Enrolled in course') as title, enrolled_at as created_at FROM enrollments ORDER BY enrolled_at DESC LIMIT 5)
    ORDER BY created_at DESC
    LIMIT 10
");
while ($row = $result->fetch_assoc()) {
    $recentActivity[] = $row;
}

require_once APP_ROOT . '/views/layouts/header.php';
?>

<div class="dashboard-layout">
    <!-- Sidebar -->
    <aside class="dashboard-sidebar">
        <div class="sidebar-user">
            <img src="<?php echo avatar($user, 80); ?>" alt="<?php echo e($user['username']); ?>" class="sidebar-avatar">
            <h4 class="sidebar-username"><?php echo e($user['username']); ?></h4>
            <span class="sidebar-role admin-badge">
                <i class="fas fa-shield-alt"></i> Administrator
            </span>
        </div>
        
        <nav class="sidebar-nav">
            <a href="<?php echo url('admin'); ?>" class="sidebar-link">
                <i class="fas fa-chart-pie"></i>
                <span>Dashboard</span>
            </a>
            <a href="<?php echo url('admin/analytics'); ?>" class="sidebar-link active">
                <i class="fas fa-chart-line"></i>
                <span>Analytics</span>
            </a>
            <a href="<?php echo url('admin/users'); ?>" class="sidebar-link">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </a>
            <a href="<?php echo url('admin/courses'); ?>" class="sidebar-link">
                <i class="fas fa-book"></i>
                <span>Courses</span>
            </a>
            <a href="<?php echo url('admin/badges'); ?>" class="sidebar-link">
                <i class="fas fa-award"></i>
                <span>Badges</span>
            </a>
            <a href="<?php echo url('admin/certificates'); ?>" class="sidebar-link">
                <i class="fas fa-certificate"></i>
                <span>Certificates</span>
            </a>
        </nav>
    </aside>
    
    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="analytics-header">
            <div class="header-left">
                <h1><i class="fas fa-chart-line"></i> Analytics Dashboard</h1>
                <p>Comprehensive platform insights and metrics</p>
            </div>
            <div class="header-right">
                <!-- Date Range Filter -->
                <form class="date-filter" method="GET">
                    <input type="date" name="start" value="<?php echo $startDate; ?>">
                    <span>to</span>
                    <input type="date" name="end" value="<?php echo $endDate; ?>">
                    <button type="submit" class="btn btn-outline btn-sm">Apply</button>
                </form>
                
                <!-- Export Buttons -->
                <div class="export-btns">
                    <button class="btn btn-outline btn-sm" onclick="exportData('csv')">
                        <i class="fas fa-file-csv"></i> CSV
                    </button>
                    <button class="btn btn-outline btn-sm" onclick="exportData('xlsx')">
                        <i class="fas fa-file-excel"></i> Excel
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon users"><i class="fas fa-users"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['total_users']); ?></span>
                    <span class="stat-label">Total Users</span>
                    <span class="stat-change positive">+<?php echo $stats['period_users']; ?> this period</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon courses"><i class="fas fa-book"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['total_courses']); ?></span>
                    <span class="stat-label">Total Courses</span>
                    <span class="stat-change"><?php echo $stats['total_lessons']; ?> lessons</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon enrollments"><i class="fas fa-user-graduate"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['total_enrollments']); ?></span>
                    <span class="stat-label">Enrollments</span>
                    <span class="stat-change positive">+<?php echo $stats['period_enrollments']; ?> this period</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon completions"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['total_completions']); ?></span>
                    <span class="stat-label">Completions</span>
                    <?php $rate = $stats['total_enrollments'] > 0 ? round(($stats['total_completions'] / $stats['total_enrollments']) * 100, 1) : 0; ?>
                    <span class="stat-change"><?php echo $rate; ?>% rate</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon certificates"><i class="fas fa-certificate"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo number_format($stats['total_certificates']); ?></span>
                    <span class="stat-label">Certificates</span>
                    <span class="stat-change">issued</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon quiz"><i class="fas fa-question-circle"></i></div>
                <div class="stat-info">
                    <span class="stat-value"><?php echo round($quizStats['avg_score'] ?? 0, 1); ?>%</span>
                    <span class="stat-label">Avg Quiz Score</span>
                    <span class="stat-change"><?php echo number_format($quizStats['total_attempts'] ?? 0); ?> attempts</span>
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
        
        <div class="charts-row">
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
        
        <!-- Top Courses Table -->
        <div class="data-card">
            <div class="data-header">
                <h3>Top Courses by Enrollment</h3>
                <button class="btn btn-sm btn-outline" onclick="exportTable('topCourses')">
                    <i class="fas fa-download"></i> Export
                </button>
            </div>
            <table class="data-table" id="topCoursesTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Course Title</th>
                        <th>Enrollments</th>
                        <th>Completions</th>
                        <th>Completion Rate</th>
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
                            <?php 
                            $rate = $course['enrollments'] > 0 ? round(($course['completions'] / $course['enrollments']) * 100, 1) : 0;
                            ?>
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
    margin-bottom: var(--space-8);
}

.header-left h1 {
    font-size: var(--text-2xl);
    margin-bottom: var(--space-1);
}

.header-left h1 i {
    color: var(--primary);
    margin-right: var(--space-2);
}

.header-left p {
    color: var(--text-muted);
}

.header-right {
    display: flex;
    gap: var(--space-4);
    align-items: center;
    flex-wrap: wrap;
}

.date-filter {
    display: flex;
    gap: var(--space-2);
    align-items: center;
}

.date-filter input {
    padding: var(--space-2) var(--space-3);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius);
    font-size: var(--text-sm);
}

.date-filter span {
    color: var(--text-muted);
}

.export-btns {
    display: flex;
    gap: var(--space-2);
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: var(--space-5);
    margin-bottom: var(--space-8);
}

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

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.stat-icon.users { background: #dbeafe; color: #2563eb; }
.stat-icon.courses { background: #d1fae5; color: #059669; }
.stat-icon.enrollments { background: #fef3c7; color: #d97706; }
.stat-icon.completions { background: #ede9fe; color: #7c3aed; }
.stat-icon.certificates { background: #fed7d7; color: #c53030; }
.stat-icon.quiz { background: #fce7f3; color: #db2777; }

.stat-info {
    display: flex;
    flex-direction: column;
}

.stat-value {
    font-size: var(--text-2xl);
    font-weight: 700;
    color: var(--text-primary);
}

.stat-label {
    font-size: var(--text-sm);
    color: var(--text-muted);
}

.stat-change {
    font-size: var(--text-xs);
    color: var(--text-secondary);
    margin-top: var(--space-1);
}

.stat-change.positive {
    color: #059669;
}

/* Charts */
.charts-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--space-6);
    margin-bottom: var(--space-6);
}

.charts-row:last-of-type {
    grid-template-columns: repeat(3, 1fr);
}

.chart-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: var(--space-6);
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    border: 1px solid var(--gray-100);
}

.chart-card h3 {
    font-size: var(--text-lg);
    margin-bottom: var(--space-5);
    color: var(--text-secondary);
}

.chart-card.small canvas {
    max-height: 200px;
}

/* Data Table */
.data-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: var(--space-6);
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    border: 1px solid var(--gray-100);
}

.data-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--space-5);
}

.data-header h3 {
    font-size: var(--text-lg);
    color: var(--text-secondary);
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: var(--space-3) var(--space-4);
    text-align: left;
    border-bottom: 1px solid var(--gray-100);
}

.data-table th {
    font-weight: 600;
    color: var(--text-secondary);
    font-size: var(--text-sm);
}

.data-table tr:hover {
    background: var(--gray-50);
}

.progress-bar {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    background: var(--gray-100);
    border-radius: 20px;
    height: 20px;
    width: 120px;
    position: relative;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--primary), #3b82f6);
    border-radius: 20px;
}

.progress-bar span {
    position: absolute;
    width: 100%;
    text-align: center;
    font-size: var(--text-xs);
    font-weight: 600;
    color: var(--text-primary);
}

@media (max-width: 1200px) {
    .charts-row { grid-template-columns: 1fr; }
    .charts-row:last-of-type { grid-template-columns: 1fr; }
}

@media (max-width: 768px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
    .analytics-header { flex-direction: column; }
}
</style>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
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
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
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
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});

// Role Breakdown Chart
const roleData = <?php echo json_encode($roleBreakdown); ?>;
new Chart(document.getElementById('roleChart'), {
    type: 'doughnut',
    data: {
        labels: roleData.map(d => d.role),
        datasets: [{
            data: roleData.map(d => d.count),
            backgroundColor: ['#2563eb', '#059669', '#d97706', '#7c3aed']
        }]
    },
    options: { responsive: true }
});

// Category Breakdown Chart
const categoryData = <?php echo json_encode($coursesByCategory); ?>;
new Chart(document.getElementById('categoryChart'), {
    type: 'doughnut',
    data: {
        labels: categoryData.map(d => d.category),
        datasets: [{
            data: categoryData.map(d => d.count),
            backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899']
        }]
    },
    options: { responsive: true }
});

// Quiz Performance Chart
const quizData = <?php echo json_encode($quizStats); ?>;
new Chart(document.getElementById('quizChart'), {
    type: 'doughnut',
    data: {
        labels: ['Passed', 'Failed'],
        datasets: [{
            data: [quizData.passed || 0, quizData.failed || 0],
            backgroundColor: ['#10b981', '#ef4444']
        }]
    },
    options: { responsive: true }
});

// Export Functions
function exportData(format) {
    const data = {
        stats: <?php echo json_encode($stats); ?>,
        userGrowth: <?php echo json_encode($userGrowth); ?>,
        enrollmentTrends: <?php echo json_encode($enrollmentTrends); ?>,
        topCourses: <?php echo json_encode($topCourses); ?>,
        quizStats: <?php echo json_encode($quizStats); ?>
    };
    
    if (format === 'csv') {
        exportToCSV(data);
    } else {
        exportToXLSX(data);
    }
}

function exportToCSV(data) {
    let csv = 'DADE Learn Analytics Report\n\n';
    
    // Stats
    csv += 'Overview Stats\n';
    csv += 'Metric,Value\n';
    for (let key in data.stats) {
        csv += key.replace(/_/g, ' ') + ',' + data.stats[key] + '\n';
    }
    
    csv += '\nTop Courses\n';
    csv += 'Title,Enrollments,Completions\n';
    data.topCourses.forEach(c => {
        csv += '"' + c.title + '",' + c.enrollments + ',' + c.completions + '\n';
    });
    
    csv += '\nUser Growth (Daily)\n';
    csv += 'Date,New Users\n';
    data.userGrowth.forEach(d => {
        csv += d.date + ',' + d.count + '\n';
    });
    
    downloadFile(csv, 'analytics-report.csv', 'text/csv');
}

function exportToXLSX(data) {
    // Simple HTML table for Excel
    let html = '<html><head><meta charset="UTF-8"></head><body>';
    
    html += '<h2>DADE Learn Analytics Report</h2>';
    html += '<table border="1">';
    html += '<tr><th>Metric</th><th>Value</th></tr>';
    for (let key in data.stats) {
        html += '<tr><td>' + key.replace(/_/g, ' ') + '</td><td>' + data.stats[key] + '</td></tr>';
    }
    html += '</table>';
    
    html += '<h3>Top Courses</h3>';
    html += '<table border="1">';
    html += '<tr><th>Title</th><th>Enrollments</th><th>Completions</th></tr>';
    data.topCourses.forEach(c => {
        html += '<tr><td>' + c.title + '</td><td>' + c.enrollments + '</td><td>' + c.completions + '</td></tr>';
    });
    html += '</table>';
    
    html += '</body></html>';
    
    downloadFile(html, 'analytics-report.xls', 'application/vnd.ms-excel');
}

function downloadFile(content, filename, type) {
    const blob = new Blob([content], { type: type });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = filename;
    link.click();
}

function exportTable(tableId) {
    const table = document.getElementById(tableId + 'Table');
    let csv = '';
    for (let row of table.rows) {
        let rowData = [];
        for (let cell of row.cells) {
            rowData.push('"' + cell.innerText.replace(/"/g, '""') + '"');
        }
        csv += rowData.join(',') + '\n';
    }
    downloadFile(csv, tableId + '.csv', 'text/csv');
}
</script>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
