<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once '../../core/autoload.php';
require_once '../../core/Database.php';
require_once '../../common/Sanitizer.php';
require_once '../../common/Mailer.php';
// require_once '../../AfricasTalkingSms/Mailer.php';

$database   = new Database();
$Sanitizer  = new Sanitizer;

$protocol       = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$url_protocol   = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$url            = $protocol . $_SERVER['HTTP_HOST'];
    
if (isset($_POST['login'])) {

    // getConnection
    $username = strtoupper(htmlspecialchars($_POST['username']));
    $password = ucfirst(htmlspecialchars($_POST['password']));

    // Perform user login using the database connection
    $stmt = $database->getConnection()->prepare('SELECT id, application_id, last_name, first_name, middle_name application_status FROM `application` WHERE application_id = ? LIMIT 1');
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $password == $user['last_name']) {

        // Login successful, set session variables or perform other actions
        session_start();
        $_SESSION['user_id']    = $user['id'];
        $_SESSION['app_id']     = $user['application_id'];
        $_SESSION['full_name']  = $user['last_name'] . ' ' . $user['first_name'];
        $_SESSION['admStatus']  = $user['application_status'];

        $response['status']     = 'success';
        $response['message']    = 'Account Successfully Logged In';
    } else {

        $response['status'] = 'error';
        $response['message'] = "Invalid Username or Password.";
        // header('Location: /fuo_pg/admission_portal/index.php?error=Invalid%20username%20or%20password.');

    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}



if (isset($_POST['register'])) {

    // getConnection
    $firstname      = ucfirst($Sanitizer->sanitizeInput($_POST['firstname']));
    $lastname       = ucfirst($Sanitizer->sanitizeInput($_POST['lastname']));
    $middlename     = ucfirst($Sanitizer->sanitizeInput($_POST['middlename']));
    $email          = $Sanitizer->sanitizeInput($_POST['email']);
    $phoneNumber    = $Sanitizer->sanitizeInput($_POST['phoneNumber']);
    $applicationID  = 'FPG' . date('y') . rand(9, 99999);
    $response       = [];
    $fullname       = $lastname . ' ' . $firstname . ' ' . $middlename;
    $Mailer         =   new Mailer();
    $application_status = 'pre_register';


    // Perform user login using the database connection.................................................................................
    $stmt = $database->getConnection()->prepare('SELECT id, application_id, last_name FROM `application` WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user == "") {

        $createUser = $database->getConnection()->prepare("INSERT INTO `application` (application_id, first_name, middle_name, last_name, phone_number, email, application_status) VALUES(?, ?, ?, ?, ?, ?, ?) ");
        $createUser->execute([$applicationID, $firstname, $middlename, $lastname, $phoneNumber, $email, $application_status]);

        // ++++++++++++++++++++ SEND MAIL TO ADMITTED USERS ++++++++++++++++++++++++++++++++++++++++++++++++++++++
        $subject    = 'FUO PG Admission';
        $body       = '<html>
                         <head>
                             <title>PG - Admission</title>
                         </head>
                         <body>
                             <h3> ADMISSION FUO | SCHOOL OF POSTGRADUATE STUDIES.</h3>
                             <h4>Hello, ' . $fullname . '</h4>
                             <h4> Congratulations ! </h4>
                             <p> Your account has been successfully created. Here are your login Details:</p>
                             <ul>
                                <li>username: ' . $applicationID . '</li>
                                <li>password: ' . $lastname . '</li>
                            </ul>
                             <a href="' . $url . '/admission_portal" style="width:100px;height:25px;background-color:green;color:#fff;text-decoration:none;padding: 4px;border-radius:10px">Click here to continue application </a>
                             <p> For further information, contact PG support. </p>
                             
                             <p> <b> NB:</b> Do not reply to this email </p>
                             <img src="https://fuo.edu.ng/wp-content/uploads/2021/02/logo.jpg" alt="Fountain University, Osogbo">
                         </body>
                     </html>';
        //  $Mailer->sendMail($email, $subject, $body, $fullName);
        // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

        if ($createUser) {
            $result = $Mailer->sendMail($email, $subject, $body, $fullname);

            if ($result === true) {

                // $pho_no     = "2347035743427";
                // $username   = "sandBox";
                // $apiKey     = "c4c956d8d7ba8511d301fc8cb964936f7074f520d596165197b2e1e1f939b26e";

                // $subject    = "SPGS, New Application";
                // $message    = $fullName . " " . " Has Just Completed An Application, Login to Follow Up.";

                $response['status'] = 'success';
                $response['app_id'] = $applicationID;
                $response['surname'] = $lastname;
                $response['message'] = "Account Created Successfully";
            } else {

                $response['status'] = 'success';
                $response['app_id'] = $applicationID;
                $response['surname'] = $lastname;
                $response['message'] = "Account Created Successfully, Unable To Send Mail.";
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error Occured ! Please check your input and try later ';
        }
    } else {

        $response['status'] = 'error';
        $response['message'] = 'Email Address Already Exist !!! ';
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}