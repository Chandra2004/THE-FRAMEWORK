<<<<<<< HEAD
<?php
    namespace {{NAMESPACE}}\App;

    use Closure;
    use {{NAMESPACE}}\App\Database;
    use {{NAMESPACE}}\App\Blueprint;

    // class Schema {
    //     public static function create($table, Closure $callback) {
    //         $db = Database::getInstance();
    //         $blueprint = new Blueprint($table);
    //         $callback($blueprint);

    //         $sql = "CREATE TABLE IF NOT EXISTS `$table` (";
    //         $sql .= implode(", ", $blueprint->getColumns());

    //         if ($blueprint->getPrimaryKey()) {
    //             $sql .= ", PRIMARY KEY (" . $blueprint->getPrimaryKey() . ")";
    //         }

    //         $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            
    //         $db->query($sql);
    //         $db->execute();
    //     }

    //     public static function dropIfExists($table) {
    //         $db = Database::getInstance();
    //         $sql = "DROP TABLE IF EXISTS `$table`;";
    //         $db->query($sql);
    //         $db->execute();
    //     }
    // }

    class Schema {
        public static function create($table, Closure $callback) {
            $db = Database::getInstance();
            $blueprint = new Blueprint($table);
            $callback($blueprint);
    
            $sql = "CREATE TABLE IF NOT EXISTS `$table` (";
            $sql .= implode(", ", $blueprint->getColumns());
    
            if ($blueprint->getPrimaryKey()) {
                $sql .= ", PRIMARY KEY (" . $blueprint->getPrimaryKey() . ")";
            }
    
            // Menambahkan foreign key ke dalam SQL
            foreach ($blueprint->getForeignKeys() as $foreignKey) {
                $sql .= ", $foreignKey";
            }
    
            $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            
            $db->query($sql);
            $db->execute();
        }
    
        public static function dropIfExists($table) {
            $db = Database::getInstance();
            $sql = "DROP TABLE IF EXISTS `$table`;";
            $db->query($sql);
            $db->execute();
        }
    }
    
=======
<?php
    namespace theframework\mapro\App;

    use Closure;
    use theframework\mapro\App\Database;
    use theframework\mapro\App\Blueprint;

    class Schema {
        public static function create($table, Closure $callback) {
            $db = Database::getInstance();
            $blueprint = new Blueprint($table);
            $callback($blueprint);

            $sql = "CREATE TABLE IF NOT EXISTS `$table` (";
            $sql .= implode(", ", $blueprint->getColumns());

            if ($blueprint->getPrimaryKey()) {
                $sql .= ", PRIMARY KEY (" . $blueprint->getPrimaryKey() . ")";
            }

            $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            
            $db->query($sql);
            $db->execute();
        }

        public static function dropIfExists($table) {
            $db = Database::getInstance();
            $sql = "DROP TABLE IF EXISTS `$table`;";
            $db->query($sql);
            $db->execute();
        }
    }
>>>>>>> 368a412195258ecde38491144f88c8387ff945a2
?>