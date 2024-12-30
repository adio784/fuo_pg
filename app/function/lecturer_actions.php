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
    require_once '../../lecturer/includes/lecturer_data.php';

    $database   = new Database();
    $Crud       = new CRUD($database);
    $Sanitizer  = new Sanitizer();
    $PaymentM   = new PAYMENT();
    $uid        = $_SESSION['user_id'];
    $db         = $database->getConnection();


    foreach ($Users as $User) {
        $name       = $User->last_name . ' ' . $User->first_name . ' ' . $User->middle_name;
        $email      = $User->email;
        $matric     = $User->application_id;
        $phone      = $User->mobile_no;
        $stdProgram = $course;
        // $uri        = $_SERVER['HTTP_HOST'];
    }



    // Create single entry course ......................................................................
    if (isset($_POST['createCourse'])) {

        // getConnection
        $courseTitle    = ucfirst($Sanitizer->sanitizeInput($_POST['courseTitle']));
        $courseCode     = strtoupper($Sanitizer->sanitizeInput($_POST['courseCode']));
        $courseUnit     = $Sanitizer->sanitizeInput($_POST['courseUnit']);
        $courseStatus   = strtoupper($Sanitizer->sanitizeInput($_POST['courseStatus']));
        $courseSemester = $Sanitizer->sanitizeInput($_POST['courseSemester']);
        $program        = $Sanitizer->sanitizeInput($_POST['program']);
        $pgCourse       = $Sanitizer->sanitizeInput($_POST['pgCourse']);


        // Perform user login using the database connection.................................................................................
        $stmt = $database->getConnection()->prepare('SELECT id, course_code, course_title FROM `departmental_courses` WHERE course_code = ? LIMIT 1');
        $stmt->execute([$courseCode]);
        $courseDetail = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($courseDetail == "") {

            $Coursedata = [
                'course_code'   => $courseCode,
                'course_title'  => $courseTitle,
                'course_status' => $courseStatus,
                'course_unit'   => $courseUnit,
                'semester'      => $courseSemester,
                'department_id' => $departmentId,
                'program_id'    => $program,
                'pg_course'     => $pgCourse,
                'is_active'     => 1
            ];
            $createCourse = $Crud->create('departmental_courses', $Coursedata);

            if ($createCourse) {

                $response['status'] = 'success';
                $response['app_id'] = $applicationID;
                $response['surname'] = $lastname;
                $response['message'] = "Course Successfully Added ...";
            } else {

                $response['status'] = 'error';
                $response['message'] = 'Error Occured ! Please check your input and try later ';
            }
        } else {

            $response['status'] = 'error';
            $response['message'] = 'Course Code Already Exist !!! ';
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }






    // Update course single entry course ......................................................................
    if (isset($_POST['updateCourse'])) {

        // getConnection
        $courseID       = ucfirst($Sanitizer->sanitizeInput($_POST['courseID']));
        $courseTitle    = ucfirst($Sanitizer->sanitizeInput($_POST['courseTitle']));
        $courseCode     = strtoupper($Sanitizer->sanitizeInput($_POST['courseCode']));
        $courseUnit     = $Sanitizer->sanitizeInput($_POST['courseUnit']);
        $courseStatus   = strtoupper($Sanitizer->sanitizeInput($_POST['courseStatus']));
        $courseSemester = $Sanitizer->sanitizeInput($_POST['courseSemester']);
        $program        = $Sanitizer->sanitizeInput($_POST['program']);
        $pgCourse       = $Sanitizer->sanitizeInput($_POST['pgCourse']);
        $isActive       = $Sanitizer->sanitizeInput($_POST['isActive']);
        


        // Perform user login using the database connection.................................................................................
        $stmt = $database->getConnection()->prepare('SELECT id, course_code, course_title FROM `departmental_courses` WHERE id = ? LIMIT 1');
        $stmt->execute([$courseID]);
        $courseDetail = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($courseDetail != "") {

            $Coursedata = [
                'course_code'   => $courseCode,
                'course_title'  => $courseTitle,
                'course_status' => $courseStatus,
                'course_unit'   => $courseUnit,
                'semester'      => $courseSemester,
                'department_id' => $departmentId,
                'program_id'    => $program,
                'pg_course'     => $pgCourse,
                'is_active'     => $isActive
            ];
            $createCourse = $Crud->update('departmental_courses', 'id', $courseID, $Coursedata,);

            if ($createCourse) {

                $response['status'] = 'success';
                $response['app_id'] = $applicationID;
                $response['surname'] = $lastname;
                $response['message'] = "Course Successfully Updated ...";
            } else {

                $response['status'] = 'error';
                $response['message'] = 'Error Occured ! Please check your input and try later ';
            }

        } else {

            $response['status'] = 'error';
            $response['message'] = 'Course Do Not Exist !!! ';
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }


    // Update Lecturer Profile ...........................................................................
    if (isset($_POST['updateProfile'])) {

        // getConnection
        $lecturerID     = ucfirst($Sanitizer->sanitizeInput($_POST['lecturerID']));
        $surname        = ucfirst($Sanitizer->sanitizeInput($_POST['surname']));
        $otherNames     = strtoupper($Sanitizer->sanitizeInput($_POST['otherNames']));
        $email          = $Sanitizer->sanitizeInput($_POST['email']);
        $phone          = strtoupper($Sanitizer->sanitizeInput($_POST['phone']));
        $gender         = $Sanitizer->sanitizeInput($_POST['gender']);
        $maritalStatus  = $Sanitizer->sanitizeInput($_POST['maritalStatus']);

        $Data = [
            'surname'           => $surname,
            'other_names'       => $otherNames,
            'gender'            => $gender,
            'marital_status'    => $maritalStatus,
            'email'             => $email,
            'phone_number'      => $phone
        ];
        $updateLecturer = $Crud->update('lecturers', 'id', $lecturerID, $Data);

        if ($updateLecturer) {

            $response['status'] = 'success';
            $response['message'] = "Lecturer Profile Successfully Updated ...";
        } else {

            $response['status'] = 'error';
            $response['message'] = 'Error Occured ! Please check your input and try later ';
        }


        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    // Change Lecturer Password ...........................................................................
    if (isset($_POST['changePassword'])) {

        // getConnection
        $userID             = ucfirst($Sanitizer->sanitizeInput($_POST['userID']));
        $oldPassword        = ucfirst($Sanitizer->sanitizeInput($_POST['oldPassword']));
        $newPassword        = password_hash($Sanitizer->sanitizeInput($_POST['newPassword']), PASSWORD_BCRYPT);
        $confirmPassword    = $Sanitizer->sanitizeInput($_POST['confirmPassword']);

        if (empty($_POST['oldPassword']) || empty($_POST['newPassword'])) {

            $response['status']     = 'error';
            $response['statusCode'] = 400;
            $response['message']    = "Fill All Fields !!!";
            
        } else {
            if ($newPassword == $confirmPassword) {

                $Data = ['password'  => $newPassword];
                $updateLecturer = $Crud->update('users', 'id', $userID, $Data);

                if ($updateLecturer) {

                    $response['status'] = 'success';
                    $response['message'] = "Password Successfully Changed ...";
                } else {

                    $response['status'] = 'error';
                    $response['message'] = 'Error Occured ! Please check your input and try later ';
                }
            } else {

                $response['status'] = 'error';
                $response['message'] = 'Confirm Password Mismatch !!! ';
            }
        }
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }



    // Bulk Upload Courses ......................................................................
    if (isset($_POST['importCourse'])) {
        
        $fileUpload    = ucfirst($Sanitizer->sanitizeInput($_POST['fileUpload']));
        $file           = $_FILES['fileUpload']['tmp_name'];

        if (($handle = fopen($file, 'r')) !== false) {
            $header = fgetcsv($handle);

            // Check if the header matches expected fields
            $expectedHeader = ['course_code', 'course_title', 'course_status', 'course_unit', 'semester', 'department_id', 'program_id', 'pg_course'];
            if ($header !== $expectedHeader) {
                
                $response['status'] = 'error';
                $response['message'] = 'Invalid CSV format. Please use the provided template.';
            }

            $importedData = [];
            $duplicateErrors = [];
            
            while (($row = fgetcsv($handle)) !== false) {
                
                $importedData[] = [
                    'course_code'   => $row[0],
                    'course_title'  => $row[1],
                    'course_status' => $row[2],
                    'course_unit'   => $row[3],
                    'semester'      => $row[4],
                    'department_id' => $row[5],
                    'program_id'    => $row[6],
                    'pg_course'     => $row[7],
                    'is_active'     => 1,
                ];
            }
            fclose($handle);

            try {
                
                $stmtSelect = $db->prepare("SELECT COUNT(*) FROM departmental_courses WHERE course_code = :course_code AND department_id = :department_id AND program_id = :program_id");
                $stmt = $db->prepare("INSERT INTO departmental_courses (course_code, course_title, course_status, course_unit, semester, department_id, program_id, pg_course, is_active) 
                    VALUES (:course_code, :course_title, :course_status, :course_unit, :semester, :department_id, :program_id, :pg_course, :is_active)");

                foreach ($importedData as $courseData) {

                    $stmtSelect->execute([
                        ':course_code'   => $courseData['course_code'],
                        ':department_id' => $courseData['department_id'],
                        ':program_id'    => $courseData['program_id'],
                    ]);

                    if ($stmtSelect->fetchColumn() == 0) {
                        $stmtInsert->execute($courseData);
                    } else {

                        $duplicateErrors[] = "Duplicate course: " . $courseData['course_code'];
                        $response['status'] = 'error';
                        $response['message'] = "Duplicate course skipped: " . $courseData['course_code'] . "<br>";
                    }
                   
                }
                
                $response['status'] = 'success';
                $response['message'] = "Data Imported Successfully ...";

                if (!empty($duplicateErrors)) {

                    $response['status'] = 'error';
                    foreach ($duplicateErrors as $error) {
                        $response['message'] .= htmlspecialchars($error) ;
                    }
                }
               
            } catch (PDOException $e) {
                //echo 'Database error: ' . $e->getMessage();
                $response['status'] = 'error';
                $response['message'] = 'Internal Server Error !!!';
            }
        } else {

            $response['status'] = 'error';
            $response['message'] = 'Failed to open the uploaded file.';
        }

        // Returning JavaScript Object Notation As Response ...............
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
        // ................................................................
    }


    // Template download courses ...............................................................................
    if (isset($_GET['download_template'])) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="course_template.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['course_code', 'course_title', 'course_status', 'course_unit', 'semester', 'department_id', 'program_id', 'pg_course']);
        fclose($output);
        exit;
    }




    // Change lecturer password on first login ........................................................................
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


    // Update lecturer profile .........................................................................................
    if (isset($_POST['updateProfile'])) {

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
} else {
    header("Location: /");
}
