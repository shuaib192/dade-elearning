<?php
/**
 * DADE Learn - Quiz Builder
 * Create and manage quiz questions for a lesson
 */

$courseId = $_GET['courseId'] ?? null;
$lessonId = $_GET['lessonId'] ?? null;
$db = getDB();
$user = Auth::user();
$userId = $user['id'];

// Verify lesson belongs to instructor's course
$stmt = $db->prepare("
    SELECT l.*, c.title as course_title, c.instructor_id 
    FROM lessons l 
    JOIN courses c ON l.course_id = c.id 
    WHERE l.id = ? AND c.id = ? AND c.instructor_id = ?
");
$stmt->bind_param("iii", $lessonId, $courseId, $userId);
$stmt->execute();
$lesson = $stmt->get_result()->fetch_assoc();

if (!$lesson) {
    Session::flash('error', 'Lesson not found or access denied.');
    Router::redirect('instructor/courses');
    return;
}

$pageTitle = 'Quiz Builder: ' . $lesson['title'];

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add_question') {
        $question = trim($_POST['question'] ?? '');
        $optionA = trim($_POST['option_a'] ?? '');
        $optionB = trim($_POST['option_b'] ?? '');
        $optionC = trim($_POST['option_c'] ?? '');
        $optionD = trim($_POST['option_d'] ?? '');
        $correctAnswer = $_POST['correct_answer'] ?? 'a';
        $points = intval($_POST['points'] ?? 1);
        
        $stmt = $db->prepare("SELECT MAX(question_order) as max_order FROM quiz_questions WHERE lesson_id = ?");
        $stmt->bind_param("i", $lessonId);
        $stmt->execute();
        $maxOrder = $stmt->get_result()->fetch_assoc()['max_order'] ?? 0;
        $nextOrder = $maxOrder + 1;
        
        $stmt = $db->prepare("
            INSERT INTO quiz_questions (lesson_id, question, option_a, option_b, option_c, option_d, correct_answer, points, question_order)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("issssssii", $lessonId, $question, $optionA, $optionB, $optionC, $optionD, $correctAnswer, $points, $nextOrder);
        
        if ($stmt->execute()) {
            Session::flash('success', 'Question added!');
        }
        Router::redirect('instructor/courses/' . $courseId . '/lessons/' . $lessonId . '/quiz');
        return;
    }
    
    if ($action === 'update_question') {
        $questionId = intval($_POST['question_id']);
        $question = trim($_POST['question'] ?? '');
        $optionA = trim($_POST['option_a'] ?? '');
        $optionB = trim($_POST['option_b'] ?? '');
        $optionC = trim($_POST['option_c'] ?? '');
        $optionD = trim($_POST['option_d'] ?? '');
        $correctAnswer = $_POST['correct_answer'] ?? 'a';
        $points = intval($_POST['points'] ?? 1);
        
        $stmt = $db->prepare("
            UPDATE quiz_questions SET question = ?, option_a = ?, option_b = ?, option_c = ?, option_d = ?, correct_answer = ?, points = ?
            WHERE id = ? AND lesson_id = ?
        ");
        $stmt->bind_param("ssssssiii", $question, $optionA, $optionB, $optionC, $optionD, $correctAnswer, $points, $questionId, $lessonId);
        
        if ($stmt->execute()) {
            Session::flash('success', 'Question updated!');
        }
        Router::redirect('instructor/courses/' . $courseId . '/lessons/' . $lessonId . '/quiz');
        return;
    }
    
    if ($action === 'delete_question') {
        $questionId = intval($_POST['question_id']);
        
        $stmt = $db->prepare("DELETE FROM quiz_questions WHERE id = ? AND lesson_id = ?");
        $stmt->bind_param("ii", $questionId, $lessonId);
        
        if ($stmt->execute()) {
            Session::flash('success', 'Question deleted.');
        }
        Router::redirect('instructor/courses/' . $courseId . '/lessons/' . $lessonId . '/quiz');
        return;
    }
}

// Get all questions
$questions = [];
$stmt = $db->prepare("SELECT * FROM quiz_questions WHERE lesson_id = ? ORDER BY question_order ASC");
$stmt->bind_param("i", $lessonId);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $questions[] = $row;
}

