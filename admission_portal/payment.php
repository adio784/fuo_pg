<?php
  session_start();
  ob_start();
    // include head start here
    if( isset($_SESSION['user_id']) && isset($_SESSION['admStatus']) ){

      require_once 'includes/head.php';
      require_once '../core/autoload.php';
      require_once '../core/Database.php';
      require_once '../common/CRUD.php';
      require_once '../common/Payment.php';
      
      $database   = new Database();
      $Crud       = new CRUD($database);
      $Payment    = new PAYMENT();
      $uid        = $_SESSION['user_id'];

      $stmt = $database->getConnection()->prepare('SELECT program_id, program_fee, programme_title FROM `programme` ORDER BY programme_title ASC');
      $stmt->execute();

      $User = $Crud->read('application', 'id', $uid);

      if ($User->application_status == 'registered') { header('Location: admission_home'); }


    } else {
      header('Location: /fuo_pg/admission_portal/index');
    }

    if( isset($_GET['programme_id']) )
    {
        $programID  = $_GET['programme_id'];
        ob_clean();
        if( $programID != "" )
        {    
            // Get data .........................................................................
            $stmt = $database->getConnection()->prepare('SELECT id, program_fee FROM `programme` WHERE program_id=? ');
            $stmt->execute([$programID]);
            $activePayment = $stmt->fetch(PDO::FETCH_OBJ);
            // .....................................................................................................

            $response['status'] = 'success';
            $response['message']= "Successfully fetch data";
            $response['amount'] = $activePayment->program_fee;
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
    
?>
<!-- Start wrapper-->
 <div id="wrapper">
  <br><br><br>
    <div class="container">
		  <div class="row">
			<div class="col-lg-10 mx-auto">
        <!-- Payment section start -->
			   <div class="card">
			     <div class="card-body">
				   
           <?php
           if( $User->application_status == 'pre_register'){?>
           <div class="card-title text-center text-uppercase">Payment Page</div>
           <h4 class="card-title"><?php echo $User->last_name .' '. $User->first_name .' '. $User->middle_name; ?> </h4>
           <h4 class="card-title"><?php echo $User->application_id; ?> </h4>
				   <hr>
				  <form id="paymentForm" method="POST" action="../app/function/application_process.php">
          <div class="form-group">
              <label for="input-3">Email Address</label>
              <input type="text" class="form-control" id="email_address" name="email_address" value="<?php echo $User->email; ?>" required readonly>
					 </div>
					 <div class="form-group">
              <label for="input-1">Programme</label>
              <select name="programme" class="form-control" id="programme" required>
                <option value="" selected>-- Select Programme -- </option>
                <?php 
                  while($programs = $stmt->fetch(PDO::FETCH_OBJ)){?>
                  <option value="<?php echo $programs->program_id; ?>"><?php echo $programs->programme_title; ?></option>
                <?php }
                ?>
              </select>
					 </div>
					 <!-- <div class="form-group">
              <label for="input-1" id="selectCourse">Course</label>
              <select name="course" class="form-control" id="#" required>
                <option value="AF">Computer Science</option>
              </select>
					 </div> -->
					 <div class="form-group">
              <label for="input-3" id="selectAmount">Amount</label>
              <input type="hidden" name="applicationForm" value="<?php echo uniqid(); ?>">
              <input type="text" class="form-control" id="pay_amount" name="pay_amount" placeholder="Amount to pay" required readonly>
					 </div>
           <?php } ?>
           
            <div class="form-group">

              <?php 
              if( $User->application_status == 'pre_register'){ ?>
                <button type="submit" id="makePaymentBtn" name="processApplicationPayment" class="btn btn-primary shadow-primary px-5 btn-block"><i class="icon-lock"></i> Make Payment</button>
                <?php }else{?>
                  <div class="card-title text-center text-uppercase">Complete Online Application</div>
				          <hr>
                  <div class="card-body">
                    <h4>Congratulations !</h4>
                    <p>You have taken the very first step towards the application process, now start your application ...</p>
                  </div>
                  <a href="start_application" class="btn btn-primary shadow-primary px-5 btn-block"><i class="icon-lock"></i> Start Application </a>
                  
                <?php } ?>
                <button class="btn btn-info d-none" type="button" onclick="anim2_noti()">SHOW ME</button> <br>
                <a href="logout" class="btn btn-danger shadow-danger px-5 btn-block"><i class="icon-lock"></i> Logout </a>
            </div>
					</form>
				 </div>
			   </div>
         <!-- Payment section end -->
			</div>
		  </div>
      <!--End Row-->

    </div>
   <!--End content-wrapper-->
   <!--Start Back To Top Button-->
    <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
    <!--End Back To Top Button-->
   
  </div><!--End wrapper-->


  <!-- Bootstrap core JavaScript-->
  <?php require_once 'includes/foot.php'; ?>

  <script>
    $(document).ready(function () {
      var authID = "<?php if(isset($_SESSION['user_id'])){ echo $_SESSION['user_id']; }else { echo ''; } ?>";
      var pay_success = "<?= (isset($_GET['pay_success'])) ? $_GET['pay_success'] : ""; ?>"; 
      var pay_error = "<?= (isset($_GET['error'])) ? $_GET['error'] : ""; ?>"; 
      var appStatus = "<?php $User->application_status; ?>"; 

      var msg = 'Select your choice of programme and course to generate payment, proceed with payment to start your application';

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
        $("#makePaymentBtn").html('<div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>');
        $("#makePaymentBtn").prop("disabled", true);
      });



    })
  </script>

  <script>
      $('#programme').on('change', function(){
        var programId = $(this).val();
        $("#selectAmount").html($('<div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>'));
        $.ajax({
          method: 'GET',
          url: "payment.php?programme_id="+programId,
          success:function(result)
          {
            if(result != ""){
                
                $('#selectAmount').html("Amount");
                $('#pay_amount').val(result.amount);

            }else{
              $("#selectAmount").html("Unable to fetch amount");
            }
          }
        });
      });
  </script>
	
</body>
</html>
