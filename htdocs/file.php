<?php
    // Cegah akses langsung tanpa parameter
    if (!isset($_GET['file'])) {
        die("Access denied.");
    }

    // Pastikan hanya nama file atau file dalam folder dummy yang diterima
    $filename = basename($_GET['file']);
    $subfolder = dirname($_GET['file']); // Ambil subfolder jika ada
    $allowedFolders = ['dummy']; // Hanya izinkan folder ini

    if ($subfolder !== "." && !in_array($subfolder, $allowedFolders)) {
        die("Access denied.");
    }

    // Path lengkap ke file
    $filepath = __DIR__ . '/../private-uploads/' . ($_GET['file']);

    // Cek apakah file ada
    if (!file_exists($filepath)) {
        die("File not found.");
    }

    // Deteksi MIME type untuk keamanan
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $filepath);
    finfo_close($finfo);

    // Pastikan hanya gambar yang bisa diakses
    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($mime, $allowedMimeTypes)) {
        die("Invalid file type.");
    }

    // Tampilkan gambar dengan header yang sesuai
    header("Content-Type: $mime");
    readfile($filepath);
    exit;
?>
