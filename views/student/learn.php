<?php
/**
 * DADE Learn - Lesson Viewer
 * Course learning interface
 */

$slug = $_GET['slug'] ?? '';
$currentLessonId = $_GET['lesson'] ?? null;
$db = getDB();
$userId = Auth::id();

// Get course details
$stmt = $db->prepare("
    SELECT c.*, u.username as instructor_name, cat.name as category_name,
           (SELECT COUNT(*) FROM lessons WHERE course_id = c.id) as total_lessons
    FROM courses c
    LEFT JOIN users u ON c.instructor_id = u.id
    LEFT JOIN categories cat ON c.category_id = cat.id
    WHERE c.slug = ? AND c.status = 'published'
");
$stmt->bind_param("s", $slug);
$stmt->execute();
$course = $stmt->get_result()->fetch_assoc();

if (!$course) {
    Session::flash('error', 'Course not found.');
    Router::redirect('courses');
    return;
}

// Check enrollment
$stmt = $db->prepare("SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?");
$stmt->bind_param("ii", $userId, $course['id']);
$stmt->execute();
$enrollment = $stmt->get_result()->fetch_assoc();

if (!$enrollment) {
    Session::flash('error', 'You must enroll in this course to view lessons.');
    Router::redirect('course/' . $slug);
    return;
}

// Get all lessons
$stmt = $db->prepare("SELECT * FROM lessons WHERE course_id = ? ORDER BY lesson_order ASC");
$stmt->bind_param("i", $course['id']);
$stmt->execute();
$result = $stmt->get_result();
$lessons = [];
while ($row = $result->fetch_assoc()) {
    $lessons[] = $row;
}

// Determine current lesson
$currentLesson = null;
if ($currentLessonId) {
    foreach ($lessons as $lesson) {
        if ($lesson['id'] == $currentLessonId) {
            $currentLesson = $lesson;
            break;
        }
    }
} else {
    // Default to first lesson or last accessed (not implemented yet, so first)
    $currentLesson = $lessons[0] ?? null;
}

// Handle empty course
if (!$currentLesson) {
    Session::flash('error', 'This course has no lessons yet.');
    Router::redirect('dashboard/courses');
    return;
}

// Handle quiz submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_quiz'])) {
    if (!class_exists('BadgeService')) {
        require_once APP_ROOT . '/core/BadgeService.php';
    }
    if (!class_exists('CertificateController')) {
        require_once APP_ROOT . '/controllers/CertificateController.php';
    }

    $lessonId = intval($_POST['lesson_id']);
    
    // Get quiz questions
    $stmt = $db->prepare("SELECT id, correct_answer, points FROM quiz_questions WHERE lesson_id = ?");
    $stmt->bind_param("i", $lessonId);
    $stmt->execute();
    $result = $stmt->get_result();
    $questions = [];
    $totalPoints = 0;
    while ($row = $result->fetch_assoc()) {
        $questions[] = $row;
        $totalPoints += $row['points'];
    }
    
    $earnedPoints = 0;
    foreach ($questions as $q) {
        $userAnswer = $_POST['q_' . $q['id']] ?? '';
        if ($userAnswer === $q['correct_answer']) {
            $earnedPoints += $q['points'];
        }
    }
    
    $score = ($totalPoints > 0) ? round(($earnedPoints / $totalPoints) * 100) : 0;
    $passingGrade = $course['passing_grade'] ?? 70;
    $passed = ($score >= $passingGrade) ? 1 : 0;
    
    // Save attempt
    $stmt = $db->prepare("INSERT INTO quiz_attempts (user_id, lesson_id, score, total_points, passed, completed_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("iiiii", $userId, $lessonId, $score, $totalPoints, $passed);
    $stmt->execute();
    
    // If passed, mark lesson as complete and check for certificate/badges
    if ($passed) {
        // Mark lesson progress
        $checkProgress = $db->prepare("SELECT id FROM lesson_progress WHERE user_id = ? AND lesson_id = ?");
        $checkProgress->bind_param("ii", $userId, $lessonId);
        $checkProgress->execute();
        if ($checkProgress->get_result()->num_rows === 0) {
            $stmt = $db->prepare("INSERT INTO lesson_progress (user_id, lesson_id, completed, completed_at) VALUES (?, ?, 1, NOW())");
            $stmt->bind_param("ii", $userId, $lessonId);
            $stmt->execute();
            
            // Re-calculate enrollment progress
            $countStmt = $db->prepare("SELECT COUNT(*) as c FROM lessons WHERE course_id = ?");
            $countStmt->bind_param("i", $course['id']);
            $countStmt->execute();
            $totalLessons = $countStmt->get_result()->fetch_assoc()['c'];
            
            $doneStmt = $db->prepare("SELECT COUNT(DISTINCT lesson_id) as c FROM lesson_progress lp JOIN lessons l ON lp.lesson_id = l.id WHERE lp.user_id = ? AND l.course_id = ? AND lp.completed = 1");
            $doneStmt->bind_param("ii", $userId, $course['id']);
            $doneStmt->execute();
            $completedCount = $doneStmt->get_result()->fetch_assoc()['c'];
            
            $progress = ($totalLessons > 0) ? round(($completedCount / $totalLessons) * 100) : 0;
            $isCourseCompleted = ($progress == 100) ? 1 : 0;
            $completedTime = $isCourseCompleted ? date('Y-m-d H:i:s') : null;
            
            $stmt = $db->prepare("UPDATE enrollments SET progress = ?, completed = ?, completed_at = ? WHERE user_id = ? AND course_id = ?");
            $stmt->bind_param("iissii", $progress, $isCourseCompleted, $completedTime, $userId, $course['id']);
            $stmt->execute();
        }
        
        // Check for badges
        BadgeService::checkAllBadges($userId);
        
        // Check if certificate course and passed final quiz
        $issued = CertificateController::checkAndIssue($userId, $course['id']);
        
        // If course is 100% complete but NO certificate was issued
        if ($isCourseCompleted && !$issued) {
            if (!class_exists('Mail')) {
                require_once APP_ROOT . '/core/Mail.php';
            }
            Mail::sendCourseCompletion($userId, $course['id']);
        }
        
        Session::flash('success', "Quiz passed! You scored {$score}%.");
    } else {
        Session::flash('error', "Quiz not passed. You scored {$score}%. Passing grade is {$passingGrade}%. Try again!");
    }
    
    Router::redirect("learn/{$slug}/{$lessonId}");
    return;
}

// Handle lesson completion (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_lesson'])) {
    $lessonId = $_POST['lesson_id'];
    
    // Check if already completed
    $stmt = $db->prepare("SELECT id FROM lesson_progress WHERE user_id = ? AND lesson_id = ?");
    $stmt->bind_param("ii", $userId, $lessonId);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows === 0) {
        $stmt = $db->prepare("INSERT INTO lesson_progress (user_id, lesson_id, completed, completed_at) VALUES (?, ?, 1, NOW())");
        $stmt->bind_param("ii", $userId, $lessonId);
        $stmt->execute();
        
        // Re-calculate enrollment progress
        $countStmt = $db->prepare("SELECT COUNT(*) as c FROM lessons WHERE course_id = ?");
        $countStmt->bind_param("i", $course['id']);
        $countStmt->execute();
        $totalLessons = $countStmt->get_result()->fetch_assoc()['c'];
        
        $doneStmt = $db->prepare("SELECT COUNT(DISTINCT lesson_id) as c FROM lesson_progress lp JOIN lessons l ON lp.lesson_id = l.id WHERE lp.user_id = ? AND l.course_id = ? AND lp.completed = 1");
        $doneStmt->bind_param("ii", $userId, $course['id']);
        $doneStmt->execute();
        $completedCount = $doneStmt->get_result()->fetch_assoc()['c'];
        
        $progress = ($totalLessons > 0) ? round(($completedCount / $totalLessons) * 100) : 0;
        $isCourseCompleted = ($progress == 100) ? 1 : 0;
        $completedTime = $isCourseCompleted ? date('Y-m-d H:i:s') : null;
        
        $stmt = $db->prepare("UPDATE enrollments SET progress = ?, completed = ?, completed_at = ? WHERE user_id = ? AND course_id = ?");
        $stmt->bind_param("iissii", $progress, $isCourseCompleted, $completedTime, $userId, $course['id']);
        $stmt->execute();
        
        // Check for badges
        if (!class_exists('BadgeService')) {
            require_once APP_ROOT . '/core/BadgeService.php';
        }
        BadgeService::checkAllBadges($userId);
        
        // Check for certificates (CertificateController handles its own emails)
        if (!class_exists('CertificateController')) {
            require_once APP_ROOT . '/controllers/CertificateController.php';
        }
        $issued = CertificateController::checkAndIssue($userId, $course['id']);
        
        // If course is 100% complete but NO certificate was issued (either not a cert course or not approved)
        // Send a general congratulations email
        if ($isCourseCompleted && !$issued) {
            if (!class_exists('Mail')) {
                require_once APP_ROOT . '/core/Mail.php';
            }
            Mail::sendCourseCompletion($userId, $course['id']);
        }
        
        Session::flash('success', 'Lesson marked as complete!');
    }
    
    // Redirect to next lesson
    $currentIndex = -1;
    foreach($lessons as $idx => $l) {
        if($l['id'] == $lessonId) {
            $currentIndex = $idx;
            break;
        }
    }
    
    if (isset($lessons[$currentIndex + 1])) {
        Router::redirect('learn/' . $slug . '/' . $lessons[$currentIndex + 1]['id']);
    } else {
        Session::flash('success', 'Course completed! Congratulations!');
        Router::redirect('dashboard/courses');
    }
    return;
}

