<?php
require_once '../../core/autoload.php';
require_once '../../core/Database.php';
require_once '../../common/Auth.php';

$database = new Database();
$auth = new Auth($database);

// Check if the login form has been submitted
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Use the login method to attempt user login
    if ($auth->login($username, $password)) {
        // Login successful, you can redirect the user to a dashboard or home page
        header('Location: dashboard.php');
        exit();
    } else {
        // Login failed, display an error message or redirect to the login page with an error message
        $error = "Invalid username or password.";
        header('Location: login.php?error=Invalid%20username%20or%20password.');
        exit();
    }
}


?>