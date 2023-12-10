<?php

    session_start();
   
    if ( !isset($_SESSION['user_id']) && !isset($_SESSION['user_status']) ) {

        header('Location: students/');

    } else {

        require_once '../../core/autoload.php';
        require_once '../../core/Database.php';
        require_once '../../common/CRUD.php';
        require_once '../../common/Payment.php';
        require_once '../../common/Sanitizer.php';

        $database   = new Database();
        $Crud       = new CRUD($database);
        $Sanitizer  = new Sanitizer();
        $PaymentM   = new PAYMENT();
        $uid        = $_SESSION['user_id'];
        $Users       = $Crud->readAll('students', 'application_no', $uid);

        foreach ($Users as $User)
        $name       = $User->last_name .' '. $User->first_name .' '. $User->middle_name;
        $email      = $User->email;
        $matric     = $User->application_id;
        $level      = $User->application_id;
        $phone      = $User->mobile_no;
        $db         = $database->getConnection();
        $uri        = $_SERVER['HTTP_HOST'];


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

        // Procees student payment ........................................................

        if ( isset($_POST['payment_process']) ) {


            $amountToPay    = $_POST['amount_topay'];
            $purpose        = $_POST['payment_purpose'];
            $transferReff   = "trn".date('Y').rand(9, 999999);
            $payment_session= $_POST['payment_session'];
            $selectedPurpose= explode(",", $purpose);

            $paymentData    = $_POST['paymentsPurposes'];

            foreach ($paymentData as $paymentType => $amount) {

                echo $paymentType . $amount . $uid;

                // Check if payment already exist ..................................
                $checkExistPayment      = $db->prepare("SELECT * FROM payments_history WHERE matric_no = ? AND payment_session=? AND payment_desc=? "); $checkExistPayment->execute([$uid, $payment_session, $paymentType]);

                if ($checkExistPayment->rowCount() > 0) {

                    $getCheckExistPayment = $checkExistPayment->fetchObject();
                    if ($getCheckExistPayment->payment_status ==1) {

                        $msg = 'Payment Already Exist !!!';
                        header("Location: /fuo_pg/students/payments?payment_error={$msg}");

                    } else {

                        $upData         = [
                            "transaction_id"    => $transferReff,
                            "amount_paid"       => $amount,
                        ];
                        $createPayment  =  $Crud->update('payments_history', 'matric_no', $uid, $upData);

                    }

                } else {

                    $crData         = [
                        "matric_no"         => $uid,
                        "payment_desc"      => $paymentType,
                        "transaction_id"    => $transferReff,
                        "amount_paid"       => $amount,
                        "payment_session"   => $payment_session
                    ];

                    $createPayment  =  $Crud->create('payments_history', $crData);
                }

            }

            if ($createPayment) {

                $data = [
                    'amount'            => $amountToPay,
                    'email'             => $email,
                    'callBackUrl'       => "http://". $uri."/fuo_pg/app/function/student_actions.php?xpayment_callback={$transferReff}",
                    "currency"          => "NGN",
                    "transactionId"     => $transferReff,
                    "metadata"          => [
                        [
                            "Name"      => "Matric No.",
                            "Value"     => $matric
                        ],
                        [
                            "Name"      => "Fullname",
                            "Value"     => $name
                        ],
                        [
                            "Name"      => "Level",
                            "Value"     => $level
                        ],
                        [
                            "Name"      => "Phone",
                            "Value"     => $phone
                        ],
                        [
                            "Name"      => "Purpose",
                            "Value"     => $purpose
                        ],
                    ]
                ];

                $paymentResult = $PaymentM->Xpresspay($data);
                if ($paymentResult['responseCode'] == "00") {

                    $paymentData = $paymentResult['data'];
                    header('Location: '. $paymentData['paymentUrl']);

                } else {
                    
                    $errorMessage = $paymentResult['data'];
                    $msg = "Payment Failed, Try Later!!!";
                    header("Location: /fuo_pg/students/payments?error={$msg}");
                }

            } else {

                $msg = 'Unable to update payment record, due to system error !!!';
                header("Location: /fuo_pg/students/payments?error={$msg}");

            }



            


        }


        // Call back function for Xpress-payments
        if ( isset($_GET['xpayment_callback']) )
        {
            $transferReff = $_GET['xpayment_callback'];
        
            $data = [
                "transactionId"     => $transferReff,
            ];
            $paymentVResult = $PaymentM->verifyXpresspay($data);
            print_r($paymentVResult->data->responseMessage);
            if ($paymentVResult->responseCode == "00") {
                
                $paymentData    = $paymentVResult->data;
                $paymentRef     = $paymentData->paymentReference;
                $paymentMsg     = $paymentVResult->responseMessage;
                $paymentSts     = 1;

                $crData = [
                    "reference"         => $paymentRef,
                    "message"           => $paymentMsg,
                    "payment_status"    => $paymentSts
                ];

                $updatePaymentRecord  =  $Crud->update('payments_history', 'transaction_id', $transferReff, $crData);
                $msg                  = "Payment Successful, Click Payment History, To Print Receipt";
                header("Location: http://{$uri}/fuo_pg/students/payments?pay_success={$msg}");

            } elseif($paymentVResult->responseCode == "02") {

                $paymentData    = $paymentVResult->data;
                $paymentRef     = $paymentData->paymentReference;
                $paymentMsg     = $paymentVResult->responseMessage;
                $paymentSts     = 1;

                $crData = [
                    "reference"       => $paymentRef,
                    "message"           => $paymentMsg,
                    "payment_status"    => $paymentSts
                ];

                $updatePaymentRecord  =  $Crud->update('payments_history', 'transaction_id', $transferReff, $crData);
                $msg                  = "Payment Successful, Click Payment History, To Print Receipt";
                header("Location: http://{$uri}/fuo_pg/students/payments?pay_success={$msg}");

            } else {
                // 09063210146
                $msg = "Sorry! Payment Could Not Be Verified !!!";
                header("Location: http://{$uri}/fuo_pg/students/payments?pay_success={$msg}");
                
            }
        }

    }

?>