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
					    <form method="POST" action="">
						  <div class="form-group">
						   <div class="position-relative has-icon-left">
							  <label for="exampleInputFullName" class="sr-only">Full Name</label>
							  <input type="text" id="exampleInputName" class="form-control" placeholder="First Name">
							  <div class="form-control-position">
								  <i class="icon-user"></i>
							  </div>
						   </div>
						  </div>
						  <div class="form-group">
							<div class="position-relative has-icon-left">
								<label for="exampleInputLastName" class="sr-only">Last Name</label>
								<input type="text" id="exampleInputName" class="form-control" placeholder="Last Name">
								<div class="form-control-position">
									<i class="icon-user"></i>
								</div>
							</div>
						   </div>
						   <div class="form-group">
							<div class="position-relative has-icon-left">
								<label for="exampleInputMiddleName" class="sr-only">Middle Name</label>
								<input type="text" id="exampleInputName" class="form-control" placeholder="Middle Name">
								<div class="form-control-position">
									<i class="icon-user"></i>
								</div>
							</div>
						   </div>
						  <div class="form-group">
						   <div class="position-relative has-icon-left">
							  <label for="exampleInputEmailId" class="sr-only">Email ID</label>
							  <input type="text" id="exampleInputEmailId" class="form-control" placeholder="Email ID">
							  <div class="form-control-position">
								  <i class="icon-envelope-open"></i>
							  </div>
						   </div>
						  </div>
						  <div class="form-group">
						   <div class="position-relative has-icon-left">
							  <label for="exampleInputPhoneNum" class="sr-only">Phone Number</label>
							  <input type="text" id="exampleInputPassword" class="form-control" placeholder="Phone Number" pattern="[0-9]{11}">
							  <div class="form-control-position">
								  <i class="icon-phone"></i>
							  </div>
						   </div>
						  </div>
						  <div class="form-group mb-0">
						   <div class="demo-checkbox">
			                <input type="checkbox" id="user-checkbox" class="filled-in chk-col-primary" checked="" />
			                <label for="user-checkbox">I Accept terms & conditions</label>
						  </div>
						 </div>
						 <button type="button" class="btn btn-outline-primary btn-block waves-effect waves-light"><a href="email_verify.php">Sign Up</a></button>
						 <div class="text-center pt-3">
						 <p class="text-muted">Already have an account? <a href="index.php"> Sign In here</a></p>
						 </div>
					</form>
				 </div>
				</div>
	    	</div>
			<div class="card mb-0">
	    	   <div class="bg-signup2"></div>
	    		<div class="card-img-overlay rounded-left my-5">
				<h2 class="text-white">Sign Up</h2>
                        <h5 class="text-white">to Fountain - Post Graduate School</h5>
                 <p class="card-text text-white pt-3">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable.</p>
             </div>
	    	</div>
	     </div>
	    </div>
    
     <!--Start Back To Top Button-->
    <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
    <!--End Back To Top Button-->
	</div><!--wrapper-->
	
  <!-- Bootstrap core JavaScript-->
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/popper.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
 
</body>

<!-- white-version/authentication-signup2.html  Wed, 31 Oct 2018 03:50:19 GMT -->
</html>
