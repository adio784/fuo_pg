<?php
// core/Database.php
class Database {
    
    private $db;

    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;dbname=my_database', 'root', 'password');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getConnection() {
        return $this->db;
    }
}
?>
