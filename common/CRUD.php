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
        $hashedPassword = password_hash($values['password'], PASSWORD_BCRYPT); // Securely hash the password
        $values['password'] = $hashedPassword;

        $stmt->execute($values);

        return $stmt->rowCount() > 0; // Return true if a row was inserted
    }

    public function read($table, $id) {
        $stmt = $this->db->prepare("SELECT * FROM $table WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Return data as an associative array
    }

    public function update($table, $id, $data) {
        $updates = [];
        $values = array_values($data);

        foreach ($data as $key => $value) {
            $updates[] = "$key = ?";
        }

        $updates = implode(', ', $updates);
        $values[] = $id;

        $stmt = $this->db->prepare("UPDATE $table SET $updates WHERE id = ?");
        $stmt->execute($values);

        return $stmt->rowCount() > 0; // Return true if a row was updated
    }

    public function delete($table, $id) {
        $stmt = $this->db->prepare("DELETE FROM $table WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0; // Return true if a row was deleted
    }
}

?>


$crud = new CRUD($database);

// Create a new record in a specific table
$crud->create('users', ['username' => 'John', 'email' => 'john@example.com', 'password' => 'password']);

// Read data from a specific table
$userData = $crud->read('users', 1);

// Update a record in a specific table
$crud->update('users', 1, ['email' => 'updated@example.com']);

// Delete a record from a specific table
$crud->delete('users', 2);
