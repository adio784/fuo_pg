<?php
session_start();

if ( isset($_SESSION['user_id']) && isset($_SESSION['admStatus']) ) {
    //FPG2332630
    require_once '../core/autoload.php';
    require_once '../core/Database.php';
    require_once '../common/CRUD.php';

    $database   = new Database();
    $Crud       = new CRUD($database);
    $uid        = $_SESSION['user_id'];
    $User      = $Crud->read('application', 'id', $uid);
    $sts        = $User->application_status;
    // var_export($User);
    if( $sts =='pre_register' ) {
        header('Location: payment');
    } else if ($sts =='paid') {
        header('Location: start_application');
    } else if ($sts =='registered') {
        header('Location: admission_home');
    } else {
        header('Location: admission_home');
    }

} else {
    header('Location: index');
}


?>