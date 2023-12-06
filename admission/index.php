<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content="FUO Post Graduate"/>
    <meta name="author" content="Abdulhammed Ridwan Adio"/>

    <title>Welcome: FUO - Admission Office</title>

    <!--favicon-->
    <link rel="icon" href="../assets/images/fuo_logo.jpeg" type="image/x-icon">

    <link rel="stylesheet" type="text/css" href="../assets/plugins/jquery.steps/css/jquery.steps.css">
    <!-- simplebar CSS-->
    <link href="../assets/plugins/simplebar/css/simplebar.css" rel="stylesheet"/> 
    <!-- Bootstrap core CSS-->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet"/>
    <!-- animate CSS-->
    <link href="../assets/css/animate.css" rel="stylesheet" type="text/css"/>
    <!-- Icons CSS-->
    <link href="../assets/css/icons.css" rel="stylesheet" type="text/css"/>
    <!-- Custom Style-->
    <link href="../assets/css/app-style.css" rel="stylesheet"/>

    <!-- Notification -->
    <link rel="stylesheet" href="../assets/plugins/notifications/css/lobibox.min.css"/>

</head>
<body>

<div id="wrapper">
	<div class="card border-primary border-top-sm border-bottom-sm card-authentication1 mx-auto my-5 animated bounceInDown">
		<div class="card-body">
		 <div class="card-content p-2">
		 	<div class="text-center">
		 		<img src="../assets/images/fuo_logo.jpeg">
		 	</div>
		  <div class="card-title text-uppercase text-center py-3">Sign In</div>
		    <form method="POST" action="../app/function/Userauth.php" id="admissionLogin">
			  <div class="form-group">
			   <div class="position-relative has-icon-right">
				  <label for="exampleInputUsername" class="sr-only">Username</label>
				  <input type="text" name="username" id="exampleInputUsername" class="form-control form-control-rounded" placeholder="Username">
				  <div class="form-control-position">
					  <i class="icon-user"></i>
				  </div>
			   </div>
			  </div>
			  <div class="form-group">
			   <div class="position-relative has-icon-right">
				  <label for="exampleInputPassword" class="sr-only">Password</label>
				  <input type="password" name="password" id="exampleInputPassword" class="form-control form-control-rounded" placeholder="Password">
				  <div class="form-control-position">
					  <i class="icon-lock"></i>
				  </div>
			   </div>
			  </div>
			<div class="form-row mr-0 ml-0">
			 <div class="form-group col-6">
			   <div class="demo-checkbox">
                <input type="checkbox" id="user-checkbox" class="filled-in chk-col-primary" checked="" />
                <label for="user-checkbox">Remember me</label>
			  </div>
			 </div>
			 <div class="form-group col-6 text-right">
			  <a href="authentication-reset-password.html">Reset Password</a>
			 </div>
			</div>
            <input type="hidden" name="login" value="<?= uniqid(); ?>">
			 <button type="submit" id="loginBtn" class="btn btn-primary shadow-primary btn-round btn-block waves-effect waves-light">Sign In</button>
			  <div class="text-center pt-3">
				<!-- <p>or Sign in with</p>
				<a href="javascript:void()" class="btn-social btn-social-circle btn-facebook waves-effect waves-light m-1"><i class="fa fa-facebook"></i></a>
				<a href="javascript:void()" class="btn-social btn-social-circle btn-google-plus waves-effect waves-light m-1"><i class="fa fa-google-plus"></i></a>
				<a href="javascript:void()" class="btn-social btn-social-circle btn-twitter waves-effect waves-light m-1"><i class="fa fa-twitter"></i></a> -->
				<hr>
				<p class="text-muted">Do not have an account? <a href="#"> Contact ICT</a></p>
			  </div>
			 </form>
		   </div>
		  </div>
	     </div>
    
     <!--Start Back To Top Button-->
    <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
    <!--End Back To Top Button-->
	</div><!--wrapper-->
	
  <!-- Bootstrap core JavaScript-->
  <script src="../assets/js/jquery.min.js"></script>
  <script src="../assets/js/popper.min.js"></script>
  <script src="../assets/js/bootstrap.min.js"></script>

  <script src="../assets/plugins/jquery.steps/js/jquery.steps.min.js" type="text/javascript"></script>
  <script src="../assets/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
  <script src="../assets/plugins/jquery.steps/js/jquery.wizard-init.js" type="text/javascript"></script>

  <!--notification js -->
  <script src="../assets/plugins/notifications/js/lobibox.min.js"></script>
  <script src="../assets/plugins/notifications/js/notifications.min.js"></script>
  <script src="../assets/plugins/notifications/js/notification-custom-script.js"></script>

  <script>
    $(document).ready(function () {
        $('#admissionLogin').submit(function (e) {
            e.preventDefault();

            // Get form data
            var formData = $(this).serialize();
            $login_btn = $('#loginBtn');
            $login_btn.addClass('disabled');
            $login_btn.html('<div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>');

            // Perform AJAX validation
            $.ajax({
                type: 'POST',
                url: '../app/function/Userauth.php', 
                data: formData,
                success: function (response) {
                    
                    if (response.status == 'success') {
                       
                       document.getElementById("admissionLogin").reset();
                       $login_btn.html('SIGN IN');
                       
                       Lobibox.notify('success', {
                           msg: response.message,
                           class: 'lobibox-notify-success',
                           title: "Success !",
                           position: 'top right',
                           icon: 'glyphicon glyphicon-ok-sign',
                           sound: 'sound2.mp3',
                           delay: 15000,
                           theme: 'minimal',
                       });

                       var nextLogin = response.user_role;
                       window.location=nextLogin;
                      
                   } else {
						
                        $login_btn.html('SIGN IN');
                        $login_btn.removeClass('disabled');

                        Lobibox.notify('error', {
                            msg: response.message,
							class: 'lobibox-notify-error',
							title: 'Error!',
							icon: 'glyphicon glyphicon-remove-sign',
        					sound: 'sound4.mp3',
                            position: 'top right',
							theme: 'minimal'
                        });
						
                    }
                }
            });
        });
    });
</script>
</body>
</html>