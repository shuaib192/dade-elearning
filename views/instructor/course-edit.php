<?php
/**
 * Dade Initiative - Create/Edit Course
 * Premium form for course management
 */

$db = getDB();
$user = Auth::user();
$userId = $user['id'];

// Check if editing
$courseId = $_GET['id'] ?? null;
$course = null;
$isEdit = false;

if ($courseId) {
    $stmt = $db->prepare("SELECT * FROM courses WHERE id = ? AND instructor_id = ?");
    $stmt->bind_param("ii", $courseId, $userId);
    $stmt->execute();
    $course = $stmt->get_result()->fetch_assoc();
    
    if (!$course) {
        Session::flash('error', 'Course not found or access denied.');
        Router::redirect('instructor/courses');
        return;
    }
    $isEdit = true;
}

$pageTitle = $isEdit ? 'Edit Course' : 'Create Course';

// Get categories
$categories = [];
$result = $db->query("SELECT * FROM categories ORDER BY name ASC");
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $categoryId = $_POST['category_id'] ?: null;
    $level = $_POST['level'] ?? 'beginner';
    $status = $_POST['status'] ?? 'draft';
    $price = floatval($_POST['price'] ?? 0);
    
    // Certificate settings
    $isCertificateCourse = isset($_POST['is_certificate_course']) ? 1 : 0;
    $passingGrade = intval($_POST['passing_grade'] ?? 70);
    
    // If certificate is requested, set status to pending for admin approval
    $certificateStatus = 'none';
    if ($isCertificateCourse) {
        // Keep approved status if already approved, otherwise set to pending
        if ($isEdit && ($course['certificate_status'] ?? '') === 'approved') {
            $certificateStatus = 'approved';
        } else {
            $certificateStatus = 'pending';
        }
    }
    
    $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $title));
    
    // Handle cover image upload
    $coverImage = $course['cover_image'] ?? null;
    $uploadError = null;
    
    if (!empty($_FILES['cover_image']['name'])) {
        if ($_FILES['cover_image']['error'] !== UPLOAD_ERR_OK) {
            $uploadError = 'Upload error code: ' . $_FILES['cover_image']['error'];
        } else {
            $file = $_FILES['cover_image'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            
            if (!in_array($ext, $allowed)) {
                $uploadError = 'Invalid file type. Allowed: ' . implode(', ', $allowed);
            } elseif ($file['size'] > 5 * 1024 * 1024) {
                $uploadError = 'File too large. Max 5MB allowed.';
            } else {
                $uploadDir = APP_ROOT . '/uploads/courses/';
                
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $filename = 'course_' . time() . '_' . uniqid() . '.' . $ext;
                $destination = $uploadDir . $filename;
                
                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    $coverImage = $filename;
                } else {
                    $uploadError = 'Failed to save file. Check folder permissions.';
                }
            }
        }
    }
    
    if ($uploadError) {
        Session::flash('error', $uploadError);
    }
    
    if ($isEdit) {
        $stmt = $db->prepare("
            UPDATE courses SET 
                title = ?, description = ?, slug = ?, category_id = ?, 
                level = ?, status = ?, cover_image = ?, price = ?,
                is_certificate_course = ?, certificate_status = ?, passing_grade = ?,
                updated_at = NOW()
            WHERE id = ? AND instructor_id = ?
        ");
        $stmt->bind_param("sssisssdssiii", $title, $description, $slug, $categoryId, 
                          $level, $status, $coverImage, $price, $isCertificateCourse, $certificateStatus, $passingGrade, $courseId, $userId);
        
        if ($stmt->execute()) {
            if ($isCertificateCourse && $certificateStatus === 'pending') {
                Session::flash('success', 'Course updated! Certificate approval is pending admin review.');
            } else {
                Session::flash('success', 'Course updated successfully!');
            }
            Router::redirect('instructor/courses');
        }
    } else {
        $stmt = $db->prepare("
            INSERT INTO courses (title, description, slug, category_id, instructor_id, 
                                 level, status, cover_image, price, is_certificate_course, certificate_status, passing_grade, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->bind_param("sssiisssdssi", $title, $description, $slug, $categoryId, $userId,
                          $level, $status, $coverImage, $price, $isCertificateCourse, $certificateStatus, $passingGrade);
        
        if ($stmt->execute()) {
            $newCourseId = $db->insert_id;
            if ($isCertificateCourse) {
                Session::flash('success', 'Course created! Certificate approval is pending admin review. Now add some lessons.');
            } else {
                Session::flash('success', 'Course created! Now add some lessons.');
            }
            Router::redirect('instructor/courses/' . $newCourseId . '/lessons');
        }
    }
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
            <a href="<?php echo url('instructor/courses/create'); ?>" class="sidebar-link <?php echo !$isEdit ? 'active' : ''; ?>">
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
                <h1><?php echo $isEdit ? '✏️ Edit Course' : '➕ Create New Course'; ?></h1>
                <p><?php echo $isEdit ? 'Update your course details.' : 'Fill in the details to create a new course.'; ?></p>
            </div>
            <a href="<?php echo url('instructor/courses'); ?>" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Back to Courses
            </a>
        </div>
        
        <?php if ($error = flash('error')): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> <?php echo e($error); ?>
        </div>
        <?php endif; ?>
        
        <div class="form-card">
            <form action="" method="POST" enctype="multipart/form-data">
                
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-info-circle"></i>
                        <h3>Basic Information</h3>
                    </div>
                    
                    <div class="form-group">
                        <label for="title" class="form-label">Course Title *</label>
                        <input type="text" id="title" name="title" class="form-input" 
                               value="<?php echo e($course['title'] ?? ''); ?>" required
                               placeholder="e.g., Introduction to Web Development">
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">Description *</label>
                        <textarea id="description" name="description" class="form-input" rows="5" required
                                  placeholder="What will students learn in this course?"><?php echo e($course['description'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="category_id" class="form-label">Category</label>
                            <select id="category_id" name="category_id" class="form-input">
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" 
                                        <?php echo ($course['category_id'] ?? '') == $cat['id'] ? 'selected' : ''; ?>>
                                    <?php echo e($cat['name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="level" class="form-label">Difficulty Level</label>
                            <select id="level" name="level" class="form-input">
                                <option value="beginner" <?php echo ($course['level'] ?? '') == 'beginner' ? 'selected' : ''; ?>>Beginner</option>
                                <option value="intermediate" <?php echo ($course['level'] ?? '') == 'intermediate' ? 'selected' : ''; ?>>Intermediate</option>
                                <option value="advanced" <?php echo ($course['level'] ?? '') == 'advanced' ? 'selected' : ''; ?>>Advanced</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-input">
                                <option value="draft" <?php echo ($course['status'] ?? 'draft') == 'draft' ? 'selected' : ''; ?>>Draft</option>
                                <option value="published" <?php echo ($course['status'] ?? '') == 'published' ? 'selected' : ''; ?>>Published</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="price" class="form-label">Course Price (<?php echo SITE_CURRENCY_SYMBOL; ?>)</label>
                            <div class="price-input-wrapper">
                                <span class="price-symbol"><?php echo SITE_CURRENCY_SYMBOL; ?></span>
                                <input type="number" id="price" name="price" class="form-input" 
                                       step="0.01" min="0" value="<?php echo e($course['price'] ?? '0.00'); ?>"
                                       placeholder="0.00">
                            </div>
                            <small class="form-hint">Set to 0.00 for a free course.</small>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-certificate"></i>
                        <h3>Certificate Settings</h3>
                    </div>
                    
                    <?php 
                    $certStatus = $course['certificate_status'] ?? 'none';
                    if ($certStatus === 'pending'): 
                    ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-clock"></i> Certificate approval is <strong>pending</strong> admin review.
                    </div>
                    <?php elseif ($certStatus === 'approved'): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> Certificate is <strong>approved</strong>! Students who pass will receive certificates.
                    </div>
                    <?php elseif ($certStatus === 'rejected'): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-times-circle"></i> Certificate request was <strong>rejected</strong>. Contact admin for details.
                    </div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_certificate_course" value="1" 
                                   <?php echo ($course['is_certificate_course'] ?? 0) ? 'checked' : ''; ?>
                                   onchange="toggleCertSettings(this.checked)">
                            <span class="checkbox-custom"></span>
                            <span>This is a certificate course</span>
                        </label>
                        <small class="form-hint">Students who pass the final quiz will receive a certificate. Requires admin approval.</small>
                    </div>
                    
                    <div id="certSettings" class="cert-settings" style="<?php echo ($course['is_certificate_course'] ?? 0) ? '' : 'display:none;'; ?>">
                        <div class="form-group">
                            <label for="passing_grade" class="form-label">Passing Grade (%)</label>
                            <input type="number" id="passing_grade" name="passing_grade" class="form-input" 
                                   min="1" max="100" value="<?php echo e($course['passing_grade'] ?? 70); ?>"
                                   placeholder="70">
                            <small class="form-hint">Students need this score or higher on the final quiz to earn a certificate.</small>
                        </div>
                    </div>
                
                <div class="form-section">
                    <div class="section-header">
                        <i class="fas fa-image"></i>
                        <h3>Cover Image</h3>
                    </div>
                    
                    <div class="form-group">
                        <div class="image-upload-area" id="uploadArea">
                            <?php if (!empty($course['cover_image'])): ?>
                            <img src="<?php echo url('uploads/courses/' . $course['cover_image']); ?>" alt="Current cover" class="preview-image" id="previewImage">
                            <?php else: ?>
                            <div class="upload-placeholder" id="uploadPlaceholder">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Click or drag to upload image</span>
                                <small>JPG, PNG or WebP (max 5MB)</small>
                            </div>
                            <?php endif; ?>
                            <input type="file" id="cover_image" name="cover_image" accept="image/*" class="file-input">
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i>
                        <?php echo $isEdit ? 'Update Course' : 'Create Course'; ?>
                    </button>
                    <?php if ($isEdit): ?>
                    <a href="<?php echo url('instructor/courses/' . $courseId . '/lessons'); ?>" class="btn btn-secondary btn-lg">
                        <i class="fas fa-list"></i> Manage Lessons
                    </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </main>
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

@media (max-width: 768px) {
    .dashboard-main { padding: var(--space-4); }
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--space-8);
    flex-wrap: wrap;
    gap: var(--space-4);
}

.welcome-text h1 { font-size: var(--text-2xl); margin-bottom: var(--space-1); }
.welcome-text p { color: var(--text-muted); }

/* Form Card */
.form-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: var(--space-8);
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    border: 1px solid var(--gray-100);
}

.form-section {
    margin-bottom: var(--space-8);
    padding-bottom: var(--space-6);
    border-bottom: 1px solid var(--gray-100);
}

.form-section:last-of-type { border-bottom: none; }

.section-header {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    margin-bottom: var(--space-5);
}

.section-header i {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, var(--primary-50), #ede9fe);
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
}

.section-header h3 { font-size: var(--text-lg); }

.form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--space-4);
}

@media (max-width: 768px) {
    .form-row { grid-template-columns: 1fr; }
}

.form-group { margin-bottom: var(--space-4); }

.form-label {
    display: block;
    font-weight: 600;
    margin-bottom: var(--space-2);
    color: var(--text-primary);
}

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

textarea.form-input { resize: vertical; min-height: 120px; }

/* Image Upload */
.image-upload-area {
    border: 2px dashed var(--gray-200);
    border-radius: var(--radius-xl);
    padding: var(--space-8);
    text-align: center;
    position: relative;
    transition: all var(--transition-fast);
    cursor: pointer;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.image-upload-area:hover {
    border-color: var(--primary);
    background: var(--primary-50);
}

.upload-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: var(--space-2);
    color: var(--text-muted);
}

.upload-placeholder i { font-size: 48px; color: var(--primary); }
.upload-placeholder small { font-size: var(--text-xs); }

.preview-image {
    max-width: 100%;
    max-height: 200px;
    border-radius: var(--radius-lg);
}

.file-input {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    cursor: pointer;
}

.form-actions {
    display: flex;
    gap: var(--space-4);
    margin-top: var(--space-6);
}

.btn-lg { padding: var(--space-4) var(--space-6); font-size: var(--text-base); }

.alert {
    padding: var(--space-4);
    border-radius: var(--radius-lg);
    margin-bottom: var(--space-6);
    display: flex;
    align-items: center;
    gap: var(--space-3);
}

.alert-error {
    background: #fee2e2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.alert-warning {
    background: #fef3c7;
    color: #d97706;
    border: 1px solid #fcd34d;
}

.alert-success {
    background: #d1fae5;
    color: #059669;
    border: 1px solid #a7f3d0;
}

/* Checkbox */
.checkbox-label {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    cursor: pointer;
    font-weight: 500;
}

.checkbox-label input[type="checkbox"] {
    width: 20px;
    height: 20px;
    accent-color: var(--primary);
}

.form-hint {
    display: block;
    color: var(--text-muted);
    font-size: var(--text-sm);
    margin-top: var(--space-1);
}

.cert-settings {
    margin-top: var(--space-4);
    padding: var(--space-4);
    background: var(--gray-50);
    border-radius: var(--radius-lg);
}

.price-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.price-symbol {
    position: absolute;
    left: var(--space-4);
    color: var(--text-muted);
    font-weight: 600;
}

.price-input-wrapper .form-input {
    padding-left: var(--space-10);
}
</style>

<script>
function toggleCertSettings(checked) {
    document.getElementById('certSettings').style.display = checked ? 'block' : 'none';
}

document.getElementById('cover_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            const area = document.getElementById('uploadArea');
            // Remove placeholder if exists
            const placeholder = document.getElementById('uploadPlaceholder');
            if (placeholder) {
                placeholder.remove();
            }
            // Check if preview image already exists
            let previewImg = document.getElementById('previewImage');
            if (!previewImg) {
                previewImg = document.createElement('img');
                previewImg.id = 'previewImage';
                previewImg.className = 'preview-image';
                area.insertBefore(previewImg, area.firstChild);
            }
            previewImg.src = event.target.result;
        };
        reader.readAsDataURL(file);
    }
});
</script>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
