<?php
/**
 * DADE Learn - Helper Functions
 * Common utility functions used throughout the application
 */

/**
 * Escape HTML output (XSS protection)
 */
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Generate asset URL
 */
function asset($path) {
    return SITE_URL . '/assets/' . ltrim($path, '/');
}

/**
 * Generate URL
 */
function url($path = '') {
    return SITE_URL . '/' . ltrim($path, '/');
}

/**
 * Include a view file
 */
function view($name, $data = []) {
    extract($data);
    $viewPath = APP_ROOT . '/views/' . str_replace('.', '/', $name) . '.php';
    
    if (file_exists($viewPath)) {
        require $viewPath;
    } else {
        throw new Exception("View not found: $name");
    }
}

/**
 * Format date for display
 */
function formatDate($date, $format = 'M d, Y') {
    if (empty($date)) return '';
    return date($format, strtotime($date));
}

/**
 * Format relative time (e.g., "2 hours ago")
 */
function timeAgo($datetime) {
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    
    if ($diff->y > 0) return $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ago';
    if ($diff->m > 0) return $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ago';
    if ($diff->d > 0) return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
    if ($diff->h > 0) return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
    if ($diff->i > 0) return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
    return 'just now';
}

/**
 * Truncate text to specified length
 */
function truncate($text, $length = 100, $suffix = '...') {
    if (strlen($text) <= $length) return $text;
    return substr($text, 0, $length) . $suffix;
}

/**
 * Generate a slug from text
 */
function slugify($text) {
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}

/**
 * Check if current route matches
 */
function isRoute($route) {
    return Router::currentRoute() === $route;
}

/**
 * Check if route starts with prefix
 */
function routeStartsWith($prefix) {
    return strpos(Router::currentRoute(), $prefix) === 0;
}

/**
 * Get flash message and clear it
 */
function flash($key) {
    return Session::flash($key);
}

/**
 * Generate CSRF token field
 */
function csrf_field() {
    return '<input type="hidden" name="_csrf" value="' . Session::csrf() . '">';
}

/**
 * Format number with K/M suffix
 */
function formatNumber($num) {
    if ($num >= 1000000) {
        return round($num / 1000000, 1) . 'M';
    }
    if ($num >= 1000) {
        return round($num / 1000, 1) . 'K';
    }
    return $num;
}

/**
 * Calculate reading time for text content
 */
function readingTime($text, $wpm = 200) {
    $wordCount = str_word_count(strip_tags($text));
    $minutes = ceil($wordCount / $wpm);
    return $minutes . ' min read';
}

/**
 * Generate star rating HTML
 */
function starRating($rating, $maxStars = 5) {
    $html = '<div class="star-rating">';
    $fullStars = floor($rating);
    $hasHalf = ($rating - $fullStars) >= 0.5;
    
    for ($i = 1; $i <= $maxStars; $i++) {
        if ($i <= $fullStars) {
            $html .= '<i class="fas fa-star"></i>';
        } elseif ($hasHalf && $i == $fullStars + 1) {
            $html .= '<i class="fas fa-star-half-alt"></i>';
        } else {
            $html .= '<i class="far fa-star"></i>';
        }
    }
    
    $html .= '</div>';
    return $html;
}

/**
 * Get user avatar URL
 */
function avatar($user, $size = 100) {
    if (!empty($user['profile_picture'])) {
        return SITE_URL . '/uploads/avatars/' . $user['profile_picture'];
    }
    
    // Default avatar with initials
    $name = $user['username'] ?? 'User';
    $initials = strtoupper(substr($name, 0, 2));
    return "https://ui-avatars.com/api/?name=" . urlencode($initials) . "&size={$size}&background=005f73&color=fff";
}

/**
 * Check if request is AJAX
 */
function isAjax() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * Return JSON response
 */
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Get input from POST or GET
 */
function input($key, $default = null) {
    return $_POST[$key] ?? $_GET[$key] ?? $default;
}

/**
 * Validate CSRF token
 * Returns true if valid, false if invalid
 */
function validateCsrf() {
    $token = input('_csrf');
    return ($token && Session::verifyCsrf($token));
}
