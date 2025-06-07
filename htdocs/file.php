<?php
    // Cegah akses langsung tanpa parameter
    if (!isset($_GET['file'])) {
        die("Access denied.");
    }

    // Pastikan hanya nama file atau file dalam folder yang diizinkan yang diterima
    $filename = basename($_GET['file']);
    $subfolder = dirname($_GET['file']); // Ambil subfolder jika ada
    $allowedFolders = ['dummy', 'user-pictures']; // Hanya izinkan folder ini

    // Tolak akses jika tidak ada subfolder atau subfolder tidak diizinkan
    if ($subfolder === "." || !in_array($subfolder, $allowedFolders)) {
        die("Access denied: Invalid folder.");
    }

    // Path lengkap ke file
    $baseUploadDir = __DIR__ . '/../private-uploads/';
    $filepath = $baseUploadDir . $_GET['file'];

    // File dummy default
    $defaultDummyFile = $baseUploadDir . 'dummy/dummy.jpg';

    // Logging untuk debugging
    $logDir = __DIR__ . '/../app/Storage/logs/';
    $logFile = $logDir . 'file_access.log';

    // Buat direktori jika belum ada
    if (!file_exists($logDir)) {
        mkdir($logDir, 0777, true);
    }

    // Fungsi untuk logging dengan penanganan error
    function safeLog($message, $logFile) {
        if (file_exists(dirname($logFile)) && is_writable(dirname($logFile))) {
            file_put_contents($logFile, $message, FILE_APPEND);
        }
    }

    // Log permintaan
    safeLog(date('Y-m-d H:i:s') . " - Requested file: $filepath\n", $logFile);

    // Cek apakah file yang diminta ada, jika tidak, gunakan file dummy
    if (!file_exists($filepath)) {
        safeLog(date('Y-m-d H:i:s') . " - File not found: $filepath, falling back to dummy\n", $logFile);
        if (!file_exists($defaultDummyFile)) {
            safeLog(date('Y-m-d H:i:s') . " - Default dummy file not found: $defaultDummyFile\n", $logFile);
            die("Default dummy file not found.");
        }
        $filepath = $defaultDummyFile; // Fallback ke file dummy
    }

    // Deteksi MIME type untuk keamanan
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $filepath);
    finfo_close($finfo);

    // Fallback: Tentukan MIME type berdasarkan ekstensi jika finfo gagal
    if (!$mime) {
        $extension = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp',
        ];
        $mime = $mimeTypes[$extension] ?? 'application/octet-stream';
        safeLog(date('Y-m-d H:i:s') . " - Fallback MIME type: $mime for file: $filepath\n", $logFile);
    }

    // Log MIME type
    safeLog(date('Y-m-d H:i:s') . " - MIME type detected: $mime for file: $filepath\n", $logFile);

    // Pastikan hanya gambar yang bisa diakses
    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($mime, $allowedMimeTypes)) {
        safeLog(date('Y-m-d H:i:s') . " - Invalid MIME type: $mime for file: $filepath\n", $logFile);
        die("Invalid file type.");
    }

    // Tampilkan gambar dengan header yang sesuai
    header("Content-Type: $mime");
    header("Content-Length: " . filesize($filepath));
    readfile($filepath);
    exit;
?>