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
<?php
if ( $User == false ) {
  echo "<h4 class='m-4'> You need to complete your application </h4> <a href='logout' class='btn btn-primary m-4'> Logout </a>"; } else {?>
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
            <h6 class="mt-2 user-title"><?php echo $uname; ?></h6>
            <p class="user-subtitle"><?php echo $User->email; ?></p>
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

      <!--Start Dashboard Content-->
      <div class="row">
         <div class="col-12 col-lg-7 col-xl-8">
           <div class="card">
		     <div class="card-header">Welcome Back ! <?php echo $fname; ?> (<?php echo $User->application_id ?>)
				 
				</div>
              <div class="card-body">
                 <div class="row">
                   <div class="col-lg-7 col-xl-8 border-right">
                        <div class="row">
                            <div class="col-lg-5 col-xl-5">
                                <div class="card-body">
                                    <img src="admissionUploads/<?php echo $User->passport ?>" alt="" class="card-img-top w-100">
                                </div>
                            </div>
                            <div class="col-lg-7 col-xl-7">
                                <div class="card-body">
                                    <div class="form-group">
                                        <h6> Fullname: </h6>
                                        <p><?php echo $fname ; ?></p>
                                        <p> <? //$_SERVER['HTTP_HOST']."/fuo_pg/admission_portal/app/function/application_process.php?accept=1&&"; ?> </p>
                                    </div>

                                    <div class="form-group">
                                        <h6> Email: </h6>
                                        <p><?php echo $User->email; ?></p>
                                    </div>

                                    <div class="form-group">
                                        <h6> Phone: </h6>
                                        <p><?php echo $User->phone_number; ?></p>
                                    </div>

                                    <div class="form-group mt-0">
                                        <h6> Gender: </h6>
                                        <p><?php echo $User->gender; ?></p>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                   </div>
                   <div class="col-lg-5 col-xl-4">

                     <p>Application Fee <span class="float-right badge badge-success">Paid</span></p>
                     <div class="progress" style="height: 7px;">
                          <div class="progress-bar bg-primary progress-bar-striped" role="progressbar" style="width: 100%"></div>
                      </div>

                      <hr>
                      <p class="mt-3">Application Form <span class="float-right badge badge-info">Completed</span></p>
                      <div class="progress" style="height: 7px;">
                          <div class="progress-bar bg-info progress-bar-striped" role="progressbar" style="width: 100%"></div>
                        </div>

                      <!-- <p class="mt-3"><i class="flag-icon flag-icon-ca mr-1"></i> Canada <span class="float-right">65%</span></p>
                      <div class="progress" style="height: 7px;">
                          <div class="progress-bar bg-danger progress-bar-striped" role="progressbar" style="width: 65%"></div>
                      </div>

                      <p class="mt-3"><i class="flag-icon flag-icon-gb mr-1"></i> England <span class="float-right">60%</span></p>
                      <div class="progress" style="height: 7px;">
                          <div class="progress-bar bg-success progress-bar-striped" role="progressbar" style="width: 60%"></div>
                        </div> -->

                        <hr>
                      <p class="mt-3"> Admission Status <span class="float-right badge badge-warning">Pending</span></p>
                      <div class="progress" style="height: 7px;">
                          <div class="progress-bar bg-warning progress-bar-striped" role="progressbar" style="width: 55%"></div>
                        </div>

                      
                        <!-- Display acceptance button when student got admitted -->
                        <?php if ($Acceptancepay != "success" && $User->application_status == "admitted" ){ ?>
                        <hr>
                        <form action="../app/function/application_process.php" method="POST" id="paymentForm">
                          <input type="hidden" name="acceptance_fee" value="60000">
                          <input type="hidden" name="email_address" value="<?= $User->email; ?>">
                          <input type="hidden" name="acceptanceForm" value="<?= uniqid(); ?>">
                          <input type="hidden" name="purpose" value="Acceptance fee">
                          <button type="submit" id="paymentBtn" class="btn btn-primary"> Proceed to pay acceptance </button>
                        </form>

                        <?php } elseif ($User->application_status == "admitted" && $Acceptancepay == "success"){ ?>
                          <hr>
                          <button type="submit" id="paymentBtn" class="btn btn-info"> Print Acceptance Letter </button>
                        <?php } else { echo ""; } ?>
                        <!-- Acceptance button ends here -->
                      
                        
                        

                      

                   </div>
                 </div>
              </div>
            </div>
         </div>

         <!-- Right sided admittion status -->
         <div class="col-12 col-lg-5 col-xl-4">
         <?php if ($User->application_status == "admitted"){ ?>
          <div class="card">
            <div class="card-body">
              <div class="media">
              <div class="media-body text-left">
                <h4 class="text-primary">Admitted</h4>
                <span> Admission Status</span>
              </div>
			        <div class="align-self-center w-circle-icon rounded gradient-violet">
                 <a href="pay_acceptance" id=""></a><i class="icon-like text-white"></i> </a>
              </div>
            </div>
            </div>
          </div>
          <?php } else {?>
          <div class="card">
            <div class="card-body">
              <div class="media">
                <div class="media-body text-left">
                  <h4 class="text-danger">Pending ... </h4>
                  <span>Admission Status</span>
                </div>
               <div class="align-self-center w-circle-icon rounded gradient-ibiza">
                <i class="icon-speech text-white"></i></div>
            </div>
            </div>
          </div>
          <?php } ?>
          <!-- Right sided admittion status -->

          <!-- Programme displayed here -->
          <div class="card">
            <div class="card-body">
              <div class="media">
                <div class="media-body text-left">
                  <h4 class="text-dark"><?php echo $Prog->programme_title; ?></h4>
                  <span>Programme</span>
                </div>
                <div class="align-self-center w-circle-icon rounded gradient-royal">
                  <i class="icon-home text-white"></i>
                </div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-body">
              <div class="media">
                <div class="media-body text-left">
                  <h4 class="text-dark"><?php echo $UProgram->course_name; ?></h4>
                  <span>Course</span>
                </div>
                <div class="align-self-center w-circle-icon rounded gradient-royal">
                  <i class="icon-pencil text-white"></i>
                </div>
              </div>
            </div>
          </div>
          <!-- Programme displayed ends here -->

         </div>
         <!-- Right column ends here --------------------- -->

      </div><!--End Row-->


      <!-- Transaction histories ends here ..................................... -->
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
		      <div class="card-header border-0">
                Transaction Histories 
                </div>
               <div class="table-responsive">
                 <table class="table align-items-center table-flush">
                  <thead>
                    
                   <tr>
                     <th>SN</th>
                     <th>Description</th>
                     <th>Transaction Ref</th>
                     <th>Amount</th>
                     <th>Status</th>                     
                     <th>Date</th>
                     <th>Action</th>
                   </tr>
                   </thead>
                  
                   <tbody>
                    <?php 
                   
                    if ($Receipts !== false ) {
                        $i =0;
                        foreach ($Receipts as $Receipt) { $i++; ?>

                      <tr>
                        <td><?php echo $i ?></td>
                        <td><?php echo $Receipt->description ?></td>
                        <td> <?php echo $Receipt->transactionId ?> </td>
                        <td>₦ <?php echo number_format($Receipt->paid_amount, 2) ?> </td>
                            <!-- <img src="../assets/images/products/05.png" class="product-img" alt="product img"> -->
                        <td><span class="badge text-white shadow <?php if ($Receipt->payment_status != ""){ echo 'shadow-danger gradient-bloody'; }else { echo 'shadow-success gradient-quepal';}?>"><?php echo $Receipt->payment_status ?></span></td>
                        <td><?php echo date('F g, Y', strtotime($Receipt->created_at) ) ?></td>
                        <td>
                            <?php //if ($Receipt->payment_status != ""){?> 
                                <form action="receipt" method='POST'>
                                  <input type="hidden" name="ref" value="<?php echo $Receipt->transactionId ?>">
                                  <button type="submit" class="btn btn-info shadow-info waves-effect waves-light m-1"><i class="fa fa-print font-lg"></i> Print</button> <?php //} else { ?>
                                </form>
                                <!-- <a href="http://localhost/fuo_pg/app/function/application_process.php?xpayment_callback={$Receipt->transactionId}" class="btn btn-info shadow-primary waves-effect waves-light m-1"><i class="fa fa-like font-lg"></i> Requery</a> <?php //} ?> -->
                            <!-- <div class="progress shadow" style="height: 6px;">
                                <div class="progress-bar gradient-bloody" role="progressbar" style="width: 40%"></div>
                            </div> -->
                        </td>
                      </tr>

                      <?php  }
                    } ?>
                    </tbody>
                    <!-- gradient-quepal
                    gradient-blooker
                    gradient-bloody -->
                 </table>
               </div>
          </div>
        </div>
      </div><!--End Row-->
      <!-- Transaction histories ends here -->
  
	  
       <!--End Dashboard Content-->

    </div>
    <!-- End container-fluid-->
    
    </div><!--End content-wrapper-->
   <!--Start Back To Top Button-->
    <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
    <!--End Back To Top Button-->
	
	<!--Start footer-->
	<footer class="footer">
      <div class="container">
        <div class="text-center">
          Copyright © 2023 FUO, PG Portal
        </div>
      </div>
    </footer>
	<!--End footer-->
   
  </div><!--End wrapper-->
<?php } ?>

  <!-- Bootstrap core JavaScript-->
  <?php require_once 'includes/foot.php'; ?>
