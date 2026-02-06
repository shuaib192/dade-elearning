<?php
/**
 * ProfileController - Handles user profile updates
 */

class ProfileController {
    
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Update Profile
     */
    public function update() {
        if (!validateCsrf()) {
            Session::flash('error', 'Invalid request.');
            Router::redirect('dashboard/profile');
            return;
        }
        
        $action = $_POST['action'] ?? '';
        
        if ($action === 'update_profile') {
            $this->updateProfile();
        } elseif ($action === 'change_password') {
            $this->changePassword();
        }
    }
    
    /**
     * Update Profile Information
     */
    private function updateProfile() {
        $userId = Auth::id();
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        
        // Validation
        if (empty($username) || strlen($username) < 2) {
            Session::flash('error', 'Please enter a valid name.');
            Router::redirect('dashboard/profile');
            return;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::flash('error', 'Please enter a valid email address.');
            Router::redirect('dashboard/profile');
            return;
        }
        
        // Check if email is taken by another user
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $userId);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            Session::flash('error', 'This email is already in use.');
            Router::redirect('dashboard/profile');
            return;
        }
        
        // Handle avatar upload
        $avatarUpdate = "";
        if (!empty($_FILES['avatar']['name'])) {
            $file = $_FILES['avatar'];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            
            if (!in_array($file['type'], $allowedTypes)) {
                Session::flash('error', 'Invalid image format. Please use JPG, PNG, GIF, or WebP.');
                Router::redirect('dashboard/profile');
                return;
            }
            
            if ($file['size'] > 5 * 1024 * 1024) {
                Session::flash('error', 'Image size must be less than 5MB.');
                Router::redirect('dashboard/profile');
                return;
            }
            
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'avatar_' . $userId . '_' . time() . '.' . $ext;
            $destination = APP_ROOT . '/uploads/avatars/' . $filename;
            
            // Create directory if not exists
            if (!is_dir(APP_ROOT . '/uploads/avatars')) {
                mkdir(APP_ROOT . '/uploads/avatars', 0755, true);
            }
            
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $avatarUpdate = ", profile_picture = ?";
            }
        }
        
        // Update user
        if ($avatarUpdate) {
            $stmt = $this->db->prepare("UPDATE users SET username = ?, email = ?, bio = ? $avatarUpdate WHERE id = ?");
            $stmt->bind_param("ssssi", $username, $email, $bio, $filename, $userId);
        } else {
            $stmt = $this->db->prepare("UPDATE users SET username = ?, email = ?, bio = ? WHERE id = ?");
            $stmt->bind_param("sssi", $username, $email, $bio, $userId);
        }
        
        if ($stmt->execute()) {
            // Update session
            Session::set('user', [
                'id' => $userId,
                'username' => $username,
                'email' => $email,
                'role' => Auth::user()['role'],
                'profile_picture' => isset($filename) ? $filename : (Auth::user()['profile_picture'] ?? null)
            ]);
            
            Session::flash('success', 'Profile updated successfully!');
        } else {
            Session::flash('error', 'Failed to update profile. Please try again.');
        }
        
        Router::redirect('dashboard/profile');
    }
    
    /**
     * Change Password
     */
    private function changePassword() {
        $userId = Auth::id();
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validation
        if (empty($currentPassword) || empty($newPassword)) {
            Session::flash('error', 'Please fill in all password fields.');
            Router::redirect('dashboard/profile');
            return;
        }
        
        if (strlen($newPassword) < 8) {
            Session::flash('error', 'New password must be at least 8 characters.');
            Router::redirect('dashboard/profile');
            return;
        }
        
        if ($newPassword !== $confirmPassword) {
            Session::flash('error', 'New passwords do not match.');
            Router::redirect('dashboard/profile');
            return;
        }
        
        // Verify current password
        $stmt = $this->db->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        
        if (!password_verify($currentPassword, $user['password'])) {
            Session::flash('error', 'Current password is incorrect.');
            Router::redirect('dashboard/profile');
            return;
        }
        
        // Update password
        $hashedPassword = Auth::hashPassword($newPassword);
        $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashedPassword, $userId);
        
        if ($stmt->execute()) {
            Session::flash('success', 'Password changed successfully!');
        } else {
            Session::flash('error', 'Failed to change password. Please try again.');
        }
        
        Router::redirect('dashboard/profile');
    }
}
