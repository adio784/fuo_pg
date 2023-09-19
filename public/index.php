<?php
require_once '../core/autoload.php';
require_once '../core/Database.php';

// Include the Auth class
require_once '../common/Auth.php';

// Start the session
session_start();

// Create a new instance of the Auth class
$auth = new Auth(new Database());

// Example: User registration
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if ($auth->register($username, $password)) {
        // Registration successful
    } else {
        // Registration failed
    }
}

// Example: User login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if ($auth->login($username, $password)) {
        // Login successful
    } else {
        // Login failed
    }
}

// Example: User logout
if (isset($_POST['logout'])) {
    $auth->logout();
}

// Example: Check if the user is authenticated
if ($auth->isAuthenticated()) {
    // User is authenticated
} else {
    // User is not authenticated
}
?>