</body>

<script>
    $(document).ready(function () {

      var authID = "<?php if(isset($_SESSION['user_id'])){ echo $_SESSION['user_id']; }else { echo ''; } ?>";
      var pay_success = "<?php if(isset($_GET['pay_success']) != "" ) { echo $_GET['pay_success']; }else{ echo ""; } ?>"; 
      var pay_error = "<?php if(isset($_GET['error']) != "" ) { echo $_GET['error']; }else { echo ""; } ?>"; 
      var appStatus = "<?php $User->application_status; ?>"; 

      var msg = 'Welcome to Fountain University, Osogbo | School of Post Graduate Studies';

      if ( authID && pay_success == "" && pay_error == "") {

          Lobibox.notify('success', {
              msg: msg,
              class: 'lobibox-notify-success',
              title: "Welcome back! ",
              position: 'top right',
              icon: false,
              sound: 'sound2.mp3',
              delay: 15000,
              theme: 'minimal',
          });

      } if (pay_success != "") {

        Lobibox.notify('info', {
            msg: pay_success,
            class: 'lobibox-notify-success',
            title: "Payment ! ",
            position: 'top right',
            icon: false,
            theme: 'minimal',
        });

      } if (pay_error != "") {

        Lobibox.notify('warning', {
            msg: pay_error,
            class: 'lobibox-notify-success',
            title: "Payment ! ",
            position: 'top right',
            icon: false,
            theme: 'minimal',
        });

      }



      $("#paymentForm").submit(function() {
        $("#paymentBtn").html('<div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>');
        $("#paymentBtn").prop("disabled", true);
      });



    })
  </script>
</html>
