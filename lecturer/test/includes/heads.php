<?php
session_start();
ob_start();

// Required includes
require_once '../core/autoload.php';
require_once '../core/Database.php';
require_once '../common/CRUD.php';
include 'includes/lecturer_data.php';

// Helper function
function formatDate($dt)
{
    return date('D-M g, Y', strtotime($dt));
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
    <title>Welcome:| FUO-PG - Welcome to Fountain University Student Portal - PG</title>

    <!--favicon-->
    <link rel="icon" href="../assets/images/fuo_logo.jpeg" type="image/x-icon">
    <!-- jquery steps CSS-->
    <link rel="stylesheet" type="text/css" href="../assets/plugins/jquery.steps/css/jquery.steps.css">
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
    <!-- Notification -->
    <link rel="stylesheet" href="../assets/plugins/notifications/css/lobibox.min.css" />
    <!-- Data Tables -->
    <link href="../assets/plugins/bootstrap-datatable/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css">
    <link href="../assets/plugins/bootstrap-datatable/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css">

    <!-- Style for overlay and preloader -->
    <style>
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
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <script src="../assets/js/jquery.min.js"></script>
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
        <div id="sidebar-wrapper" data-simplebar="" data-simplebar-auto-hide="true" class="border-right border-light shadow-none">
            <br>
            <div class="brand-logo">
                <a href="student_home">
                    <img src="../assets/images/logo/logo.jpg" style="height: 50px;" class="logo-icon" alt="logo icon">
                    <h5 class="logo-text" style="font-size: 22px; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-weight:bolder">PG Portal</h5>
                </a>
            </div>
            <ul class="sidebar-menu do-nicescrol">
                <br>
                <li class="sidebar-header">MAIN NAVIGATION</li>
                <li><a href="student_home" class="waves-effect"><i class="icon-home"></i> <span>Dashboard</span></a></li>
                <li><a href="pre_payments" class="waves-effect"><i class="icon-wallet"></i> <span>Payments</span></a></li>
                <li><a href="payment_history" class="waves-effect"><i class="icon-list"></i> <span>Payments History</span></a></li>
                <li>
                    <a href="#" class="waves-effect">
                        <i class="icon-calendar"></i> <span>Courses</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="course_registration"><i class="fa fa-circle-o"></i> New Registration</a></li>
                        <li><a href="registered_courses"><i class="fa fa-circle-o"></i> Registered</a></li>
                        <li><a href="previous_course_registration"><i class="fa fa-circle-o"></i> Past Registration</a></li>
                    </ul>
                </li>
                <li><a href="students_result" class="waves-effect"><i class="icon-graduation"></i> <span>Result</span></a></li>
                <li><a href="student_transcript" class="waves-effect"><i class="icon-menu"></i> <span>Transcript</span></a></li>
                <li class="sidebar-header">OTHER LINKS</li>
                <li><a href="logout" class="waves-effect"><i class="fa fa-circle-o text-yellow"></i> <span>Logout</span></a></li>
            </ul>
        </div>
        <!--End sidebar-wrapper-->

        <!--Start topbar header-->
        <header class="topbar-nav">
            <nav class="navbar navbar-expand fixed-top bg-body border-bottom border-light shadow-none">
                <ul class="navbar-nav mr-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link toggle-menu" href="javascript:void();"><i class="icon-menu menu-icon"></i></a>
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
                            <i class="icon-bell"></i><span class="badge badge-danger badge-up"><?= '10' ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    You have 10 Notifications
                                    <span class="badge badge-primary">10</span>
                                </li>
                                <li class="list-group-item"><a href="notifications">See All Notifications</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown" href="#">
                            <span class="user-profile">
                                <img src="<?= $passport ?>" class="img-circle" alt="user avatar">
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li class="dropdown-item"><a href="logout"><i class="icon-power mr-2"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </header>
        <!--End topbar header-->
