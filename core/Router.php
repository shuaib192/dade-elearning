<?php
/**
 * DADE Learn - Simple Router
 * Handles URL routing without file extensions
 */

class Router {
    private static $routes = [];
    private static $currentRoute = null;
    
    /**
     * Register a GET route
     */
    public static function get($path, $callback) {
        self::$routes['GET'][$path] = $callback;
    }
    
    /**
     * Register a POST route
     */
    public static function post($path, $callback) {
        self::$routes['POST'][$path] = $callback;
    }
    
    /**
     * Match route with parameters
     */
    private static function matchRoute($method, $uri) {
        if (!isset(self::$routes[$method])) {
            return null;
        }
        
        foreach (self::$routes[$method] as $route => $callback) {
            // Convert route pattern to regex
            $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $route);
            $pattern = "#^" . $pattern . "$#";
            
            if (preg_match($pattern, $uri, $matches)) {
                // Filter out numeric keys
                $params = array_filter($matches, function($key) {
                    return !is_int($key);
                }, ARRAY_FILTER_USE_KEY);
                
                return ['callback' => $callback, 'params' => $params];
            }
        }
        
        return null;
    }
    
    /**
     * Dispatch the request to appropriate handler
     */
    public static function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = isset($_GET['route']) ? $_GET['route'] : '';
        $uri = trim($uri, '/');
        
        // Default to home
        if (empty($uri)) {
            $uri = '';
        }
        
        self::$currentRoute = $uri;
        
        $match = self::matchRoute($method, $uri);
        
        if ($match) {
            $callback = $match['callback'];
            $params = $match['params'];
            
            if (is_callable($callback)) {
                return call_user_func_array($callback, $params);
            } elseif (is_string($callback)) {
                // Format: Controller@method
                list($controller, $method) = explode('@', $callback);
                $controllerFile = APP_ROOT . '/controllers/' . $controller . '.php';
                
                if (file_exists($controllerFile)) {
                    require_once $controllerFile;
                    $instance = new $controller();
                    return call_user_func_array([$instance, $method], $params);
                }
            }
        }
        
        // 404 Not Found
        http_response_code(404);
        require_once APP_ROOT . '/views/errors/404.php';
    }
    
    /**
     * Get current route
     */
    public static function currentRoute() {
        return self::$currentRoute;
    }
    
    /**
     * Generate URL for a path
     */
    public static function url($path = '') {
        return SITE_URL . '/' . ltrim($path, '/');
    }
    
    /**
     * Redirect to a URL
     */
    public static function redirect($path) {
        header('Location: ' . self::url($path));
        exit;
    }

    /**
     * Redirect to the previous page
     */
    public static function back() {
        if (isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            self::redirect('');
        }
        exit;
    }
}
