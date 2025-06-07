<?php
    namespace {{NAMESPACE}}\Http\Controllers;

    use Spatie\ImageOptimizer\OptimizerChainFactory;
    use {{NAMESPACE}}\App\{Config, Database, View, CacheManager};
    use {{NAMESPACE}}\Helpers\Helper;

    use {{NAMESPACE}}\Models\HomeModel;

    use Exception;
    use Defuse\Crypto\Crypto;

    class HomeController {
        private $homeModel;

        public function __construct() {
            $this->homeModel = new HomeModel();
        }

        public function index() {
            View::render('interface.home', ['status' => $this->checkDatabaseConnection()]);
        }

        public function user() {
            // Ambil flash message
            $notification = Helper::get_flash('notification');

            View::render('interface.user', [
                'userData' => $this->homeModel->getUserData()['users'] ?? [],
                'status' => $this->checkDatabaseConnection(),
                'notification' => $notification,
            ]);
        }

        public function detail(string $id) {
            $userDetail = $this->homeModel->getUserDetail($id);
            if (!$userDetail) return Helper::redirectToNotFound();

            // Ambil flash message
            $notification = Helper::get_flash('notification');

            View::render('interface.detail', [
                'userData' => $this->homeModel->getUserData()['users'] ?? [],
                'user' => $userDetail,
                'notification' => $notification,
            ]);
        }

        public function deleteUser(string $id) {
            try {
                $this->homeModel->deleteUserData($id);
                CacheManager::forget(['all_users', "user_detail:{$id}"]);
                Helper::redirect('/user', 'success', 'User deleted successfully');
            } catch (Exception $e) {
                Helper::redirect('/user', 'error', $e->getMessage());
            }
        }

        public function createUser() {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                // Ambil flash message
                $notification = Helper::get_flash('notification');

                return View::render('interface.user', [
                    'userData' => $this->homeModel->getUserData()['users'] ?? [],
                    'status' => $this->checkDatabaseConnection(),
                    'notification' => $notification,
                ]);
            }

            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $profilePicture = $_FILES['profile_picture'] ?? null;

            if (empty($name) || empty($email)) {
                return View::render('interface.user', [
                    'userData' => $this->homeModel->getUserData()['users'] ?? [],
                    'status' => $this->checkDatabaseConnection(),
                    'error' => "Name and email cannot be empty",
                ]);
            }

            $fileName = null;
            if ($profilePicture && $profilePicture['error'] === UPLOAD_ERR_OK) {
                $fileName = $this->processImage($profilePicture);
                if ($fileName instanceof Exception) {
                    return View::render('interface.user', [
                        'userData' => $this->homeModel->getUserData()['users'] ?? [],
                        'status' => $this->checkDatabaseConnection(),
                        'error' => $fileName->getMessage(),
                    ]);
                }
            }

            try {
                $this->homeModel->createUser($name, $email, $fileName);
                CacheManager::forget('all_users');
                Helper::redirect('/user', 'success', 'User created successfully');
            } catch (Exception $e) {
                View::render('interface.user', [
                    'userData' => $this->homeModel->getUserData()['users'] ?? [],
                    'status' => $this->checkDatabaseConnection(),
                    'error' => "Failed to save user data: " . $e->getMessage(),
                ]);
            }
        }

        public function updateUser(string $id) {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') return Helper::redirect("/user/{$id}");

            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $profilePicture = $_FILES['profile_picture'] ?? null;
            $deleteProfilePicture = isset($_POST['delete_profile_picture']);

            if (empty($name) || empty($email)) {
                return Helper::redirect("/user/{$id}", 'error', 'Name and Email cannot be empty');
            }

            $userData = $this->homeModel->getUserDetail($id);
            if (!$userData) {
                return Helper::redirect("/user/{$id}", 'error', 'User not found');
            }

            $fileName = $userData['profile_picture'];
            if ($deleteProfilePicture && $fileName) {
                // $uploadDir = dirname(__DIR__, 2) . '/private-uploads/user-pictures/';
                $uploadDir = dirname(__DIR__, 3) . '/private-uploads/user-pictures/';
                $filePath = $uploadDir . $fileName;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $fileName = null;
            }

            if ($profilePicture && $profilePicture['error'] === UPLOAD_ERR_OK) {
                $fileName = $this->processImage($profilePicture, $id);
                if ($fileName instanceof Exception) {
                    return Helper::redirect("/user/{$id}", 'error', $fileName->getMessage());
                }
            }

            try {
                $this->homeModel->updateUserData($id, $name, $email, $fileName);
                Helper::redirect('/user', 'success', 'User updated successfully');
            } catch (Exception $e) {
                Helper::redirect("/user/{$id}", 'error', "Failed to update user data: " . $e->getMessage());
            }
        }

        private function checkDatabaseConnection() {
            try {
                Database::getInstance();
                return "success";
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }

        private function processImage($file, $userId = null) {
            if ($file['error'] !== UPLOAD_ERR_OK) {
                return new \Exception("Gagal upload gambar.");
            }

            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedExtensions)) {
                return new \Exception("Ekstensi file tidak didukung.");
            }

            $maxFileSize = 2 * 1024 * 1024; // 2MB
            if ($file['size'] > $maxFileSize) {
                return new \Exception("Ukuran file terlalu besar. Maksimal 2MB.");
            }

            $mime = mime_content_type($file['tmp_name']);
            $allowedMimeTypes = ['image/jpeg', 'image/png'];
            if (!in_array($mime, $allowedMimeTypes)) {
                return new \Exception("Tipe MIME tidak valid.");
            }

            // Buat direktori upload jika belum ada
            $uploadDir = ROOT_DIR . '/private-uploads/user-pictures/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Buat nama file unik dan path tujuan
            $fileName = uniqid('img_') . '.webp';
            $targetPath = $uploadDir . $fileName;

            // Resize dan konversi ke WebP
            $source = null;
            if ($ext === 'jpg' || $ext === 'jpeg') {
                $source = imagecreatefromjpeg($file['tmp_name']);
            } elseif ($ext === 'png') {
                $source = imagecreatefrompng($file['tmp_name']);
            }

            if (!$source) {
                return new \Exception("Gagal membaca gambar.");
            }

            // Resize jika terlalu lebar
            $maxWidth = 800;
            $width = imagesx($source);
            $height = imagesy($source);

            if ($width > $maxWidth) {
                $newHeight = intval($height * ($maxWidth / $width));
                $resized = imagecreatetruecolor($maxWidth, $newHeight);
                imagecopyresampled($resized, $source, 0, 0, 0, 0, $maxWidth, $newHeight, $width, $height);
                imagedestroy($source);
                $source = $resized;
            }

            // Simpan ke WebP
            imagewebp($source, $targetPath, 80);
            imagedestroy($source);

            // Hapus gambar lama (jika ada)
            if ($userId) {
                $old = $this->homeModel->getUserDetail($userId)['profile_picture'] ?? null;
                if ($old && file_exists($uploadDir . $old)) {
                    unlink($uploadDir . $old);
                }
            }

            return $fileName;
        }

    }
?>