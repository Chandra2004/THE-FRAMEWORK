<?php
    namespace {{NAMESPACE}}\Controller;

    use Spatie\ImageOptimizer\OptimizerChainFactory;
    use {{NAMESPACE}}\App\{Config, Database, View, CacheManager};
    use {{NAMESPACE}}\Models\HomeModel;
    use Exception;

    class HomeController {
        private $homeModel;

        public function __construct() {
            $this->homeModel = new HomeModel();
        }

        public function index() {
            View::render('interface.home', ['status' => $this->checkDatabaseConnection()]);
        }

        public function user() {
            View::render('interface.user', [
                'userData' => $this->homeModel->getUserData()['users'] ?? [],
                'status' => $this->checkDatabaseConnection(),
            ]);
        }

        public function detail(string $id) {
            $userDetail = $this->homeModel->getUserDetail($id);
            if (!$userDetail) return $this->redirectToNotFound();
            
            View::render('interface.detail', [
                'userData' => $this->homeModel->getUserData()['users'] ?? [],
                'user' => $userDetail,
            ]);
        }

        public function deleteUser(string $id) {
            try {
                $this->homeModel->deleteUserData($id);
                CacheManager::forget(['all_users', "user_detail:{$id}"]);
                $this->redirect('/user', 'success', 'User deleted successfully');
            } catch (Exception $e) {
                $this->redirect('/user', 'error', $e->getMessage());
            }
        }

        public function createUser() {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') return View::render('interface.user');
        
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $profilePicture = $_FILES['profile_picture'] ?? null;
        
            if (empty($name) || empty($email)) {
                return View::render('interface.user', ['error' => "Name and email cannot be empty"]);
            }
        
            $fileName = null; // Default null jika tidak ada foto
            if ($profilePicture && $profilePicture['error'] === UPLOAD_ERR_OK) {
                $fileName = $this->processImage($profilePicture);
                if ($fileName instanceof Exception) {
                    return View::render('interface.user', ['error' => $fileName->getMessage()]);
                }
            }
        
            try {
                $db = Database::getInstance();
                $db->query("INSERT INTO users (name, email, profile_picture) VALUES (:name, :email, :profile_picture)");
                
                $db->bind(':name', $name);
                $db->bind(':email', $email);
                $db->bind(':profile_picture', $fileName); // Bisa null jika tidak ada foto
                
                $db->execute();
                $this->redirect('/user');
            } catch (Exception $e) {
                View::render('interface.user', ['error' => "Failed to save user data: " . $e->getMessage()]);
            }
        }        

        public function updateUser(string $id) {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') return $this->redirect("/user/{$id}");
        
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $profilePicture = $_FILES['profile_picture'] ?? null;
            $deleteProfilePicture = isset($_POST['delete_profile_picture']); // Cek apakah user ingin menghapus foto
        
            if (empty($name) || empty($email)) {
                return $this->redirect("/user/{$id}", 'error', 'Name and Email cannot be empty');
            }
        
            // Ambil data user lama untuk mempertahankan foto lama jika tidak di-update
            $userData = $this->homeModel->getUserDetail($id);
            if (!$userData) {
                return $this->redirect("/user/{$id}", 'error', 'User not found');
            }
        
            $fileName = $userData['profile_picture']; // Default pakai foto lama
        
            // Jika user ingin menghapus foto, set profile_picture ke NULL dan hapus file lama
            if ($deleteProfilePicture && $fileName) {
                $uploadDir = __DIR__ . '/../../htdocs/uploads/';
                $filePath = $uploadDir . $fileName;
                if (file_exists($filePath)) {
                    unlink($filePath); // Hapus file dari server
                }
                $fileName = null; // Set profile_picture menjadi NULL di database
            }
        
            // Jika user mengunggah foto baru, ganti dengan yang baru
            if ($profilePicture && $profilePicture['error'] === UPLOAD_ERR_OK) {
                $fileName = $this->processImage($profilePicture, $id);
                if ($fileName instanceof Exception) {
                    return $this->redirect("/user/{$id}", 'error', $fileName->getMessage());
                }
            }
        
            try {
                $db = Database::getInstance();
                $db->query("UPDATE users SET name = :name, email = :email, profile_picture = :profile_picture WHERE id = :id");
                
                $db->bind(':name', $name);
                $db->bind(':email', $email);
                $db->bind(':profile_picture', $fileName); // Bisa null jika dihapus
                $db->bind(':id', $id);
                
                $db->execute();
                CacheManager::forget(['all_users', "user_detail:{$id}"]);
                $this->redirect('/user', 'success', 'User updated successfully');
            } catch (Exception $e) {
                $this->redirect("/user/{$id}", 'error', "Failed to update user data: " . $e->getMessage());
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
            if ($file['error'] !== UPLOAD_ERR_OK) return new Exception("Failed to upload profile picture");
        
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedExtensions)) return new Exception("Invalid file type");
        
            $maxFileSize = 2 * 1024 * 1024;
            if ($file['size'] > $maxFileSize) return new Exception("File size exceeds 2MB limit");
        
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if (!in_array($mime, $allowedMimeTypes)) {
                return new Exception("Invalid file type.");
            }

        
            $uploadDir = dirname(__DIR__, 2) . '/private-uploads/';
            if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
        
            $fileName = uniqid() . '.webp';
            $filePath = $uploadDir . $fileName;
            move_uploaded_file($file['tmp_name'], $filePath);
        
            $image = imagecreatefromstring(file_get_contents($filePath));
            if (!$image) return new Exception("Failed to process the image");
        
            imagewebp($image, $filePath, 70);
            imagedestroy($image);
        
            OptimizerChainFactory::create()->optimize($filePath);
        
            if ($userId) {
                $oldProfilePicture = $this->homeModel->getUserDetail($userId)['profile_picture'] ?? null;
                if ($oldProfilePicture && file_exists($uploadDir . $oldProfilePicture)) unlink($uploadDir . $oldProfilePicture);
            }
        
            return $fileName;
        }        

        private function redirectToNotFound() {
            header("Location: " . Config::get('BASE_URL') . "/404");
            exit();
        }

        private function redirect($url, $status = null, $message = null) {
            $query = $status ? "?status={$status}&message=" . urlencode($message) : '';
            header("Location: " . Config::get('BASE_URL') . "{$url}{$query}");
            exit();
        }
    }
?>