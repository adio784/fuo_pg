<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once '../../core/autoload.php';
require_once '../../core/Database.php';
require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';


$database   = new Database();
$mail       = new PHPMailer(true);

if (isset($_POST['login'])) {
    
    // getConnection
    $username = strtoupper(htmlspecialchars($_POST['username']));
    $password = ucfirst(htmlspecialchars($_POST['password']));

    // Perform user login using the database connection
    $stmt = $database->getConnection()->prepare('SELECT id, application_id, last_name, application_status FROM `application` WHERE application_id = ? LIMIT 1');
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $password == $user['last_name']) {

        // Login successful, set session variables or perform other actions
        session_start();
        $_SESSION['user_id']    = $user['id'];
        $_SESSION['app_id']     = $user['application_id'];
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
    $firstname      = ucfirst(htmlspecialchars($_POST['firstname']));
    $lastname       = ucfirst(htmlspecialchars($_POST['lastname']));
    $middlename     = ucfirst(htmlspecialchars($_POST['middlename']));
    $email          = htmlspecialchars($_POST['email']);
    $phoneNumber    = htmlspecialchars($_POST['phoneNumber']);
    $applicationID  = 'FPG'.date('y').rand(9,99999);
    $response       = [];
    $fullname       = $lastname. ' '. $firstname. ' '. $middlename;
    $application_status = 'pre_register';


    // Perform user login using the database connection.................................................................................
    $stmt = $database->getConnection()->prepare('SELECT id, application_id, last_name FROM `application` WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user == "") {

        $createUser = $database->getConnection()->prepare("INSERT INTO `application` (application_id, first_name, middle_name, last_name, phone_number, email, application_status) VALUES(?, ?, ?, ?, ?, ?, ?) ");
        $createUser->execute([$applicationID, $firstname, $middlename, $lastname, $phoneNumber, $email, $application_status]);

        if($createUser)
        {
        
            try {
                //Server settings
                $mail->SMTPDebug = SMTP::DEBUG_OFF; // Enable verbose debugging (0 for no debug output)
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'adioridwan784@gmail.com'; // Your Gmail email address
                $mail->Password   = 'uvrn ibfh mfrs kjti';       // Your Gmail password or app password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
                $mail->Port       = 587; // TCP port to connect to

                //Recipients
                $mail->setFrom('info@fuo.edu.ng', 'Fountain University, School Of Post Graduate.');
                $mail->addAddress($email, $fullname); // Add recipient email address and name

                // Content
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = 'Online Application';
                $mail->Body    .= 'Hello! '. $fullname. '<br>';
                $mail->Body    .= 'Thank you for signin up on our portal, here is your login details: <br><br>';
                $mail->Body    .= 'Application ID: '. '<b>'. $applicationID .'</b><br>';
                $mail->Body    .= 'Password: '. '<b>'. $lastname .'</b><br><br>';
                $mail->Body    .= 'For more information or enquiry, contact us. <br>';
                $mail->Body    .= '<b> NB:</b> Do not reply to this email';

                $mail->send();
                $response['status'] = 'success';
                $response['message'] = 'Account Successfully Created, Check your inbox for application ID';
            } catch (Exception $e) {

                $response['status'] = 'error';
                $response['message'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";

            }

            // $response['status'] = 'success';
            // $response['message'] = 'Account Successfully Created, Check your inbox for application ID';

        }
        else
        {
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



?>