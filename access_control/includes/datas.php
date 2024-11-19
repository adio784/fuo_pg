<?php

    $uemail     = $_SESSION['user_id'];
    $Users      = $db->prepare("
                        SELECT *,
                        users.role,
                        department.department_name,
                        department.id as departmentID
                        FROM `lecturer`
                        INNER JOIN users ON users.username = lecturer.email
                        LEFT JOIN department ON department.id = lecturer.department_id
                        WHERE lecturer.email = ? LIMIT 1");
                        $Users->execute([$uemail]);
    $User               = $Users->fetchObject();
    $uid                = $User->id;
    $fullname           = $User->surname .' '. $User->othernames;
    $email              = $User->email;
    $phone              = $User->phone;
    $role               = $User->role;
    $department         = $User->department_name;
    $departmentId       = $User->departmentID;
    $isHod              = $User->is_hod;
    $csession           = $Crud->read('access', 'setting', 'current_session');
    $current_session    = $csession->value;
?>