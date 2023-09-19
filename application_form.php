<?php
    // include head start here
    require_once 'includes/head.php';
?>

<!-- Start wrapper-->
 <div id="wrapper">
  <br><br><br>
  <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header text-uppercase text-center">
              Application Form
            </div>
            <div class="card-body">
             <form id="wizard-validation-form" action="congrate_msg.php" method="get">
                    <div>
                        <h3>Personal Information</h3>
                        <section>
                            <div class="form-group">
                                <label for="userName2"> First Name *</label>
                                <input class="required form-control" id="userName2" name="#" type="text">
                            </div>
                            <div class="form-group">
                                <label for="name2"> Middle Name *</label>
                                <input id="name2" name="#" type="text" class="required form-control">
                            </div>
                            <div class="form-group">
                                <label for="surname2"> Last Name *</label>
                                <input id="surname2" name="#" type="text" class="required form-control">
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                <label for="confirm2" class="col-sm-12 col-form-label">Gender *</label>
                                <select name="#" class="required form-control" id="confirm2">
                                  <option value="#"></option>
                                  <option value="Male">Male</option>
                                  <option value="Female">Female</option>
                                  <option value="Others">Others</option>
                                </select>
                                </div>
                              
                                <div class="col-sm-4">
                                <label for="confirm" class="col-sm-12 col-form-label">Religion *</label>
                                <select name="#" class="required form-control" id="confirm2">
                                  <option value="#"></option>
                                  <option value="Islam">Islam</option>
                                  <option value="Christainity">Christainity</option>
                                  <option value="Others">Others</option>
                                </select>
                                </div>

                                <div class="col-sm-4">
                                <label for="confirm" class="col-sm-12 col-form-label">Dat of Birth *</label>
                                  <input id="confirm2" name="confirm" type="date" class="required form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-12 control-label">(*) Mandatory</label>
                            </div>
                        </section>
                        <h3>Contact Information</h3>
                        <section>
                            <div class="form-group">
                              <label for="Address"> Address *</label>
                              <input id="name2" name="#" type="text" class="required form-control">
                            </div>
                            <div class="form-group">
                              <label for="Country"> Country *</label>
                              <select name="#" class="required form-control" id="confirm2">
                                  <option value="#"></option>
                                  <option value="oyo">Nigeria</option>
                                  <option value="osun">USA</option>
                                  <option value="lagos">.........</option>
                              </select>
                            </div>
                            
                            <div class="form-group row">
                              <div class="col-sm-6">
                                <label for="confirm2" class="col-sm-12 col-form-label">State *</label>
                                <select name="#" class="required form-control" id="confirm2">
                                  <option value="#"></option>
                                  <option value="oyo">Oyo</option>
                                  <option value="osun">Osun</option>
                                  <option value="lagos">lagos</option>
                                </select>
                              </div>
                              
                              <div class="col-sm-6">
                                <label for="confirm" class="col-sm-12 col-form-label">City *</label>
                                <select name="#" class="required form-control" id="confirm2">
                                  <option value="#"></option>
                                  <option value="Ibadan">Ibadan</option>
                                  <option value="Ogbomoso">Ogbomoso</option>
                                  <option value="Osogbo">Osogbo</option>
                                </select>
                              </div>
                            </div>
                            <div class="form-group row">
                              <div class="col-sm-6">
                                <label for="confirm" class="col-sm-12 col-form-label">Email Address *</label>
                                <input id="confirm2" name="#" type="email" class="required form-control">
                              </div>
                              <div class="col-sm-6">
                                <label for="confirm" class="col-sm-12 col-form-label">Phone Number *</label>
                                <input id="confirm2" name="#" type="text" class="required form-control" pattern="[0-9]{11}">
                              </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-12 control-label">(*) Mandatory</label>
                            </div>
                        </section>

                        <h3>Pogramme Details</h3>
                        <section>
                            <div class="form-group row">
                              <div class="col-sm-6">
                                <label for="confirm2" class="col-sm-12 col-form-label">Undergraduate Course *</label>
                                <input id="name2" name="#" type="text" class="required form-control">
                              </div>
                              
                              <div class="col-sm-6">
                                <label for="confirm" class="col-sm-12 col-form-label">Class of Degree *</label>
                                <input id="name2" name="#" type="text" class="required form-control">
                              </div>

                            </div>

                            <div class="form-group">
                              <label for="Address"> Institution Attended *</label>
                              <input id="name2" name="#" type="text" class="required form-control">
                            </div>

                            <div class="form-group">
                              <label for="Address"> Mode of Entry *</label>
                              <input id="name2" name="#" type="text" class="required form-control">
                            </div>

                            <div class="form-group">
                              <label for="Address"> Course of Study *</label>
                              <input id="name2" name="#" type="text" class="required form-control">
                            </div>

                            <div class="form-group">
                                <label class="col-lg-12 control-label">(*) Mandatory</label>
                            </div>
                        </section>

                        <h3>Uploading of Documents</h3>
                          <section>
                              <div class="form-group">
                                <label for="Address">O Levels *</label>
                                <input id="name2" name="#" type="file" class="required form-control">
                              </div>

                              <div class="form-group">
                                <label for="Address">Undergraduate Certificate *</label>
                                <input id="name2" name="#" type="file" class="required form-control">
                              </div>
                              <div class="form-group">
                                <label for="Address"> Transcripts *</label>
                                <input id="name2" name="#" type="file" class="required form-control">
                              </div>

                              <div class="form-group">
                                <label for="Address"> Passport *</label>
                                <input id="name2" name="#" type="file" class="required form-control">
                              </div>
                              <div class="form-group">
                                  <label class="col-lg-12 control-label">(*) Mandatory</label>
                              </div>
                          </section>



                        <!-- <section>
                            <div class="form-group">
                                <div class="col-lg-12">
                                    <input id="acceptTerms-2" name="acceptTerms2" type="checkbox" class="required">
                                    <label for="acceptTerms-2">I agree with the Terms and Conditions.</label>
                                </div>
                            </div>
                        </section> -->
                    </div>
                </form>
            </div>
          </div>
        </div>
      </div>
      <!-- End Row-->
    </div>
   <!--Start Back To Top Button-->
    <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
    <!--End Back To Top Button-->
  </div>
  <!--End wrapper-->


  <!-- Bootstrap core JavaScript-->
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/popper.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
	
	<!-- simplebar js -->
	<script src="assets/plugins/simplebar/js/simplebar.js"></script>
  <!-- waves effect js -->
  <script src="assets/js/waves.js"></script>
	<!-- sidebar-menu js -->
	<script src="assets/js/sidebar-menu.js"></script>
  <!-- Custom scripts -->
  <script src="assets/js/app-script.js"></script>

  <!--Form Wizard-->
  <script src="assets/plugins/jquery.steps/js/jquery.steps.min.js" type="text/javascript"></script>
  <script type="text/javascript" src="assets/plugins/jquery-validation/js/jquery.validate.min.js"></script>
  <!--wizard initialization-->
  <script src="assets/plugins/jquery.steps/js/jquery.wizard-init.js" type="text/javascript"></script>
	
</body>

<!-- white-version/form-step-wizard.html  Wed, 31 Oct 2018 03:46:40 GMT -->
</html>
