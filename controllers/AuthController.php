<?php
/**
 * AuthController - Handles all authentication operations
 * Login, Registration, Google OAuth, Password Reset
 */

class AuthController {
    
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Show Login Page
     */
    public function showLogin() {
        if (Auth::check()) {
            Router::redirect('dashboard');
            return;
        }
        require_once APP_ROOT . '/views/auth/login.php';
    }
    
    /**
     * Handle Login Form Submission
     */
    public function login() {
        if (Auth::check()) {
            Router::redirect('dashboard');
            return;
        }
        
        // Validate CSRF
        if (!validateCsrf()) {
            Session::flash('error', 'Invalid request. Please try again.');
            Router::redirect('login');
            return;
        }
        
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);
        
        // Validation
        if (empty($email) || empty($password)) {
            Session::flash('error', 'Please enter both email and password.');
            Router::redirect('login');
            return;
        }
        
        // Attempt login
        $result = Auth::attempt($email, $password);
        
        if ($result['success']) {
            // Handle remember me
            if ($remember) {
                ini_set('session.cookie_lifetime', 60 * 60 * 24 * 30); // 30 days
            }
            
            Session::flash('success', 'Welcome back!');
            Router::redirect('dashboard');
        } else {
            Session::flash('error', $result['message']);
            Router::redirect('login');
        }
    }
    
    /**
     * Show Registration Page
     */
    public function showRegister() {
        if (Auth::check()) {
            Router::redirect('dashboard');
            return;
        }
        require_once APP_ROOT . '/views/auth/register.php';
    }
    
    /**
     * Handle Registration Form Submission
     */
    public function register() {
        if (Auth::check()) {
            Router::redirect('dashboard');
            return;
        }
        
        // Validate CSRF
        if (!validateCsrf()) {
            Session::flash('error', 'Invalid request. Please try again.');
            Router::redirect('register');
            return;
        }
        
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        $role = $_POST['role'] ?? 'volunteer';
        
        // Validation
        $errors = [];
        
        if (empty($username)) {
            $errors[] = 'Full name is required.';
        } elseif (strlen($username) < 2) {
            $errors[] = 'Name must be at least 2 characters.';
        }
        
        if (empty($email)) {
            $errors[] = 'Email is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }
        
        if (empty($password)) {
            $errors[] = 'Password is required.';
        } elseif (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters.';
        }
        
        if ($password !== $passwordConfirm) {
            $errors[] = 'Passwords do not match.';
        }
        
        // Handle instructor applications
        $instructorPending = 0;
        if ($role === 'mentor') {
            $role = 'volunteer';  // Start as student (volunteer), pending approval
            $instructorPending = 1;
        } elseif (!in_array($role, ['student', 'volunteer', 'mentor'])) {
            $role = 'volunteer';
        }
        
        // Check if email exists
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors[] = 'An account with this email already exists.';
        }
        
        if (!empty($errors)) {
            Session::flash('error', implode('<br>', $errors));
            Router::redirect('register');
            return;
        }
        
        // Create user
        $hashedPassword = Auth::hashPassword($password);
        $verificationToken = bin2hex(random_bytes(32));
        
        // Split username into first and last name for table compatibility
        $parts = explode(' ', $username, 2);
        $firstName = $parts[0];
        $lastName = $parts[1] ?? 'User';
        
        // Build query based on instructor application
        if ($instructorPending) {
            $stmt = $this->db->prepare("
                INSERT INTO users (username, first_name, last_name, email, password, role, verification_token, instructor_pending, instructor_applied_at, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, 1, NOW(), NOW())
            ");
            $stmt->bind_param("sssssss", $username, $firstName, $lastName, $email, $hashedPassword, $role, $verificationToken);
        } else {
            $stmt = $this->db->prepare("
                INSERT INTO users (username, first_name, last_name, email, password, role, verification_token, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            $stmt->bind_param("sssssss", $username, $firstName, $lastName, $email, $hashedPassword, $role, $verificationToken);
        }
        
        if ($stmt->execute()) {
            $userId = $this->db->insert_id;
            
            // Send verification email
            Mail::sendVerification($email, $username, $verificationToken);
            
            // Auto login the user
            $user = [
                'id' => $userId,
                'username' => $username,
                'email' => $email,
                'role' => $role,
                'email_verified' => 0
            ];
            Auth::loginUser($user);
            
            if ($instructorPending) {
                Session::flash('success', 'Account created! Your instructor application is pending admin approval. You can start learning while you wait.');
            } else {
                Session::flash('success', 'Account created successfully! Please check your email to verify your account.');
            }
            Router::redirect('dashboard');
        } else {
            Session::flash('error', 'An error occurred. Please try again.');
            Router::redirect('register');
        }
    }
    
    /**
     * Initiate Google OAuth (alias for routes)
     */
    public function googleRedirect() {
        $this->googleAuth();
    }
    
    /**
     * Verify Email (alias)
     */
    public function verify() {
        $this->verifyEmail();
    }
    
    /**
     * Initiate Google OAuth
     */
    public function googleAuth() {
        $clientId = GOOGLE_CLIENT_ID;
        $redirectUri = GOOGLE_REDIRECT_URI;
        
        $state = bin2hex(random_bytes(16));
        Session::set('google_oauth_state', $state);
        
        $params = http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => 'email profile',
            'state' => $state,
            'access_type' => 'online',
            'prompt' => 'select_account'
        ]);
        
        header('Location: https://accounts.google.com/o/oauth2/v2/auth?' . $params);
        exit;
    }
    
    /**
     * Handle Google OAuth Callback
     */
    public function googleCallback() {
        $code = $_GET['code'] ?? '';
        $state = $_GET['state'] ?? '';
        
        // Verify state
        if ($state !== Session::get('google_oauth_state')) {
            Session::flash('error', 'Invalid authentication state.');
            Router::redirect('login');
            return;
        }
        
        if (empty($code)) {
            Session::flash('error', 'Authentication was cancelled.');
            Router::redirect('login');
            return;
        }
        
        // Exchange code for token
        $tokenUrl = 'https://oauth2.googleapis.com/token';
        $tokenData = [
            'code' => $code,
            'client_id' => GOOGLE_CLIENT_ID,
            'client_secret' => GOOGLE_CLIENT_SECRET,
            'redirect_uri' => GOOGLE_REDIRECT_URI,
            'grant_type' => 'authorization_code'
        ];
        
        $ch = curl_init($tokenUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($tokenData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        $response = curl_exec($ch);
        curl_close($ch);
        
        $token = json_decode($response, true);
        
        if (isset($token['error'])) {
            Session::flash('error', 'Authentication failed. Please try again.');
            Router::redirect('login');
            return;
        }
        
        // Get user info
        $accessToken = $token['access_token'];
        $userInfoUrl = 'https://www.googleapis.com/oauth2/v2/userinfo';
        
        $ch = curl_init($userInfoUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);
        $response = curl_exec($ch);
        curl_close($ch);
        
        $googleUser = json_decode($response, true);
        
        if (!isset($googleUser['email'])) {
            Session::flash('error', 'Could not retrieve account information.');
            Router::redirect('login');
            return;
        }
        
        // Check if user exists
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? OR google_id = ?");
        $stmt->bind_param("ss", $googleUser['email'], $googleUser['id']);
        $stmt->execute();
        $existingUser = $stmt->get_result()->fetch_assoc();
        
        if ($existingUser) {
            // Update Google ID if not set
            if (empty($existingUser['google_id'])) {
                $update = $this->db->prepare("UPDATE users SET google_id = ?, email_verified = 1 WHERE id = ?");
                $update->bind_param("si", $googleUser['id'], $existingUser['id']);
                $update->execute();
            }
            
            // Login existing user
            Auth::loginUser($existingUser);
            Session::flash('success', 'Welcome back!');
        } else {
            // Create new user
            $username = $googleUser['name'] ?? explode('@', $googleUser['email'])[0];
            $role = 'volunteer';
            
            $stmt = $this->db->prepare("
                INSERT INTO users (username, email, google_id, profile_picture, role, email_verified, created_at)
                VALUES (?, ?, ?, ?, ?, 1, NOW())
            ");
            $stmt->bind_param("sssss", $username, $googleUser['email'], $googleUser['id'], $googleUser['picture'], $role);
            $stmt->execute();
            
            $newUser = [
                'id' => $this->db->insert_id,
                'username' => $username,
                'email' => $googleUser['email'],
                'role' => $role,
                'email_verified' => 1
            ];
            
            Auth::loginUser($newUser);
            Session::flash('success', 'Welcome to Dade Initiative! Your account has been created.');
        }
        
        Router::redirect('dashboard');
    }
    
    /**
     * Logout
     */
    public function logout() {
        Auth::logout();
        Session::flash('success', 'You have been logged out successfully.');
        Router::redirect('login');
    }
    
    /**
     * Show Forgot Password Page
     */
    public function showForgotPassword() {
        require_once APP_ROOT . '/views/auth/forgot-password.php';
    }
    
    /**
     * Handle Forgot Password Form
     */
    /**
     * Handle Forgot Password Form
     */
    public function forgotPassword() {
        if (!validateCsrf()) {
            Session::flash('error', 'Invalid request.');
            Router::redirect('forgot-password');
            return;
        }
        
        $email = trim($_POST['email'] ?? '');
        
        if (empty($email)) {
            Session::flash('error', 'Please enter your email address.');
            Router::redirect('forgot-password');
            return;
        }
        
        // Check if user exists
        $stmt = $this->db->prepare("SELECT id, username FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        
        if ($user) {
            // Generate 6-digit code
            $code = rand(100000, 999999);
            // Expiry 1 hour from now (using PHP time for consistency)
            $expiry = date('Y-m-d H:i:s', time() + 3600);
            
            $stmt = $this->db->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE id = ?");
            $stmt->bind_param("ssi", $code, $expiry, $user['id']);
            $stmt->execute();
            
            // Send reset email with code
            Mail::sendPasswordReset($email, $user['username'], $code);
            
            // Store email in session for the next step
            Session::set('reset_email', $email);
            Session::flash('success', 'We sent a verification code to your email.');
            Router::redirect('reset-password');
        } else {
            // Always show success to prevent email enumeration
            Session::flash('success', 'If an account with that email exists, we sent a verification code.');
            Router::redirect('forgot-password');
        }
    }
    
    /**
     * Show Reset Password Page
     */
    public function showResetPassword() {
        // If we don't have an email in session (flow interrupted), redirect to forgot password
        if (!Session::get('reset_email')) {
            Session::flash('error', 'Session expired. Please start over.');
            Router::redirect('forgot-password');
            return;
        }
        
        require_once APP_ROOT . '/views/auth/reset-password.php';
    }
    
    /**
     * Handle Reset Password Form
     */
    public function resetPassword() {
        if (!validateCsrf()) {
            Session::flash('error', 'Invalid request.');
            Router::redirect('reset-password');
            return;
        }
        
        $email = Session::get('reset_email');
        if (!$email) {
            Session::flash('error', 'Session expired. Please start over.');
            Router::redirect('forgot-password');
            return;
        }
        
        $code = trim($_POST['code'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        
        if (empty($code)) {
            Session::flash('error', 'Please enter the verification code.');
            Router::redirect('reset-password');
            return;
        }
        
        if (strlen($password) < 8) {
            Session::flash('error', 'Password must be at least 8 characters.');
            Router::redirect('reset-password');
            return;
        }
        
        if ($password !== $passwordConfirm) {
            Session::flash('error', 'Passwords do not match.');
            Router::redirect('reset-password');
            return;
        }
        
        // Find user by email and code, check expiry
        // Using PHP time for comparison to ensure matching timezones
        $now = date('Y-m-d H:i:s');
        
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ? AND reset_token = ? AND reset_token_expiry > ?");
        $stmt->bind_param("sss", $email, $code, $now);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        
        if (!$user) {
            Session::flash('error', 'Invalid or expired verification code.');
            Router::redirect('reset-password');
            return;
        }
        
        // Update password
        $hashedPassword = Auth::hashPassword($password);
        $stmt = $this->db->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?");
        $stmt->bind_param("si", $hashedPassword, $user['id']);
        $stmt->execute();
        
        // Clear session
        Session::remove('reset_email');
        
        Session::flash('success', 'Your password has been reset. You can now log in.');
        Router::redirect('login');
    }
    
    /**
     * Verify Email
     */
    public function verifyEmail() {
        $token = $_GET['token'] ?? '';
        
        if (empty($token)) {
            Session::flash('error', 'Invalid verification link.');
            Router::redirect('login');
            return;
        }
        
        $stmt = $this->db->prepare("SELECT id FROM users WHERE verification_token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        
        if (!$user) {
            Session::flash('error', 'Invalid verification link.');
            Router::redirect('login');
            return;
        }
        
        // Verify the email
        $stmt = $this->db->prepare("UPDATE users SET email_verified = 1, verification_token = NULL WHERE id = ?");
        $stmt->bind_param("i", $user['id']);
        $stmt->execute();
        
        Session::flash('success', 'Your email has been verified! You can now access all features.');
        
        if (Auth::check()) {
            Router::redirect('dashboard');
        } else {
            Router::redirect('login');
        }
    }
    /**
     * Resend Verification Email
     */
    public function resendVerification() {
        Auth::requireLogin();
        
        $user = Auth::user();
        if ($user['email_verified'] == 1) {
            Session::flash('info', 'Your email is already verified.');
            Router::redirect('dashboard');
            return;
        }
        
        // Check if we already have a token, if not generate one
        $token = $user['verification_token'];
        if (empty($token)) {
            $token = Auth::generateToken();
            $stmt = $this->db->prepare("UPDATE users SET verification_token = ? WHERE id = ?");
            $stmt->bind_param("si", $token, $user['id']);
            $stmt->execute();
        }
        
        $verifyUrl = SITE_URL . '/verify-email?token=' . $token;
        $result = Mail::sendVerification($user['email'], $user['username'], $token);
        
        if ($result === true) {
            Session::flash('success', 'A new verification link has been sent to your email.');
        } else {
            Session::flash('error', 'Failed to send verification email. Please try again later.');
        }
        
        Router::redirect('dashboard');
    }
}