$totalPoints = array_sum(array_column($questions, 'points'));

require_once APP_ROOT . '/views/layouts/header.php';
?>

<div class="dashboard-layout">
    <!-- Sidebar -->
    <aside class="dashboard-sidebar">
        <div class="sidebar-user">
            <img src="<?php echo avatar($user, 80); ?>" alt="<?php echo e($user['username']); ?>" class="sidebar-avatar">
            <h4 class="sidebar-username"><?php echo e($user['username']); ?></h4>
            <span class="sidebar-role instructor-badge">
                <i class="fas fa-chalkboard-teacher"></i> Instructor
            </span>
        </div>
        
        <nav class="sidebar-nav">
            <a href="<?php echo url('instructor'); ?>" class="sidebar-link">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>
            <a href="<?php echo url('instructor/courses'); ?>" class="sidebar-link">
                <i class="fas fa-book"></i>
                <span>My Courses</span>
            </a>
            <a href="<?php echo url('instructor/courses/create'); ?>" class="sidebar-link">
                <i class="fas fa-plus-circle"></i>
                <span>Create Course</span>
            </a>
            <a href="<?php echo url('instructor/students'); ?>" class="sidebar-link">
                <i class="fas fa-users"></i>
                <span>My Students</span>
            </a>
        </nav>
    </aside>
    
    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="dashboard-header">
            <div class="welcome-text">
                <h1>❓ Quiz Builder</h1>
                <p><?php echo e($lesson['title']); ?> • <?php echo e($lesson['course_title']); ?></p>
            </div>
            <div class="header-actions">
                <a href="<?php echo url('instructor/courses/' . $courseId . '/lessons'); ?>" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Back to Lessons
                </a>
                <button type="button" class="btn btn-primary" onclick="openModal('addModal')">
                    <i class="fas fa-plus"></i> Add Question
                </button>
            </div>
        </div>
        
        <?php if ($success = flash('success')): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo e($success); ?>
        </div>
        <?php endif; ?>
        
        <!-- Quiz Summary -->
        <div class="quiz-summary">
            <div class="summary-item">
                <i class="fas fa-list-ol"></i>
                <span><strong><?php echo count($questions); ?></strong> Questions</span>
            </div>
            <div class="summary-item">
                <i class="fas fa-star"></i>
                <span><strong><?php echo $totalPoints; ?></strong> Total Points</span>
            </div>
        </div>
        
        <?php if (empty($questions)): ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-question-circle"></i>
            </div>
            <h2>No questions yet</h2>
            <p>Add your first quiz question to get started.</p>
            <button type="button" class="btn btn-primary btn-lg" onclick="openModal('addModal')">
                <i class="fas fa-plus"></i> Add First Question
            </button>
        </div>
        <?php else: ?>
        <div class="questions-list">
            <?php foreach ($questions as $index => $q): ?>
            <div class="question-card">
                <div class="question-header">
                    <span class="question-number">Q<?php echo $index + 1; ?></span>
                    <span class="question-points"><?php echo $q['points']; ?> pt<?php echo $q['points'] > 1 ? 's' : ''; ?></span>
                </div>
                <div class="question-text">
                    <?php echo e($q['question']); ?>
                </div>
                <div class="options-grid">
                    <div class="option <?php echo $q['correct_answer'] === 'a' ? 'correct' : ''; ?>">
                        <span class="option-letter">A</span>
                        <span><?php echo e($q['option_a']); ?></span>
                        <?php if ($q['correct_answer'] === 'a'): ?><i class="fas fa-check"></i><?php endif; ?>
                    </div>
                    <div class="option <?php echo $q['correct_answer'] === 'b' ? 'correct' : ''; ?>">
                        <span class="option-letter">B</span>
                        <span><?php echo e($q['option_b']); ?></span>
                        <?php if ($q['correct_answer'] === 'b'): ?><i class="fas fa-check"></i><?php endif; ?>
                    </div>
                    <?php if (!empty($q['option_c'])): ?>
                    <div class="option <?php echo $q['correct_answer'] === 'c' ? 'correct' : ''; ?>">
                        <span class="option-letter">C</span>
                        <span><?php echo e($q['option_c']); ?></span>
                        <?php if ($q['correct_answer'] === 'c'): ?><i class="fas fa-check"></i><?php endif; ?>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($q['option_d'])): ?>
                    <div class="option <?php echo $q['correct_answer'] === 'd' ? 'correct' : ''; ?>">
                        <span class="option-letter">D</span>
                        <span><?php echo e($q['option_d']); ?></span>
                        <?php if ($q['correct_answer'] === 'd'): ?><i class="fas fa-check"></i><?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="question-actions">
                    <button type="button" class="btn btn-sm btn-ghost" onclick='editQuestion(<?php echo json_encode($q); ?>)'>
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button type="button" class="btn btn-sm btn-ghost text-danger" onclick="showDeleteModal(<?php echo $q['id']; ?>)">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </main>
</div>

