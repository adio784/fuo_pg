<?php

    session_start();
    $protocol       = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url_protocol   = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $url            = $protocol . $_SERVER['HTTP_HOST'];


    // Payment Initialization
    if( !isset($_SESSION['user_id']) && !isset($_SESSION['user_status']) ){
        header('Location: admission_portal/index');
    } else {

        require_once '../../core/autoload.php';
        require_once '../../core/Database.php';
        require_once '../../common/CRUD.php';
        require_once '../../common/Payment.php';
        require_once '../../common/Sanitizer.php';
        require_once '../../common/Mailer.php';

        $database   =   new Database();
        $Crud       =   new CRUD($database);
        $Sanitizer  =   new Sanitizer();
        $Mailer     =   new Mailer();
        $xpressPay  =   new PAYMENT();
        $thisYear   =   date('Y');
        $db         =   $database->getConnection();

        // Get current session =============================================================
        $Session            = $Crud->read("access", "setting", "current_session");       //=
        $current_session    = $Session->value;                                           //=
        // =================================================================================

        // Recommend Student for Admission .................................................
        
        if ( isset($_GET['recommend']) )
        {
            $appID = $_GET['recommend'];
            $appData    = ["application_status" => "recommend", "application_session" => $current_session];
            $upd_admiss = $Crud->update("application", "application_id", $appID, $appData);

            if ($upd_admiss) {

                $response['status']         = 'success';
                $response['statusCode']     = 200;
                $response['message']        = 'Student Recommended for Admittion. ';

            } else {

                $response['status']         = 'fail';
                $response['statusCode']      = 400;
                $response['message']        = 'An Error Occured While Processing Your Request, Try Later!!!';

            }

            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
            
        }
        // .................................................................................


        // Admitting Student ---------------------------------------------------------------
        if ( isset($_GET['admit']) )
        {  
            
            $appID = $_GET['admit'];
        
            try {
            
            //  echo $appID;
                $qstd      = $db->prepare("SELECT matric_no, first_name FROM students WHERE application_id=?"); $qstd->execute([$appID]);
                
            //  Check if student already created ...............................................
            if ($qstd->rowCount() < 1) {
                $std       = $db->prepare("SELECT * FROM `application` WHERE application_id=?");
                $std->execute([$appID]);

                
                
                if ( $std->rowCount() > 0 ) {
                    
                    while ($row = $std->fetchObject() )
                    {
                        $nmatric    = "***";
                        $firstName  = $row->first_name;
                        $lastName   = $row->last_name;
                        $middleName = $row->middle_name;
                        $fullName   = strtoupper($lastName) . ' '. $firstName .' '. $middleName;
                        $email      = $row->email;
                        $phoneNumber= $row->phone_number;
                        $dob        = $row->date_of_birth;
                        $gender     = $row->gender;
                        $religion   = $row->religion;
                        $program    = $row->program;
                        $department = $row->department;
                        $college    = $row->course;
                    }
                    // registered
                    $username   = strtolower($appID);
                    $surname    = strtolower($lastName);
                    $password   = password_hash($surname, PASSWORD_BCRYPT);
                    $role       = "not_student";
                    $status     = "active";

                    $result = $Mailer->sendMail($email, $subject, $body, $fullName);

                    $createStudent  =   $db->prepare("INSERT INTO students (matric_no, application_id, last_name, first_name, middle_name, email, mobile_no, dob, gender, religion, admission_session, admission_year, course, department, program) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                    $createStudent->execute([ $nmatric, $appID, $lastName, $firstName, $middleName, $email, $phoneNumber, $dob, $gender, $religion, $current_session, $thisYear, $college, $department, $program]);

                    $createUser  =   $db->prepare("INSERT INTO users (username, email, password, role, status) VALUES(?,?,?,?,?)");
                    $createUser->execute([$username, $email, $password, $role, $status]);
                    
                    if ($createStudent && $createUser) {

                            $appData    = ["application_status" => "admitted", "application_session" => $current_session];
                            $upd_admiss = $Crud->update("application", "application_id", $appID, $appData);
                        

                            // ++++++++++++++++++++ SEND MAIL TO ADMITTED USERS ++++++++++++++++++++++++++++++++++++++++++++++++++++++
                            $subject    = 'FUO PG Admission';
                            $body       = '<html>
                                            <head>
                                                <title>PG - Admission</title>
                                            </head>
                                            <body>
                                                <h3> ADMISSION FUO | SCHOOL OF POST GRADUATE STUDIES.</h3>
                                                <h4>Hello, '.$fullName .'</h4>
                                                <p> Congratulations! You have been offered a provision admission at Fountain University, Osogbo.</p>
                                                <p> Proceed to you application portal, to make payment for the acceptance fee.</p>
                                                <p> You have been profiled on the student portal, which you are required to login and continue from as a student of our institution </p>
                                                <p> Here are your login Details:</p>
                                                <ul>
                                                    <li>username: '. $appID.'</li>
                                                    <li>password: '. $surname .'</li>
                                                </ul>
                                                <h4>Fountain University, Osogbo.</h4>
                                                <a href="' . $url . '/students/" style="width:100px;height:25px;background-color:green;color:#fff;text-decoration:none;padding: 4px;border-radius:10px">Click here to proceed to the student portal.</a>                                                
                                                <p> <b> NB:</b> Do not reply to this email </p>
                                                <img src="https://fuo.edu.ng/wp-content/uploads/2021/02/logo.jpg" alt="Fountain University, Osogbo">
                                            </body>
                                        </html>';
                                    // $Mailer->sendMail($email, $subject, $body, $fullName);
                            // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

                            if ($upd_admiss) {

                                $result = $Mailer->sendMail($email, $subject, $body, $fullName);

                                if ($result === true) {

                                    $response['status']         = 'success';
                                    $response['statusCode']     = 200;
                                    $response['message']        = 'Student Successfully Admited, and Profiled for Student Portal ';

                                } else {

                                    $response['status']         = 'fail';
                                    $response['statusCode']      = 400;
                                    $response['message']        = "Student Created Successfully, But Could Not Be Notified, Dues to System Error !!! {$result}";

                                }

                            } else {

                                $response['status']         = 'fail';
                                $response['statusCode']      = 400;
                                $response['message']        = 'Student Created, But Unable To Admit Student Due to System Error !!!';
        
                            }

                        } else {

                            $response['status']         = 'fail';
                            $response['statusCode']      = 400;
                            $response['message']        = 'Error Admitting Student !!!';

                        }
                    } else {

                        $response['status']         = 'fail';
                        $response['statusCode']      = 400;
                        $response['message']        = 'Unable to Profile Student !!!';

                    }
            
                } else {

                    $response['status']         = 'fail';
                    $response['statusCode']      = 400;
                    $response['message']        = 'Student Already Profiled, Contact ICT !!!';

                }
                // new_student
                // not_student
                // student
            } catch (\Throwable $th) {
                //throw $th;

                $response['status']         = 'fail';
                $response['statusCode']     = 500;
                $response['message']        = explode(',', $th);

            }

            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        }

        // Rejecting Student  --------------------------------------------------------------
        if ( isset($_GET['reject']) )
        {  
           $appID = $_GET['reject'];
           
            try {
               
               // Check if student already created ...............................................
              
                $std       = $db->prepare("SELECT * FROM `application` WHERE application_id=?");
                $std->execute([$appID]);

                if ( $std->rowCount() > 0 ) {

                    $appData    = ["application_status" => "rejected", "application_session" => $current_session];
                    $upd_admiss = $Crud->update("application", "application_id", $appID, $appData);

                    // ++++++++++++++++++++ SEND MAIL TO ADMITTED USERS ++++++++++++++++++++++++++++++++++++++++++++++++++++++
                    $subject    = 'FUO PG Admission';
                    $body       = '<html>
                                    <head>
                                        <title>PG - Admission</title>
                                    </head>
                                    <body>
                                        <h3> ADMISSION FUO | SCHOOL OF POST GRADUATE STUDIES.</h3>
                                        <h4>Hello, '.$fullName .'</h4>
                                        <p> We are sorry to let you know that you application does not meet up to our standard recommendation.</p>
                                        <p> You can always checkout for other programmes on our website</p>
                                        <p> Thank you for taking part in the application process </p>
                                        <p> Hope to receive your application, some other times.</p>
                                        <p> Best regards.</p>
                                        <img src="https://fuo.edu.ng/wp-content/uploads/2021/02/logo.jpg" alt="Fountain University, Osogbo">
                                    </body>
                                </html>';
                                // $Mailer->sendMail($email, $subject, $body, $fullName);
                    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

                    if ($upd_admiss) {

                        $result = $Mailer->sendMail($email, $subject, $body, $fullName);

                        if ($result === true) {

                            $response['status']         = 'success';
                            $response['statusCode']     = 200;
                            $response['message']        = 'Application Successfully Rejected And Notified !!! ';

                        } else {

                            $response['status']         = 'fail';
                            $response['statusCode']      = 400;
                            $response['message']        = "Application Successfully Rejected, Due To System Error !!! {$result}";

                        }

                    } else {

                        $response['status']         = 'fail';
                        $response['statusCode']      = 400;
                        $response['message']        = 'Application Could Not Be Rejected !!!';

                    }

                } else {
                
                    $response['status']         = 'fail';
                    $response['statusCode']      = 400;
                    $response['message']        = 'Applicant Record Doesn\'t Exist !!!';

                }
               
            } catch (\Throwable $th) {
                //throw $th;

                $response['status']         = 'fail';
                $response['statusCode']     = 500;
                $response['message']        = $th;

            }

            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        }


        // Adding matriculation number for each student --------------------------------------
        if ( isset($_POST['addMatric']))
        {
            $appID      = $_POST['appId'];
            $matricId   = $_POST['matricId'];

            try {

                $upd_students   = $db->prepare("UPDATE students SET matric_no=? WHERE application_id=?");
                $upd_students->execute([$matricId, $appID]);

                $upd_users   = $db->prepare("UPDATE users SET username=? WHERE username=?");
                $upd_users->execute([$matricId, $appID]);

                $createNotice   =   $db->prepare("INSERT INTO notification (matric_no, subject, message) VALUES(?,?,?)");
                $createNotice->execute([ $matricId, 'Matric Number', 'You have been issued matric number, you can now login with it.']);

                if ( $upd_students && $upd_users && $createNotice) {
                    $response['status']         = 'success';
                    $response['statusCode']     = 200;
                    $response['message']        = 'Matric Number Successfully Added !!! ';
                } else {
                    $response['status']         = 'fail';
                    $response['statusCode']      = 400;
                    $response['message']        = "Matric Number Couldn't Be Added !!!";
                }

            } catch (\Throwable $th) {
                //throw $th;

                $response['status']         = 'fail';
                $response['statusCode']     = 500;
                $response['message']        = explode(',', $th);

            }

            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        }
    }

?>