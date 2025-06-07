<?php
    try {
        // Validate CLI argument
        if (!isset($argv[1])) {
            throw new Exception('Namespace not provided. Usage: php update-namespace.php <namespace>');
        }

        $newNamespace = rtrim($argv[1], '\\');
        if (!preg_match('/^[a-zA-Z][a-zA-Z0-9\\\\]*[a-zA-Z0-9]$/', $newNamespace)) {
            throw new Exception('Invalid namespace. Use alphanumeric characters and backslashes without spaces or special characters.');
        }

        // Read composer.json
        $composerFile = __DIR__ . '/composer.json';
        if (!file_exists($composerFile)) {
            throw new Exception('composer.json file not found.');
        }

        $composer = json_decode(file_get_contents($composerFile), true);
        if (!isset($composer['autoload']['psr-4'])) {
            throw new Exception('PSR-4 autoload not found in composer.json.');
        }

        // Define new PSR-4 mappings
        $newAutoload = [
            "$newNamespace\\" => "app/",
            "$newNamespace\\App\\" => "app/App/",
            "$newNamespace\\Http\\Controllers\\" => "app/Http/Controllers/",
            "$newNamespace\\Middleware\\" => "app/Middleware/",
            "$newNamespace\\Models\\" => "app/Models/",
            "$newNamespace\\Models\\Seeders\\" => "app/Models/Seeders/",
            "Database\\Migrations\\" => "database/migrations/",
            "Database\\Seeders\\" => "database/seeders/"
        ];

        $composer['autoload']['psr-4'] = $newAutoload;

        // Update scripts section (replace {{NAMESPACE}})
        if (isset($composer['scripts'])) {
            foreach ($composer['scripts'] as &$scriptCommands) {
                if (is_array($scriptCommands)) {
                    foreach ($scriptCommands as &$command) {
                        $command = str_replace('{{NAMESPACE}}', $newNamespace, $command);
                    }
                } elseif (is_string($scriptCommands)) {
                    $scriptCommands = str_replace('{{NAMESPACE}}', $newNamespace, $scriptCommands);
                }
            }
        }

        // Save composer.json
        file_put_contents($composerFile, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        echo "composer.json updated successfully.\n";

        // Directories and additional files to update namespace
        $directories = [
            __DIR__ . '/app',
            __DIR__ . '/database/migrations',
            __DIR__ . '/database/seeders'
        ];

        $additionalFiles = [
            __DIR__ . '/htdocs/index.php'
        ];

        // Function to update namespace inside PHP files
        function updateFileNamespace($file, $newNamespace) {
            $content = file_get_contents($file);
            $originalContent = $content;

            if (strpos($content, '{{NAMESPACE}}') !== false) {
                $content = str_replace('{{NAMESPACE}}', $newNamespace, $content);
            } else {
                $content = preg_replace(
                    '/namespace\s+[^;]+;/',
                    "namespace $newNamespace;",
                    $content
                );
            }

            if ($content !== $originalContent) {
                file_put_contents($file, $content);
                echo "Updated namespace in: $file\n";
            }
        }

        // Update PHP files in specified directories
        foreach ($directories as $dir) {
            if (!is_dir($dir)) {
                echo "Directory $dir not found, skipping.\n";
                continue;
            }

            $directoryIterator = new RecursiveDirectoryIterator($dir);
            $iterator = new RecursiveIteratorIterator($directoryIterator);

            foreach ($iterator as $file) {
                if ($file->getExtension() === 'php') {
                    updateFileNamespace($file->getPathname(), $newNamespace);
                }
            }
        }

        // Update additional specific files
        foreach ($additionalFiles as $file) {
            if (file_exists($file)) {
                updateFileNamespace($file, $newNamespace);
            } else {
                echo "File $file not found, skipping.\n";
            }
        }

        echo "Namespace successfully updated to $newNamespace.\n";

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        exit(1);
    }
?>