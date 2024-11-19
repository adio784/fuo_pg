<?php
session_start();
ob_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['user_status'])) {

    require_once '../core/autoload.php';
    require_once '../core/Database.php';
    require_once '../common/CRUD.php';
    require_once '../common/Sanitizer.php';
    $database   = new Database();
    $Crud       = new CRUD($database);
    $Sanitizer  = new Sanitizer;
    $db         = $database->getConnection();
    include 'datas.php';

    if ($role === 'lecturer' and $isHod === 1) {
        // get all lecturers by department ---------------------------------------------------
        $Lecturers  = $Crud->readAllBy('lecturer', 'department_id', $departmentId);
        // -----------------------------------------------------------------------------------
        // get all student -------------------------------------------------------------------
        $Students  = $Crud->readAllBy('students', 'department', $departmentId);
        // -----------------------------------------------------------------------------------
        // get all student -------------------------------------------------------------------
        $Courses  = $Crud->readAllBy('department_courses', 'department', $departmentId);
        // -----------------------------------------------------------------------------------
        // get all lecturers by department ---------------------------------------------------
        $programs  = $Crud->readAllBy('program_course', 'department_id', $departmentId);
        // -----------------------------------------------------------------------------------
    } else if ($role == 'admin' || $role == "super_admin") {
        // get all lecturers ----------------------------------------------------------------
        $Lecturers  = $Crud->readAll('lecturer');
        // -----------------------------------------------------------------------------------
        // get all student -------------------------------------------------------------------
        $Students  = $Crud->readAll('students');
        // -----------------------------------------------------------------------------------
        // get all lecturers by department ---------------------------------------------------
        $programs  = $Crud->readAll('program_course');
        // -----------------------------------------------------------------------------------
    } else {
        // get all lecturers ----------------------------------------------------------------
        $Lecturers  = $Crud->readAll('lecturer');
        // -----------------------------------------------------------------------------------
        // get all student -------------------------------------------------------------------
        $Students  = $Crud->readAll('students');
        // -----------------------------------------------------------------------------------
        // get all lecturers by department ---------------------------------------------------
        $programs  = $Crud->readAll('students');
        // -----------------------------------------------------------------------------------
    }
} else {
    header("Location: index");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Welcome: FUO - School Of Post Graduate Studies </title>
    <!--favicon-->
    <link rel="icon" href="../assets/images/fuo_logo.jpeg" type="image/x-icon">
    <!-- Vector CSS -->
    <link href="../assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
    <!-- simplebar CSS-->
    <link href="../assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <!-- Bootstrap core CSS-->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <!-- animate CSS-->
    <link href="../assets/css/animate.css" rel="stylesheet" type="text/css" />
    <!-- Icons CSS-->
    <link href="../assets/css/icons.css" rel="stylesheet" type="text/css" />
    <!-- Sidebar CSS-->
    <link href="../assets/css/sidebar-menu.css" rel="stylesheet" />
    <!-- Custom Style-->
    <link href="../assets/css/app-style.css" rel="stylesheet" />

    <!--Data Tables -->
    <link href="../assets/plugins/bootstrap-datatable/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css">
    <link href="../assets/plugins/bootstrap-datatable/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css">

    <script src="../assets/js/jquery.min.js"></script>


</head>

<body>

    <!-- Start wrapper-->
    <div id="wrapper">

        <!--Start sidebar-wrapper-->
        <div id="sidebar-wrapper" data-simplebar="" data-simplebar-auto-hide="true">
            <!-- class="bg-primary text-light border-right border-light shadow-none" -->
            <div class="brand-logo">
                <a href="home">
                    <!-- <img src="../assets/images/logo-icon.png" class="logo-icon" alt="logo icon"> -->
                    <img src="../assets/images/logo/logo.jpg" class="logo-icon" alt="logo icon" style="height: 40px;">
                    <h5 class="logo-text">PG Portal</h5>
                </a>
            </div>
            <ul class="sidebar-menu do-nicescrol">
                <li class="sidebar-header">MAIN NAVIGATION</li>
                <li>
                    <a href="home2" class="waves-effect">
                        <i class="icon-home"></i> <span>Home</span> </i>
                    </a>
                </li>
                <li>
                    <a href="home" class="waves-effect">
                        <i class="icon-home"></i> <span>Dashboard</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="admins"><i class="fa fa-circle-o"></i> Admins</a></li>
                        <li><a href="current_session"><i class="fa fa-circle-o"></i> Current Session</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javaScript:void();" class="waves-effect">
                        <i class="icon-briefcase"></i>
                        <span>Students</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="add-student"><i class="fa fa-circle-o"></i> Add Student </a></li>
                        <li><a href="list-student"><i class="fa fa-circle-o"></i> Student List</a></li>
                        <!-- List student, Edit Student, Delete Student, View Student, See Courses Registered, See Payments,  -->
                    </ul>
                </li>
                <li>
                    <a href="lecturer" class="waves-effect">
                        <i class="icon-calendar"></i> <span>Lecturer </span>
                        <small class="badge float-right badge-info">New</small>
                    </a>
                </li>

                <li>
                    <a href="javaScript:void();" class="waves-effect">
                        <i class="icon-envelope"></i>
                        <span>Notification</span>
                        <small class="badge float-right badge-warning">12</small>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="mail-inbox.html"><i class="fa fa-circle-o"></i> Inbox</a></li>
                        <li><a href="mail-compose.html"><i class="fa fa-circle-o"></i> Compose</a></li>
                        <li><a href="mail-read.html"><i class="fa fa-circle-o"></i> Read Mail</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javaScript:void();" class="waves-effect">
                        <i class="icon-layers"></i>
                        <span>Courses</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="add-course"><i class="fa fa-circle-o"></i> Add Course</a></li>
                        <li><a href="view-course"><i class="fa fa-circle-o"></i> View Courses</a></li>
                        <!-- List courses, Edit courses, Delete courses, View course, course allocation, See Payments,  -->
                    </ul>
                </li>
                <li>
                    <a href="javaScript:void();" class="waves-effect">
                        <i class="icon-note"></i> <span>Results</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="add-result"><i class="fa fa-circle-o"></i> Add Result </a></li>
                        <li><a href="view-result"><i class="fa fa-circle-o"></i> View Result</a></li>
                        <li><a href="result-spreadsheet"><i class="fa fa-circle-o"></i> Result Spreadsheet</a></li>
                        <li><a href="result-analysis"><i class="fa fa-circle-o"></i> Result Analysis</a></li>
                        <li><a href="result-report"><i class="fa fa-circle-o"></i> Result Report</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javaScript:void();" class="waves-effect">
                        <i class="icon-grid"></i> <span>Payments</span>
                        <i class="fa fa-angle-left float-right"></i>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="add-payment"><i class="fa fa-circle-o"></i> Add Payments</a></li>
                        <li><a href="view-payment"><i class="fa fa-circle-o"></i> View Payments</a></li>
                        <li><a href="payment-history"><i class="fa fa-circle-o"></i> Payment History</a></li>
                        <li><a href="debtor-list"><i class="fa fa-circle-o"></i> Debtors List</a></li>
                        <li><a href="clear-student"><i class="fa fa-circle-o"></i> Cleared Student </a></li>
                    </ul>
                </li>

                <li>
                    <a href="javaScript:void();" class="waves-effect">
                        <i class="icon-lock"></i> <span>Roles/ Permissions</span>
                        <i class="fa fa-angle-left float-right"></i>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="set-role"><i class="fa fa-circle-o"></i> Set Roles</a></li>
                        <li><a href="set-permission"><i class="fa fa-circle-o"></i> Set Permissions </a></li>
                    </ul>
                </li>
                <li class="sidebar-header">LABELS</li>
                <li><a href="profile" class="waves-effect"><i class="fa fa-circle-o text-red"></i> <span>Profile</span></a></li>
                <li><a href="logout" class="waves-effect"><i class="fa fa-circle-o text-yellow"></i> <span>Logout</span></a></li>
                <li><a href="lock-screen" class="waves-effect"><i class="fa fa-circle-o text-aqua"></i> <span>Lock Screen </span></a></li>
            </ul>

        </div>
        <!--End sidebar-wrapper-->

        <!--Start topbar header-->
        <header class="topbar-nav">
            <!-- <nav class="navbar navbar-expand fixed-top bg-white border-bottom border-light shadow-none"> -->
            <nav class="navbar navbar-expand fixed-top bg-white">
                <ul class="navbar-nav mr-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link toggle-menu" href="javascript:void();">
                            <i class="icon-menu menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <form class="search-bar">
                            <input type="text" class="form-control" placeholder="Enter keywords">
                            <a href="javascript:void();"><i class="icon-magnifier"></i></a>
                        </form>
                    </li>
                </ul>

                <ul class="navbar-nav align-items-center right-nav-link">

                    <li class="nav-item dropdown-lg">
                        <a class="nav-link dropdown-toggle dropdown-toggle-nocaret waves-effect" data-toggle="dropdown" href="javascript:void();">
                            <i class="icon-bell"></i><span class="badge badge-primary badge-up">10</span></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    You have 10 Notifications
                                    <span class="badge badge-primary">10</span>
                                </li>
                                <li class="list-group-item">
                                    <a href="javaScript:void();">
                                        <div class="media">
                                            <i class="icon-people fa-2x mr-3 text-info"></i>
                                            <div class="media-body">
                                                <h6 class="mt-0 msg-title">New Registered Users</h6>
                                                <p class="msg-info">Lorem ipsum dolor sit amet...</p>
                                            </div>
                                        </div>
                                    </a>
                                </li>

                                <li class="list-group-item">
                                    <a href="javaScript:void();">
                                        <div class="media">
                                            <i class="icon-bell fa-2x mr-3 text-danger"></i>
                                            <div class="media-body">
                                                <h6 class="mt-0 msg-title">New Updates</h6>
                                                <p class="msg-info">Lorem ipsum dolor sit amet...</p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="list-group-item"><a href="javaScript:void();">See All Notifications</a></li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown" href="#">
                            <span class="user-profile"><img src="../assets/images/avatars/avatar-17.png" class="img-circle" alt="user avatar"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li class="dropdown-item user-details">
                                <a href="javaScript:void();">
                                    <div class="media">
                                        <div class="avatar"><img class="align-self-start mr-3" src="../assets/images/avatars/avatar-17.png" alt="user avatar"></div>
                                        <div class="media-body">
                                            <h6 class="mt-2 user-title"><?= $fullname ?></h6>
                                            <p class="user-subtitle"><?= $email ?> </p>
                                            <p class="user-subtitle"><?= $departmentId ?> </p>

                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="dropdown-divider"></li>
                            <li class="dropdown-item"><i class="icon-envelope mr-2"></i> Inbox</li>
                            <li class="dropdown-divider"></li>
                            <li class="dropdown-item"><i class="icon-wallet mr-2"></i> Account</li>
                            <li class="dropdown-divider"></li>
                            <li class="dropdown-item"><i class="icon-settings mr-2"></i> Setting</li>
                            <li class="dropdown-divider"></li>
                            <li class="dropdown-item"><i class="icon-power mr-2"></i> Logout</li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </header>
        <!--End topbar header-->
        <div class="clearfix"></div>
        <div class="content-wrapper">
            <div class="container-fluid">