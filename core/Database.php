<?php
// core/Database.php
class Database {
    
    private $db;

    public function __construct() {
        // $this->db = new PDO('mysql:host=localhost;dbname=fuoedung_pg_school', 'fuoedung_pg_school', 'FountainUniversityPG');
        $this->db = new PDO('mysql:host=localhost;dbname=pg_school', 'root', '');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getConnection() {
        return $this->db;
    }
}
?>