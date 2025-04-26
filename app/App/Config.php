<?php
    namespace {{NAMESPACE}}\app;

    use Dotenv\Dotenv;
    use Predis\Client;

    class Config {
        private static $redis;

        public static function loadEnv() {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
            $dotenv->load();
        }

        public static function get($key) {
            return $_ENV[$key] ?? null;
        }

        public static function redis() {
            if (!self::$redis) {
                self::$redis = new Client([
                    'scheme' => 'tcp',
                    'host'   => self::get('REDIS_HOST') ?? '127.0.0.1',
                    'port'   => self::get('REDIS_PORT') ?? 6379,
                ]);
            }
            return self::$redis;
        }
    }
?>