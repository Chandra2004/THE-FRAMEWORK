<?php
    namespace {{NAMESPACE}}\App;

    use {{NAMESPACE}}\App\Config;
    use Exception;

    class CacheManager {
        /**
         * Mengambil data dari cache jika tersedia, atau menyimpan hasil callback ke cache.
         *
         * @param string $key Kunci cache
         * @param int $ttl Waktu hidup cache dalam detik
         * @param callable $callback Fungsi untuk menghasilkan data jika tidak ada di cache
         * @return mixed Data yang diambil dari cache atau hasil callback
         */
        public static function remember($key, $ttl, $callback) {
            try {
                $redis = Config::redis();
                $cached = $redis->get($key);

                if ($cached) {
                    return json_decode($cached, true);
                }

                $data = $callback();
                $redis->setex($key, $ttl, json_encode($data));
                return $data;
            } catch (Exception $e) {
                return $callback();
            }
        }

        /**
         * Menghapus data dari cache berdasarkan kunci tertentu.
         *
         * @param string $key Kunci cache yang akan dihapus
         * @return bool True jika berhasil dihapus, false jika gagal
         */
        public static function forget($key) {
            try {
                $redis = Config::redis();
                return $redis->del($key) > 0;
            } catch (Exception $e) {
                return false;
            }
        }
    }
?>