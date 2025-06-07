<?php
namespace {{NAMESPACE}}\App;

class CacheManager
{
    public static function remember($key, $ttl, $callback)
    {
        $cacheDir = __DIR__ . '/../../../storage/cache/';
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }

        $filePath = $cacheDir . md5($key) . '.cache';

        if (file_exists($filePath)) {
            $data = json_decode(file_get_contents($filePath), true);
            if (time() < $data['expires_at']) {
                return $data['value'];
            }
        }

        $value = $callback();
        $data = [
            'value' => $value,
            'expires_at' => time() + $ttl
        ];
        file_put_contents($filePath, json_encode($data));
        return $value;
    }

    public static function forget($key)
    {
        $filePath = __DIR__ . '/../../../storage/cache/' . md5($key) . '.cache';
        return file_exists($filePath) && unlink($filePath);
    }
}