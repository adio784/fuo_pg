<?php

$database   = new Database();
$Crud       = new CRUD($database);
$db         = $database->getConnection();
$uid        = $_SESSION['user_id'];

$Users      = $db->prepare("
                        SELECT *,
                        user_credentials.application_id as appId,
                        programme.programme_title,
                        department.department_name,
                        program_course.course_name
                        FROM `students`
                        INNER JOIN user_credentials ON user_credentials.application_id = students.application_id
                        INNER JOIN programme ON programme.program_id = students.program
                        INNER JOIN program_course ON program_course.program_id = programme.program_id
                        LEFT JOIN department ON department.id = students.department
                        WHERE students.application_id = ? LIMIT 1");
$Users->execute([$uid]);

$User               = $Users->fetchObject();
$fullname           = $User->last_name . ' ' . $User->first_name . ' ' . $User->middle_name;
$email              = $User->email;
$phone              = $User->mobile_no;
$programId          = $User->program;
$program            = $User->programme_title;
$course             = $User->course_name;
$courseId           = $User->course;
$department         = $User->department_name;
$departmentId       = $User->department;
$matricNo           = $User->matric_no;
$passport           = ($User->passport != '') ? "../admission_portal/admissionUploads/$User->passport" : "../assets/images/avatars/avatar-17.png";
$csession           = $Crud->read('access', 'setting', 'current_session');
$current_session    = $csession->value;
$Levels             = $Crud->readAllBy('levels', 'status', 'active');
