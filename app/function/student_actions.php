<?php

    session_start();
   
    if ( !isset($_SESSION['user_id']) && !isset($_SESSION['user_status']) ) {

        header('Location: /fuo_pg/students/');

    } else {

        require_once '../../core/autoload.php';
        require_once '../../core/Database.php';
        require_once '../../common/CRUD.php';
        require_once '../../common/Payment.php';
        require_once '../../common/Sanitizer.php';

        $database   = new Database();
        $Crud       = new CRUD($database);
        $Sanitizer  = new Sanitizer();
        $uid        = $_SESSION['user_id'];
        $User       = $Crud->read('users', 'username', $uid);
        $sts        = $User->role;
        $db         = $database->getConnection();


        // Change student password on first login ........................................
        if (isset($_POST['passwordChange'])) {

            if ( empty($_POST['oldPassword']) || empty($_POST['newPassword'])) {

                $response['status']     = 'error';
                $response['statusCode'] = 400;
                $response['message']    = "Fill All Fields !!!";

            } else {

                $oldPassword    = $Sanitizer->sanitizeInput($_POST['oldPassword']);
                $newPassword    = password_hash($Sanitizer->sanitizeInput($_POST['newPassword']), PASSWORD_BCRYPT);
                $username       = strtolower($uid);

                //  check old password ...................................................
                $checkOld       =   $db->prepare("SELECT username, password FROM users WHERE username=?");
                $checkOld->execute([$username]);
                $checkOldUser   = $checkOld->fetch(PDO::FETCH_ASSOC);

                if (password_verify($oldPassword, $checkOldUser['password'])) {

                    $userData           =   ["password" => $newPassword, "role"=> "student"];
                    $updatePassword     =   $Crud->update("users", "username", $username, $userData);

                    if ($updatePassword) {

                        session_destroy();
                        $response['status']     = 'success';
                        $response['statusCode'] = 200;
                        $response['message']    = "Password Successfully Changed, Now Login";

                    } else {

                        $response['status']     = 'error';
                        $response['statusCode'] = 400;
                        $response['message']    = "Unable To Change Password !!!";

                    }

                } else {

                    $response['status']     = 'error';
                    $response['statusCode'] = 400;
                    $response['message']    = "Old Password Does Not Match Our Record !!!";

                }
            }
            // Returning JavaScript Object Notation As Response ...............
            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
            // ................................................................


            


        }

    }

?>