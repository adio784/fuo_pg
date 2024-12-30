<?php

$database   = new Database();
$Crud       = new CRUD($database);
$db         = $database->getConnection();
$uid        = $_SESSION['userId'];

// Fetch lecturer details
$Users      = $db->prepare("
                        SELECT *,
                        lecturers.id as lecturerId,
                        department.department_name,
                        college.college_name,
                        college.college_abbr,
                        users.email as user_email
                        FROM `users`
                        INNER JOIN lecturers ON users.id = lecturers.user_id
                        INNER JOIN department ON department.id = lecturers.department_id
                        INNER JOIN college ON college.id = lecturers.college_id
                        WHERE users.id = ? AND lecturers.user_id = ? LIMIT 1");
$Users->execute([$uid, $uid]);

// Fetch the lecturer object

$User               = $Users->fetchObject();
$usurname           = $User->surname;
$uothernames        = $User->other_names;
$fullname           = $User->surname . ' ' . $User->other_names;
$email              = $User->user_email;
$phone              = $User->phone_number;
$gender             = $User->gender;
$maritalStatus      = $User->marital_status;
$department         = $User->department_name;
$departmentId       = $User->department_id;
$lecturerId         = $User->lecturerId;
$college            = $User->college_name;
$collegeId          = $User->college_id;
$isCourseCord       = ($User->is_course_cord == 1) ? "Yes" : "No";
$isActive           = ($User->is_active == 1) ? "Active" : "Inactive";
$createdAt          = $User->created_at;
$passport           = ($User->passport != '') ? "../uploads/lecturer_passports/$User->passport" : "../assets/images/avatars/avatar-17.png";

// Fetch the current session
$csession           = $Crud->read('access', 'setting', 'current_session');
$current_session    = $csession->value;

// Fetch active levels
$Levels             = $Crud->readAllBy('levels', 'status', 'active');

$secretKey = 'myf@unt@!nSecret*';


function encodeUserId($userId, $secretKey)
{
    $data = $userId . '|' . time();
    $hash = hash_hmac('sha256', $data, $secretKey);
    return base64_encode($data . '|' . $hash);
}

function decodeUserId($encodedData, $secretKey)
{
    $decoded = base64_decode($encodedData);
    list($userId, $timestamp, $hash) = explode('|', $decoded);
    $computedHash = hash_hmac('sha256', $userId . '|' . $timestamp, $secretKey);

    if (hash_equals($computedHash, $hash)) {
        return $userId;
    }
    return null;
}

