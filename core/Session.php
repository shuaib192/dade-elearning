<?php
/**
 * DADE Learn - Session Manager
 * Handles secure session management
 */

class Session {
    private static $started = false;
    
    /**
     * Start session with secure settings
     */
    public static function start() {
        if (self::$started) {
            return;
        }
        
        if (session_status() === PHP_SESSION_NONE) {
            // Secure session settings
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_only_cookies', 1);
            ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
            
            session_start();
            self::$started = true;
            
            // Regenerate session ID periodically for security
            if (!isset($_SESSION['_created'])) {
                $_SESSION['_created'] = time();
            } elseif (time() - $_SESSION['_created'] > 1800) {
                session_regenerate_id(true);
                $_SESSION['_created'] = time();
            }
        }
    }
    
    /**
     * Set a session value
     */
    public static function set($key, $value) {
        self::start();
        $_SESSION[$key] = $value;
    }
    
    /**
     * Get a session value
     */
    public static function get($key, $default = null) {
        self::start();
        return $_SESSION[$key] ?? $default;
    }
    
    /**
     * Check if session key exists
     */
    public static function has($key) {
        self::start();
        return isset($_SESSION[$key]);
    }
    
    /**
     * Remove a session value
     */
    public static function remove($key) {
        self::start();
        unset($_SESSION[$key]);
    }
    
    /**
     * Flash message (available for one request)
     */
    public static function flash($key, $value = null) {
        self::start();
        
        if ($value === null) {
            // Get and remove
            $val = $_SESSION['_flash'][$key] ?? null;
            unset($_SESSION['_flash'][$key]);
            return $val;
        }
        
        // Set
        $_SESSION['_flash'][$key] = $value;
    }
    
    /**
     * Destroy session
     */
    public static function destroy() {
        self::start();
        session_unset();
        session_destroy();
        self::$started = false;
    }
    
    /**
     * Generate CSRF token
     */
    public static function csrf() {
        self::start();
        
        if (!isset($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['_csrf_token'];
    }
    
    /**
     * Verify CSRF token
     */
    public static function verifyCsrf($token) {
        self::start();
        return isset($_SESSION['_csrf_token']) && hash_equals($_SESSION['_csrf_token'], $token);
    }
}
