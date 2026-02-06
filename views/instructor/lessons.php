<?php
/**
 * DADE Learn - Lesson Management
 * Premium lesson management with PDF/file upload and quiz builder
 */

$courseId = $_GET['id'] ?? null;
$db = getDB();
$user = Auth::user();
$userId = $user['id'];

// Get course
$stmt = $db->prepare("SELECT * FROM courses WHERE id = ? AND instructor_id = ?");
$stmt->bind_param("ii", $courseId, $userId);
$stmt->execute();
$course = $stmt->get_result()->fetch_assoc();

if (!$course) {
    Session::flash('error', 'Course not found or access denied.');
    Router::redirect('instructor/courses');
    return;
}

$pageTitle = 'Manage Lessons: ' . $course['title'];

// Create uploads directory
$uploadDir = APP_ROOT . '/uploads/lessons/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Handle lesson actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add_lesson') {
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $lessonType = $_POST['lesson_type'] ?? 'text';
        
        // Handle video file upload
        $videoPath = null;
        if (!empty($_FILES['video_file']['name'])) {
            $vFile = $_FILES['video_file'];
            $vExt = strtolower(pathinfo($vFile['name'], PATHINFO_EXTENSION));
            $vAllowed = ['mp4', 'webm', 'ogg'];
            
            // 100MB limit
            if (in_array($vExt, $vAllowed) && $vFile['size'] < 100 * 1024 * 1024) {
                $vUploadDir = APP_ROOT . '/uploads/lessons/videos/';
                if (!is_dir($vUploadDir)) mkdir($vUploadDir, 0777, true);
                
                $vFilename = 'video_' . time() . '_' . uniqid() . '.' . $vExt;
                if (move_uploaded_file($vFile['tmp_name'], $vUploadDir . $vFilename)) {
                    $videoPath = $vFilename;
                }
            }
        }
        
        // Handle file upload
        $attachment = null;
        if (!empty($_FILES['attachment']['name'])) {
            $file = $_FILES['attachment'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'txt', 'zip'];
            
            if (in_array($ext, $allowed) && $file['size'] < 50 * 1024 * 1024) {
                $filename = 'lesson_' . time() . '_' . uniqid() . '.' . $ext;
                if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
                    $attachment = $filename;
                }
            }
        }
        
        $stmt = $db->prepare("SELECT MAX(lesson_order) as max_order FROM lessons WHERE course_id = ?");
        $stmt->bind_param("i", $courseId);
        $stmt->execute();
        $maxOrder = $stmt->get_result()->fetch_assoc()['max_order'] ?? 0;
        $nextOrder = $maxOrder + 1;
        
        $stmt = $db->prepare("
            INSERT INTO lessons (course_id, title, content, video_url, video_path, attachment, lesson_type, lesson_order, order_index, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->bind_param("issssssii", $courseId, $title, $content, $videoUrl, $videoPath, $attachment, $lessonType, $nextOrder, $nextOrder);
        
        if ($stmt->execute()) {
            $newLessonId = $db->insert_id;
            if ($lessonType === 'quiz') {
                Session::flash('success', 'Quiz lesson created! Now add questions.');
                Router::redirect('instructor/courses/' . $courseId . '/lessons/' . $newLessonId . '/quiz');
            } else {
                Session::flash('success', 'Lesson added successfully!');
                Router::redirect('instructor/courses/' . $courseId . '/lessons');
            }
        }
        return;
    }
    
    if ($action === 'update_lesson') {
        $lessonId = $_POST['lesson_id'];
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $videoUrl = trim($_POST['video_url'] ?? '');
        $lessonType = $_POST['lesson_type'] ?? 'text';
        
        // Handle file upload
        $attachmentUpdate = "";
        $params = [$title, $content, $videoUrl, $lessonType];
        $types = "ssss";
        
        if (!empty($_FILES['attachment']['name'])) {
            $file = $_FILES['attachment'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'txt', 'zip'];
            
            if (in_array($ext, $allowed) && $file['size'] < 50 * 1024 * 1024) {
                $filename = 'lesson_' . time() . '_' . uniqid() . '.' . $ext;
                if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
                    $attachmentUpdate = ", attachment = ?";
                    $params[] = $filename;
                    $types .= "s";
                }
            }
        }
        
        // Handle video upload
        if (!empty($_FILES['video_file']['name'])) {
            $vFile = $_FILES['video_file'];
            $vExt = strtolower(pathinfo($vFile['name'], PATHINFO_EXTENSION));
            $vAllowed = ['mp4', 'webm', 'ogg'];
            
            if (in_array($vExt, $vAllowed) && $vFile['size'] < 100 * 1024 * 1024) {
                $vUploadDir = APP_ROOT . '/uploads/lessons/videos/';
                if (!is_dir($vUploadDir)) mkdir($vUploadDir, 0777, true);
                
                $vFilename = 'video_' . time() . '_' . uniqid() . '.' . $vExt;
                if (move_uploaded_file($vFile['tmp_name'], $vUploadDir . $vFilename)) {
                    $attachmentUpdate .= ", video_path = ?";
                    $params[] = $vFilename;
                    $types .= "s";
                }
            }
        }
        
        $params[] = $lessonId;
        $params[] = $courseId;
        $types .= "ii";
        
        $stmt = $db->prepare("
            UPDATE lessons SET title = ?, content = ?, video_url = ?, lesson_type = ?" . $attachmentUpdate . "
            WHERE id = ? AND course_id = ?
        ");
        $stmt->bind_param($types, ...$params);
        
        if ($stmt->execute()) {
            Session::flash('success', 'Lesson updated!');
        }
        Router::redirect('instructor/courses/' . $courseId . '/lessons');
        return;
    }
    
    if ($action === 'delete_lesson') {
        $lessonId = $_POST['lesson_id'];
        
        $stmt = $db->prepare("DELETE FROM lessons WHERE id = ? AND course_id = ?");
        $stmt->bind_param("ii", $lessonId, $courseId);
        
        if ($stmt->execute()) {
            Session::flash('success', 'Lesson deleted.');
        }
        Router::redirect('instructor/courses/' . $courseId . '/lessons');
        return;
    }
    
    if ($action === 'remove_attachment') {
        $lessonId = $_POST['lesson_id'];
        
        $stmt = $db->prepare("UPDATE lessons SET attachment = NULL WHERE id = ? AND course_id = ?");
        $stmt->bind_param("ii", $lessonId, $courseId);
        
        if ($stmt->execute()) {
            Session::flash('success', 'Attachment removed.');
        }
        Router::redirect('instructor/courses/' . $courseId . '/lessons');
        return;
    }
}