<!-- Add Question Modal -->
<div id="addModal" class="modal">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h3><i class="fas fa-plus-circle"></i> Add Question</h3>
            <button type="button" class="modal-close" onclick="closeModal('addModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="" method="POST">
            <input type="hidden" name="action" value="add_question">
            
            <div class="form-group">
                <label class="form-label">Question *</label>
                <textarea name="question" class="form-input" rows="3" required placeholder="Enter your question..."></textarea>
            </div>
            
            <div class="options-form">
                <div class="form-group">
                    <label class="form-label">Option A *</label>
                    <input type="text" name="option_a" class="form-input" required placeholder="First option">
                </div>
                <div class="form-group">
                    <label class="form-label">Option B *</label>
                    <input type="text" name="option_b" class="form-input" required placeholder="Second option">
                </div>
                <div class="form-group">
                    <label class="form-label">Option C (optional)</label>
                    <input type="text" name="option_c" class="form-input" placeholder="Third option">
                </div>
                <div class="form-group">
                    <label class="form-label">Option D (optional)</label>
                    <input type="text" name="option_d" class="form-input" placeholder="Fourth option">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Correct Answer *</label>
                    <select name="correct_answer" class="form-input" required>
                        <option value="a">A</option>
                        <option value="b">B</option>
                        <option value="c">C</option>
                        <option value="d">D</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Points</label>
                    <input type="number" name="points" class="form-input" value="1" min="1" max="10">
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('addModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Question</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Question Modal -->
<div id="editModal" class="modal">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h3><i class="fas fa-edit"></i> Edit Question</h3>
            <button type="button" class="modal-close" onclick="closeModal('editModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="" method="POST">
            <input type="hidden" name="action" value="update_question">
            <input type="hidden" name="question_id" id="edit_question_id">
            
            <div class="form-group">
                <label class="form-label">Question *</label>
                <textarea name="question" id="edit_question" class="form-input" rows="3" required></textarea>
            </div>
            
            <div class="options-form">
                <div class="form-group">
                    <label class="form-label">Option A *</label>
                    <input type="text" name="option_a" id="edit_option_a" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Option B *</label>
                    <input type="text" name="option_b" id="edit_option_b" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Option C (optional)</label>
                    <input type="text" name="option_c" id="edit_option_c" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Option D (optional)</label>
                    <input type="text" name="option_d" id="edit_option_d" class="form-input">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Correct Answer *</label>
                    <select name="correct_answer" id="edit_correct_answer" class="form-input" required>
                        <option value="a">A</option>
                        <option value="b">B</option>
                        <option value="c">C</option>
                        <option value="d">D</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Points</label>
                    <input type="number" name="points" id="edit_points" class="form-input" value="1" min="1" max="10">
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('editModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Question</button>
            </div>
        </form>
    </div>
</div>

<style>
/* Dashboard Layout */
.dashboard-layout {
    display: flex;
    min-height: calc(100vh - var(--header-height));
    background: var(--gray-50);
}

.dashboard-sidebar {
    width: 280px;
    background: var(--white);
    border-right: 1px solid var(--gray-100);
    padding: var(--space-6);
    position: sticky;
    top: var(--header-height);
    height: calc(100vh - var(--header-height));
    overflow-y: auto;
}

@media (max-width: 1024px) {
    .dashboard-sidebar { display: none; }
}

.sidebar-user {
    text-align: center;
    padding-bottom: var(--space-6);
    border-bottom: 1px solid var(--gray-100);
    margin-bottom: var(--space-6);
}

.sidebar-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    margin-bottom: var(--space-3);
    border: 3px solid var(--primary);
    object-fit: cover;
}

.sidebar-username { margin-bottom: var(--space-2); font-size: var(--text-lg); }

.sidebar-role {
    display: inline-flex;
    align-items: center;
    gap: var(--space-2);
    font-size: var(--text-sm);
    padding: 6px 14px;
    border-radius: 20px;
}

.instructor-badge {
    background: linear-gradient(135deg, var(--primary), #7c3aed);
    color: white;
}

.sidebar-nav { display: flex; flex-direction: column; gap: var(--space-1); }

.sidebar-link {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    padding: var(--space-3) var(--space-4);
    border-radius: var(--radius-lg);
    color: var(--text-secondary);
    font-weight: 500;
    transition: all var(--transition-fast);
}

.sidebar-link:hover { background: var(--gray-50); color: var(--text-primary); }
.sidebar-link.active { background: linear-gradient(135deg, var(--primary-50), #ede9fe); color: var(--primary); }
.sidebar-link i { width: 20px; text-align: center; }

.dashboard-main {
    flex: 1;
    padding: var(--space-8);
    max-width: 900px;
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--space-6);
    flex-wrap: wrap;
    gap: var(--space-4);
}

.header-actions { display: flex; gap: var(--space-3); }

.welcome-text h1 { font-size: var(--text-2xl); margin-bottom: var(--space-1); }
.welcome-text p { color: var(--text-muted); }

/* Quiz Summary */
.quiz-summary {
    display: flex;
    gap: var(--space-6);
    margin-bottom: var(--space-6);
    padding: var(--space-4);
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    border-radius: var(--radius-xl);
}

.summary-item {
    display: flex;
    align-items: center;
    gap: var(--space-2);
    color: #92400e;
}

.summary-item i { font-size: 20px; }

/* Questions List */
.questions-list {
    display: flex;
    flex-direction: column;
    gap: var(--space-4);
}

.question-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: var(--space-5);
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    border: 1px solid var(--gray-100);
}

.question-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--space-3);
}

