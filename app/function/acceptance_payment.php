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
        $name       =   $_SESSION['full_name'];
        $thisYear   =   date('Y');
        $db         =   $database->getConnection();
        $uri        =   $_SERVER['HTTP_HOST'];
        
    // Callback url for paystack acceptance fee
        
        // Api response to update application payment (Paystack)
        if ( isset($_GET['reference']))
        {
            $transferReff   = $_GET['trnId']; //$_GET['trnId']; //
            $reference      = $_GET['reference'];

            $paymentVResult = $xpressPay->verifyPaystack($reference);
            // print_r($paymentVResult);
            if ($paymentVResult['status'] == true) {
                
                $paymentData = $paymentVResult['data'];
                // echo $paymentVResult['message'];
                $crData = [
                    "payment_ref"       => $paymentData['reference'],
                    "message"           => $paymentVResult['message'],
                    "payment_status"    => "success"
                ];
                $createPayment  = $Crud->update('application_payment', 'transactionId', $transferReff, $crData);
                // $createPayment  = $Crud->update('application_payment', 'description', 'Acceptance fee', $crData);
                header('Location: http://localhost/fuo_pg/admission_portal/admission_home?pay_success=Payment%20Successful!!!');

            } 
            else {
            
                header('Location: http://localhost/fuo_pg/admission_portal/admission_home?error=Payment%20%20Could%20Not%20Be%20Verified,%20Try%20Requery%20!!!');
            }
        }

        // Call back function for Xpress-payments
        if ( isset($_GET['xpacceptance_callback']) )
        {
            $transferReff = $_GET['xpacceptance_callback'];
        
            $data = [
                "transactionId"     => $transferReff,
            ];
            $paymentVResult = $xpressPay->verifyXpresspay($data);
            if ($paymentVResult->responseCode == "00") {
                // var_dump($paymentData);
                $crData = [
                    "payment_ref"       => $paymentData->paymentReference,
                    "message"           => $paymentVResult->responseMessage,
                    "payment_status"    => "success"
                ];
                $appData  = [ "application_status" => "paid" ];
                $createPayment  =  $Crud->update('application_payment', 'transactionId', $transferReff, $crData);
                header('Location: http://localhost/fuo_pg/admission_portal/admission_home?pay_success=Payment%20Successful!!!');

            } else {
            
                header('Location: http://localhost/fuo_pg/admission_portal/admission_home?error=Payment%20%20Could%20Not%20Be%20Verified,%20Try%20Requery%20!!!');
            }
        }

    }
?>