// Get lesson completion status for the student
$completedLessons = [];
$stmt = $db->prepare("
    SELECT lp.lesson_id 
    FROM lesson_progress lp
    JOIN lessons l ON lp.lesson_id = l.id
    WHERE lp.user_id = ? AND l.course_id = ? AND lp.completed = 1
");
$stmt->bind_param("ii", $userId, $course['id']);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $completedLessons[] = $row['lesson_id'];
}

require_once APP_ROOT . '/views/layouts/header.php';
?>

<div class="learn-layout">
    <!-- Sidebar / Lesson List -->
    <aside class="learn-sidebar">
        <div class="learn-sidebar-header">
            <a href="<?php echo url('dashboard/courses'); ?>" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <h3 class="course-title"><?php echo e($course['title']); ?></h3>
            <div class="progress-container">
                <div class="progress-bar">
                    <div class="progress-fill" style="width: <?php echo $enrollment['progress']; ?>%"></div>
                </div>
                <span class="progress-text"><?php echo round($enrollment['progress']); ?>% Complete</span>
            </div>
        </div>
        
        <div class="lesson-list">
            <?php foreach ($lessons as $index => $lesson): 
                $isCompleted = in_array($lesson['id'], $completedLessons);
                $isActive = ($lesson['id'] == $currentLesson['id']);
            ?>
            <a href="<?php echo url('learn/' . $slug . '/' . $lesson['id']); ?>" class="lesson-item <?php echo $isActive ? 'active' : ''; ?>">
                <span class="lesson-status">
                    <?php if ($isCompleted): ?>
                        <i class="fas fa-check-circle text-success"></i>
                    <?php elseif ($isActive): ?>
                        <i class="fas fa-play-circle text-primary"></i>
                    <?php else: ?>
                        <i class="far fa-circle text-muted"></i>
                    <?php endif; ?>
                </span>
                <span class="lesson-info">
                    <span class="lesson-number">Lesson <?php echo $index + 1; ?></span>
                    <span class="lesson-name"><?php echo e($lesson['title']); ?></span>
                </span>
                <?php if ($lesson['lesson_type'] === 'video'): ?>
                <span class="lesson-meta"><i class="fas fa-video"></i></span>
                <?php endif; ?>
            </a>
            <?php endforeach; ?>
        </div>
    </aside>
    
    <!-- Main Content -->
    <div class="learn-content">
        <div class="lesson-container">
            <div class="lesson-header">
                <div class="lesson-header-top">
                    <h1><?php echo e($currentLesson['title']); ?></h1>
                    <button class="btn btn-secondary btn-sm sidebar-toggle hide-desktop" id="sidebarToggle">
                        <i class="fas fa-list"></i> Lessons
                    </button>
                </div>
            </div>
            
            <div class="lesson-body">
                <?php if ($currentLesson['lesson_type'] === 'video' && (!empty($currentLesson['video_path']) || !empty($currentLesson['video_url']))): ?>
                <div class="video-container">
                    <?php if (!empty($currentLesson['video_path'])): ?>
                    <div class="video-item">
                        <video controls src="<?php echo url('uploads/lessons/videos/' . $currentLesson['video_path']); ?>"></video>
                        <p class="video-caption">Uploaded Video</p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($currentLesson['video_url'])): ?>
                    <div class="video-item">
                        <?php if (strpos($currentLesson['video_url'], 'youtube.com') !== false || strpos($currentLesson['video_url'], 'youtu.be') !== false): 
                            if (strpos($currentLesson['video_url'], 'youtu.be') !== false) {
                                $videoId = substr(parse_url($currentLesson['video_url'], PHP_URL_PATH), 1);
                            } else {
                                parse_str(parse_url($currentLesson['video_url'], PHP_URL_QUERY), $yt);
                                $videoId = $yt['v'] ?? '';
                            }
                        ?>
                        <iframe src="https://www.youtube.com/embed/<?php echo $videoId; ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        <?php else: ?>
                        <video controls src="<?php echo e($currentLesson['video_url']); ?>"></video>
                        <?php endif; ?>
                        <p class="video-caption">External Link</p>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <?php if ($currentLesson['lesson_type'] === 'quiz'): ?>
                    <?php
                    // Get quiz questions for this lesson
                    $quizStmt = $db->prepare("SELECT * FROM quiz_questions WHERE lesson_id = ? ORDER BY question_order ASC");
                    $quizStmt->bind_param("i", $currentLesson['id']);
                    $quizStmt->execute();
                    $quizResult = $quizStmt->get_result();
                    $quizQuestions = [];
                    while($q = $quizResult->fetch_assoc()) {
                        $quizQuestions[] = $q;
                    }
                    ?>
                    
                    <?php if (!empty($quizQuestions)): ?>
                    <form method="POST" class="quiz-form" id="quizForm">
                        <input type="hidden" name="submit_quiz" value="1">
                        <input type="hidden" name="lesson_id" value="<?php echo $currentLesson['id']; ?>">
                        
                        <?php foreach($quizQuestions as $qIndex => $question): ?>
                        <div class="quiz-question">
                            <div class="question-header">
                                <span class="question-number">Question <?php echo $qIndex + 1; ?></span>
                                <span class="question-points"><?php echo $question['points']; ?> point<?php echo $question['points'] > 1 ? 's' : ''; ?></span>
                            </div>
                            <p class="question-text"><?php echo e($question['question']); ?></p>
                            
                            <div class="options-list">
                                <label class="option-item">
                                    <input type="radio" name="q_<?php echo $question['id']; ?>" value="a" required>
                                    <span class="option-letter">A</span>
                                    <span class="option-text"><?php echo e($question['option_a']); ?></span>
                                </label>
                                <label class="option-item">
                                    <input type="radio" name="q_<?php echo $question['id']; ?>" value="b">
                                    <span class="option-letter">B</span>
                                    <span class="option-text"><?php echo e($question['option_b']); ?></span>
                                </label>
                                <?php if (!empty($question['option_c'])): ?>
                                <label class="option-item">
                                    <input type="radio" name="q_<?php echo $question['id']; ?>" value="c">
                                    <span class="option-letter">C</span>
                                    <span class="option-text"><?php echo e($question['option_c']); ?></span>
                                </label>
                                <?php endif; ?>
                                <?php if (!empty($question['option_d'])): ?>
                                <label class="option-item">
                                    <input type="radio" name="q_<?php echo $question['id']; ?>" value="d">
                                    <span class="option-letter">D</span>
                                    <span class="option-text"><?php echo e($question['option_d']); ?></span>
                                </label>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane"></i> Submit Quiz
                        </button>
                    </form>
                    <?php else: ?>
                    <div class="empty-quiz">
                        <i class="fas fa-question-circle"></i>
                        <p>This quiz has no questions yet.</p>
                    </div>
                    <?php endif; ?>
                <?php else: ?>
                <div class="lesson-description">
                    <?php echo nl2br(e($currentLesson['content'])); ?>
                </div>
                <?php endif; ?>

                <?php if (!empty($currentLesson['attachment'])): ?>
                <div class="lesson-resources">
                    <h3><i class="fas fa-download"></i> Downloadable Resources</h3>
                    <div class="resource-card">
                        <div class="resource-info">
                            <i class="fas fa-file-pdf"></i>
                            <span><?php echo e($currentLesson['attachment']); ?></span>
                        </div>
                        <a href="<?php echo url('uploads/lessons/' . $currentLesson['attachment']); ?>" class="btn btn-sm btn-primary" download>
                            Download <i class="fas fa-arrow-down"></i>
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="lesson-footer">
                <?php 
                $currentIndex = -1;
                foreach($lessons as $idx => $l) {
                    if($l['id'] == $currentLesson['id']) {
                        $currentIndex = $idx;
                        break;
                    }
                }
                ?>
                
                <div class="nav-buttons">
                    <?php if ($currentIndex > 0): ?>
                    <a href="<?php echo url('learn/' . $slug . '/' . $lessons[$currentIndex - 1]['id']); ?>" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i> Previous
                    </a>
                    <?php else: ?>
                    <div></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <input type="hidden" name="lesson_id" value="<?php echo $currentLesson['id']; ?>">
                        <button type="submit" name="complete_lesson" class="btn btn-primary">
                            Mark Complete & Next <i class="fas fa-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.learn-layout {
    display: flex;
    height: calc(100vh - var(--header-height));
    overflow: hidden;
}
.learn-sidebar {
    width: 350px;
    background: var(--white);
    border-right: 1px solid var(--gray-100);
    display: flex;
    flex-direction: column;
    z-index: 10;
}
.learn-sidebar-header {
    padding: var(--space-5);
    border-bottom: 1px solid var(--gray-100);
}
.back-link {
    font-size: var(--text-sm);
    color: var(--text-muted);
    margin-bottom: var(--space-3);
    display: inline-flex;
    align-items: center;
    gap: var(--space-2);
}
.course-title {
    font-size: var(--text-lg);
    margin-bottom: var(--space-3);
    line-height: 1.3;
}
.progress-container {
    background: var(--gray-50);
    padding: var(--space-3);
    border-radius: var(--radius-lg);
}
.progress-bar {
    height: 6px;
    background: var(--gray-200);
    border-radius: var(--radius-full);
    margin-bottom: var(--space-2);
    overflow: hidden;
}
.progress-fill {
    height: 100%;
    background: var(--success);
}
.progress-text {
    font-size: var(--text-xs);
    color: var(--text-muted);
    font-weight: 600;
}
.lesson-list {
    flex: 1;
    overflow-y: auto;
    padding: var(--space-2);
}
.lesson-item {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    padding: var(--space-3) var(--space-4);
    border-radius: var(--radius-lg);
    color: var(--text-secondary);
    transition: all var(--transition-fast);
    margin-bottom: 1px;
}
.lesson-item:hover {
    background: var(--gray-50);
}
.lesson-item.active {
    background: var(--primary-50);
    color: var(--primary);
}
.lesson-status {
    flex-shrink: 0;
    font-size: var(--text-lg);
}
.lesson-info {
    flex: 1;
    min-width: 0;
}
.lesson-number {
    display: block;
    font-size: var(--text-xs);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 2px;
}
.lesson-name {
    display: block;
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.learn-content {
    flex: 1;
    background: var(--gray-50);
    overflow-y: auto;
    padding: var(--space-8);
}
.lesson-container {
    max-width: 900px;
    margin: 0 auto;
    background: var(--white);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-sm);
    padding: var(--space-8);
    min-height: 100%;
}
.lesson-header {
    border-bottom: 1px solid var(--gray-100);
    padding-bottom: var(--space-6);
    margin-bottom: var(--space-6);
}
.video-container {
    position: relative;
    padding-bottom: 56.25%; /* 16:9 */
    height: 0;
    background: #000;
    border-radius: var(--radius-lg);
    overflow: hidden;
    margin-bottom: var(--space-6);
}
.video-item {
    margin-bottom: var(--space-4);
}
.video-caption {
    font-size: var(--text-xs);
    color: var(--text-muted);
    text-align: center;
    margin-top: var(--space-2);
}
.video-container iframe,
.video-container video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
.lesson-description {
    line-height: 1.8;
    color: var(--text-secondary);
    margin-bottom: var(--space-8);
    word-wrap: break-word;
    overflow-wrap: break-word;
    word-break: break-word;
}

