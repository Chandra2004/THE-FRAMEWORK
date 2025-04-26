<?php
    namespace Database\Migrations;

    use {{NAMESPACE}}\Database\Migration;

    class CreateUsersTable extends Migration {
        public function up() {
            $sql = "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            $this->db->query($sql);
            $this->db->execute();
        }

        public function down() {
            $sql = "DROP TABLE IF EXISTS users";
            $this->db->query($sql);
            $this->db->execute();
        }
    }
?>
