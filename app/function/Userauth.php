<?php

require_once '../../core/autoload.php';
require_once '../../core/Database.php';
require_once '../../common/Auth.php';
require_once '../../common/Sanitizer.php';
require_once '../../common/CRUD.php';

$database   = new Database();
$auth       = new Auth($database);
$Sanitizer  = new Sanitizer;
$Crud       = new CRUD($database);

// Check if the login form has been submitted
if (isset($_POST['login'])) {

    $username = strtolower($Sanitizer->sanitizeInput($_POST['username']));
    $password = $Sanitizer->sanitizeInput($_POST['password']);

    // Use the login method to attempt user login
    if ($auth->login($username, $password)) {
        $user = $Crud->read('users', 'username', $username);
        if ($user->status == "active") {

            session_start();
            $_SESSION['user_id']        = $user->username;
            $_SESSION['userId']         = $user->id;
            $_SESSION['user_role']      = $user->role;
            $_SESSION['user_status']    = $user->status;
            $_SESSION['last_login']     = $user->last_login;

            // Checking individual role to login .................................
            if ($user->role == "student") {
                $user_login = "student_home";
            } elseif ($user->role == "lecturer") {
                $user_login = "lecturer_home";
            } elseif ($user->role == "admission") {
                $user_login = "admission_home";
            } elseif ($user->role == "admission_dept") {
                $user_login = "admission_home";
            } elseif ($user->role == "super_admin") {
                $user_login = "super_admin";
            } elseif ($user->role == "not_student") {
                $user_login = "not_student";
            } else {
                $user_login = "/";
            }
            // .......................................................................

            $response['status']         = 'success';
            $response['user_role']      = $user_login;
            $response['message']        = 'Account Successfully Logged In';

            $now        = date('Y-m-d h:m:i');
            $appData    = ["last_login" => $now];
            $Crud->update('users', 'username', $username, $appData);
            // Roles ++++++++++++++
            // + student          +
            // + lecturer         +
            // + super_admin      +
            // + admin            +
            // + admission        +
            // ++++++++++++++++++++

        } else {

            $response['status'] = 'error';
            $response['message'] = "Your Account Is Inactive, Contact ICT";
        }
    } else {

        $response['status'] = 'error';
        $response['message'] = "Invalid Username or Password.";
    }

    // Returning JavaScript Object Notation As Response ...............
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
    // ................................................................

}