/* Quiz Styles */
.quiz-form {
    max-width: 700px;
}
.quiz-question {
    background: var(--gray-50);
    border-radius: var(--radius-xl);
    padding: var(--space-6);
    margin-bottom: var(--space-6);
}
.quiz-question .question-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--space-3);
}
.quiz-question .question-number {
    background: linear-gradient(135deg, var(--primary), #7c3aed);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: var(--text-sm);
    font-weight: 700;
}
.quiz-question .question-points {
    background: #fef3c7;
    color: #92400e;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: var(--text-sm);
    font-weight: 600;
}
.question-text {
    font-size: var(--text-lg);
    font-weight: 500;
    margin-bottom: var(--space-4);
    color: var(--text-primary);
}
.options-list {
    display: flex;
    flex-direction: column;
    gap: var(--space-3);
}
.option-item {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    padding: var(--space-4);
    background: var(--white);
    border-radius: var(--radius-lg);
    border: 2px solid var(--gray-200);
    cursor: pointer;
    transition: all var(--transition-fast);
}
.option-item:hover {
    border-color: var(--primary);
}
.option-item input[type="radio"] {
    display: none;
}
.option-item input[type="radio"]:checked + .option-letter {
    background: var(--primary);
    color: white;
}
.option-item input[type="radio"]:checked ~ .option-text {
    font-weight: 600;
}
.option-letter {
    width: 32px;
    height: 32px;
    background: var(--gray-100);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: var(--text-sm);
    flex-shrink: 0;
    transition: all var(--transition-fast);
}
.option-text {
    flex: 1;
}
.empty-quiz {
    text-align: center;
    padding: var(--space-12);
    color: var(--text-muted);
}
.empty-quiz i {
    font-size: 48px;
    margin-bottom: var(--space-4);
    display: block;
}
/* Resources */
.lesson-resources {
    margin-top: var(--space-8);
    padding-top: var(--space-6);
    border-top: 1px dashed var(--gray-200);
}

.lesson-resources h3 {
    font-size: var(--text-lg);
    margin-bottom: var(--space-4);
}

.resource-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: var(--gray-50);
    padding: var(--space-4);
    border-radius: var(--radius-lg);
    border: 1px solid var(--gray-100);
    gap: var(--space-4);
}

