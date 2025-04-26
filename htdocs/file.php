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
    $logFile = __DIR__ . '/../app/Storage/logs/file_access.log';
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - Requested file: $filepath\n", FILE_APPEND);

    // Cek apakah file yang diminta ada, jika tidak, gunakan file dummy
    if (!file_exists($filepath)) {
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - File not found: $filepath, falling back to dummy\n", FILE_APPEND);
        if (!file_exists($defaultDummyFile)) {
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - Default dummy file not found: $defaultDummyFile\n", FILE_APPEND);
            die("Default dummy file not found.");
        }
        $filepath = $defaultDummyFile; // Fallback ke file dummy
    }

    // Deteksi MIME type untuk keamanan
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $filepath);
    finfo_close($finfo);

    // Logging MIME type
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - MIME type detected: $mime for file: $filepath\n", FILE_APPEND);

    // Pastikan hanya gambar yang bisa diakses
    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($mime, $allowedMimeTypes)) {
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - Invalid MIME type: $mime for file: $filepath\n", FILE_APPEND);
        die("Invalid file type.");
    }

    // Tampilkan gambar dengan header yang sesuai
    header("Content-Type: $mime");
    readfile($filepath);
    exit;
?>