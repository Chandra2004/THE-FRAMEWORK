<?php
    namespace {{NAMESPACE}}\app;

    use Dotenv\Dotenv;

    class Config {
        public static function loadEnv() {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
            $dotenv->load();
        }

        public static function get($key) {
            return $_ENV[$key] ?? null; // Mengembalikan nilai dari .env atau null jika tidak ada
        }
    }
?>