<?php
    namespace {{NAMESPACE}}\Models;

    use {{NAMESPACE}}\App\Database;

    class HomeModel {
        private $db;

        public function __construct()
        {
            $this->db = Database::getInstance();
        }

        public function getUserData() {
            $this->db->query("SELECT * FROM users");
            $data['users'] = $this->db->resultSet();

            return $data;
        }

        public function getUserDetail($id) {
            $this->db->query("SELECT * FROM users WHERE id = :id");
            $this->db->bind('id', $id);
            $data = $this->db->single();

            return $data;
        }
    }
?>
