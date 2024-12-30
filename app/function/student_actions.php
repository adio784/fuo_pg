<?php

session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['user_status'])) {

    $protocol       = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url_protocol   = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $url            = $protocol . $_SERVER['HTTP_HOST'];


    require_once '../../core/autoload.php';
    require_once '../../core/Database.php';
    require_once '../../common/CRUD.php';
    require_once '../../common/Payment.php';
    require_once '../../common/Sanitizer.php';
    require_once '../../core/error_log.php';
    require_once '../../students/includes/students_data.php';

    $database   = new Database();
    $Crud       = new CRUD($database);
    $Sanitizer  = new Sanitizer();
    $PaymentM   = new PAYMENT();
    $uid        = $_SESSION['user_id'];
    $Users      = $Crud->readAll('students', 'application_no', $uid);
    $db         = $database->getConnection();


    foreach ($Users as $User) {
        $name       = $User->last_name . ' ' . $User->first_name . ' ' . $User->middle_name;
        $email      = $User->email;
        $matric     = $User->application_id;
        $phone      = $User->mobile_no;
        $stdProgram = $course;
        // $uri        = $_SERVER['HTTP_HOST'];
    }



    // Change student password on first login ........................................
    if (isset($_POST['passwordChange'])) {

        if (empty($_POST['oldPassword']) || empty($_POST['newPassword'])) {

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

                $userData           =   ["password" => $newPassword, "role" => "student"];
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
    if (isset($_POST['payment_process'])) {


        $amountToPay        = $Sanitizer->sanitizeInput($_POST['amount_topay']);
        $purpose            = $Sanitizer->sanitizeInput($_POST['payment_id']);
        $transferReff       = "trn" . date('Y') . rand(9, 999999);
        $payment_session    = $Sanitizer->sanitizeInput($_POST['payment_session']);
        echo "Yes, payment processed" . $purpose;

        // Check if payment already exist ..................................
        $checkPayment      = $db->prepare("SELECT * FROM payments_history WHERE matric_no = ? AND payment_session = ? AND payment_id = ? ");
        $checkPayment->execute([$uid, $payment_session, $purpose]);


        if ($checkPayment->rowCount() > 0) {

            $getCheckExistPayment = $checkPayment->fetchObject();
            if ($getCheckExistPayment->payment_status == 1) {

                $msg = 'Payment Already Exist !!!';
                header("Location: payments?payment_error={$msg}");
            } else {

                $upData         = [
                    "transaction_id"    => $transferReff,
                    "amount_paid"       => $amountToPay,
                ];
                $createPayment  =  $Crud->update('payments_history', 'matric_no', $uid, $upData);
                // echo "Success update";
            }
        } else {

            $crData         = [
                "matric_no"         => $uid,
                "payment_id"        => $purpose,
                "transaction_id"    => $transferReff,
                "amount_paid"       => $amountToPay,
                "payment_session"   => $payment_session
            ];

            $createPayment  =  $Crud->create('payments_history', $crData);
            // echo "Success Create";
        }

        //Paystack Metadata .....................................................................................

        $metadata = [
            "custom_fields" => [
                [
                    "display_name"    => "Matric Number",
                    "variable_name"   => "stu_matricNo",
                    "value"           => $matric
                ],
                [
                    "display_name"    => "Full Name",
                    "variable_name"   => "stu_name",
                    "value"           => $name,
                ],
                [
                    "display_name"    => "Phone",
                    "variable_name"   => "stu_phone",
                    "value"           => $phone,
                ],
                [
                    "display_name"    => "Program Type",
                    "variable_name"   => "program_type",
                    "value"           => $stdProgram,
                ],
                [
                    "display_name"    => "Purpose",
                    "variable_name"   => "stu_purpose",
                    "value"           => $purpose,
                ],
            ]
        ];
        // ............................................................................................................

        if ($createPayment) {
            // callBackUrl
            $data = [
                'amount'            => ($amountToPay + 300) * 100,
                'email'             => $email,
                'callback_url'      => $url_protocol, // . "/app/function/student_actions.php",
                "currency"          => "NGN",
                "reference"         => $transferReff,
                'trxref'            =>  '',
                "metadata"          => $metadata
            ];

            $paymentResult  = $PaymentM->PayStack($data);
            $paymentStatus  = $paymentResult['status'];
            $paymentData    = $paymentResult['data'];
            $paymentURL     = $paymentData['authorization_url'];
            var_export($paymentResult) . "<br>";
            echo $url_protocol;

            if ($paymentStatus == true) {

                // Handle the successful payment initiation

                echo "Passed" . $paymentURL;
                header('location: ' . $paymentURL);
            } else {
                echo "Not passed";
                // Handle the payment initiation error
                $errorMessage = $paymentResult['data'];
                $msg = "Payment Failed, Try Later!!!";
                header("location: ../../students/payments?error={$msg}");
            }
        } else {
            // echo "Unable to proceed";
            var_export($paymentResult);
            $msg = 'Unable to update payment record, due to system error !!!';
            // header("location: ../../students/payments?error={$msg}");
        }

        // echo "<br/>" . $msg;
    }

    // http://localhost/fuo_pg/app/function/student_actions.php?reference=3bkwwad1yi
    // Api response to update application payment (Paystack)
    if (isset($_GET['reference'])) {
        $transferReff = $_GET['reference'];
        echo "Verification...";

        $paymentVResult = $PaymentM->verifyPaystack($transferReff);
        // var_export($paymentVResult);
        if ($paymentVResult['status'] == true) {

            $paymentData = $paymentVResult['data'];
            // var_export($paymentData);
            $crData = [
                "reference"       => $paymentData['id'],
                "message"           => $paymentVResult['message'],
                "payment_status"    => 1,
            ];
            $appData        = ["payment_status" => 1];
            $createPayment  = $Crud->update('payments_history', 'transaction_id', $transferReff, $crData);
            $msg            = "Payment Successful, Click Payment History, To Print Receipt";
            header("Location: ../../students/payments?pay_success={$msg}");
        } else {

            header('Location: ../../students/payments?error=Payment%20%20Could%20Not%20Be%20Verified,%20Try%20Requery%20!!!');
        }
    }
} else {
    header("Location: /");
}
