<?php
session_start();
if( isset($_SESSION['user_id']) && isset($_SESSION['admStatus']) ){

    require_once 'includes/head.php';
    require_once '../core/autoload.php';
    require_once '../core/Database.php';
    require_once '../common/CRUD.php';
    require_once 'includes/admission_check.php';

  } else {
    header('Location: /fuo_pg/admission_portal/index');
  } 
?>

<!-- Start wrapper-->
 <div id="wrapper">
 

<!--Start topbar header-->
<header class="topbar-nav">
 <nav class="navbar navbar-expand fixed-top bg-white">
  <ul class="navbar-nav mr-auto align-items-center">

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
	  <i class="icon-bell"></i><span class="badge badge-primary badge-up">0</span></a>
      <div class="dropdown-menu dropdown-menu-right">
        <ul class="list-group list-group-flush">
          <!-- <li class="list-group-item d-flex justify-content-between align-items-center">
          You have 10 Notifications
          <span class="badge badge-primary">0</span>
          </li> -->
          <!-- <li class="list-group-item">
          <a href="javaScript:void();">
           <div class="media">
             <i class="icon-people fa-2x mr-3 text-info"></i>
            <div class="media-body">
            <h6 class="mt-0 msg-title">New Registered Users</h6>
            <p class="msg-info">Lorem ipsum dolor sit amet...</p>
            </div>
          </div>
          </a>
          </li> -->
          <!-- <li class="list-group-item">
          <a href="javaScript:void();">
           <div class="media">
             <i class="icon-cup fa-2x mr-3 text-warning"></i>
            <div class="media-body">
            <h6 class="mt-0 msg-title">New Received Orders</h6>
            <p class="msg-info">Lorem ipsum dolor sit amet...</p>
            </div>
          </div>
          </a>
          </li> -->
          <!-- <li class="list-group-item">
          <a href="javaScript:void();">
           <div class="media">
             <i class="icon-bell fa-2x mr-3 text-danger"></i>
            <div class="media-body">
            <h6 class="mt-0 msg-title">New Updates</h6>
            <p class="msg-info">Lorem ipsum dolor sit amet...</p>
            </div>
          </div>
          </a>
          </li> -->
          <!-- <li class="list-group-item"><a href="javaScript:void();">See All Notifications</a></li> -->
        </ul>
      </div>
    </li>
   
    <li class="nav-item">
      <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown" href="#">
        <span class="user-profile"><img src="admissionUploads/<?php echo $User->passport ?>" class="img-circle" alt="user avatar"></span>
      </a>
      <ul class="dropdown-menu dropdown-menu-right">
       <li class="dropdown-item user-details">
        <a href="javaScript:void();">
           <div class="media">
             <div class="avatar"><img class="align-self-start mr-3" src="admissionUploads/<?php echo $User->passport ?>" alt="user avatar"></div>
            <div class="media-body">
            <h6 class="mt-2 user-title"><?php echo $uname ?></h6>
            <p class="user-subtitle"><?php echo $User->email ?></p>
            </div>
           </div>
          </a>
        </li>
      
        <li class="dropdown-divider"></li>
        <a href="admission_home" class="dropdown-item"><i class="icon-home mr-2"></i> Home </a>
        <li class="dropdown-divider"></li>
        <a href="my_application" class="dropdown-item"><i class="icon-wallet mr-2"></i> My Application </a>
        <li class="dropdown-divider"></li>
        <a href="logout" class="dropdown-item"><i class="icon-power mr-2"></i> Logout </a>
      </ul>
    </li>
  </ul>
</nav>
</header>
<!--End topbar header-->

<div class="clearfix"></div>
	
