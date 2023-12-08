<?php

session_start();

if ( isset($_SESSION['user_id']) && isset($_SESSION['user_status']) ) {
    //FPG2332630
    require_once '../core/autoload.php';
    require_once '../core/Database.php';
    require_once '../common/CRUD.php';

    $database   = new Database();
    $Crud       = new CRUD($database);
    $uid        = $_SESSION['user_id'];
    $User      = $Crud->read('users', 'username', $uid);
    $sts        = $User->role;
    // echo $sts;
   
    if( $sts =='not_student' ) {

        header('Location: /fuo_pg/students/not_student');

    } else if ($sts =='student') {

        header('Location: /fuo_pg/students/student_dashboard');

    } else if ($sts =='banned') {

        session_destroy();
        header('Location: /fuo_pg/students/');

    } else {

        header('Location: /fuo_pg/students/');
    }

} else {
    header('Location: /fuo_pg/students/index');
}

?>