<?php
/**
 * DADE Learn - Authentication Helper
 * Handles user authentication and authorization
 */

class Auth {
    /**
     * Attempt to log in a user
     */
    public static function attempt($email, $password) {
        $db = getDB();
        
        $stmt = $db->prepare("SELECT id, username, email, password, role, email_verified, status FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'Invalid email or password'];
        }
        
        $user = $result->fetch_assoc();
        
        // Verify password
        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Invalid email or password'];
        }
        
        // Check if account is active
        if (isset($user['status']) && $user['status'] === 'suspended') {
            return ['success' => false, 'message' => 'Your account has been suspended'];
        }
        
        // Log the user in
        self::loginUser($user);
        
        return ['success' => true];
    }
    
    /**
     * Log in a user by setting session data
     */
    public static function loginUser($user) {
        Session::set('user_id', $user['id']);
        Session::set('user_email', $user['email']);
        Session::set('user_name', $user['username']);
        Session::set('user_role', $user['role']);
        Session::set('logged_in', true);
        
        // Update last login
        $db = getDB();
        $stmt = $db->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        $stmt->bind_param("i", $user['id']);
        $stmt->execute();
    }
    
    /**
     * Check if user is logged in
     */
    public static function check() {
        return Session::get('logged_in', false) === true;
    }
    
    /**
     * Get current user ID
     */
    public static function id() {
        return Session::get('user_id');
    }
    
    /**
     * Get current user's data
     */
    public static function user() {
        if (!self::check()) {
            return null;
        }
        
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $userId = self::id();
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_assoc();
    }
    
    /**
     * Check if user has a specific role
     */
    public static function hasRole($role) {
        return Session::get('user_role') === $role;
    }
    
    /**
     * Check if user is admin
     */
    public static function isAdmin() {
        return self::hasRole(ROLE_ADMIN);
    }
    
    /**
     * Check if user is instructor
     */
    public static function isInstructor() {
        return self::hasRole(ROLE_INSTRUCTOR) || self::isAdmin();
    }
    
    /**
     * Check if user email is verified
     */
    public static function isVerified() {
        if (!self::check()) return false;
        
        $user = self::user();
        return (isset($user['email_verified']) && $user['email_verified'] == 1);
    }

    /**
     * Log out the current user
     */
    public static function logout() {
        Session::destroy();
    }
    
    /**
     * Require authentication (redirect if not logged in)
     */
    public static function requireLogin() {
        if (!self::check()) {
            Session::flash('error', 'Please log in to continue');
            Session::set('intended_url', $_SERVER['REQUEST_URI']);
            Router::redirect('login');
        }
    }
    
    /**
     * Require specific role
     */
    public static function requireRole($role) {
        self::requireLogin();
        
        if (!self::hasRole($role) && !self::isAdmin()) {
            Session::flash('error', 'You do not have permission to access this page');
            Router::redirect('dashboard');
        }
    }
    
    /**
     * Create a password hash
     */
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    /**
     * Generate a random token
     */
    public static function generateToken($length = 32) {
        return bin2hex(random_bytes($length));
    }
}
