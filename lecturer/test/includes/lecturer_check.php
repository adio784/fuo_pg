<?php

// Initialize the database and session user ID
$database   = new Database();
$Crud       = new CRUD($database);
$uid        = $_SESSION['user_id'];

// Fetch lecturer details
$Users = $database->getConnection()->prepare("
    SELECT u.id as user_id, 
           u.surname, 
           u.other_names, 
           u.email, 
           u.phone, 
           l.college_id, 
           l.department_id, 
           l.is_course_cord, 
           l.is_active, 
           c.name as college_name, 
           d.department_name 
    FROM users u
    INNER JOIN lecturers l ON l.user_id = u.id
    LEFT JOIN colleges c ON c.id = l.college_id
    LEFT JOIN department d ON d.id = l.department_id
    WHERE u.id = ? 
    LIMIT 1
");
$Users->execute([$uid]);

// Fetch the lecturer object
$User = $Users->fetchObject();

if ($User !== false) {
    // Populate lecturer-specific information
    $fullname       = $User->surname . ' ' . $User->other_names;
    $email          = $User->email;
    $phone          = $User->phone;
    $collegeName    = $User->college_name;
    $departmentName = $User->department_name;
    $isCourseCord   = $User->is_course_cord ? "Yes" : "No";
    $isActive       = $User->is_active ? "Active" : "Inactive";

    // Read other related data if necessary
    $currentSession = $Crud->read('access', 'setting', 'current_session');
    $sessionValue   = $currentSession->value;

    // Optionally, perform any additional checks or computations here
} else {
    // Handle cases where the lecturer is not found
    echo "Lecturer not found or inactive.";
}
