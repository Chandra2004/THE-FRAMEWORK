<?php
    namespace {{NAMESPACE}}\Models;

    use {{NAMESPACE}}\App\CacheManager;
    use {{NAMESPACE}}\App\Database;
    use {{NAMESPACE}}\App\Config;

    class HomeModel {
        private $db;

        public function __construct()
        {
            $this->db = Database::getInstance();
        }

        public function getUserData() {
            return CacheManager::remember(
                'all_users', 
                60, 
                function() {
                    $this->db->query("SELECT * FROM users");
                    $data['users'] = $this->db->resultSet();
                    return $data;
                }
            );
        }

        public function getUserDetail($id) {
            return CacheManager::remember(
                "user_detail:{$id}", 60, function() use ($id) { 
                    $this->db->query("SELECT * FROM users WHERE id = :id");
                    $this->db->bind('id', $id);
                    $data = $this->db->single();
                    return $data;
                }
            );
        }

        public function deleteUserData($id) {
            $this->db->query("SELECT profile_picture FROM users WHERE id = :id");
            $this->db->bind(':id', $id);
            $user = $this->db->single();
            

            if ($user && !empty($user['profile_picture'])) {
                $filePath = __DIR__ . '/../../htdocs/uploads/' . $user['profile_picture'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $this->db->query("DELETE FROM users WHERE id = :id");
            $this->db->bind(':id', $id);
            return $this->db->execute();
        }

        public function updateUserData($id, $name, $email, $profilePicture = null) {
            $query = "UPDATE users SET name = :name, email = :email" .
                     ($profilePicture ? ", profile_picture = :profile_picture" : "") .
                     " WHERE id = :id";
            $this->db->query($query);
            $this->db->bind(':name', $name);
            $this->db->bind(':email', $email);
            if ($profilePicture) {
                $this->db->bind(':profile_picture', $profilePicture);
            }
            $this->db->bind(':id', $id);
            return $this->db->execute();
        }
    }
?>
