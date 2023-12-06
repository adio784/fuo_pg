<?php

    session_start();

    // Payment Initialization
    if( !isset($_SESSION['user_id']) && !isset($_SESSION['admStatus']) ){
        header('Location: /fuo_pg/admission_portal/index');
    } else {

        require_once '../../core/autoload.php';
        require_once '../../core/Database.php';
        require_once '../../common/CRUD.php';
        require_once '../../common/Payment.php';
        require_once '../../common/Sanitizer.php';

        $database   =   new Database();
        $Crud       =   new CRUD($database);
        $Sanitizer  =   new Sanitizer();
        $xpressPay  =   new PAYMENT();
        $appID      =   $_SESSION['app_id'];

        // Generation Unique Name For Uploads
        function generateUniqueFileName($originalFileName) {
            return uniqid() . '_' . bin2hex(random_bytes(8)) . '_' . $originalFileName;
        }

        if( isset($_POST['applicationForm']) ) {

            $transferReff   =   "TR".date('Y').'AP'.rand(9, 999999);
            $amount         =   trim($_POST['pay_amount']);
            $email          =   trim($_POST['email_address']);
            $programme      =   trim($_POST['programme']);

            // Check for active payment .........................................................................
            $stmt = $database->getConnection()->prepare('SELECT id, name FROM `payment_method` WHERE status=? ');
            $stmt->execute([1]);
            $activePayment = $stmt->fetch(PDO::FETCH_OBJ);
            // .....................................................................................................

            // Check if user hasn't make same payment before .......................................................
            $chkPay = $database->getConnection()->prepare('SELECT id, application_id FROM `application_payment` WHERE application_id=?  ');
            $chkPay->execute([$appID]);
            $exPayment = $chkPay->fetch(PDO::FETCH_OBJ);
            // ......................................................................................................
            
            if ( $exPayment == "" ) {
                // AND payment_status=?

                $crData = [
                    "application_id"    => $appID,
                    "transactionId"     => $transferReff,
                    "paid_amount"       => $amount,
                    "description"       => "Application Fee"
                ];
                $appData        =  [ 'program' => $programme ];

                $createPayment  =  $Crud->create('application_payment', $crData);
                $updateAppl     =  $Crud->update('application', 'application_id', $appID, $appData);

                if($createPayment) {

                    if( $activePayment->name == 'Xpress-pay')
                    {

                        $data = [
                            'amount'            => $amount,
                            'email'             => $email,
                            'callBackUrl'       => "http://localhost/fuo_pg/app/function/application_process.php?xpayment_callback={$transferReff}",
                            "currency"          => "NGN",
                            "transactionId"     => $transferReff,
                        ];

                        $paymentResult = $xpressPay->Xpresspay($data);
                        if ($paymentResult['responseCode'] == "00") {
                            // Handle the successful payment initiation
                            $paymentData = $paymentResult['data'];
                            header('Location: '. $paymentData['paymentUrl']);
                        } else {
                            // Handle the payment initiation error
                            $errorMessage = $paymentResult['data'];
                            header('Location: /fuo_pg/admission_portal/payment?error="Payment%20Failed,%20Try%20Later!!!"');
                        }
                    }    
                    else 
                    {
                        $data = [
                            'amount'            => $amount * 100,
                            'email'             => $email,
                            'callback_url'      => "http://localhost/fuo_pg/app/function/application_process.php",
                            "currency"          => "NGN",
                            "transactionId"     => $transferReff,
                        ];

                        $paymentResult = $xpressPay->PayStack($data);
                        $paymentStatus = $paymentResult['status'];
                        var_export ($paymentResult);
                        if ($paymentStatus== true) {
                            // Handle the successful payment initiation
                            $paymentData = $paymentResult['data'];
                            header('Location: '. $paymentData['authorization_url']);
                        } else {
                            // Handle the payment initiation error
                            $errorMessage = $paymentResult['data'];
                            header('Location: /fuo_pg/admission_portal/payment?error=Unable to process payment');
                        }
                    }

                }
            } else if ( $exPayment->payment_status == 'fail' || $exPayment->payment_status == '' ) {

                $crData = [
                    "application_id"    => $appID,
                    "transactionId"     => $transferReff,
                    "paid_amount"       => $amount,
                    "description"       => "Application Fee"
                ];
                $appData        =  [ 'program' => $programme ];
                
                $createPayment  =  $Crud->update('application_payment', 'application_id', $appID, $crData); 
                $updateAppl     =  $Crud->update('application', 'application_id', $appID, $appData);

                if($createPayment) {

                    if( $activePayment->name == 'Xpress-pay')
                    {

                        $data = [
                            'amount'            => $amount,
                            'email'             => $email,
                            'callBackUrl'       => "http://localhost/fuo_pg/app/function/application_process.php?xpayment_callback={$transferReff}",
                            "currency"          => "NGN",
                            "transactionId"     => $transferReff,
                        ];

                        $paymentResult = $xpressPay->Xpresspay($data);
                        if ($paymentResult['responseCode'] == "00") {
                            // Handle the successful payment initiation
                            $paymentData = $paymentResult['data'];
                            header('Location: '. $paymentData['paymentUrl']);
                        } else {
                            // Handle the payment initiation error
                            $errorMessage = $paymentResult['data'];
                            header('Location: /fuo_pg/admission_portal/payment?error="Payment%20Failed,%20Try%20Later!!!"');
                        }
                    }    
                    else 
                    {
                        $data = [
                            'amount'            => $amount * 100,
                            'email'             => $email,
                            'callback_url'      => "http://localhost/fuo_pg/app/function/application_process.php",
                            "currency"          => "NGN",
                            "transactionId"     => $transferReff,
                        ];

                        $paymentResult = $xpressPay->PayStack($data);
                        $paymentStatus = $paymentResult['status'];
                        var_export ($paymentResult);
                        if ($paymentStatus== true) {
                            // Handle the successful payment initiation
                            $paymentData = $paymentResult['data'];
                            header('Location: '. $paymentData['authorization_url']);
                        } else {
                            // Handle the payment initiation error
                            $errorMessage = $paymentResult['data'];
                            header('Location: /fuo_pg/admission_portal/payment?error=Unable to process payment');
                        }
                    }

                }

            } else {
                header('Location: /fuo_pg/admission_portal/payment?error=Payment%20Already%20Exist%20!!!');
            }
            
        }


        if( isset($_GET['xpayment_callback']) )
        {
            $transferReff = $_GET['xpayment_callback'];
        
            $data = [
                "transactionId"     => $transferReff,
            ];
            $paymentVResult = $xpressPay->verifyXpresspay($data);
            if ($paymentVResult->responseCode == "00") {
                
                $paymentData = $paymentVResult->data;
                $stmt = $database->getConnection()->prepare('SELECT program_id, program_fee, programme_title FROM `programme` ORDER BY programme_title ASC');
                $stmt->execute();
                // var_dump($paymentData);
                $crData = [
                    "payment_ref"       => $paymentData->paymentReference,
                    "message"           => $paymentVResult->responseMessage,
                    "payment_status"    => "success"
                ];
                $appData  = [ "application_status" => "paid" ];
                $createPayment  =  $Crud->update('application_payment', 'transactionId', $transferReff, $crData);
                $updateAppl     =  $Crud->update('application', 'application_id', $appID, $appData);
                header('Location: http://localhost/fuo_pg/admission_portal/payment?pay_success=Payment%20Successful!!!');

            } else {
            
                header('Location: http://localhost/fuo_pg/admission_portal/payment?error=Payment%20%20Could%20Not%20Be%20Verified,%20Try%20Requery%20!!!');
            }
        }


        // Api response to update application payment (Paystack)
        if( isset($_GET['reference']) )
        {
            $transferReff = $_GET['reference'];

            $paymentVResult = $xpressPay->verifyPaystack($transferReff);
            
            if ($paymentVResult['status'] == true) {
                
                $paymentData = $paymentVResult['data'];
                // var_dump($paymentData);
                $crData = [
                    "payment_ref"       => $paymentData['reference'],
                    "message"           => $paymentVResult['message'],
                    "payment_status"    => "success"
                ];
                $appData        = [ "application_status" => "paid" ];
                $createPayment  = $Crud->update('application_payment', 'transactionId', $transferReff, $crData);
                $updateAppl     = $Crud->update('application', 'application_id', $appID, $appData);
                header('Location: http://localhost/fuo_pg/admission_portal/payment?pay_success=Payment%20Successful!!!');

            } 
            else {
            
                header('Location: http://localhost/fuo_pg/admission_portal/payment?error=Payment%20%20Could%20Not%20Be%20Verified,%20Try%20Requery%20!!!');
            }
        }


        // Api response to update application payment (Xpresspay)
        if( isset($_POST['pgAppToken']) )
        {

            $timestamp      = time();
            $date           = date('Y-m-d H:m:s');
            $uploadDir      = "../../admission_portal/admissionUploads/"; 
            
            $firstname      = ucfirst(Sanitizer::sanitizeInput($_POST['firstName']));
            $lastname       = ucfirst(Sanitizer::sanitizeInput($_POST['lastName']));
            $middlename     = ucfirst(Sanitizer::sanitizeInput($_POST['middleName']));
            $gender         = Sanitizer::sanitizeInput($_POST['gender']);
            $religion       = Sanitizer::sanitizeInput($_POST['religion']);
            $birthDate      = Sanitizer::sanitizeInput($_POST['birthDate']);
            $address        = ucfirst(Sanitizer::sanitizeInput($_POST['address']) );
            $country        = Sanitizer::sanitizeInput($_POST['country']);
            $state          = Sanitizer::sanitizeInput($_POST['state']);
            $city           = Sanitizer::sanitizeInput($_POST['city']);
            $emailAddress   = Sanitizer::sanitizeInput($_POST['emailAddress']);
            $phoneNumber    = Sanitizer::sanitizeInput($_POST['phoneNumber']);
            $classDegree    = Sanitizer::sanitizeInput($_POST['classDegree']);
            $instituteAtt   = ucfirst(Sanitizer::sanitizeInput($_POST['instituteAtt']) );
            // $entryMode      = Sanitizer::sanitizeInput($_POST['entryMode']);
            $courseOfStudy  = Sanitizer::sanitizeInput($_POST['courseOfStudy']);
            $countryOrigin  = Sanitizer::sanitizeInput($_POST['countryOrigin']);
            $stateOrigin    = Sanitizer::sanitizeInput($_POST['stateOrigin']);
            $lgaOrigin      = Sanitizer::sanitizeInput($_POST['lgaOrigin']);
            $appSess        = date('Y').'/'.date('Y')-1;

            $oLevel         = basename($_FILES['oLevel']['name']);
            $undergCert     = basename($_FILES['undergCert']['name']);
            $transcript     = basename($_FILES['transcript']['name']);
            $passport       = basename($_FILES['passport']['name']);

            $oLevelNew      = generateUniqueFileName($oLevel);
            $undergCertNew  = generateUniqueFileName($undergCert);
            $transcriptNew  = generateUniqueFileName($transcript);
            $passportNew    = generateUniqueFileName($passport);
            $responseNew    = [];
            $fullnameNew    = $lastname. ' '. $firstname. ' '. $middlename;
            $application_status     = 'registered';
            $undergraduateCourse    = ucfirst(Sanitizer::sanitizeInput($_POST['undergraduateCourse']) );

            $oLevelNewDest     = $uploadDir . $oLevelNew;
            $undergCertNewDest = $uploadDir . $undergCertNew;
            $transcriptNewDest = $uploadDir . $transcriptNew;
            $passportNewDest   = $uploadDir . $passportNew  ;
            

            if( isset($_FILES['masterCert']['name']) ){ 
                $masterCert         = basename($_FILES['masterCert']['name']); 
                $masterCertNew      = generateUniqueFileName($masterCert);
                $masterCertNewDest  = $uploadDir . $masterCertNew;
                move_uploaded_file($masterCertNewDest, $uploadDir);
            }else {  
                $masterCert = NULL; 
                $masterCertNew  = NULL;
                $masterCertNewDest = NULL;
            }

            // Update application record ..................................................................
            $userData       = [
                'first_name'    => $firstname,
                'middle_name'   => $middlename,
                'last_name'     => $lastname,
                'phone_number'  => $phoneNumber,
                'email'         => $emailAddress,
                'gender'        => $gender,
                'religion'      => $religion,
                'address'       => $address,
                'country'       => $country,
                'state'         => $state,
                'lga'           => $city,
                'course'        => $courseOfStudy,
                'institute_attended'=> $instituteAtt,
                'course_studied'    => $undergraduateCourse,
                'class_degree'      => $classDegree,
                'date_of_birth'     => $birthDate,
                'country_of_origin' => $countryOrigin,
                'state_of_origin'   => $stateOrigin,
                'local_government'  => $lgaOrigin,
                'application_status'=> $application_status,
                'application_session'=> $appSess,
                'updated_at'        => $date,
                
            ];
            $userCredentials = [
                'application_id'    => $appID, 
                'o_level'           => $oLevelNew, 
                'transcripts'       => $transcriptNew, 
                'undergraduate'     => $undergCertNew,
                'passport'          => $passportNew,
                'masters'           => $masterCertNew,
            ];
            $updateUser     =  $Crud->update('application', 'application_id', $appID, $userData);
            $insertCred     =  $Crud->create('user_credentials', $userCredentials);
            if ( $updateUser && $userCredentials) {

                move_uploaded_file($_FILES['oLevel']['tmp_name'], $oLevelNewDest);
                move_uploaded_file($_FILES['undergCert']['tmp_name'], $undergCertNewDest);
                move_uploaded_file($_FILES['transcript']['tmp_name'], $transcriptNewDest);
                move_uploaded_file($_FILES['passport']['tmp_name'], $passportNewDest);
                
                $response['status'] = 'success';
                $response['message'] = 'Application Successfully Completed, Kindly Await Admission';
            }
            else {
                $response['status'] = 'error';
                $response['message'] = 'Application Could Not Be Completed Successfully, Try Later';
            }
            // Upload the images to folder ................................................................

            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        }


        // Payment of acceptance fee
        if( isset($_POST['acceptanceForm']) ) {

            $transferReff   =   "TR".date('Y').'AC'.rand(9, 999999);
            $amount         =   trim($_POST['acceptance_fee']);
            $email          =   trim($_POST['email_address']);
            $desc           =   trim($_POST['purpose']);
    
            // Check for active payment .........................................................................
            $stmt = $database->getConnection()->prepare('SELECT id, name FROM `payment_method` WHERE status=? ');
            $stmt->execute([1]);
            $activePayment = $stmt->fetch(PDO::FETCH_OBJ);
            // .....................................................................................................
    
            // Check if user hasn't make same payment before .......................................................
            $chkPay = $database->getConnection()->prepare('SELECT id, application_id FROM `application_payment` WHERE application_id=? AND description=? ');
            $chkPay->execute([$appID, $desc]);
            $exPayment = $chkPay->fetch(PDO::FETCH_OBJ);
            // ......................................................................................................
            
            if ( $exPayment == "" ) {
                // AND payment_status=?
    
                $crData = [
                    "application_id"    => $appID,
                    "transactionId"     => $transferReff,
                    "paid_amount"       => $amount,
                    "description"       => "Application Fee"
                ];
                $appData        =  [ 'program' => $programme ];
    
                $createPayment  =  $Crud->create('application_payment', $crData);
                $updateAppl     =  $Crud->update('application', 'application_id', $appID, $appData);
    
                if($createPayment) {
    
                    if( $activePayment->name == 'Xpress-pay')
                    {
    
                        $data = [
                            'amount'            => $amount,
                            'email'             => $email,
                            'callBackUrl'       => "http://localhost/fuo_pg/app/function/application_process.php?xpayment_callback={$transferReff}",
                            "currency"          => "NGN",
                            "transactionId"     => $transferReff,
                        ];
    
                        $paymentResult = $xpressPay->Xpresspay($data);
                        if ($paymentResult['responseCode'] == "00") {
                            // Handle the successful payment initiation
                            $paymentData = $paymentResult['data'];
                            header('Location: '. $paymentData['paymentUrl']);
                        } else {
                            // Handle the payment initiation error
                            $errorMessage = $paymentResult['data'];
                            header('Location: /fuo_pg/admission_portal/payment?error="Payment%20Failed,%20Try%20Later!!!"');
                        }
                    }    
                    else 
                    {
                        $data = [
                            'amount'            => $amount * 100,
                            'email'             => $email,
                            'callback_url'      => "http://localhost/fuo_pg/app/function/application_process.php",
                            "currency"          => "NGN",
                            "transactionId"     => $transferReff,
                        ];
    
                        $paymentResult = $xpressPay->PayStack($data);
                        $paymentStatus = $paymentResult['status'];
                        var_export ($paymentResult);
                        if ($paymentStatus== true) {
                            // Handle the successful payment initiation
                            $paymentData = $paymentResult['data'];
                            header('Location: '. $paymentData['authorization_url']);
                        } else {
                            // Handle the payment initiation error
                            $errorMessage = $paymentResult['data'];
                            header('Location: /fuo_pg/admission_portal/payment?error=Unable to process payment');
                        }
                    }
    
                }
            } else if ( $exPayment->payment_status == 'fail' || $exPayment->payment_status == '' ) {
    
                $crData = [
                    "application_id"    => $appID,
                    "transactionId"     => $transferReff,
                    "paid_amount"       => $amount,
                    "description"       => $desc
                ];
                $appData        =  [ 'program' => $programme ];
                
                $createPayment  =  $Crud->update('application_payment', 'application_id', $appID, $crData); 
    
                if($createPayment) {
    
                    if( $activePayment->name == 'Xpress-pay')
                    {
    
                        $data = [
                            'amount'            => $amount,
                            'email'             => $email,
                            'callBackUrl'       => "http://localhost/fuo_pg/app/function/application_process.php?xpayment_callback={$transferReff}",
                            "currency"          => "NGN",
                            "transactionId"     => $transferReff,
                            "metadata"          => ["purpose" => $desc]
                        ];
    
                        $paymentResult = $xpressPay->Xpresspay($data);
                        if ($paymentResult['responseCode'] == "00") {
                            // Handle the successful payment initiation
                            $paymentData = $paymentResult['data'];
                            header('Location: '. $paymentData['paymentUrl']);
                        } else {
                            // Handle the payment initiation error
                            $errorMessage = $paymentResult['data'];
                            header('Location: /fuo_pg/admission_portal/admission_home?error="Payment%20Failed,%20Try%20Later!!!"');
                        }
                    }    
                    else 
                    {
                        $data = [
                            'amount'            => $amount * 100,
                            'email'             => $email,
                            'callback_url'      => "http://localhost/fuo_pg/app/function/application_process.php",
                            "currency"          => "NGN",
                            "transactionId"     => $transferReff,
                            "metadata"          => ["purpose" => $desc]
                        ];
    
                        $paymentResult = $xpressPay->PayStack($data);
                        $paymentStatus = $paymentResult['status'];
                        // var_export ($paymentResult);
                        if ($paymentStatus== true) {
                            // Handle the successful payment initiation
                            $paymentData = $paymentResult['data'];
                            header('Location: '. $paymentData['authorization_url']);
                        } else {
                            // Handle the payment initiation error
                            $errorMessage = $paymentResult['data'];
                            header('Location: /fuo_pg/admission_portal/admission_home?error=Unable to process payment');
                        }
                    }
    
                }
    
            } else {
                header('Location: /fuo_pg/admission_portal/admission_home?error=Payment%20Already%20Exist%20!!!');
            }
            
        }

    
    }
?>