// Get all lessons with quiz question count
$lessons = [];
$stmt = $db->prepare("
    SELECT l.*, 
           (SELECT COUNT(*) FROM quiz_questions WHERE lesson_id = l.id) as question_count
    FROM lessons l 
    WHERE l.course_id = ? 
    ORDER BY l.lesson_order ASC
");
$stmt->bind_param("i", $courseId);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $lessons[] = $row;
}

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
                <h1>📝 Manage Lessons</h1>
                <p><?php echo e($course['title']); ?></p>
            </div>
            <div class="header-actions">
                <a href="<?php echo url('instructor/courses/' . $courseId . '/edit'); ?>" class="btn btn-outline">
                    <i class="fas fa-edit"></i> Edit Course
                </a>
                <button type="button" class="btn btn-primary" onclick="openModal('addModal')">
                    <i class="fas fa-plus"></i> Add Lesson
                </button>
            </div>
        </div>
        
        <?php if ($success = flash('success')): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo e($success); ?>
        </div>
        <?php endif; ?>
        
        <?php if (empty($lessons)): ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-list-alt"></i>
            </div>
            <h2>No lessons yet</h2>
            <p>Add your first lesson to start building your course content.</p>
            <button type="button" class="btn btn-primary btn-lg" onclick="openModal('addModal')">
                <i class="fas fa-plus"></i> Add First Lesson
            </button>
        </div>
        <?php else: ?>
        <div class="lessons-list">
            <?php foreach ($lessons as $index => $lesson): ?>
            <div class="lesson-card">
                <div class="lesson-drag">
                    <i class="fas fa-grip-vertical"></i>
                </div>
                <div class="lesson-number"><?php echo $index + 1; ?></div>
                <div class="lesson-info">
                    <h4><?php echo e($lesson['title']); ?></h4>
                    <div class="lesson-meta">
                        <span class="lesson-type type-<?php echo $lesson['lesson_type']; ?>">
                            <i class="fas fa-<?php 
                                echo $lesson['lesson_type'] === 'video' ? 'video' : 
                                    ($lesson['lesson_type'] === 'quiz' ? 'question-circle' : 'file-alt'); 
                            ?>"></i>
                            <?php echo ucfirst($lesson['lesson_type']); ?>
                        </span>
                        <?php if (!empty($lesson['video_url'])): ?>
                        <span class="has-video"><i class="fas fa-link"></i> Has video</span>
                        <?php endif; ?>
                        <?php if (!empty($lesson['attachment'])): ?>
                        <span class="has-file"><i class="fas fa-paperclip"></i> Has file</span>
                        <?php endif; ?>
                        <?php if ($lesson['lesson_type'] === 'quiz'): ?>
                        <span class="quiz-count"><i class="fas fa-list-ol"></i> <?php echo $lesson['question_count']; ?> questions</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="lesson-actions">
                    <?php if ($lesson['lesson_type'] === 'quiz'): ?>
                    <a href="<?php echo url('instructor/courses/' . $courseId . '/lessons/' . $lesson['id'] . '/quiz'); ?>" class="btn btn-sm btn-quiz" title="Manage Quiz">
                        <i class="fas fa-question-circle"></i>
                    </a>
                    <?php endif; ?>
                    <button type="button" class="btn btn-sm btn-ghost" onclick='editLesson(<?php echo json_encode($lesson); ?>)' title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-ghost text-danger" title="Delete" onclick="showDeleteModal(<?php echo $lesson['id']; ?>, '<?php echo e($lesson['title']); ?>')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </main>
