<?php
    // Pastikan session berjalan sebelum digunakan
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Header keamanan
    header("X-Powered-By: Native-Chandra");
    header('X-Frame-Options: DENY'); // Mencegah Clickjacking
    header('X-Content-Type-Options: nosniff'); // Mencegah MIME type sniffing
    header('Referrer-Policy: no-referrer-when-downgrade'); // Batasi informasi referrer
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()'); // Batasi akses perangkat
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload'); // Paksa HTTPS
    // header('Content-Security-Policy: default-src \'self\'; script-src \'self\' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com; style-src \'self\' \'unsafe-inline\' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com; img-src \'self\' data:;'); // Batasi sumber konten eksternal

    require_once __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/../app/Helpers/helpers.php';

    use {{NAMESPACE}}\BladeInit;
    use {{NAMESPACE}}\App\Config;
    use {{NAMESPACE}}\App\Router;
    use {{NAMESPACE}}\Middleware\CsrfMiddleware;
    use {{NAMESPACE}}\Controller\HomeController;

    // Load konfigurasi .env
    Config::loadEnv();

    // Inisialisasi token CSRF (gunakan metode di middleware agar lebih terstruktur)
    CsrfMiddleware::generateToken();

    // Definisi route aplikasi
    Router::add('GET', '/', HomeController::class, 'index');
    Router::add('GET', '/user', HomeController::class, 'user');
    Router::add('POST', '/user', HomeController::class, 'createUser', [CsrfMiddleware::class]);
    Router::add('GET', '/user/information/{id}', HomeController::class, 'detail');
    Router::add('GET', '/user/{id}/delete', HomeController::class, 'deleteUser');
    Router::add('POST', '/user/{id}/update', HomeController::class, 'updateUser', [CsrfMiddleware::class]);

    // Inisialisasi Blade untuk templating
    BladeInit::init();

    // Cache route agar lebih cepat (hanya jika mode production)
    Router::cacheRoutes();

    // Jalankan routing aplikasi
    Router::run();
?>
