<?php 

class CRUD {
    private $db;

    public function __construct($database) {
        $this->db = $database->getConnection();
    }

    public function create($table, $data) {
        $fields = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $stmt = $this->db->prepare("INSERT INTO $table ($fields) VALUES ($placeholders)");

        $values = array_values($data);
        $stmt->execute($values);

        return $stmt->rowCount() > 0; // Return true if a row was inserted
    }

    public function register($table, $data) {
        $fields = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $stmt = $this->db->prepare("INSERT INTO $table ($fields) VALUES ($placeholders)");

        $values = array_values($data);
        $hashedPassword = password_hash($values['password'], PASSWORD_BCRYPT); // Securely hash the password
        $values['password'] = $hashedPassword;

        $stmt->execute($values);

        return $stmt->rowCount() > 0; // Return true if a row was inserted
    }

    public function read($table, $field, $id) {
        $stmt = $this->db->prepare("SELECT * FROM $table WHERE $field = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ); // Return data as an associative array
    }

    public function countAll($table) {
        $stmt = $this->db->prepare("SELECT COUNT(id) AS num FROM $table");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ); // Return data as an associative array
    }

    public function countByOne($table, $field, $id) {
        $stmt = $this->db->prepare("SELECT COUNT(id) AS num FROM $table WHERE $field = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ); // Return data as an associative array
    }

    public function countByTwo($table, $field, $id, $field2, $val) {
        $stmt = $this->db->prepare("SELECT COUNT(id) AS num FROM $table WHERE $field = ? AND $field2 = ?");
        $stmt->execute([$id, $val]);
        return $stmt->fetch(PDO::FETCH_OBJ); // Return data as an associative array
    }
    
    public function readAll($tableName) {
        try {
            $query = "SELECT * FROM $tableName";
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $result;
        } catch (PDOException $e) {
            // Handle database errors here
            return false;
        }
    }

    public function readAllBy($tableName, $field, $value) {
        try {
            $query = "SELECT * FROM $tableName WHERE $field = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$value]);

            $result = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $result;
        } catch (PDOException $e) {
            // Handle database errors here
            return false;
        }
    }

    public function readAllByTwo($tableName, $field, $value, $cond, $field2, $value2) {
        try {
            $query = "SELECT * FROM $tableName WHERE $field = ? $cond $field2 =? ORDER BY id DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$value, $value2]);

            $result = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $result;
        } catch (PDOException $e) {
            // Handle database errors here
            return false;
        }
    }

    public function readAllByThree($tableName, $field, $value, $cond, $field2, $value2, $cond2, $field3, $value3) {
        try {
            $query = "SELECT * FROM $tableName WHERE $field = ? $cond $field2 =? $cond2 $field3 = ? ORDER BY id DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$value, $value2, $value3]);

            $result = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $result;
        } catch (PDOException $e) {
            // Handle database errors here
            return false;
        }
    }
    

    public function readAllByOrder($tableName, $field, $value, $orderby, $order) {
        try {
            $query = "SELECT * FROM $tableName WHERE $field = ? ORDER BY $orderby $order";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$value]);

            $result = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $result;
        } catch (PDOException $e) {
            // Handle database errors here
            return false;
        }
    }

    public function update($table, $field, $id, $data) {
        $updates = [];
        $values = array_values($data);

        foreach ($data as $key => $value) {
            $updates[] = "$key = ?";
        }

        $updates = implode(', ', $updates);
        $values[] = $id;

        $stmt = $this->db->prepare("UPDATE $table SET $updates WHERE $field = ?");
        $stmt->execute($values);

        return $stmt->rowCount() > 0; // Return true if a row was updated
    }



    public function delete($table, $id) {
        $stmt = $this->db->prepare("DELETE FROM $table WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0; // Return true if a row was deleted
    }
}


// $crud = new CRUD($database);

// // Create a new record in a specific table
// $crud->create('users', ['username' => 'John', 'email' => 'john@example.com', 'password' => 'password']);

// // Read data from a specific table
// $userData = $crud->read('users', 1);

// // Update a record in a specific table
// $crud->update('users', 1, ['email' => 'updated@example.com']);

// // Delete a record from a specific table
// $crud->delete('users', 2);
?>