<?php

session_start();

$protocol       = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$url_protocol   = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$url            = $protocol . $_SERVER['HTTP_HOST'];


// Payment Initialization
if (!isset($_SESSION['user_id']) && !isset($_SESSION['admStatus'])) {
    header('Location: ' . $url . '/admission_portal/index');
} else {

    require_once '../../core/autoload.php';
    require_once '../../core/Database.php';
    require_once '../../common/CRUD.php';
    require_once '../../common/Payment.php';
    require_once '../../common/Sanitizer.php';
    require_once '../../core/error_log.php';

    $database   =   new Database();
    $Crud       =   new CRUD($database);
    $Sanitizer  =   new Sanitizer();
    $xpressPay  =   new PAYMENT();
    $appID      =   $_SESSION['app_id'];
    $name       =   $_SESSION['full_name'];
    $thisYear   =   date('Y');
    $db         =   $database->getConnection();
    $uri        =   $_SERVER['HTTP_HOST']; //$_SERVER['REQUEST_URI'];

    // Generation Unique Name For Uploads
    function generateUniqueFileName($originalFileName)
    {
        return bin2hex(random_bytes(8)) . $originalFileName;
        // return uniqid() . '_' . bin2hex(random_bytes(8)) . '_' . $originalFileName;
    }

    // Get current session =============================================================
    $Session            = $Crud->read("access", "setting", "current_session");       //=
    $current_session    = $Session->value;                                           //=
    // =================================================================================

    // Make payment for application form
    if (isset($_POST['applicationForm'])) {

        $transferReff   =   "trn" . date('Y') . rand(9, 999999);
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

        if ($exPayment == "") {
            // AND payment_status=?

            $crData = [
                "application_id"    => $appID,
                "transactionId"     => $transferReff,
                "paid_amount"       => $amount,
                "description"       => "Application Fee"
            ];
            $appData        =  ['program' => $programme];

            $createPayment  =  $Crud->create('application_payment', $crData);
            $updateAppl     =  $Crud->update('application', 'application_id', $appID, $appData);

            if ($createPayment) {

                if ($activePayment->name == 'Xpress-pay') {

                    $data = [
                        'amount'            => $amount,
                        'email'             => $email,
                        'callBackUrl'       => $url . "/app/function/application_process.php?xpayment_callback={$transferReff}",
                        "currency"          => "NGN",
                        "transactionId"     => $transferReff,
                        "metadata"          => [
                            [
                                "Name"      => "Application",
                                "Value"     => $appID
                            ],
                            [
                                "Name"      => "Fullname",
                                "Value"     => $name
                            ],
                            [
                                "Name"      => "Purpose",
                                "Value"     => "Application Fee"
                            ],
                        ]
                    ];

                    $paymentResult = $xpressPay->Xpresspay($data);
                    if ($paymentResult['responseCode'] == "00") {
                        // Handle the successful payment initiation
                        $paymentData = $paymentResult['data'];
                        header('Location: ' . $paymentData['paymentUrl']);
                    } else {
                        // Handle the payment initiation error
                        $errorMessage = $paymentResult['data'];
                        header('Location: ' . $url . '/admission_portal/payment?error="Payment%20Failed,%20Try%20Later!!!"');
                    }
                } else {
                    $data = [
                        'amount'            => $amount * 100,
                        'email'             => $email,
                        'callback_url'      => $url . "/app/function/application_process.php",
                        "currency"          => "NGN",
                        "reference"         => $transferReff,
                        'trxref'        =>  '',
                        "metadata"          => [
                            [
                                "Name"      => "Application",
                                "Value"     => $appID
                            ],
                            [
                                "Name"      => "Fullname",
                                "Value"     => $name
                            ],
                            [
                                "Name"      => "Email",
                                "Value"     => $email
                            ],
                            [
                                "Name"      => "Amount paid",
                                "Value"     => $amount
                            ],
                            [
                                "Name"      => "Purpose",
                                "Value"     => "Application Fee"
                            ],
                        ]
                    ];

                    $paymentResult = $xpressPay->PayStack($data);
                    $paymentStatus = $paymentResult['status'];
                    // var_export ($paymentResult);
                    if ($paymentStatus == true) {
                        // Handle the successful payment initiation
                        $paymentData = $paymentResult['data'];
                        header('Location: ' . $paymentData['authorization_url']);
                    } else {
                        // Handle the payment initiation error
                        $errorMessage = $paymentResult['data'];
                        header('Location: ' . $url . '/admission_portal/payment?error=Unable to process payment');
                    }
                }
            }
        } else if ($exPayment->payment_status == 'fail' || $exPayment->payment_status == '') {

            $crData = [
                "application_id"    => $appID,
                "transactionId"     => $transferReff,
                "paid_amount"       => $amount,
                "description"       => "Application Fee"
            ];
            $appData        =  ['program' => $programme];

            $createPayment  =  $Crud->update('application_payment', 'application_id', $appID, $crData);
            $updateAppl     =  $Crud->update('application', 'application_id', $appID, $appData);

            if ($createPayment) {

                if ($activePayment->name == 'Xpress-pay') {

                    $data = [
                        'amount'            => $amount,
                        'email'             => $email,
                        'callBackUrl'       => $url . "/app/function/application_process.php?xpayment_callback={$transferReff}",
                        "currency"          => "NGN",
                        "transactionId"     => $transferReff,
                        "metadata"          => [
                            [
                                "Name"      => "Application",
                                "Value"     => $appID
                            ],
                            [
                                "Name"      => "Fullname",
                                "Value"     => $name
                            ],
                            [
                                "Name"      => "Purpose",
                                "Value"     => "Application Fee"
                            ],
                        ]
                    ];

                    $paymentResult = $xpressPay->Xpresspay($data);
                    if ($paymentResult['responseCode'] == "00") {
                        // Handle the successful payment initiation
                        $paymentData = $paymentResult['data'];
                        header('Location: ' . $paymentData['paymentUrl']);
                    } else {
                        // Handle the payment initiation error
                        $errorMessage = $paymentResult['data'];
                        header('Location: ' . $url . '/admission_portal/payment?error="Payment%20Failed,%20Try%20Later!!!"');
                    }
                } else {
                    $data = [
                        'amount'            => $amount * 100,
                        'email'             => $email,
                        'callback_url'      => $url . "/app/function/application_process.php",
                        "currency"          => "NGN",
                        "reference"         => $transferReff,
                        'trxref'            =>  '',
                        "metadata"          => [
                            [
                                "Name"      => "Application",
                                "Value"     => $appID
                            ],
                            [
                                "Name"      => "Fullname",
                                "Value"     => $name
                            ],
                            [
                                "Name"      => "Email",
                                "Value"     => $email
                            ],
                            [
                                "Name"      => "Amount paid",
                                "Value"     => $amount
                            ],
                            [
                                "Name"      => "Purpose",
                                "Value"     => "Application Fee"
                            ],
                        ]
                    ];

                    $paymentResult = $xpressPay->PayStack($data);
                    $paymentStatus = $paymentResult['status'];
                    // var_export ($paymentResult);
                    if ($paymentStatus == true) {
                        // Handle the successful payment initiation
                        $paymentData = $paymentResult['data'];
                        header('Location: ' . $paymentData['authorization_url']);
                    } else {
                        // Handle the payment initiation error
                        $errorMessage = $paymentResult['data'];
                        header('Location: ' . $url . '/admission_portal/payment?error=Unable to process payment');
                    }
                }
            }
        } else {
            header('Location: ' . $url . '/admission_portal/payment?error=Payment%20Already%20Exist%20!!!');
        }
    }


    // Api response to update application payment (Xpresspay)
    if (isset($_GET['xpayment_callback'])) {
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
                "payment_status"    => "success",
                "payment_mode"      => "xpresspay"
            ];
            $appData  = ["application_status" => "paid"];
            $createPayment  =  $Crud->update('application_payment', 'transactionId', $transferReff, $crData);
            $updateAppl     =  $Crud->update('application', 'application_id', $appID, $appData);
            header('Location: ' . $url . '/admission_portal/payment?pay_success=Payment%20Successful!!!');
        } else {

            header('Location: ' . $url . '/admission_portal/payment?error=Payment%20%20Could%20Not%20Be%20Verified,%20Try%20Requery%20!!!');
        }
    }


    // Api response to update application payment (Paystack)
    if (isset($_GET['reference'])) {
        $transferReff = $_GET['reference'];

        $paymentVResult = $xpressPay->verifyPaystack($transferReff);

        if ($paymentVResult['status'] == true) {

            $paymentData = $paymentVResult['data'];
            // var_export(json_encode($paymentData));
            $crData = [
                "payment_ref"       => $paymentData['id'],
                "message"           => $paymentVResult['message'],
                "payment_status"    => "success",
                "payment_mode"      => "paystack"
            ];
            $appData        = ["application_status" => "paid"];
            $createPayment  = $Crud->update('application_payment', 'transactionId', $transferReff, $crData);
            $updateAppl     = $Crud->update('application', 'application_id', $appID, $appData);
            header('Location: ' . $url . '/admission_portal/payment?trx_id=' . $transferReff . '&&pay_success=Payment%20Successful!!!');
        } else {

            header('Location: ' . $url . '/admission_portal/payment?error=Payment%20%20Could%20Not%20Be%20Verified,%20Try%20Requery%20!!!');
        }
    }


    // Application form submission
    if (isset($_POST['pgAppToken'])) {

        // validating emptynes -------------------------------------------------------------------------------------------------

        if (empty($_POST['firstName']) || !isset($_POST['firstName']) || isset($_POST['firstName']) == "") {

            $response['status'] = 'error';
            $response['message'] = 'First Name is Required ...';
        } else if (empty($_POST['lastName']) || !isset($_POST['lastName']) || isset($_POST['lastName']) == "") {

            $response['status'] = 'error';
            $response['message'] = 'Last Name is Required ...';
        } else if (empty($_POST['gender']) || !isset($_POST['gender']) || isset($_POST['gender']) == "") {

            $response['status'] = 'error';
            $response['message'] = 'Gender Field is Required ...';
        } else if (empty($_POST['religion']) || !isset($_POST['religion']) || isset($_POST['religion']) == "") {

            $response['status'] = 'error';
            $response['message'] = 'Religion Field is Required ...';
        } else if (empty($_POST['birthDate']) || !isset($_POST['birthDate']) || isset($_POST['birthDate']) == "") {

            $response['status'] = 'error';
            $response['message'] = 'Date of Birth is Required ...';
        } else if (empty($_POST['address']) || !isset($_POST['address']) || isset($_POST['address']) == "") {

            $response['status'] = 'error';
            $response['message'] = 'Address Field is Required ...';
        } else if (empty($_POST['country']) || !isset($_POST['country']) || isset($_POST['country']) == "") {

            $response['status'] = 'error';
            $response['message'] = 'Country Field is Required ...';
        } else if (empty($_POST['state']) || !isset($_POST['state']) || isset($_POST['state']) == "") {

            $response['status'] = 'error';
            $response['message'] = 'State Field is Required ...';

            // } else if ( empty($_POST['city']) || !isset($_POST['city']) || isset($_POST['city']) == "" ) {

            //     $response['status'] = 'error';
            //     $response['message'] = 'City is Required ...';

            // } 
        } else if (empty($_POST['emailAddress']) || !isset($_POST['emailAddress']) || isset($_POST['emailAddress']) == "") {

            $response['status'] = 'error';
            $response['message'] = 'Email Address Field is Required ...';
        } else if (empty($_POST['phoneNumber']) || !isset($_POST['phoneNumber']) || isset($_POST['phoneNumber']) == "") {

            $response['status'] = 'error';
            $response['message'] = 'Phone Number Field is Required ...';
        } else if (empty($_POST['classDegree']) || !isset($_POST['classDegree']) || isset($_POST['classDegree']) == "") {

            $response['status'] = 'error';
            $response['message'] = 'Class of Degree is Required ...';
        } else if (empty($_POST['instituteAtt']) || !isset($_POST['instituteAtt']) || isset($_POST['instituteAtt']) == "") {

            $response['status'] = 'error';
            $response['message'] = 'Institution Attended is Required ...';
        } else if (empty($_POST['courseOfStudy']) || !isset($_POST['courseOfStudy']) || isset($_POST['courseOfStudy']) == "") {

            $response['status'] = 'error';
            $response['message'] = 'Course of Study is Required ...';
        } else if (empty($_POST['countryOrigin']) || !isset($_POST['countryOrigin']) || isset($_POST['countryOrigin']) == "") {

            $response['status'] = 'error';
            $response['message'] = 'Country of Origin is Required ...';
        } else if (empty($_POST['stateOrigin']) || !isset($_POST['stateOrigin']) || isset($_POST['stateOrigin']) == "") {

            $response['status'] = 'error';
            $response['message'] = 'State of Origin is Required ...';
        } else if (empty($_POST['lgaOrigin']) || !isset($_POST['lgaOrigin']) || isset($_POST['lgaOrigin']) == "") {

            $response['status'] = 'error';
            $response['message'] = 'Local Government of Origin is Required ...';
        } else if (empty($_POST['undergraduateCourse']) || !isset($_POST['firstName']) || isset($_POST['firstName']) == "") {

            $response['status'] = 'error';
            $response['message'] = 'Undergraduate Course is Required ...';
        } else if (empty($_FILES['oLevel']['name']) || !isset($_FILES['oLevel']['name']) || isset($_FILES['oLevel']['name']) == "") {

            $response['status'] = 'error';
            $response['message'] = 'OLevel Cannot Be Empty ...';
        } else if (empty($_FILES['undergCert']['name']) || !isset($_FILES['undergCert']['name']) || isset($_FILES['undergCert']['name']) == "") {

            $response['status'] = 'error';
            $response['message'] = 'Undergraduate Certificate Can Not Be Empty ...';
        } else if (empty($_FILES['passport']['name']) || !isset($_FILES['passport']['name']) || isset($_FILES['passport']['name']) == "") {

            $response['status'] = 'error';
            $response['message'] = 'Passport Can Not Be Empty ...';
        } else { // ---------------------------------------------------------------------------------------------------------------------------------------


            $timestamp      = time();
            $date           = date('Y-m-d H:m:s');
            $uploadDir      = "../../admission_portal/admissionUploads/";

            $firstname      = ucfirst(Sanitizer::sanitizeInput($_POST['firstName']));
            $lastname       = ucfirst(Sanitizer::sanitizeInput($_POST['lastName']));
            $middlename     = ucfirst(Sanitizer::sanitizeInput($_POST['middleName']));
            $gender         = Sanitizer::sanitizeInput($_POST['gender']);
            $religion       = Sanitizer::sanitizeInput($_POST['religion']);
            $birthDate      = Sanitizer::sanitizeInput($_POST['birthDate']);
            $address        = ucfirst(Sanitizer::sanitizeInput($_POST['address']));
            $country        = Sanitizer::sanitizeInput($_POST['country']);
            $state          = Sanitizer::sanitizeInput($_POST['state']);
            $city           = Sanitizer::sanitizeInput($_POST['city']);
            $emailAddress   = Sanitizer::sanitizeInput($_POST['emailAddress']);
            $phoneNumber    = Sanitizer::sanitizeInput($_POST['phoneNumber']);
            $classDegree    = Sanitizer::sanitizeInput($_POST['classDegree']);
            $instituteAtt   = ucfirst(Sanitizer::sanitizeInput($_POST['instituteAtt']));
            // $entryMode      = Sanitizer::sanitizeInput($_POST['entryMode']);
            $courseOfStudy  = Sanitizer::sanitizeInput($_POST['courseOfStudy']);
            $countryOrigin  = Sanitizer::sanitizeInput($_POST['countryOrigin']);
            $stateOrigin    = Sanitizer::sanitizeInput($_POST['stateOrigin']);
            $lgaOrigin      = Sanitizer::sanitizeInput($_POST['lgaOrigin']);
            $appSess        = $current_session;

            $oLevel         = basename($_FILES['oLevel']['name']);
            $undergCert     = basename($_FILES['undergCert']['name']);
            $transcript     = basename($_FILES['transcript']['name']);
            $passport       = basename($_FILES['passport']['name']);

            $oLevelExt      = pathinfo($oLevel, PATHINFO_EXTENSION);
            $undergCertExt  = pathinfo($undergCert, PATHINFO_EXTENSION);
            $transcriptExt  = pathinfo($transcript, PATHINFO_EXTENSION);
            $passportExt    = pathinfo($passport, PATHINFO_EXTENSION);
            $allowedFormat  = array('jpeg', 'png', 'jpg');
            $allowedFT      = array('pdf');

            if (!in_array($oLevelExt, $allowedFormat) || !in_array($undergCertExt, $allowedFormat) || !in_array($passportExt, $allowedFormat)) {

                $response['status'] = 'error';
                $response['message'] = 'Sorry only JPEG, JPG, PNG image formats are allowed for oLevel, undergraduate certificate and passport, Please Try Later';
            } elseif ($_FILES['oLevel']['size'] > 150000) {

                $response['status'] = 'error';
                $response['message'] = 'Sorry maximum file size allowed for olevel is 150kb, check files and try Later';
            } elseif ($_FILES['undergCert']['size'] > 150000) {

                $response['status'] = 'error';
                $response['message'] = 'Sorry maximum file size allowed for undergraduate certificate is 150kb, check files and try Later';
            } elseif ($_FILES['passport']['size'] > 150000) {

                $response['status'] = 'error';
                $response['message'] = 'Sorry maximum file size allowed for passport is 150kb, check files and try Later';
            } else {

                // If master certificate is present ===============================================================
                if (isset($_FILES['masterCert']['name'])) {

                    $masterCert         = basename($_FILES['masterCert']['name']);
                    $masterCertExt      = basename($_FILES['masterCert']['name']);
                    $masterCertNew      = generateUniqueFileName($masterCert);
                    $masterCertNewDest  = $uploadDir . $masterCertNew;

                    if (!in_array($masterCertExt, $allowedFormat) && $_FILES['oLevel']['size'] > 150000) {

                        $response['status'] = 'error';
                        $response['message'] = 'Invalid file format or file size, JPEG, JPG, PNG image formats 
                            are allowed with not more than 150kb size for Master Certificate, Please Try Later';
                    } else {

                        move_uploaded_file($masterCertNewDest, $uploadDir);
                    }
                } else {

                    $masterCert = NULL;
                    $masterCertNew  = NULL;
                    $masterCertNewDest = NULL;
                }
                // ===================================================================================================

                $oLevelNew      = generateUniqueFileName($oLevel);
                $undergCertNew  = generateUniqueFileName($undergCert);
                $transcriptNew  = generateUniqueFileName($transcript);
                $passportNew    = generateUniqueFileName($passport);
                $responseNew    = [];
                $fullnameNew    = $lastname . ' ' . $firstname . ' ' . $middlename;
                $application_status     = 'registered';
                $undergraduateCourse    = ucfirst(Sanitizer::sanitizeInput($_POST['undergraduateCourse']));

                $oLevelNewDest     = $uploadDir . $oLevelNew;
                $undergCertNewDest = $uploadDir . $undergCertNew;
                $transcriptNewDest = $uploadDir . $transcriptNew;
                $passportNewDest   = $uploadDir . $passportNew;

                // Get department ID from the PG Course selected =============================================
                $getDepartment  = $Crud->read('program_course', 'id', $courseOfStudy);
                $department     = $getDepartment->department_id;
                // ===========================================================================================

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
                    'department'    => $department,
                    'institute_attended' => $instituteAtt,
                    'course_studied'    => $undergraduateCourse,
                    'class_degree'      => $classDegree,
                    'date_of_birth'     => $birthDate,
                    'country_of_origin' => $countryOrigin,
                    'state_of_origin'   => $stateOrigin,
                    'local_government'  => $lgaOrigin,
                    'application_status' => $application_status,
                    'application_session' => $appSess,
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
                if ($updateUser && $userCredentials) {

                    move_uploaded_file($_FILES['oLevel']['tmp_name'], $oLevelNewDest);
                    move_uploaded_file($_FILES['undergCert']['tmp_name'], $undergCertNewDest);
                    move_uploaded_file($_FILES['transcript']['tmp_name'], $transcriptNewDest);
                    move_uploaded_file($_FILES['passport']['tmp_name'], $passportNewDest);

                    $response['status'] = 'success';
                    $response['message'] = 'Application Successfully Completed, Kindly Await Admission';
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Application Could Not Be Completed Successfully, Try Later';
                }
                // Upload the images to folder ................................................................

            }
        }
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }


    // Payment of acceptance fee
    if (isset($_POST['acceptanceForm'])) {

        $transferReff   =   "trn" . date('Y') . rand(9, 999999);
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

        if ($exPayment == "") {
            // AND payment_status=?

            $crData = [
                "application_id"    => $appID,
                "transactionId"     => $transferReff,
                "paid_amount"       => $amount,
                "description"       => $desc
            ];

            $createPayment  =  $Crud->create('application_payment', $crData);

            if ($createPayment) {

                if ($activePayment->name == 'Xpress-pay') {

                    $data = [
                        'amount'            => $amount,
                        'email'             => $email,
                        'callBackUrl'       => $uri . "/fuo_pg/app/function/acceptance_payment.php?xpacceptance_callback={$transferReff}",
                        "currency"          => "NGN",
                        "transactionId"     => $transferReff,
                        "metadata"          => [
                            [
                                "Name"      => "Application Id",
                                "Value"     => $appID
                            ],
                            [
                                "Name"      => "Fullname",
                                "Value"     => $name
                            ],
                            [
                                "Name"      => "Email",
                                "Value"     => $email
                            ],
                            [
                                "Name"      => "Amount paid",
                                "Value"     => $amount
                            ],
                            [
                                "Name"      => "Purpose",
                                "Value"     => "Application Fee"
                            ],
                        ]
                    ];

                    $paymentResult = $xpressPay->Xpresspay($data);
                    if ($paymentResult['responseCode'] == "00") {
                        // Handle the successful payment initiation
                        $paymentData = $paymentResult['data'];
                        header('Location: ' . $paymentData['paymentUrl']);
                    } else {
                        // Handle the payment initiation error
                        $errorMessage = $paymentResult['data'];
                        header('Location: ' . $url . '/admission_portal/admission_home?error="Payment%20Failed,%20Try%20Later!!!"');
                    }
                } else {
                    $data = [
                        'amount'            => $amount * 100,
                        'email'             => $email,
                        'callback_url'      => $uri . "/fuo_pg/app/function/acceptance_payment.php?trnId={$transferReff}",
                        "currency"          => "NGN",
                        "transactionId"     => $transferReff,
                        "metadata"          => [
                            [
                                "Name"      => "Application Id",
                                "Value"     => $appID
                            ],
                            [
                                "Name"      => "Fullname",
                                "Value"     => $name
                            ],
                            [
                                "Name"      => "Email",
                                "Value"     => $email
                            ],
                            [
                                "Name"      => "Amount paid",
                                "Value"     => $amount
                            ],
                            [
                                "Name"      => "Purpose",
                                "Value"     => "Application Fee"
                            ],
                        ]
                    ];

                    $paymentResult = $xpressPay->PayStack($data);
                    $paymentStatus = $paymentResult['status'];
                    // var_export ($paymentResult);
                    if ($paymentStatus == true) {
                        // Handle the successful payment initiation
                        $paymentData = $paymentResult['data'];
                        header('Location: ' . $paymentData['authorization_url']);
                    } else {
                        // Handle the payment initiation error
                        $errorMessage = $paymentResult['data'];
                        header('Location: ' . $url . '/admission_portal/admission_home?error=Unable to process payment');
                    }
                }
            }
        } else if ($exPayment->payment_status == 'fail' || $exPayment->payment_status == '') {

            $createPayment  =  $db->prepare("UPDATE application_payment SET transactionId=? WHERE application_id=? AND description=?");
            $createPayment->execute([$transferReff, $appID, $desc]);

            if ($createPayment) {

                if ($activePayment->name == 'Xpress-pay') {

                    $data = [
                        'amount'            => $amount,
                        'email'             => $email,
                        'callBackUrl'       => $uri . "/app/function/acceptance_payment.php?xpacceptance_callback={$transferReff}",
                        "currency"          => "NGN",
                        "transactionId"     => $transferReff,
                        "metadata"          => [
                            [
                                "Name"      => "Application Id",
                                "Value"     => $appID
                            ],
                            [
                                "Name"      => "Fullname",
                                "Value"     => $name
                            ],
                            [
                                "Name"      => "Email",
                                "Value"     => $email
                            ],
                            [
                                "Name"      => "Amount paid",
                                "Value"     => $amount
                            ],
                            [
                                "Name"      => "Purpose",
                                "Value"     => "Application Fee"
                            ],
                        ]
                    ];

                    $paymentResult = $xpressPay->Xpresspay($data);
                    if ($paymentResult['responseCode'] == "00") {
                        // Handle the successful payment initiation
                        $paymentData = $paymentResult['data'];
                        header('Location: ' . $paymentData['paymentUrl']);
                    } else {
                        // Handle the payment initiation error
                        $errorMessage = $paymentResult['data'];
                        header('Location: ' . $url . '/admission_portal/admission_home?error="Payment%20Failed,%20Try%20Later!!!"');
                    }
                } else {

                    $data = [
                        'amount'            => $amount * 100,
                        'email'             => $email,
                        'callback_url'      => $uri . "/app/function/acceptance_payment.php?trnId={$transferReff}",
                        "currency"          => "NGN",
                        "transactionId"     => $transferReff,
                        "metadata"          => [
                            [
                                "Name"      => "Application Id",
                                "Value"     => $appID
                            ],
                            [
                                "Name"      => "Fullname",
                                "Value"     => $name
                            ],
                            [
                                "Name"      => "Email",
                                "Value"     => $email
                            ],
                            [
                                "Name"      => "Amount paid",
                                "Value"     => $amount
                            ],
                            [
                                "Name"      => "Purpose",
                                "Value"     => "Application Fee"
                            ],
                        ]
                    ];

                    $paymentResult = $xpressPay->PayStack($data);
                    $paymentStatus = $paymentResult['status'];
                    // var_export ($paymentResult);
                    if ($paymentStatus == true) {
                        $paymentData = $paymentResult['data'];
                        header('Location: ' . $paymentData['authorization_url']);
                    } else {
                        // Handle the payment initiation error
                        $errorMessage = $paymentResult['data'];
                        header('Location: ' . $url . '/admission_portal/admission_home?error=Unable to process payment');
                    }
                }
            }
        } else if ($exPayment->payment_status == 'success') {

            header('Location: ' . $url . '/admission_portal/admission_home?error=Payment%20Already%20Exist%20!!!');
        } else {
            header('Location: ' . $url . '/admission_portal/admission_home?error=Payment%20Already%20Exist%20!!!');
        }
    }
}