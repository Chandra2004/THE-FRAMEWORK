<?php
    function handleNamespaceUpdate($newNamespace) {
        $directories = [
            __DIR__ . '/app',
            __DIR__ . '/database/migrations',
            __DIR__ . '/database/seeders'
        ];
    
        // Ganti namespace di file PHP
        foreach ($directories as $dir) {
            $directory = new RecursiveDirectoryIterator($dir);
            $iterator = new RecursiveIteratorIterator($directory);
    
            foreach ($iterator as $file) {
                if ($file->getExtension() === 'php') {
                    $content = file_get_contents($file->getPathname());
                    $updatedContent = str_replace('{{NAMESPACE}}', $newNamespace, $content);
                    file_put_contents($file->getPathname(), $updatedContent);
                }
            }
        }
    
        echo "Namespace berhasil diperbarui di semua file PHP dalam app, migrations, dan seeders.\n";
    
        // Cek dan ganti namespace di public/index.php
        $indexFile = __DIR__ . '/htdocs/index.php';
        if (file_exists($indexFile)) {
            $content = file_get_contents($indexFile);
            $updatedContent = str_replace('{{NAMESPACE}}', $newNamespace, $content);
            file_put_contents($indexFile, $updatedContent);
            echo "Namespace di htdocs/index.php berhasil diperbarui.\n";
        }
    
        // Update namespace pada composer.json (post-autoload-dump)
        $composerFile = __DIR__ . '/composer.json';
        if (file_exists($composerFile)) {
            $composer = json_decode(file_get_contents($composerFile), true);
            
            // Ganti {{NAMESPACE}} dengan nilai namespace baru di bagian post-autoload-dump
            if (isset($composer['scripts']['post-autoload-dump'])) {
                $composer['scripts']['post-autoload-dump'] = array_map(function ($command) use ($newNamespace) {
                    return str_replace('{{NAMESPACE}}', $newNamespace, $command);
                }, $composer['scripts']['post-autoload-dump']);
                
                file_put_contents($composerFile, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                echo "Namespace di composer.json (post-autoload-dump) berhasil diperbarui.\n";
            }
        }
    }
     

    if (php_sapi_name() === 'cli') {
        echo "Apakah Anda yakin ingin mengganti NAMESPACE? (y/n): ";
        $confirm = trim(fgets(STDIN));

        if (strtolower($confirm) === 'y') {
            $composer = json_decode(file_get_contents(__DIR__ . '/composer.json'), true);
            if (!isset($composer['autoload']['psr-4'])) {
                die("PSR-4 autoload tidak ditemukan di composer.json\n");
            }

            $newNamespace = array_keys($composer['autoload']['psr-4'])[0];
            $newNamespace = rtrim($newNamespace, '\\');

            echo "Namespace baru: $newNamespace\n";

            handleNamespaceUpdate($newNamespace);
        } else {
            echo "Proses pembaruan namespace dibatalkan.\n";
        }
    } else {
        if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
            $composer = json_decode(file_get_contents(__DIR__ . '/composer.json'), true);
            if (!isset($composer['autoload']['psr-4'])) {
                die("PSR-4 autoload tidak ditemukan di composer.json\n");
            }

            $newNamespace = array_keys($composer['autoload']['psr-4'])[0];
            $newNamespace = rtrim($newNamespace, '\\');

            echo "Namespace baru: $newNamespace\n";

            handleNamespaceUpdate($newNamespace);
        } else {
            echo '
            <!DOCTYPE html>
            <html lang="id">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Konfirmasi Ganti Namespace</title>
                <script src="https://cdn.tailwindcss.com"></script>
            </head>
            <body class="bg-gray-100">
                <div class="flex justify-center items-center min-h-screen">
                    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
                        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Apakah Anda yakin ingin mengganti NAMESPACE?</h2>
                        <p class="text-gray-600 mb-6">Setelah mengganti, namespace di semua file terkait akan diperbarui.</p>
                        <form method="POST" class="flex justify-between">
                            <button type="submit" name="confirm" value="yes" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400">Iya</button>
                            <button type="submit" name="confirm" value="no" class="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400">Tidak</button>
                        </form>
                    </div>
                </div>
            </body>
            </html>';
        }
    }
?>
