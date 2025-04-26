<?php
    namespace {{NAMESPACE}}\Middleware;

    use {{NAMESPACE}}\App\Config;

    class AuthMiddleware implements Middleware {
        function before()
        {
            session_start();
            if (!isset($_SESSION['user_id'])) {
                header('location: ' . Config::get('BASE_URL') . '/login');
                exit();
            }
        }
    }
?>
