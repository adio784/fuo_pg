<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';

class Mailer {

    private $mailer;

    public function __construct()
    {
        
        $this->mailer = new PHPMailer(true);

        $this->mailer->SMTPDebug = SMTP::DEBUG_OFF;              //SMTP::DEBUG_OFF; //SMTP::DEBUG_SERVER;
        $this->mailer->isSMTP();
        $this->mailer->Host       = 'mail.fuo.edu.ng';
        $this->mailer->SMTPAuth   = true;
        $this->mailer->Username   = 'pgschool@fuo.edu.ng';
        $this->mailer->Password   = 'Fount@!nUniversity';
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;        //PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port       = 465;                                //587
        
    }

    public function sendMail($to, $subject, $body, $name)
    {

        try {

            $schName = 'Fountain University, School of Post Graduate Studies.';
            //Recipients
            $this->mailer->setFrom('pgschool@fuo.edu.ng', $schName);
            $this->mailer->addAddress($to, $name); 

            // Content
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;

            $this->mailer->send();
            
            return true;

        } catch (Exception $e) {

            return "Message could not be sent to recipeint. Mailer Error: {$this->mailer->ErrorInfo}";

        }
    }
    
}


?>