</div>

<!-- Add Lesson Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-plus-circle"></i> Add New Lesson</h3>
            <button type="button" class="modal-close" onclick="closeModal('addModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add_lesson">
            
            <div class="form-group">
                <label for="title" class="form-label">Lesson Title *</label>
                <input type="text" id="title" name="title" class="form-input" required placeholder="e.g., Getting Started">
            </div>
            
            <div class="form-group">
                <label for="lesson_type" class="form-label">Lesson Type</label>
                <select id="lesson_type" name="lesson_type" class="form-input" onchange="toggleLessonFields(this.value)">
                    <option value="text">📄 Text/Article</option>
                    <option value="video">🎬 Video</option>
                    <option value="quiz">❓ Quiz</option>
                </select>
                <small class="form-hint">Quiz lessons let students test their knowledge with questions.</small>
            </div>
            
            <div id="videoFields">
                <div class="form-group">
                    <label for="video_url" class="form-label">Video URL (YouTube/Vimeo)</label>
                    <input type="url" id="video_url" name="video_url" class="form-input" placeholder="https://youtube.com/watch?v=...">
                </div>
                
                <div class="form-group">
                    <label for="video_file" class="form-label">
                        <i class="fas fa-video"></i> Upload Video File (Optional)
                    </label>
                    <input type="file" id="video_file" name="video_file" class="form-input" accept="video/mp4,video/webm,video/ogg">
                    <small class="form-hint">MP4, WebM or Ogg (Max 100MB). Recommended for paid courses.</small>
                </div>
            </div>
            
            <div id="contentFields">
                <div class="form-group">
                    <label for="content" class="form-label">Content</label>
                    <textarea id="content" name="content" class="form-input" rows="6" placeholder="Lesson content..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="attachment" class="form-label">
                        <i class="fas fa-paperclip"></i> Downloadable File (optional)
                    </label>
                    <input type="file" id="attachment" name="attachment" class="form-input" 
                           accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt,.zip">
                    <small class="form-hint">PDF, Word, PowerPoint, Excel, or ZIP (max 50MB)</small>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('addModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Lesson</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Lesson Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-edit"></i> Edit Lesson</h3>
            <button type="button" class="modal-close" onclick="closeModal('editModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="update_lesson">
            <input type="hidden" name="lesson_id" id="edit_lesson_id">
            
            <div class="form-group">
                <label for="edit_title" class="form-label">Lesson Title *</label>
                <input type="text" id="edit_title" name="title" class="form-input" required>
            </div>
            
            <div class="form-group">
                <label for="edit_lesson_type" class="form-label">Lesson Type</label>
                <select id="edit_lesson_type" name="lesson_type" class="form-input" onchange="toggleEditFields(this.value)">
                    <option value="text">📄 Text/Article</option>
                    <option value="video">🎬 Video</option>
                    <option value="quiz">❓ Quiz</option>
                </select>
            </div>
            
            <div id="edit_videoFields">
                <div class="form-group">
                    <label for="edit_video_url" class="form-label">Video URL (YouTube/Vimeo)</label>
                    <input type="url" id="edit_video_url" name="video_url" class="form-input">
                </div>
                
                <div id="current_video_file"></div>
                
                <div class="form-group">
                    <label for="edit_video_file" class="form-label">
                        <i class="fas fa-video"></i> Replace/Upload Video File
                    </label>
                    <input type="file" id="edit_video_file" name="video_file" class="form-input" accept="video/mp4,video/webm,video/ogg">
                    <small class="form-hint">Max 100MB. Only use if not using a URL above.</small>
                </div>
            </div>
            
            <div id="edit_contentFields">
                <div class="form-group">
                    <label for="edit_content" class="form-label">Content</label>
                    <textarea id="edit_content" name="content" class="form-input" rows="6"></textarea>
                </div>
                
                <div id="current_attachment"></div>
                
                <div class="form-group">
                    <label for="edit_attachment" class="form-label">
                        <i class="fas fa-paperclip"></i> Replace/Add File
                    </label>
                    <input type="file" id="edit_attachment" name="attachment" class="form-input" 
                           accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt,.zip">
                    <small class="form-hint">Upload new file to replace existing</small>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('editModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Lesson</button>
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
    max-width: 1000px;
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--space-8);
    flex-wrap: wrap;
    gap: var(--space-4);
}

