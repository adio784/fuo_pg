<?php

session_start();
if( isset($_SESSION['user_id']) && isset($_SESSION['admStatus']) ){
    // user_role
    require_once '../core/autoload.php';
    require_once '../core/Database.php';
    require_once '../common/CRUD.php';

    $database   = new Database();
    $Crud       = new CRUD($database);
    $db         = $database->getConnection();
    $uid        = $_SESSION['user_id'];

    $csession           = $Crud->read('access', 'setting', 'current_session');
    $current_session    = $csession->value;

    $Admitted   = $Crud->countByTwo('application', 'application_status', 'admitted', 'application_session', $current_session);
    $NotAdmitted= $Crud->countByTwo('application', 'application_status', 'registered', 'application_session', $current_session);
    $TApp       = $Crud->countByOne('application', 'application_session', $current_session);
    $Allapp     = $Crud->countByOne('application', 'application_status', 'admitted');

    //  Get recent applicants .................................................................................................
    $rquery     = $db->prepare("SELECT  
                                application.first_name, application.last_name, application.middle_name,
                                application.created_at, application.application_id, user_credentials.passport,
                                application.email, programme.programme_title, program_course.course_name
                                FROM application
                                INNER JOIN programme
                                ON application.program = programme.program_id
                                INNER JOIN program_course
                                ON programme.program_id = program_course.program_id
                                INNER JOIN user_credentials
                                ON application.application_id = user_credentials.application_id
                                -- WHERE application.application_session = ? 
                                AND application.application_status = ?
                                GROUP BY application.application_id
                                ORDER BY application.id DESC LIMIT 5");
    $rquery->execute(['registered']);
    $rcount     = $rquery->rowCount();


} else {
header('Location: /fuo_pg/admission_portal/index');
} 


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
  <meta name="description" content=""/>
  <meta name="author" content=""/>
  <title>Welcome: FUO - Admission Office</title>
  <!--favicon-->
  <link rel="icon" href="../assets/images/fuo_logo.jpeg" type="image/x-icon">
  <!-- Vector CSS -->
  <link href="../assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet"/>
  <!-- simplebar CSS-->
  <link href="../assets/plugins/simplebar/css/simplebar.css" rel="stylesheet"/>
  <!-- Bootstrap core CSS-->
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet"/>
  <!-- animate CSS-->
  <link href="../assets/css/animate.css" rel="stylesheet" type="text/css"/>
  <!-- Icons CSS-->
  <link href="../assets/css/icons.css" rel="stylesheet" type="text/css"/>
  <!-- Sidebar CSS-->
  <link href="../assets/css/sidebar-menu.css" rel="stylesheet"/>
  <!-- Custom Style-->
  <link href="../assets/css/app-style.css" rel="stylesheet"/>
  
  <!-- Style for overlay and preloader -->
  <style>
    
    /* Overlay Styles */
    .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        z-index: 9999;
    }

    /* Preloader Styles */
    .preloader {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 10000;
    }

    .loader {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

  </style>
  <!-- Overlay preloader -->
  
</head>

<body>

<!-- Start wrapper-->
 <div id="wrapper">
 
 <!-- Overlay -->
<div id="overlay" class="overlay"></div>

<!-- Preloader -->
<div id="preloader" class="preloader">
    <div class="loader"></div>
</div>

  <!--Start sidebar-wrapper-->
   <div id="sidebar-wrapper" data-simplebar="" data-simplebar-auto-hide="true" class="bg-body border-right border-light shadow-none">
     <div class="brand-logo">
      <a href="index.html">
       <img src="../assets/images/fuo_logo.jpeg" class="logo-icon" alt="logo icon">
       <h5 class="logo-text">Admission Office</h5>
     </a>
	 </div>
	 <ul class="sidebar-menu do-nicescrol">
      <li class="sidebar-header">MAIN NAVIGATION</li>
      <li>
        <a href="/admission_home" class="waves-effect">
          <i class="icon-home"></i> <span>Dashboard</span> <i class="fa fa-angle-left pull-right"></i>
        </a>
      </li>
      <li>
        <a href="#" class="waves-effect">
          <i class="icon-briefcase"></i>
          <span>New Applications</span> <i class="fa fa-angle-left pull-right"></i>
        </a>
      </li>
      <li>
        <a href="#" class="waves-effect">
          <i class="icon-calendar"></i> <span>Admitted</span>
          <small class="badge float-right badge-info">New</small>
        </a>
      </li>
	  
      <li>
        <a href="#" class="waves-effect">
          <i class="icon-layers"></i>
          <span>Not Admitted</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
      </li>
     
	
      <li class="sidebar-header">OTHER link</li>
      <li><a href="javaScript:void();" class="waves-effect"><i class="fa fa-circle-o text-red"></i> <span>Profile</span></a></li>
      <li><a href="javaScript:void();" class="waves-effect"><i class="fa fa-circle-o text-yellow"></i> <span>Logout</span></a></li>
    </ul>
	 
   </div>
   <!--End sidebar-wrapper-->

    <!--Start topbar header-->
    <header class="topbar-nav">
        <nav class="navbar navbar-expand fixed-top bg-body border-bottom border-light shadow-none">
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
                        <h6 class="mt-2 user-title">Katrina Mccoy</h6>
                        <p class="user-subtitle">mccoy@example.com</p>
                        </div>
                    </div>
                    </a>
                    </li>
                    <li class="dropdown-divider"></li>
                    <li class="dropdown-divider"></li>
                    <li class="dropdown-item"><i class="icon-power mr-2"></i> Logout</li>
                </ul>
                </li>
            </ul>
        </nav>
    </header>
    <!--End topbar header-->