.resource-info {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    color: var(--text-secondary);
    min-width: 0; /* Allow shrinking */
}

.resource-info span {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-size: var(--text-sm);
}

@media (max-width: 576px) {
    .resource-card {
        flex-direction: column;
        align-items: flex-start;
    }
    .resource-info {
        width: 100%;
    }
    .resource-info span {
        white-space: normal;
        word-break: break-all;
    }
    .resource-card .btn {
        width: 100%;
    }
}

.resource-info i {
    font-size: var(--text-xl);
    color: var(--primary);
}

.lesson-footer {
    border-top: 1px solid var(--gray-100);
    padding-top: var(--space-6);
}
.nav-buttons {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
@media (max-width: 1024px) {
    .learn-sidebar {
        position: fixed;
        left: -350px;
        top: 0;
        bottom: 0;
        transition: left 0.3s ease;
        box-shadow: var(--shadow-xl);
    }
    .learn-sidebar.open {
        left: 0;
    }
    .learn-content {
        padding: var(--space-4);
    }
    .lesson-container {
        padding: var(--space-4);
    }
    .lesson-header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: var(--space-4);
    }
    .lesson-header h1 {
        font-size: var(--text-xl);
        margin: 0;
    }
    .nav-buttons {
        flex-direction: column;
        gap: var(--space-3);
    }
    .nav-buttons .btn,
    .nav-buttons form,
    .nav-buttons form button {
        width: 100%;
    }
    .hide-desktop {
        display: flex;
    }
}

@media (min-width: 1025px) {
    .hide-desktop {
        display: none;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.learn-sidebar');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('open');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 1024 && !sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });
    }
});
</script>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
