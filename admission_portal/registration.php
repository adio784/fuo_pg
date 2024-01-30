<?php
    // include head start here
    require_once 'includes/head.php';
?>
 <!-- Start wrapper-->
 <div id="wrapper">
 	<br>
	   <div class="card-authentication2 mx-auto my-3">
	    <div class="card-group">
	    	<div class="card mb-0">
	    		<div class="card-body">
	    			<div class="card-content p-3">
					<?php
						// include logo start here
						require_once 'includes/logo.php';
					?>
					 <div class="card-title text-uppercase text-center py-3">Sign Up</div>
					 <h3 id="appIDOutput"></h3>
					    <form method="POST" id="admregisterForm" action="../app/function/admissionAuth.php">
						  <div class="form-group">
						   <div class="position-relative has-icon-left">
							  <label for="exampleInputFullName" class="sr-only">Full Name</label>
							  <input type="text" id="exampleInputName" class="form-control" placeholder="First Name" name="firstname" required>
							  <div class="form-control-position">
								  <i class="icon-user"></i>
							  </div>
						   </div>
						  </div>
						  <div class="form-group">
							<div class="position-relative has-icon-left">
								<label for="exampleInputLastName" class="sr-only">Last Name</label>
								<input type="text" id="exampleInputName" class="form-control" placeholder="Last Name" name="lastname" required>
								<div class="form-control-position">
									<i class="icon-user"></i>
								</div>
							</div>
						   </div>
						   <div class="form-group">
							<div class="position-relative has-icon-left">
								<label for="exampleInputMiddleName" class="sr-only">Middle Name</label>
								<input type="text" id="exampleInputName" class="form-control" placeholder="Middle Name" name="middlename">
								<div class="form-control-position">
									<i class="icon-user"></i>
								</div>
							</div>
						   </div>
						  <div class="form-group">
						   <div class="position-relative has-icon-left">
							  <label for="exampleInputEmailId" class="sr-only">Email ID</label>
							  <input type="email" id="exampleInputEmailId" class="form-control" placeholder="Email Address" name="email" required>
							  <div class="form-control-position">
								  <i class="icon-envelope-open"></i>
							  </div>
						   </div>
						  </div>
						  <div class="form-group">
						   <div class="position-relative has-icon-left">
							  <label for="exampleInputPhoneNum" class="sr-only">Phone Number</label>
							  <input type="tel" id="exampleInputPassword" maxlength="11" class="form-control" placeholder="Phone Number" name="phoneNumber" pattern="[0-9]{11}" required >
							  <div class="form-control-position">
								  <i class="icon-phone"></i>
							  </div>
						   </div>
						  </div>
						 
						 <input type="hidden" name="register" value="<?php echo rand() ?>">
						 <button type="submit" id="registerBtn" class="btn btn-outline-primary btn-block waves-effect waves-light"> Sign Up </button>
						
						 <div class="text-center pt-3">
						 <p class="text-muted">Already have an account? <a href="index"> Sign In</a></p>
						 </div>
					</form>
				 </div>
				</div>
	    	</div>
			<div class="card mb-0">
	    	   <div class="bg-signup2"></div>
	    		<div class="card-img-overlay rounded-left my-5">
				<h2 class="text-white">Create An Account</h2>
                <h5 class="text-white">Welcome to School of Postgraduate Studies (SPGS), Fountain University, Osogbo.</h5>
                <p class="card-text text-white pt-3"> Fill the form correctly and pay attention to the prompt after the registration.</p>
				<p>
					<a href='index' class="btn btn-info mt-4 mb-2"> Click here to login </a>
				</p>
                <p class="card-text text-white pt-3"> Thank you!.</p>
             </div>
	    	</div>
	     </div>
	    </div>
    
     <!--Start Back To Top Button-->
    <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>

	<button id="noticeModal" class="btn btn-primary m-1 d-none" data-toggle="modal" data-target="#fullprimarymodal">Primary</button>
	<div class="modal fade" id="fullprimarymodal">
		<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-primary">
			<h5 class="modal-title text-white"><i class="fa fa-star"></i> Successful Application </h5>
			<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
			</div>
			<div class="modal-body">
			<h4>Congratulations !</h4>
			<p>You account has been successfully created. </p>
			<p>Here are your login Details:</p>
			<ul>
				<li> Username : <strong id="appid"> </strong> </li>
				<li> Password : <strong id="lastname"> </strong> </li>
			</ul>
			<p> <a href="index" class="btn btn-info p-2"> Click here to continue application </a>
			<p> <strong>NB:</strong>  Copy / Screenshot the registration details. </p>
			<p> For further information, contact PG support. </p>
			</div>
			<div class="modal-footer">
			<button type="button" class="btn btn-light" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
			</div>
		</div>
		</div>
	</div>
    <!--End Back To Top Button-->
	</div><!--wrapper-->
	
<!-- Bootstrap core JavaScript-->
<?php require_once 'includes/foot.php'; ?>

<script>
    $(document).ready(function () {
        $('#admregisterForm').submit(function (e) {
            e.preventDefault();

            // Get form data
            var formData = $(this).serialize();
            $login_btn = $('#registerBtn');
            $login_btn.addClass('disabled');
            $login_btn.html('<div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>');

            // Perform AJAX validation
            $.ajax({
                type: 'POST',
                url: '../app/function/admissionAuth.php', 
				data: formData,
				dataType: 'json',				
                success: function (response) {

                    if (response.status == 'success') {
                       
						$('#admregisterForm')[0].reset();
						$login_btn.html('SIGN UP');
						$("#appIDOutput").html(response.app_id);
						$("#appid").html(response.app_id);
						$("#lastname").html(response.surname);
						$("#noticeModal").click();

						
                        // Lobibox.notify('success', {
                        //     msg: response.message,
						// 	class: 'lobibox-notify-success',
						// 	title: "Success !",
                        //     position: 'top right',
						// 	icon: 'glyphicon glyphicon-ok-sign',
						// 	sound: 'sound2.mp3',
						// 	delay: 15000,
						// 	theme: 'minimal',
                        // });
                       
                    } else {
						
                        $login_btn.html('SIGN UP');
						$("#appIDOutput").html(response.app_id);
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
                },
				error: function (xhr, status, error) {
						console.error(xhr.responseText);
					}
				});
        });
    });
</script>
 
</body>

</html>
