<?php
/**
 * DADE Learn - Shared Admin Sidebar
 * Include this partial in all admin pages for consistent navigation
 * 
 * Required variables before including:
 * - $user (from Auth::user())
 * - $activePage (string: 'dashboard', 'analytics', 'users', 'courses', 'badges', 'certificates')
 */

$activePage = $activePage ?? '';
?>

<aside class="admin-sidebar">
    <div class="sidebar-header">
        <img src="<?php echo avatar($user, 60); ?>" alt="" class="admin-avatar">
        <div class="admin-info">
            <h4><?php echo e($user['username']); ?></h4>
            <span class="admin-role"><i class="fas fa-shield-alt"></i> Admin</span>
        </div>
    </div>
    
    <nav class="sidebar-menu">
        <a href="<?php echo url('admin'); ?>" class="menu-item <?php echo $activePage === 'dashboard' ? 'active' : ''; ?>">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="<?php echo url('admin/analytics'); ?>" class="menu-item <?php echo $activePage === 'analytics' ? 'active' : ''; ?>">
            <i class="fas fa-chart-line"></i> Analytics
        </a>
        <a href="<?php echo url('admin/users'); ?>" class="menu-item <?php echo $activePage === 'users' ? 'active' : ''; ?>">
            <i class="fas fa-users"></i> Users
        </a>
        <a href="<?php echo url('admin/courses'); ?>" class="menu-item <?php echo $activePage === 'courses' ? 'active' : ''; ?>">
            <i class="fas fa-book"></i> Courses
        </a>
        <a href="<?php echo url('admin/categories'); ?>" class="menu-item <?php echo $activePage === 'categories' ? 'active' : ''; ?>">
            <i class="fas fa-folder"></i> Categories
        </a>
        <a href="<?php echo url('admin/badges'); ?>" class="menu-item <?php echo $activePage === 'badges' ? 'active' : ''; ?>">
            <i class="fas fa-award"></i> Badges
        </a>
        <a href="<?php echo url('admin/certificates'); ?>" class="menu-item <?php echo $activePage === 'certificates' ? 'active' : ''; ?>">
            <i class="fas fa-certificate"></i> Certificates
        </a>
        <div class="sidebar-divider" style="height:1px; background:rgba(255,255,255,0.1); margin:12px 0;"></div>
        <a href="<?php echo url('dashboard'); ?>" class="menu-item">
            <i class="fas fa-home"></i> My Dashboard
        </a>
    </nav>
</aside>

<style>
/* Admin Sidebar Styles */
.admin-container {
    display: flex;
    min-height: calc(100vh - var(--header-height, 70px));
    background: #f8fafc;
}

.admin-sidebar {
    width: 260px;
    background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
    color: white;
    padding: 24px 16px;
    position: sticky;
    top: var(--header-height, 70px);
    height: calc(100vh - var(--header-height, 70px));
    overflow-y: auto;
    flex-shrink: 0;
}

.sidebar-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding-bottom: 20px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    margin-bottom: 20px;
}

.admin-avatar {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    object-fit: cover;
    border: 2px solid #10b981;
}

.admin-info h4 {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 2px;
}

.admin-role {
    font-size: 11px;
    color: #10b981;
    display: flex;
    align-items: center;
    gap: 4px;
}

.sidebar-menu {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.menu-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border-radius: 10px;
    color: #94a3b8;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s;
    text-decoration: none;
}

.menu-item:hover {
    background: rgba(255,255,255,0.05);
    color: white;
}

.menu-item.active {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.menu-item i {
    width: 20px;
    text-align: center;
}

/* Admin Main Content */
.admin-main {
    flex: 1;
    padding: 32px;
    min-width: 0;
    max-width: calc(100vw - 260px);
    overflow-x: auto;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
    flex-wrap: wrap;
    gap: 16px;
}

.page-header h1 {
    font-size: 28px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 4px;
}

.page-header h1 i {
    color: #10b981;
    margin-right: 10px;
}

.page-header p {
    color: #64748b;
    font-size: 14px;
}

/* Responsive */
@media (max-width: 1024px) {
    .admin-sidebar {
        display: none;
    }
    .admin-main {
        padding: 20px;
    }
}
</style>
