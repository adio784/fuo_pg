<?php
$database   = new Database();
$Crud       = new CRUD($database);
$uid        = $_SESSION['user_id'];

$Users      = $database->getConnection()->prepare("
                SELECT *, 
                user_credentials.application_id as appId 
                FROM `application`
                INNER JOIN user_credentials ON user_credentials.application_id = application.application_id
                WHERE application.id = ? LIMIT 1");
$Users->execute([$uid]);

$User       = $Users->fetchObject();
if ( $User !== false)
{
$fname      = $User->last_name. ' ' .$User->first_name. ' ' .$User->middle_name;
$uname      = $User->first_name. ' ' .$User->middle_name;
$appID      = $User->application_id;
$Receipts   = $Crud->readAllBy('application_payment', 'application_id', $appID);
$adminStat  = 1;
$isPHD      = 0;
$Prog       = $Crud->read('programme', 'program_id', $User->program);
$UProgram   = $Crud->read('program_course', 'id', $User->course);
$UCr        = $Crud->read('countries', 'country_code', $User->country);
$RCr        = $Crud->read('countries', 'country_code', $User->country_of_origin);
}
// var_export($User);
?>