.question-number {
    background: linear-gradient(135deg, var(--primary), #7c3aed);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: var(--text-sm);
    font-weight: 700;
}

.question-points {
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

.options-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--space-3);
    margin-bottom: var(--space-4);
}

@media (max-width: 600px) {
    .options-grid { grid-template-columns: 1fr; }
}

.option {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    padding: var(--space-3);
    background: var(--gray-50);
    border-radius: var(--radius-lg);
    font-size: var(--text-sm);
}

.option.correct {
    background: #d1fae5;
    border: 1px solid #a7f3d0;
}

.option.correct i {
    color: #059669;
    margin-left: auto;
}

.option-letter {
    width: 28px;
    height: 28px;
    background: var(--white);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: var(--text-sm);
    flex-shrink: 0;
}

.option.correct .option-letter {
    background: #059669;
    color: white;
}

.question-actions {
    display: flex;
    gap: var(--space-2);
    padding-top: var(--space-3);
    border-top: 1px solid var(--gray-100);
}

/* Empty State */
.empty-state {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: var(--space-16);
    text-align: center;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    border: 1px solid var(--gray-100);
}

.empty-icon {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--space-6);
}

.empty-icon i { font-size: 48px; color: #d97706; }
.empty-state h2 { margin-bottom: var(--space-2); }
.empty-state p { color: var(--text-muted); margin-bottom: var(--space-6); }

/* Modal */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: var(--space-4);
}

