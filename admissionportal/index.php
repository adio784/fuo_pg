<?php
session_start();
    // include head start here
    if( isset($_SESSION['user_id']) && isset($_SESSION['admStatus']) ){
        header('Location: /admission_portal/dashboard');
      } else {
        require_once 'includes/head.php';
      }
    
?>
    <!-- Start wrapper-->
    <div id="wrapper">
        <br><br><br>
        <div class="card-authentication2 mx-auto my-5">
            <div class="card-group">
            <div class="card mb-0 ">
                    <div class="card-body">
                        <div class="card-content p-3">
                        <?php
                            // include logo start here
                            require_once 'includes/logo.php';
                        ?>
                            <div class="card-title text-uppercase text-center py-3">Sign In</div>
                            <form method="POST" action="../app/function/admissionAuth.php" id="admLoginForm">
                                <div class="form-group">
                                    <div class="position-relative has-icon-left">
                                        <label for="exampleInputUsername" class="sr-only">Username</label>
                                        <input type="text" id="exampleInputUsername" class="form-control" placeholder="Username" name="username" required>
                                        <div class="form-control-position">
                                            <i class="icon-user"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="position-relative has-icon-left">
                                        <label for="exampleInputPassword" class="sr-only">Password</label>
                                        <input type="password" id="exampleInputPassword" class="form-control" placeholder="Password" name="password" required>
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
                                        <a href="authentication-reset-password2.html">Reset Password</a>
                                    </div>
                                </div>
                                <input type="hidden" name="login" value="<?php echo rand() ?>">
                                <button type="submit" id="loginBtn" class="btn btn-outline-primary btn-block waves-effect waves-light">Login</button>
                                <div class="text-center pt-3">
                                    <p class="text-muted">New applicant ? <a href="registration"> Register </a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card mb-0">
                    <div class="bg-signin2" ></div>
                    <div class="card-img-overlay rounded-left my-5">
                        <h2 class="text-white">Fountain University</h2>
                        <h5 class="text-white"> Welcome to School of Postgraduate Studies (SPGS), Fountain University, Osogbo.</h5>
                        
                        <p class="card-text text-white pt-3">
                            <span>To have access to the admission portal, kindly login with: </span>
                            <ol class="text-white">
                                <li> Your Application ID as your username </li>
                                <li> Your Lastname / Surname as your password</li>
                            </ol>
                            <span class="text-white"><b> NB: </b> Your application ID has been sent to your registered email address at the point of registration </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!--Start Back To Top Button-->
        <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
        <!--End Back To Top Button-->
    </div>
    <!--wrapper-->

<!-- Bootstrap core JavaScript-->
<?php require_once 'includes/foot.php'; ?>

<script>
    $(document).ready(function () {
        $('#admLoginForm').submit(function (e) {
            e.preventDefault();

            // Get form data
            var formData = $(this).serialize();
            $login_btn = $('#loginBtn');
            $login_btn.addClass('disabled');
            $login_btn.html('<div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>');

            // Perform AJAX validation
            $.ajax({
                type: 'POST',
                url: '../app/function/admissionAuth.php', 
                data: formData,
                success: function (response) {
                    
                    if (response.status == 'success') {
                       console.log(response);
                       document.getElementById("admLoginForm").reset();
                       $login_btn.html('SIGN IN');
                       window.location.href='dashboard';

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

                       
                      
                   } else {
						
                        $login_btn.html('SIGN IN');
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