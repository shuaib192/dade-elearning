<?php
/**
 * DADE Learn - Admin Category Management
 * Premium category management with CRUD
 */

$pageTitle = 'Category Management';
$db = getDB();
$user = Auth::user();

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add_category') {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
        
        if ($name) {
            $stmt = $db->prepare("INSERT INTO categories (name, slug, description, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("sss", $name, $slug, $description);
            if ($stmt->execute()) {
                Session::flash('success', 'Category added successfully!');
            }
        }
        Router::redirect('admin/categories');
        return;
    }
    
    if ($action === 'update_category') {
        $catId = intval($_POST['category_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
        
        if ($catId && $name) {
            $stmt = $db->prepare("UPDATE categories SET name = ?, slug = ?, description = ? WHERE id = ?");
            $stmt->bind_param("sssi", $name, $slug, $description, $catId);
            if ($stmt->execute()) {
                Session::flash('success', 'Category updated!');
            }
        }
        Router::redirect('admin/categories');
        return;
    }
    
    if ($action === 'delete_category') {
        $catId = intval($_POST['category_id'] ?? 0);
        if ($catId) {
            // Set courses to uncategorized
            $db->query("UPDATE courses SET category_id = NULL WHERE category_id = $catId");
            
            $stmt = $db->prepare("DELETE FROM categories WHERE id = ?");
            $stmt->bind_param("i", $catId);
            if ($stmt->execute()) {
                Session::flash('success', 'Category deleted.');
            }
        }
        Router::redirect('admin/categories');
        return;
    }
}

// Get all categories with course count
$categories = [];
$result = $db->query("
    SELECT c.*, 
           (SELECT COUNT(*) FROM courses WHERE category_id = c.id) as course_count
    FROM categories c
    ORDER BY c.name ASC
");
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
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
            <a href="<?php echo url('admin/users'); ?>" class="sidebar-link">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </a>
            <a href="<?php echo url('admin/courses'); ?>" class="sidebar-link">
                <i class="fas fa-book"></i>
                <span>Courses</span>
            </a>
            <a href="<?php echo url('admin/categories'); ?>" class="sidebar-link active">
                <i class="fas fa-folder"></i>
                <span>Categories</span>
            </a>
            <a href="<?php echo url('admin/analytics'); ?>" class="sidebar-link">
                <i class="fas fa-chart-line"></i>
                <span>Analytics</span>
            </a>
            <a href="<?php echo url('admin/badges'); ?>" class="sidebar-link">
                <i class="fas fa-award"></i>
                <span>Badges</span>
            </a>
            <div class="sidebar-divider"></div>
            <a href="<?php echo url('dashboard'); ?>" class="sidebar-link">
                <i class="fas fa-home"></i>
                <span>My Dashboard</span>
            </a>
        </nav>
    </aside>
    
    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="dashboard-header">
            <div class="welcome-text">
                <h1>📁 Category Management</h1>
                <p><?php echo count($categories); ?> categories</p>
            </div>
            <button type="button" class="btn btn-primary" onclick="openModal('addModal')">
                <i class="fas fa-plus"></i> Add Category
            </button>
        </div>
        
        <?php if ($success = flash('success')): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo e($success); ?>
        </div>
        <?php endif; ?>
        
        <?php if (empty($categories)): ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-folder-open"></i>
            </div>
            <h2>No categories yet</h2>
            <p>Create categories to organize your courses.</p>
            <button type="button" class="btn btn-primary btn-lg" onclick="openModal('addModal')">
                <i class="fas fa-plus"></i> Add First Category
            </button>
        </div>
        <?php else: ?>
        <div class="categories-grid">
            <?php foreach ($categories as $cat): ?>
            <div class="category-card">
                <div class="category-icon">
                    <i class="fas fa-folder"></i>
                </div>
                <div class="category-info">
                    <h3><?php echo e($cat['name']); ?></h3>
                    <?php if (!empty($cat['description'])): ?>
                    <p class="category-desc"><?php echo e(substr($cat['description'], 0, 80)); ?>...</p>
                    <?php endif; ?>
                    <span class="course-count">
                        <i class="fas fa-book"></i> <?php echo $cat['course_count']; ?> courses
                    </span>
                </div>
                <div class="category-actions">
                    <button type="button" class="btn btn-sm btn-ghost" onclick='editCategory(<?php echo json_encode($cat); ?>)' title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-ghost text-danger" title="Delete" onclick="showDeleteModal(<?php echo $cat['id']; ?>, '<?php echo e($cat['name']); ?>')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </main>
</div>

<!-- Add Category Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-plus-circle"></i> Add Category</h3>
            <button type="button" class="modal-close" onclick="closeModal('addModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="" method="POST">
            <input type="hidden" name="action" value="add_category">
            
            <div class="form-group">
                <label for="name" class="form-label">Category Name *</label>
                <input type="text" id="name" name="name" class="form-input" required placeholder="e.g., Web Development">
            </div>
            
            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" class="form-input" rows="3" placeholder="Brief description..."></textarea>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('addModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Category</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-edit"></i> Edit Category</h3>
            <button type="button" class="modal-close" onclick="closeModal('editModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="" method="POST">
            <input type="hidden" name="action" value="update_category">
            <input type="hidden" name="category_id" id="edit_category_id">
            
            <div class="form-group">
                <label for="edit_name" class="form-label">Category Name *</label>
                <input type="text" id="edit_name" name="name" class="form-input" required>
            </div>
            
            <div class="form-group">
                <label for="edit_description" class="form-label">Description</label>
                <textarea id="edit_description" name="description" class="form-input" rows="3"></textarea>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('editModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Category</button>
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
    border: 3px solid #dc2626;
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

.admin-badge {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
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
.sidebar-link.active { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #dc2626; }
.sidebar-link i { width: 20px; text-align: center; }
.sidebar-divider { height: 1px; background: var(--gray-100); margin: var(--space-4) 0; }

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

.welcome-text h1 { font-size: var(--text-2xl); margin-bottom: var(--space-1); }
.welcome-text p { color: var(--text-muted); }

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
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--space-6);
}

.empty-icon i { font-size: 48px; color: #dc2626; }
.empty-state h2 { margin-bottom: var(--space-2); }
.empty-state p { color: var(--text-muted); margin-bottom: var(--space-6); }

/* Categories Grid */
.categories-grid {
    display: flex;
    flex-direction: column;
    gap: var(--space-4);
}

.category-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: var(--space-5);
    display: flex;
    align-items: center;
    gap: var(--space-4);
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    border: 1px solid var(--gray-100);
    transition: all var(--transition-fast);
}

.category-card:hover {
    transform: translateX(4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
}

.category-icon {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: #dc2626;
}

.category-info { flex: 1; }

.category-info h3 {
    font-size: var(--text-lg);
    margin-bottom: var(--space-1);
}

.category-desc {
    font-size: var(--text-sm);
    color: var(--text-muted);
    margin-bottom: var(--space-2);
}

.course-count {
    font-size: var(--text-sm);
    color: var(--text-secondary);
}

.course-count i { margin-right: 4px; color: var(--primary); }

.category-actions { display: flex; gap: var(--space-2); }

.btn-ghost {
    background: transparent;
    color: var(--text-secondary);
    padding: var(--space-2);
    border-radius: var(--radius-md);
    border: none;
    cursor: pointer;
    transition: all var(--transition-fast);
}

.btn-ghost:hover { background: var(--gray-100); }
.text-danger { color: #dc2626; }

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
    max-width: 450px;
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

.modal-header h3 i { color: #dc2626; }

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
}

.modal-close:hover { background: var(--gray-100); }

.modal-content form { padding: var(--space-5); }

.form-group { margin-bottom: var(--space-4); }
.form-label { display: block; font-weight: 600; margin-bottom: var(--space-2); }

.form-input {
    width: 100%;
    padding: var(--space-3) var(--space-4);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-lg);
    font-size: var(--text-base);
}

.form-input:focus {
    outline: none;
    border-color: #dc2626;
    box-shadow: 0 0 0 3px #fee2e2;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: var(--space-3);
    padding-top: var(--space-4);
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
</style>

<script>
function openModal(id) {
    document.getElementById(id).classList.add('active');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('active');
}

function editCategory(cat) {
    document.getElementById('edit_category_id').value = cat.id;
    document.getElementById('edit_name').value = cat.name;
    document.getElementById('edit_description').value = cat.description || '';
    openModal('editModal');
}

document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) closeModal(this.id);
    });
});

function showDeleteModal(catId, catName) {
    document.getElementById('delete_category_id').value = catId;
    document.getElementById('delete_category_name').textContent = catName;
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
            <h3 style="margin-bottom: var(--space-2);">Delete Category</h3>
            <p style="color: var(--text-muted); margin-bottom: var(--space-6);">Delete "<strong id="delete_category_name"></strong>"? Courses will become uncategorized.</p>
            <div style="display: flex; gap: var(--space-3); justify-content: center;">
                <button type="button" class="btn btn-outline" onclick="closeModal('deleteConfirmModal')">Cancel</button>
                <form action="" method="POST" style="display:inline;">
                    <input type="hidden" name="action" value="delete_category">
                    <input type="hidden" name="category_id" id="delete_category_id" value="">
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