.header-actions { display: flex; gap: var(--space-3); }

.welcome-text h1 { font-size: var(--text-2xl); margin-bottom: var(--space-1); }
.welcome-text p { color: var(--text-muted); }

/* Lessons List */
.lessons-list {
    display: flex;
    flex-direction: column;
    gap: var(--space-3);
}

.lesson-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: var(--space-4) var(--space-5);
    display: flex;
    align-items: center;
    gap: var(--space-4);
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    border: 1px solid var(--gray-100);
    transition: all var(--transition-fast);
}

.lesson-card:hover {
    transform: translateX(4px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.lesson-drag { color: var(--gray-300); cursor: grab; }

.lesson-number {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, var(--primary-50), #ede9fe);
    color: var(--primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: var(--text-sm);
    flex-shrink: 0;
}

.lesson-info { flex: 1; min-width: 0; }
.lesson-info h4 { margin-bottom: var(--space-1); font-size: var(--text-base); }

.lesson-meta {
    display: flex;
    gap: var(--space-3);
    font-size: var(--text-sm);
    color: var(--text-muted);
    flex-wrap: wrap;
}

.lesson-type {
    padding: 2px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
}

.type-video { background: #dbeafe; color: #2563eb; }
.type-text { background: var(--gray-100); color: var(--text-secondary); }
.type-quiz { background: #fef3c7; color: #d97706; }

.has-video, .has-file, .quiz-count { color: var(--text-muted); }
.has-file { color: var(--primary); }

.lesson-actions { display: flex; gap: var(--space-2); flex-shrink: 0; }

.btn-ghost { background: transparent; color: var(--text-secondary); padding: var(--space-2); }
.btn-ghost:hover { background: var(--gray-100); }
.text-danger { color: #dc2626; }

.btn-quiz {
    background: linear-gradient(135deg, #fef3c7, #fcd34d);
    color: #92400e;
}
.btn-quiz:hover { background: #fcd34d; }

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
    background: linear-gradient(135deg, var(--primary-50), #ede9fe);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--space-6);
}

.empty-icon i { font-size: 48px; color: var(--primary); }
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
    max-width: 550px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 25px 50px rgba(0,0,0,0.25);
}

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
.form-hint { display: block; color: var(--text-muted); font-size: var(--text-sm); margin-top: 4px; }

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

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: var(--space-3);
    padding-top: var(--space-4);
    margin-top: var(--space-4);
    border-top: 1px solid var(--gray-100);
}

.current-file {
    background: var(--gray-50);
    padding: var(--space-3);
    border-radius: var(--radius-md);
    margin-bottom: var(--space-3);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.current-file span { color: var(--primary); font-weight: 500; }

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
</style>

<script>
function openModal(id) {
    document.getElementById(id).classList.add('active');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('active');
}

function toggleLessonFields(type) {
    const videoFields = document.getElementById('videoFields');
    const contentFields = document.getElementById('contentFields');
    
    if (type === 'quiz') {
        videoFields.style.display = 'none';
        contentFields.style.display = 'none';
    } else if (type === 'video') {
        videoFields.style.display = 'block';
        contentFields.style.display = 'block';
    } else {
        videoFields.style.display = 'none';
        contentFields.style.display = 'block';
    }
}

function toggleEditFields(type) {
    const videoFields = document.getElementById('edit_videoFields');
    const contentFields = document.getElementById('edit_contentFields');
    
    if (type === 'quiz') {
        videoFields.style.display = 'none';
        contentFields.style.display = 'none';
    } else if (type === 'video') {
        videoFields.style.display = 'block';
        contentFields.style.display = 'block';
    } else {
        videoFields.style.display = 'none';
        contentFields.style.display = 'block';
    }
}

function editLesson(lesson) {
    document.getElementById('edit_lesson_id').value = lesson.id;
    document.getElementById('edit_title').value = lesson.title;
    document.getElementById('edit_lesson_type').value = lesson.lesson_type;
    document.getElementById('edit_video_url').value = lesson.video_url || '';
    document.getElementById('edit_content').value = lesson.content || '';
    
    toggleEditFields(lesson.lesson_type);
    
    // Show current attachment if exists
    const attachmentDiv = document.getElementById('current_attachment');
    if (lesson.attachment) {
        attachmentDiv.innerHTML = `
            <div class="current-file">
                <span><i class="fas fa-file"></i> ${lesson.attachment}</span>
                <a href="<?php echo url('uploads/lessons/'); ?>${lesson.attachment}" target="_blank" class="btn btn-sm btn-outline">View</a>
            </div>
        `;
    } else {
        attachmentDiv.innerHTML = '';
    }
    
    // Show current video file if exists
    const videoDiv = document.getElementById('current_video_file');
    if (lesson.video_path) {
        videoDiv.innerHTML = `
            <div class="current-file">
                <span><i class="fas fa-video"></i> ${lesson.video_path}</span>
                <span class="badge badge-sm badge-success">Uploaded</span>
            </div>
        `;
    } else {
        videoDiv.innerHTML = '';
    }
    
    openModal('editModal');
}

// Close modal on outside click
document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) closeModal(this.id);
    });
});

// Delete modal functions
function showDeleteModal(lessonId, lessonTitle) {
    document.getElementById('delete_lesson_id').value = lessonId;
    document.getElementById('delete_lesson_title').textContent = lessonTitle;
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
            <h3 style="margin-bottom: var(--space-2);">Delete Lesson</h3>
            <p style="color: var(--text-muted); margin-bottom: var(--space-6);">Delete "<strong id="delete_lesson_title"></strong>"? This cannot be undone.</p>
            <div style="display: flex; gap: var(--space-3); justify-content: center;">
                <button type="button" class="btn btn-outline" onclick="closeModal('deleteConfirmModal')">Cancel</button>
                <form action="" method="POST" style="display:inline;">
                    <input type="hidden" name="action" value="delete_lesson">
                    <input type="hidden" name="lesson_id" id="delete_lesson_id" value="">
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