<div class="content-wrapper" style="margin-left: 0px;">
    <div class="container-fluid">
      <!-- Breadcrumb-->
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
		    <h4 class="page-title">My Application</h4>
		    <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javaScript:void();">Home</a></li>
            <li class="breadcrumb-item"><a href="javaScript:void();">Admission</a></li>
            <li class="breadcrumb-item active" aria-current="page">My Application</li>
         </ol>
	   </div>
     </div>
    <!-- End Breadcrumb-->
      <div class="row">
        <div class="col-lg-4">
           <div class="profile-card-3">
            <div class="card">
			 <div class="user-fullimage">
			   <img src="admissionUploads/<?php echo $User->passport ?>" alt="user avatar" class="card-img-top">
			    <div class="details">
			      <h5 class="mb-1 text-white ml-3"><?php echo $uname ?></h5>
				  <h6 class="text-white ml-3"><?php echo $User->email ?></h6>
				  <h6 class="text-white ml-3"><?php echo $User->phone_number ?></h6>
				 </div>
			  </div>
            <div class="card-body text-center">
             <p><?php echo $User->address ?></p>
				   <div class="row">
				    <div class="col p-2">
					 <h6 class="mb-0 line-height-5">Gender</h6>
					 <small class="mb-0 font-weight-bold"><?php echo $User->gender ?></small>
					</div>
					<div class="col p-2">
					 <h6 class="mb-0 line-height-5">Religion</h6>
					 <small class="mb-0 font-weight-bold"><?php echo $User->religion ?></small>
					</div>
					<div class="col p-2">
					 <h6 class="mb-0 line-height-5">Birth</h6>
					 <small class="mb-0 font-weight-bold"><?php echo date('g M, Y', strtotime($User->date_of_birth) ) ?></small>
					</div>
				   </div>

				  <hr>
				  <a href="javascript:void():" class="btn btn-primary shadow-primary btn-sm btn-round waves-effect waves-light m-1"><?php echo $User->state ?></a>
				  <a href="javascript:void():" class="btn btn-outline-primary btn-sm btn-round waves-effect waves-light m-1"><?php echo $User->country ?></a>
                </div>
             </div>
			</div>
        </div>
        <div class="col-lg-8">
           <div class="card">
            <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-primary top-icon nav-justified">
                <li class="nav-item">
                    <a href="javascript:void();" data-target="#profile" data-toggle="pill" class="nav-link active"><i class="icon-user"></i> <span class="hidden-xs">MY APPLICATIONS</span></a>
                </li>
            </ul>
            <div class="tab-content p-3">
                <div class="tab-pane active" id="profile">
                    <h5 class="mb-3">Programme </h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Undergraduate Course</h6>
                            <p>
                                <?php echo $User->course_studied ?>
                            </p>
                            <h6>Institution Attended </h6>
                            <p>
                                <?php echo $User->institute_attended ?>
                            </p>
                            <h6>Class of Degree</h6>
                            <p>
                                <?php echo $User->class_degree ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6>Origin</h6>
                            <a href="javascript:void();" class="badge badge-dark badge-pill p-2">Country: <?php echo $User->country_of_origin ?></a>
                            <a href="javascript:void();" class="badge badge-dark badge-pill p-2">State: <?php echo $User->state_of_origin ?></a>
                            <a href="javascript:void();" class="badge badge-dark badge-pill p-2">LGA: <?php echo $User->local_government ?></a>
                            <hr>
                            <h6>Residence</h6>
                            <span class="badge badge-primary p-2"><i class="fa fa-user"></i> Country: <?php echo $User->country ?></span>
                            <span class="badge badge-success p-2"><i class="fa fa-cog"></i> State: <?php echo $User->state ?></span>
                            <span class="badge badge-info p-2"><i class="fa fa-eye"></i> City: <?php echo $User->lga ?></span>
                        </div>
                        <div class="col-md-12">
                            <h5 class="mt-2 mb-3"><span class="fa fa-clock-o ion-clock float-right"></span> Application</h5>
                            <table class="table table-hover table-striped">
                                <tbody>                                    
                                    <tr>
                                        <td>
                                            <strong>Programme: </strong> <?php echo $Prog->programme_title ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Course</strong> <?php echo $UProgram->course_name ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--/row-->
                </div>
            </div>
        </div>
      </div>
      </div>
        
    </div>

    </div>
    <!-- End container-fluid-->
   </div><!--End content-wrapper-->
   <!--Start Back To Top Button-->
    <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
    <!--End Back To Top Button-->
	
	<!--Start footer-->
	<footer class="footer" style="margin: left 0;">
      <div class="container">
        <div class="text-center">
          Copyright © 2023 FUO, PG Portal
        </div>
      </div>
    </footer>
	<!--End footer-->
   
  </div><!--End wrapper-->

  <!-- Bootstrap core JavaScript-->
  <script src="../assets/js/jquery.min.js"></script>
  <script src="../assets/js/popper.min.js"></script>
  <script src="../assets/js/bootstrap.min.js"></script>
	
  <!-- simplebar js -->
  <script src="../assets/plugins/simplebar/js/simplebar.js"></script>
  <!-- waves effect js -->
  <script src="../assets/js/waves.js"></script>
  <!-- sidebar-menu js -->
  <script src="../assets/js/sidebar-menu.js"></script>
  <!-- Custom scripts -->
  <script src="../assets/js/app-script.js"></script>
  
  <!-- Vector map JavaScript -->
  <script src="../assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
  <script src="../assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
  <!-- Chart js -->
  <script src="../assets/plugins/Chart.js/Chart.min.js"></script>
  <!-- Index js -->
  <script src="../assets/js/index.js"></script>
  
</body>

<!-- white-version/index.html  Wed, 31 Oct 2018 03:22:03 GMT -->
</html>
