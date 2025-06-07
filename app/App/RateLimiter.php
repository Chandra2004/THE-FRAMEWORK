<?php
namespace {{NAMESPACE}}\App;

use {{NAMESPACE}}\App\Logging;

class RateLimiter {
    private static $fallbackDir = __DIR__ . '/../Storage/cache/ratelimit/';
    private static $fallbackLimit = 50; // Limit fallback lebih ketat
    private static $window = 60; // Waktu window dalam detik

    /**
     * Mengecek apakah permintaan bisa diproses berdasarkan rate limiting.
     *
     * @param string $key Kunci untuk rate limiting (misalnya IP address atau user ID)
     * @param int $limit Jumlah permintaan maksimal dalam window waktu
     * @param int $window Waktu dalam detik untuk jendela rate limiting
     * @return bool True jika permintaan diizinkan, False jika terlalu banyak permintaan
     */
    public static function check($key, $limit = 100, $window = 60) {
        try {
            return self::fallbackCheck($key, $limit, $window);
        } catch (\Exception $e) {
            Logging::getLogger()->error('Rate Limiter Error: ' . $e->getMessage());
            return self::fallbackCheck($key, $limit, $window);
        }
    }

    /**
     * Fallback untuk mengecek rate limit menggunakan file cache.
     *
     * @param string $key Kunci untuk rate limiting
     * @param int $limit Batas permintaan
     * @param int $window Jendela waktu dalam detik
     * @return bool True jika permintaan diizinkan, False jika rate limit tercapai
     */
    private static function fallbackCheck($key, $limit, $window) {
        if (!is_dir(self::$fallbackDir)) {
            mkdir(self::$fallbackDir, 0755, true);
        }

        $file = self::$fallbackDir . md5($key) . '.json';
        $data = file_exists($file) ? json_decode(file_get_contents($file), true) : [
            'count' => 0,
            'timestamp' => time()
        ];

        // Reset hit counter jika window telah lewat
        if (time() - $data['timestamp'] > $window) {
            $data = ['count' => 0, 'timestamp' => time()];
        }

        // Jika sudah mencapai limit, kirimkan response 429
        if ($data['count'] >= $limit) {
            self::sendRateLimitResponse();
            return false; // Block request
        }

        // Inkrementasi hit counter
        $data['count']++;
        file_put_contents($file, json_encode($data), LOCK_EX);
        return true; // Izinkan request
    }

    /**
     * Kirimkan response dengan status 429 jika rate limit tercapai
     */
    private static function sendRateLimitResponse() {
        http_response_code(429);
        echo json_encode(['error' => 'Too many requests']);
        exit;
    }
}