.modal.active { display: flex; }

.modal-content {
    background: var(--white);
    border-radius: var(--radius-xl);
    width: 100%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 25px 50px rgba(0,0,0,0.25);
}

.modal-lg { max-width: 650px; }

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--space-5);
    border-bottom: 1px solid var(--gray-100);
}

.modal-header h3 {
    display: flex;
    align-items: center;
    gap: var(--space-2);
    margin: 0;
}

.modal-header h3 i { color: var(--primary); }

.modal-close {
    background: none;
    border: none;
    font-size: 20px;
    color: var(--text-muted);
    cursor: pointer;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all var(--transition-fast);
}

.modal-close:hover { background: var(--gray-100); color: var(--text-primary); }

.modal-content form { padding: var(--space-5); }

.form-group { margin-bottom: var(--space-4); }
.form-label { display: block; font-weight: 600; margin-bottom: var(--space-2); }

.form-input {
    width: 100%;
    padding: var(--space-3) var(--space-4);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-lg);
    font-size: var(--text-base);
    transition: all var(--transition-fast);
}

.form-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-50);
}

.options-form {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--space-4);
}

@media (max-width: 600px) {
    .options-form { grid-template-columns: 1fr; }
}

.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--space-4);
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: var(--space-3);
    padding-top: var(--space-4);
    margin-top: var(--space-4);
    border-top: 1px solid var(--gray-100);
}

.alert {
    padding: var(--space-4);
    border-radius: var(--radius-lg);
    margin-bottom: var(--space-6);
    display: flex;
    align-items: center;
    gap: var(--space-3);
}

.alert-success {
    background: #d1fae5;
    color: #059669;
    border: 1px solid #a7f3d0;
}

.btn-ghost { background: transparent; color: var(--text-secondary); }
.btn-ghost:hover { background: var(--gray-100); }
.text-danger { color: #dc2626; }
</style>

<script>
function openModal(id) {
    document.getElementById(id).classList.add('active');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('active');
}

function editQuestion(q) {
    document.getElementById('edit_question_id').value = q.id;
    document.getElementById('edit_question').value = q.question;
    document.getElementById('edit_option_a').value = q.option_a;
    document.getElementById('edit_option_b').value = q.option_b;
    document.getElementById('edit_option_c').value = q.option_c || '';
    document.getElementById('edit_option_d').value = q.option_d || '';
    document.getElementById('edit_correct_answer').value = q.correct_answer;
    document.getElementById('edit_points').value = q.points;
    openModal('editModal');
}

// Close modal on outside click
document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) closeModal(this.id);
    });
});

// Delete question modal
function showDeleteModal(questionId) {
    document.getElementById('delete_question_id').value = questionId;
    openModal('deleteConfirmModal');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal.active').forEach(m => closeModal(m.id));
    }
});
</script>

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmModal" class="modal">
    <div class="modal-content" style="max-width: 380px; text-align: center;">
        <div style="padding: var(--space-8);">
            <div style="width: 64px; height: 64px; background: #fee2e2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-4);">
                <i class="fas fa-exclamation-triangle" style="font-size: 28px; color: #dc2626;"></i>
            </div>
            <h3 style="margin-bottom: var(--space-2);">Delete Question</h3>
            <p style="color: var(--text-muted); margin-bottom: var(--space-6);">Are you sure you want to delete this question?</p>
            <div style="display: flex; gap: var(--space-3); justify-content: center;">
                <button type="button" class="btn btn-outline" onclick="closeModal('deleteConfirmModal')">Cancel</button>
                <form action="" method="POST" style="display:inline;">
                    <input type="hidden" name="action" value="delete_question">
                    <input type="hidden" name="question_id" id="delete_question_id" value="">
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
