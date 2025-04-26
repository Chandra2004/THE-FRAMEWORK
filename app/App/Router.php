<?php
    namespace {{NAMESPACE}}\App;

    use {{NAMESPACE}}\App\Config;
    use {{NAMESPACE}}\Controller\ErrorController;
    use Exception;

    class Router {
        private static array $routes = [];
        private static bool $routeFound = false;

        public static function add(string $method, string $path, string $controller, string $function, array $middlewares = []) {
            // Convert path to regex pattern for dynamic parameters
            $patternPath = preg_replace('/\{([a-z]+)\}/', '(?P<$1>[^/]+)', $path);
            $compiledPattern = "#^" . $patternPath . "$#i"; // Case-insensitive

            self::$routes[] = [
                'method' => strtoupper($method),
                'path' => $compiledPattern,
                'controller' => $controller,
                'function' => $function,
                'middleware' => $middlewares
            ];
        }

        public static function run() {
            Config::loadEnv();
        
            // Handle CORS preflight
            if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
                header('Access-Control-Allow-Origin: *');
                header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
                header('Access-Control-Allow-Headers: Content-Type, Authorization');
                exit;
            }
        
            // Set error handling based on environment
            if (Config::get('APP_ENV') === 'production') {
                error_reporting(0);
                ini_set('display_errors', '0');
                ini_set('log_errors', '1');
            } else {
                error_reporting(E_ALL);
                ini_set('display_errors', '1');
            }
        
            try {
                $path = $_SERVER['PATH_INFO'] ?? '/';
                $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        
                foreach (self::$routes as $route) {
                    if ($method !== $route['method']) continue;
        
                    if (preg_match($route['path'], $path, $matches)) {
                        // Process middleware
                        foreach ($route['middleware'] as $middleware) {
                            $instance = new $middleware();
                            if (!$instance->before()) {
                                // Middleware blocked the request
                                self::handleAbort();
                                return;
                            }
                        }
        
                        // Validate controller and method
                        if (!class_exists($route['controller'])) {
                            throw new Exception("Controller {$route['controller']} not found");
                        }
        
                        $controller = new $route['controller']();
                        $function = $route['function'];
        
                        if (!method_exists($controller, $function)) {
                            throw new Exception("Method {$function} not found in {$route['controller']}");
                        }
        
                        // Extract named parameters
                        $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
        
                        call_user_func_array([$controller, $function], $params);
                        self::$routeFound = true;
                        return;
                    }
                }
        
                if (!self::$routeFound) {
                    self::handle404();
                }
        
            } catch (Exception $e) {
                self::handle500($e);
            }
        }
        
        private static function handle500(Exception $e) {
            http_response_code(500);
            
            if (Config::get('APP_ENV') === 'production') {
                $controller = new ErrorController();
                $controller->error500();
            } else {
                echo "Error 500: Internal Server Error<br>";
                echo "<strong>Message:</strong> " . $e->getMessage() . "<br>";
                echo "<strong>File:</strong> " . $e->getFile() . "<br>";
                echo "<strong>Line:</strong> " . $e->getLine() . "<br>";
            }
        
            exit;
        }    

        private static function handleAbort() {
            http_response_code(403);
            echo "Access forbidden";
            exit;
        }

        private static function handle404() {
            http_response_code(404);
            $controller = new ErrorController();
            $controller->error404();
            exit;
        }

        // Untuk cache routes di production
        public static function cacheRoutes() {
            if (Config::get('APP_ENV') === 'production') {
                $cacheDir = __DIR__ . '/../storage/cache';
                $cacheFile = $cacheDir . '/routes.cache';
        
                // Buat direktori jika belum ada
                if (!file_exists($cacheDir)) {
                    mkdir($cacheDir, 0755, true); // true untuk membuat parent directory
                }
        
                if (!file_exists($cacheFile)) {
                    file_put_contents($cacheFile, '<?php return ' . var_export(self::$routes, true) . ';');
                }
            }
        }
    }
?>
