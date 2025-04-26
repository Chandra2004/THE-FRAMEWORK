<?php
    function handleNamespaceUpdate($newNamespace) {
        try {
            $directories = [
                __DIR__ . '/app',
                __DIR__ . '/database/migrations',
                __DIR__ . '/database/seeders'
            ];

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

            $indexFile = __DIR__ . '/htdocs/index.php';
            if (file_exists($indexFile)) {
                $content = file_get_contents($indexFile);
                $updatedContent = str_replace('{{NAMESPACE}}', $newNamespace, $content);
                file_put_contents($indexFile, $updatedContent);
            }

            $composerFile = __DIR__ . '/composer.json';
            if (file_exists($composerFile)) {
                $composer = json_decode(file_get_contents($composerFile), true);
                
                if (isset($composer['scripts']['post-autoload-dump'])) {
                    $composer['scripts']['post-autoload-dump'] = array_map(function ($command) use ($newNamespace) {
                        return str_replace('{{NAMESPACE}}', $newNamespace, $command);
                    }, $composer['scripts']['post-autoload-dump']);
                    
                    file_put_contents($composerFile, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                }
            }

            return ['success' => true, 'message' => 'Namespace berhasil diperbarui di semua file dan konfigurasi!'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Gagal memperbarui namespace: ' . $e->getMessage()];
        }
    }

    function renderUI($title, $message, $isSuccess = true, $namespace = null) {
        $color = $isSuccess ? 'cyan' : 'red';
        $icon = $isSuccess ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z';
        ?>
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?= $title ?></title>
            <script src="https://cdn.tailwindcss.com"></script>
        </head>
        <body class="bg-gradient-to-br from-gray-900 to-gray-800 min-h-screen">
            <main class="flex justify-center items-center min-h-screen px-4">
                <div class="bg-gray-800/50 backdrop-blur-lg p-8 rounded-2xl shadow-xl border border-gray-700/50 w-full max-w-md">
                    <div class="text-center">
                        <div class="mb-6 flex justify-center">
                            <svg class="w-16 h-16 <?= 'text-' . $color . '-400' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="<?= $icon ?>"/>
                            </svg>
                        </div>
                        
                        <h1 class="text-2xl font-bold text-gray-100 mb-2"><?= $title ?></h1>
                        <p class="text-gray-400 mb-6"><?= $message ?></p>
                        
                        <?php if($namespace): ?>
                        <div class="bg-gray-700/30 rounded-lg p-4 mb-6">
                            <code class="text-sm text-<?= $color ?>-400"><?= $namespace ?></code>
                        </div>
                        <?php endif; ?>
                        
                        <a href="{{ url('/') }}" class="inline-block bg-gray-700/50 hover:bg-gray-700/70 text-gray-300 px-6 py-2 rounded-lg transition-colors">
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </main>
        </body>
        </html>
        <?php
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
            try {
                $composer = json_decode(file_get_contents(__DIR__ . '/composer.json'), true);
                if (!isset($composer['autoload']['psr-4'])) {
                    throw new Exception("PSR-4 autoload tidak ditemukan di composer.json");
                }

                $newNamespace = array_keys($composer['autoload']['psr-4'])[0];
                $newNamespace = rtrim($newNamespace, '\\');

                $result = handleNamespaceUpdate($newNamespace);
                
                if ($result['success']) {
                    renderUI(
                        'Update Berhasil!', 
                        $result['message'],
                        true,
                        $newNamespace
                    );
                } else {
                    renderUI(
                        'Update Gagal!', 
                        $result['message'],
                        false,
                        $newNamespace
                    );
                }
            } catch (Exception $e) {
                renderUI(
                    'Terjadi Kesalahan!', 
                    $e->getMessage(),
                    false
                );
            }
        } else {
            ?>
            <!DOCTYPE html>
            <html lang="id">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Konfirmasi Update Namespace</title>
                <script src="https://cdn.tailwindcss.com"></script>
            </head>
            <body class="bg-gradient-to-br from-gray-900 to-gray-800 min-h-screen">
                <main class="flex justify-center items-center min-h-screen px-4">
                    <div class="bg-gray-800/50 backdrop-blur-lg p-8 rounded-2xl shadow-xl border border-gray-700/50 w-full max-w-md">
                        <div class="text-center">
                            <div class="mb-6 flex justify-center">
                                <div class="w-16 h-16 bg-cyan-400/10 rounded-2xl flex items-center justify-center">
                                    <svg class="w-8 h-8 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <h1 class="text-2xl font-bold text-gray-100 mb-2">Konfirmasi Update Namespace</h1>
                            <p class="text-gray-400 mb-6">Anda akan mengubah namespace aplikasi. Pastikan sudah melakukan backup!</p>
                            
                            <form method="POST" class="flex flex-col gap-4">
                                <button type="submit" name="confirm" value="yes" 
                                        class="bg-cyan-500/20 hover:bg-cyan-500/30 text-cyan-400 px-6 py-3 rounded-lg 
                                            transition-all font-medium flex items-center justify-center gap-2">
                                    Konfirmasi Update
                                </button>
                                <a href="/" 
                                class="border border-gray-600/50 hover:border-gray-600/80 text-gray-300 px-6 py-3 
                                        rounded-lg transition-colors text-sm">
                                    Batalkan
                                </a>
                            </form>
                        </div>
                    </div>
                </main>
            </body>
            </html>
            <?php
        }
    }
?>
