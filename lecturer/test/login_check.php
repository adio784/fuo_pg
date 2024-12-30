<?php

session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['user_status'])) {
    // Load necessary files
    require_once '../core/autoload.php';
    require_once '../core/Database.php';
    require_once '../common/CRUD.php';

    $database   = new Database();
    $Crud       = new CRUD($database);
    $uid        = $_SESSION['user_id'];
    $User       = $Crud->read('users', 'username', $uid);
    $role       = $User->role;
    $uri        = $_SESSION['HTTP_HOST'];

    if ($role === 'not_lecturer') {
        header("Location: not_lecturer");

    } else if ($role === 'lecturer') {
        header("Location: lecturer_home");

    } else if ($role === 'banned') {
        session_destroy();

        header("Location: index");

    } else {
        header("Location: index");
    }
        }
?>
