<?php
    namespace {{NAMESPACE}}\Middleware;

    use {{NAMESPACE}}\App\Config;

    class AuthMiddleware implements Middleware {
        function before()
        {
            if (session_status() === PHP_SESSION_NONE) {
                session_start(); // Tambahkan session_start agar tidak error
            }

            if (!isset($_SESSION['user_id'])) {
                header('location: ' . Config::get('BASE_URL') . '/login');
                exit();
            }
        }
    }
?>
