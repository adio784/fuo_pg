<?php
session_start();

if ( isset($_SESSION['user_id']) ) {
    //FPG2332630
    if( $_SESSION['admStatus']=='pre_register' ) {
        header('Location: /fuo_pg/admission_portal/payment');
    } else if ($_SESSION['admStatus']=='paid') {
        header('Location: /fuo_pg/admission_portal/start_application');
    } else if ($_SESSION['admStatus']=='pending') {
        header('Location: /fuo_pg/admission_portal/home_not_admitted');
    } else {
        header('Location: /fuo_pg/admission_portal/home_admitted');
    }

} else {
    header('Location: /fuo_pg/admission_portal/home');
}


?>