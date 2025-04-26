<?php
    namespace {{NAMESPACE}}\App;

    class Blueprint {
        private $table;
        private $columns = [];
        private $primaryKey = null;

        public function __construct($table) {
            $this->table = $table;
        }

        public function increments($column) {
            $this->columns[] = "`$column` INT UNSIGNED AUTO_INCREMENT";
            $this->primaryKey = "`$column`";
            return $this;
        }

        public function string($column, $length = 255) {
            $this->columns[] = "`$column` VARCHAR($length)";
            return $this;
        }

        public function integer($column, $unsigned = false) {
            $unsigned = $unsigned ? " UNSIGNED" : "";
            $this->columns[] = "`$column` INT$unsigned";
            return $this;
        }

        public function text($column) {
            $this->columns[] = "`$column` TEXT";
            return $this;
        }

        public function boolean($column) {
            $this->columns[] = "`$column` TINYINT(1)";
            return $this;
        }

        public function timestamp($column) {
            $this->columns[] = "`$column` TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
            return $this;
        }

        public function nullable() {
            $lastColumn = array_pop($this->columns);
            $this->columns[] = $lastColumn . " NULL";
            return $this;
        }

        public function unique() {
            $lastColumn = explode(' ', $this->columns[count($this->columns) - 1])[0];
            $this->columns[] = "UNIQUE ($lastColumn)";
            return $this;
        }

        public function default($value) {
            $lastColumn = array_pop($this->columns);
            $defaultValue = is_string($value) ? "'$value'" : $value;
            $this->columns[] = $lastColumn . " DEFAULT $defaultValue";
            return $this;
        }

        public function getColumns() {
            return $this->columns;
        }

        public function getPrimaryKey() {
            return $this->primaryKey;
        }
    }
?>