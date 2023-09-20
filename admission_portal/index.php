<?php
    // include head start here
    require_once 'includes/head.php';
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
                                <button type="submit" id="loginBtn" class="btn btn-outline-primary btn-block waves-effect waves-light">Sign In</button>
                                <button class="btn btn-success" type="button" onclick="anim5_noti()">SHOW ME</button>
                                <button class="btn btn-danger" type="button" onclick="anim4_noti()">SHOW ME</button>
                                <button type="button" type="button" class="btn btn-danger m-1" onclick="error_noti()">DANGER</button>
                                <div class="text-center pt-3">
                                    <p class="text-muted">Do not have an account? <a href="registration"> Sign Up</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card mb-0">
                    <div class="bg-signin2" ></div>
                    <div class="card-img-overlay rounded-left my-5">
                        <h2 class="text-white">Sign In</h2>
                        <h5 class="text-white">to Fountain - Post Graduate School</h5>
                        <p class="card-text text-white pt-3">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable.</p>
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
<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/popper.min.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>

<!--notification js -->
<script src="../assets/plugins/notifications/js/lobibox.min.js"></script>
<script src="../assets/plugins/notifications/js/notifications.min.js"></script>
<script src="../assets/plugins/notifications/js/notification-custom-script.js"></script>

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
                       
                       document.getElementById("admLoginForm").reset();
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

                       window.location='dashboard';
                      
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