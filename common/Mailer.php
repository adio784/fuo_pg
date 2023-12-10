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

        $this->mailer->SMTPDebug = SMTP::DEBUG_OFF;
        $this->mailer->isSMTP();
        $this->mailer->Host       = 'mail.fuo.edu.ng'; //'smtp.gmail.com';
        $this->mailer->SMTPAuth   = true;
        $this->mailer->Username   = 'pg@fuo.edu.ng'; //'adioridwan784@gmail.com';
        $this->mailer->Password   = 'FountainUniversity-PG'; //'oyja rpia qbyv zmsa';
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port       = 465; //587;

    }

    public function sendMail($to, $subject, $body, $name)
    {

        try {

            //Recipients
            $this->mailer->setFrom('pg@fuo.edu.ng', 'Fountain University, School of Post Graduate Studies.');
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