<?php
session_start();

if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_status'])) {
    header('Location: /fuo_pg/lecturer/');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content="FUO Lecturer Portal"/>
    <meta name="author" content="Abdulhammed Ridwan Adio"/>

    <title>Welcome: Fountain University, Osogbo</title>

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
                <img src="../assets/images/logo/logo.jpg" style="height: 100px;">
            </div>
          <div class="card-title text-uppercase text-center py-3">Change Default Password</div>
            <form method="POST" action="../app/function/lecturer_actions.php" id="lecturerLogin">
              <div class="form-group">
               <div class="position-relative has-icon-right">
                  <label for="oldPassword" class="sr-only">Old Password</label>
                  <input type="text" name="oldPassword" id="oldPassword" class="form-control form-control-rounded" placeholder="Old Password" required>
                  <div class="form-control-position">
                      <i class="icon-user"></i>
                  </div>
               </div>
              </div>
            <div class="form-group">
                <div class="position-relative has-icon-right">
                    <label for="newPassword" class="sr-only">New Password</label>
                    <input type="password" name="newPassword" id="newPassword" class="form-control form-control-rounded" placeholder="New Password" required>
                    <div class="form-control-position">
                        <i class="icon-lock"></i>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="position-relative has-icon-right">
                    <label for="confirmPassword" class="sr-only">Confirm Password</label>
                    <input type="password" name="confirmPassword" id="confirmPassword" class="form-control form-control-rounded" placeholder="Confirm Password" required>
                    <div class="form-control-position">
                        <i class="icon-lock"></i>
                    </div>
                </div>
            </div>
            <div class="form-row mr-0 ml-0">
             <div class="form-group col-6">
               <div class="demo-checkbox">
                <input type="checkbox" id="showPassword" class="filled-in chk-col-primary"/>
                <label for="showPassword">Show password</label>
              </div>
             </div>
            </div>
            <input type="hidden" name="passwordChange" value="<?= uniqid(); ?>">
             <button type="submit" id="loginBtn" class="btn btn-primary shadow-primary btn-round btn-block waves-effect waves-light">Submit</button>
              
             </form>
           </div>
          </div>
         </div>


         <button id="noticeModal" class="btn btn-primary m-1 d-none" data-toggle="modal" data-target="#fullprimarymodal">Primary</button>
                <div class="modal fade" id="fullprimarymodal">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white"><i class="fa fa-star"></i> Notification</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <h4>Fountain University, Osogbo</h4>
                        <p>Welcome to Fountain University - Lecturer Portal </p>
                        <p>Login to the lecturer portal with your credentials.</p>

                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                      </div>
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

        $('#lecturerLogin').submit(function (e) {
            e.preventDefault();

            // Get form data
            var formData    = $(this).serialize();
            $login_btn      = $('#loginBtn');
            $login_btn.addClass('disabled');
            $login_btn.html('<div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>');

            // Perform AJAX validation
            $.ajax({
                type: 'POST',
                url: '../app/function/lecturer_actions.php', 
                data: formData,
                success: function (response) {
                    console.log(response);
                    if (response.status == 'success') {
                       
                       document.getElementById("lecturerLogin").reset();
                       $login_btn.html('Submit');
                       
                       Lobibox.notify('success', {
                           msg: response.message,
                           class: 'lobibox-notify-success',
                           title: "Success !",
                           position: 'top right',
                           icon: false,
                           delay: 15000,
                           theme: 'minimal',
                       });

                       var nextLogin = "index";
                       window.location = nextLogin;
                      
                   } else {
                        
                        $login_btn.html('Submit');
                        $login_btn.removeClass('disabled');

                        Lobibox.notify('error', {
                            msg: response.message,
                            class: 'lobibox-notify-error',
                            title: 'Error!',
                            icon: false,
                            position: 'top right',
                            theme: 'minimal'
                        });
                        
                    }
                }
            });
        });

        $('#showPassword').on('click', function() {
        
            var x = document.getElementById("newPassword");
            var y = document.getElementById("confirmPassword");
            if (x.type === "password" && y.type === "password") {
                x.type = "text";
                y.type = "text";
            } else {
                x.type = "password";
                y.type = "password";
            }
        });
    });
</script>
</body>
</html>
