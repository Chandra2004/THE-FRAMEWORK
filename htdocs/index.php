<?php
    require_once __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/../app/Helpers/helpers.php';

    define('ROOT_DIR', dirname(__DIR__));

    use {{NAMESPACE}}\BladeInit;
    use {{NAMESPACE}}\App\Config;
    use {{NAMESPACE}}\App\Router;
    use {{NAMESPACE}}\App\SessionManager;
    use {{NAMESPACE}}\Middleware\CsrfMiddleware;
    use {{NAMESPACE}}\Middleware\WAFMiddleware;
    use {{NAMESPACE}}\Middleware\ValidationMiddleware;
    use {{NAMESPACE}}\Http\Controllers\HomeController;

    SessionManager::startSecureSession();

    Config::loadEnv();

    header('X-Powered-By: Native-Chandra');
    header('X-Frame-Options: DENY');
    header('X-Content-Type-Options: nosniff');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: no-referrer-when-downgrade');
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');

    // ⚠️ Nonaktifkan sementara CSP agar tidak membatasi development di InfinityFree
    // $nonce = base64_encode(random_bytes(16));
    // header("Content-Security-Policy: default-src 'self'; script-src 'self' 'nonce-$nonce' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com; img-src 'self' data:; font-src 'self'; connect-src 'self';");

    $clientIp = $_SERVER['REMOTE_ADDR'];
    $rateLimitFile = __DIR__ . '/../storage/rate_limit/' . md5($clientIp);
    $limit = 100; 
    $window = 120; 

    if (!file_exists(dirname($rateLimitFile))) {
        mkdir(dirname($rateLimitFile), 0755, true);
    }

    $hits = 0;
    if (file_exists($rateLimitFile)) {
        $data = json_decode(file_get_contents($rateLimitFile), true);
        if (time() - $data['timestamp'] < $window) {
            $hits = $data['hits'];
        } else {
            $hits = 0;
        }
    }

    $hits++;
    file_put_contents($rateLimitFile, json_encode(['hits' => $hits, 'timestamp' => time()]));

    if ($hits > $limit) {
        http_response_code(429);
        exit('Too many requests. Please slow down.');
    }

    CsrfMiddleware::generateToken();

    Router::add('GET', '/', HomeController::class, 'index', [WAFMiddleware::class]);
    Router::add('GET', '/user', HomeController::class, 'user', [WAFMiddleware::class]);
    Router::add('POST', '/user', HomeController::class, 'createUser', [
        WAFMiddleware::class,
        CsrfMiddleware::class,
        ValidationMiddleware::class
    ]);
    Router::add('GET', '/user/information/{id}', HomeController::class, 'detail', [WAFMiddleware::class]);
    Router::add('GET', '/user/{id}/delete', HomeController::class, 'deleteUser', [
        WAFMiddleware::class,
        ValidationMiddleware::class
    ]);
    Router::add('POST', '/user/{id}/update', HomeController::class, 'updateUser', [
        WAFMiddleware::class,
        CsrfMiddleware::class,
        ValidationMiddleware::class
    ]);

    BladeInit::init();
    Router::run();
?>
