<?php
    namespace Database\Seeders;

    use {{NAMESPACE}}\App\Database;
    use Faker\Factory;

    class UserSeeder {
        protected Database $db;

        public function __construct() {
            $this->db = Database::getInstance();
        }

        public function run() {
            $faker = Factory::create();
        
            $this->db->beginTransaction();
            try {
                for ($i = 0; $i < 500; $i++) {
                    $this->db->query("INSERT INTO users (username, password, created_at) VALUES (:username, :password, :created_at)");
                    $this->db->bind(':username', $faker->name);
                    $this->db->bind(':password', $faker->email);
                    $this->db->bind(':created_at', date('Y-m-d H:i:s'));
                    $this->db->execute(); // Tambahkan ini agar query berjalan
                }
                $this->db->commit();
                echo "Seeding successful!\n";
            } catch (\Exception $e) {
                $this->db->rollBack();
                echo "Error seeding: " . $e->getMessage();
            }
        }    
    }
?>