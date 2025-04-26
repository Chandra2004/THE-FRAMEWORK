<?php
    try {
        // Validasi argumen CLI
        if (!isset($argv[1])) {
            throw new Exception('Namespace tidak diberikan. Gunakan: php update-namespace.php <namespace>');
        }

        $newNamespace = rtrim($argv[1], '\\');
        if (!preg_match('/^[a-zA-Z][a-zA-Z0-9\\\\]*[a-zA-Z0-9]$/', $newNamespace)) {
            throw new Exception('Namespace tidak valid. Gunakan karakter alfanumerik dan backslash tanpa spasi atau karakter khusus.');
        }

        // Baca composer.json
        $composerFile = __DIR__ . '/composer.json';
        if (!file_exists($composerFile)) {
            throw new Exception('File composer.json tidak ditemukan.');
        }
        $composer = json_decode(file_get_contents($composerFile), true);
        if (!isset($composer['autoload']['psr-4'])) {
            throw new Exception('PSR-4 autoload tidak ditemukan di composer.json.');
        }

        // Update autoload di composer.json
        $newAutoload = [];
        foreach ($composer['autoload']['psr-4'] as $ns => $path) {
            $newNs = str_replace('{{NAMESPACE}}', $newNamespace, $ns);
            $newAutoload[$newNs] = $path;
        }
        $composer['autoload']['psr-4'] = $newAutoload;

        // Update skrip post-autoload-dump
        if (isset($composer['scripts']['post-autoload-dump'])) {
            $composer['scripts']['post-autoload-dump'] = array_map(function ($command) use ($newNamespace) {
                return str_replace('{{NAMESPACE}}', $newNamespace, $command);
            }, $composer['scripts']['post-autoload-dump']);
        }

        // Simpan composer.json
        file_put_contents($composerFile, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        echo "composer.json diperbarui.\n";

        // Direktori untuk diperbarui
        $directories = [
            __DIR__ . '/app',
            __DIR__ . '/database/migrations',
            __DIR__ . '/database/seeders'
        ];

        // Update file PHP
        foreach ($directories as $dir) {
            if (!is_dir($dir)) {
                echo "Direktori $dir tidak ditemukan, dilewati.\n";
                continue;
            }
            $directory = new RecursiveDirectoryIterator($dir);
            $iterator = new RecursiveIteratorIterator($directory);

            foreach ($iterator as $file) {
                if ($file->getExtension() === 'php') {
                    $content = file_get_contents($file->getPathname());
                    $updatedContent = str_replace('{{NAMESPACE}}', $newNamespace, $content);
                    echo "Mengupdate {$file->getPathname()}:\n$updatedContent\n---\n";
                    file_put_contents($file->getPathname(), $updatedContent);
                }
            }
        }

        // Update index.php
        $indexFile = __DIR__ . '/htdocs/index.php';
        if (file_exists($indexFile)) {
            $content = file_get_contents($indexFile);
            $updatedContent = str_replace('{{NAMESPACE}}', $newNamespace, $content);
            echo "Mengupdate $indexFile:\n$updatedContent\n---\n";
            file_put_contents($indexFile, $updatedContent);
        } else {
            echo "File $indexFile tidak ditemukan.\n";
        }

        echo "Namespace berhasil diupdate ke $newNamespace.\n";
    } catch (Exception $e) {
        echo "Gagal mengupdate namespace: " . $e->getMessage() . "\n";
        exit(1);
    }
?>