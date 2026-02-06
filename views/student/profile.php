<?php
/**
 * DADE Learn - Profile Page
 * User profile settings and account management
 */

$pageTitle = 'My Profile';
$db = getDB();
$user = Auth::user();
$userId = $user['id'];

// Get fresh user data
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$userData = $stmt->get_result()->fetch_assoc();

require_once APP_ROOT . '/views/layouts/header.php';
?>

<div class="dashboard-layout">
    <!-- Sidebar -->
    <aside class="dashboard-sidebar">
        <div class="sidebar-user">
            <img src="<?php echo avatar($userData, 80); ?>" alt="<?php echo e($userData['username']); ?>" class="sidebar-avatar">
            <h4 class="sidebar-username"><?php echo e($userData['username']); ?></h4>
            <span class="sidebar-role"><?php echo ucfirst(str_replace('_', ' ', $userData['role'])); ?></span>
        </div>
        
        <nav class="sidebar-nav">
            <a href="<?php echo url('dashboard'); ?>" class="sidebar-link">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="<?php echo url('dashboard/courses'); ?>" class="sidebar-link">
                <i class="fas fa-book-open"></i>
                <span>My Courses</span>
            </a>
            <a href="<?php echo url('dashboard/certificates'); ?>" class="sidebar-link">
                <i class="fas fa-certificate"></i>
                <span>Certificates</span>
            </a>
            <a href="<?php echo url('dashboard/profile'); ?>" class="sidebar-link active">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>
        </nav>
    </aside>
    
    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="dashboard-header">
            <div class="welcome-text">
                <h1>My Profile</h1>
                <p>Manage your account settings and preferences.</p>
            </div>
        </div>
        
        <!-- Flash Messages -->
        <?php if ($success = flash('success')): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle alert-icon"></i>
            <div class="alert-content"><?php echo e($success); ?></div>
        </div>
        <?php endif; ?>
        
        <?php if ($error = flash('error')): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle alert-icon"></i>
            <div class="alert-content"><?php echo e($error); ?></div>
        </div>
        <?php endif; ?>
        
        <div class="profile-grid">
            <!-- Profile Card -->
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar-wrapper">
                        <img src="<?php echo avatar($userData, 120); ?>" alt="<?php echo e($userData['username']); ?>" class="profile-avatar-lg">
                        <label for="avatar-upload" class="avatar-edit-btn">
                            <i class="fas fa-camera"></i>
                        </label>
                    </div>
                    <h2><?php echo e($userData['username']); ?></h2>
                    <p class="profile-email"><?php echo e($userData['email']); ?></p>
                    <span class="profile-badge">
                        <i class="fas fa-<?php echo $userData['email_verified'] ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                        <?php echo $userData['email_verified'] ? 'Verified' : 'Not Verified'; ?>
                    </span>
                </div>
                
                <div class="profile-stats">
                    <div class="profile-stat">
                        <span class="profile-stat-value">
                            <?php 
                            $stmt = $db->prepare("SELECT COUNT(*) as c FROM enrollments WHERE user_id = ?");
                            $stmt->bind_param("i", $userId);
                            $stmt->execute();
                            echo $stmt->get_result()->fetch_assoc()['c'];
                            ?>
                        </span>
                        <span class="profile-stat-label">Courses</span>
                    </div>
                    <div class="profile-stat">
                        <span class="profile-stat-value">
                            <?php 
                            $stmt = $db->prepare("SELECT COUNT(*) as c FROM certificates WHERE user_id = ?");
                            $stmt->bind_param("i", $userId);
                            $stmt->execute();
                            echo $stmt->get_result()->fetch_assoc()['c'];
                            ?>
                        </span>
                        <span class="profile-stat-label">Certificates</span>
                    </div>
                    <div class="profile-stat">
                        <span class="profile-stat-value"><?php echo date('M Y', strtotime($userData['created_at'])); ?></span>
                        <span class="profile-stat-label">Member Since</span>
                    </div>
                </div>
            </div>
            
            <!-- Profile Form -->
            <div class="profile-form-card">
                <h3>Personal Information</h3>
                
                <form action="<?php echo url('dashboard/profile'); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <input type="file" id="avatar-upload" name="avatar" accept="image/*" style="display: none;">
                    
                    <div class="form-group">
                        <label for="username" class="form-label">Full Name</label>
                        <input type="text" id="username" name="username" class="form-input" 
                               value="<?php echo e($userData['username']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="form-input" 
                               value="<?php echo e($userData['email']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="bio" class="form-label">Bio</label>
                        <textarea id="bio" name="bio" class="form-input" rows="4" 
                                  placeholder="Tell us about yourself..."><?php echo e($userData['bio'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="action" value="update_profile" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Change Password -->
            <div class="profile-form-card">
                <h3>Change Password</h3>
                
                <form action="<?php echo url('dashboard/profile'); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    
                    <div class="form-group">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" id="current_password" name="current_password" class="form-input" 
                               placeholder="Enter current password">
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" id="new_password" name="new_password" class="form-input" 
                               placeholder="Enter new password" minlength="8">
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-input" 
                               placeholder="Confirm new password">
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="action" value="change_password" class="btn btn-secondary">
                            <i class="fas fa-lock"></i>
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<style>
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
    display: none;
}
@media (min-width: 1024px) {
    .dashboard-sidebar {
        display: block;
    }
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
}
.sidebar-username {
    margin-bottom: var(--space-1);
    font-size: var(--text-lg);
}
.sidebar-role {
    font-size: var(--text-sm);
    color: var(--text-muted);
}
.sidebar-nav {
    display: flex;
    flex-direction: column;
    gap: var(--space-1);
}
.sidebar-link {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    padding: var(--space-3) var(--space-4);
    border-radius: var(--radius-lg);
    color: var(--text-secondary);
    font-weight: 500;
}
.sidebar-link:hover {
    background: var(--gray-50);
    color: var(--text-primary);
}
.sidebar-link.active {
    background: var(--primary-50);
    color: var(--primary);
}
.sidebar-link i {
    width: 20px;
}
.dashboard-main {
    flex: 1;
    padding: var(--space-8);
    max-width: 1000px;
}
.dashboard-header {
    margin-bottom: var(--space-6);
}
.welcome-text h1 {
    font-size: var(--text-2xl);
    margin-bottom: var(--space-1);
}
.welcome-text p {
    color: var(--text-secondary);
    margin: 0;
}
.profile-grid {
    display: grid;
    gap: var(--space-6);
}
@media (min-width: 768px) {
    .profile-grid {
        grid-template-columns: 320px 1fr;
    }
    .profile-form-card:last-child {
        grid-column: 2;
    }
}
.profile-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: var(--space-8);
    text-align: center;
    box-shadow: var(--shadow-sm);
    height: fit-content;
}
.profile-avatar-wrapper {
    position: relative;
    display: inline-block;
    margin-bottom: var(--space-4);
}
.profile-avatar-lg {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 4px solid var(--primary-50);
}
.avatar-edit-btn {
    position: absolute;
    bottom: 4px;
    right: 4px;
    width: 36px;
    height: 36px;
    background: var(--primary);
    color: var(--white);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    border: 3px solid var(--white);
}
.profile-header h2 {
    margin-bottom: var(--space-1);
}
.profile-email {
    color: var(--text-muted);
    margin-bottom: var(--space-3);
}
.profile-badge {
    display: inline-flex;
    align-items: center;
    gap: var(--space-1);
    padding: var(--space-1) var(--space-3);
    background: var(--success);
    color: var(--white);
    border-radius: var(--radius-full);
    font-size: var(--text-xs);
    font-weight: 600;
}
.profile-stats {
    display: flex;
    justify-content: center;
    gap: var(--space-6);
    margin-top: var(--space-6);
    padding-top: var(--space-6);
    border-top: 1px solid var(--gray-100);
}
.profile-stat {
    text-align: center;
}
.profile-stat-value {
    display: block;
    font-size: var(--text-xl);
    font-weight: 700;
    color: var(--text-primary);
}
.profile-stat-label {
    font-size: var(--text-sm);
    color: var(--text-muted);
}
.profile-form-card {
    background: var(--white);
    border-radius: var(--radius-xl);
    padding: var(--space-8);
    box-shadow: var(--shadow-sm);
}
.profile-form-card h3 {
    margin-bottom: var(--space-6);
    padding-bottom: var(--space-4);
    border-bottom: 1px solid var(--gray-100);
}
.form-actions {
    margin-top: var(--space-6);
}
</style>

<?php require_once APP_ROOT . '/views/layouts/footer.php'; ?>
