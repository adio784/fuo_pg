<?php
    // include head start here
    session_start();
    ob_start();
    if( isset($_SESSION['user_id']) && isset($_SESSION['admStatus']) ){

      require_once 'includes/head.php';
      require_once '../core/autoload.php';
      require_once '../core/Database.php';
      require_once '../common/CRUD.php';

      $database   = new Database();
      $Crud       = new CRUD($database);
      $uid        = $_SESSION['user_id'];
      $User       = $Crud->read('application', 'id', $uid);
      $sts        = $User->application_status;
      $active     = 1;
      $isPHD      = 0;
      $Countries  = $database->getConnection()->prepare('SELECT country_name, country_code, code FROM `countries` ORDER BY country_name ASC');
      $Countries->execute();
      $CountryOrg = $database->getConnection()->prepare('SELECT country_name, country_code, code FROM `countries` WHERE `status`=? ORDER BY country_name ASC');
      $CountryOrg->execute([1]);

      $Courses    = $Crud->readAllByOrder('program_course', 'program_id', $User->program, 'course_name', 'ASC');

      // Get requests -------------------------------------------------------------------------------------------
      // State of origin .............................
      if( isset($_GET['ocountry_id']) )
      {
          $ocountry_id  = $_GET['ocountry_id'];
          ob_clean();
          if( $ocountry_id != "" )
          {    
              // Get data .........................................................................
              $ocountry_ids = $Crud->readAllByOrder('states', 'country_code', $ocountry_id, 'name', 'ASC');
              // .....................................................................................................

              $response['status']   = 'success';
              $response['message']  = "Successfully fetch data";
              $response['data']     = $ocountry_ids;
          }

          header('Content-Type: application/json');
          echo json_encode($response);
          exit();
      }

      // LGA of origin ..........................................
      if( isset($_GET['ostate_id']) )
      {
          $ostate_id  = $_GET['ostate_id'];
          ob_clean();
          if( $ostate_id != "" )
          {    
              // Get data .........................................................................
              $ostate_ids = $Crud->readAllByOrder('cities', 'state_id', $ostate_id, 'name', 'ASC');
              // .....................................................................................................

              $response['status']   = 'success';
              $response['message']  = "Successfully fetch data";
              $response['data']     = $ostate_ids;
          }

          header('Content-Type: application/json');
          echo json_encode($response);
          exit();
      }

      // Residence ...............................................
      if( isset($_GET['country_id']) )
      {
          $country_id  = $_GET['country_id'];
          ob_clean();
          if( $country_id != "" )
          {    
              // Get data .........................................................................
              $country_ids = $Crud->readAllByOrder('states', 'country_code', $country_id, 'name', 'ASC');
              // .....................................................................................................

              $response['status']   = 'success';
              $response['message']  = "Successfully fetch data";
              $response['data']     = $country_ids;
          }

          header('Content-Type: application/json');
          echo json_encode($response);
          exit();
      }

      if( isset($_GET['state_id']) )
      {
          $state_id  = $_GET['state_id'];
          ob_clean();
          if( $state_id != "" )
          {    
              // Get data .........................................................................
              $state_ids = $Crud->readAllByOrder('cities', 'state_id', $state_id, 'name', 'ASC');
              // .....................................................................................................

              $response['status']   = 'success';
              $response['message']  = "Successfully fetch data";
              $response['data']     = $state_ids;
          }

          header('Content-Type: application/json');
          echo json_encode($response);
          exit();
      }

      // get request ends here ___________________________________________________________________________________


    } else {
      header('Location: /fuo_pg/admission_portal/index');
    }



?>

<?php 
if ($sts == "registered" || $sts == "admitted"){
  header("Location: admission_home");
} else { ?>
<!-- Overlay -->
<div id="overlay" class="overlay"></div>

<!-- Preloader -->
<div id="preloader" class="preloader">
    <div class="loader"></div>
</div>


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
            <div class="card-body" >
             <form id="wizard-validation-form" method="POST">
                    <div>
                        <h3>Personal Information</h3>
                        <section style="display: block; overflow: scroll">
                            <div class="form-group">
                                <label for="firstName"> First Name *</label>
                                <input class="required form-control" id="firstName" type="text" required value="<?php echo $User->first_name; ?>">
                            </div>
                            <div class="form-group">
                                <label for="middleName"> Middle Name </label>
                                <input id="middleName" type="text" class="required form-control" value="<?php echo $User->middle_name; ?>">
                            </div>
                            <div class="form-group">
                                <label for="lastName"> Last Name *</label>
                                <input id="lastName" type="text" class="required form-control" required value="<?php echo $User->last_name; ?>">
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                <label for="gender" class="col-sm-12 col-form-label">Gender *</label>
                                <select class="required form-control" id="gender" required>
                                  <option value="" selected> -- Select Option -- </option>
                                  <option value="Male">Male</option>
                                  <option value="Female">Female</option>
                                  <option value="Others">Others</option>
                                </select>
                                </div>
                              
                                <div class="col-sm-4">
                                <label for="religion" class="col-sm-12 col-form-label">Religion *</label>
                                <select class="required form-control" id="religion" required>
                                  <option value="">-- Select Religion -- </option>
                                  <option value="Islam">Islam</option>
                                  <option value="Christainity">Christainity</option>
                                  <option value="Others">Others</option>
                                </select>
                                </div>

                                <div class="col-sm-4">
                                <label for="birthDate" class="col-sm-12 col-form-label">Dat of Birth *</label>
                                  <input id="birthDate" type="date" class="required form-control" max="2003-12-31" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-12 control-label">(*) Mandatory</label>
                            </div>
                        </section>
                        <h3>Contact Information</h3>
                        <section style="display: block; overflow: scroll">
                          <div class="row">
                            <div class="col-4">
                              <label for="">Country of Origin</label>
                              <select class="required form-control" id="countryOrigin" required>
                                  <option value="" selected>-- Select Country --</option>
                                  <option value="160">Nigeria</option>
                                  <?php 
                                    while($CtryOrg = $CountryOrg->fetchObject() ){?>
                                    <option value="<?php echo $CtryOrg->country_code; ?>"><?php echo $CtryOrg->country_name; ?></option>
                                  <?php } ?>
                              </select>
                            </div>
                            <div class="col-4">
                              <label for="">State of Origin</label>
                              <select class="required form-control" id="stateOrigin" required>
                                  <option value="" selected>-- Select State --</option>
                              </select>
                            </div>
                            <div class="col-4">
                              <label for="">LGA of Origin</label>
                              <select class="required form-control" id="lgaOrigin" required>
                                  <option value="" selected>-- Select LGA --</option>
                              </select>
                            </div>
                          </div>
                            <div class="form-group mt-3">
                              <label for="address"> Address *</label>
                              <textarea id="address" cols="5" rows="2" class="required form-control" required></textarea>
                              <!-- <input id="address" type="text" class="required form-control"> -->
                            </div>
                            <div class="form-group">
                              <label for="country"> Country of Residence *</label>
                              <select class="required form-control" id="country" required>
                                  <option value="" selected>-- Select Country --</option>
                                  <option value="160">Nigeria</option>
                                  <?php 
                                    while($Country = $Countries->fetchObject()){?>
                                    <option value="<?php echo $Country->country_code; ?>"><?php echo $Country->country_name; ?></option>
                                  <?php } ?>
                              </select>
                            </div>
                            
                            <div class="form-group row">
                              <div class="col-sm-6">
                                <label for="state" class="col-sm-12 col-form-label">State *</label>
                                <select class="required form-control" id="state" required>
                                  <option value="" selected> -- Select State -- </option>
                                  <option value="oyo">Oyo</option>
                                  <option value="osun">Osun</option>
                                  <option value="lagos">lagos</option>
                                </select>
                              </div>
                              
                              <div class="col-sm-6">
                                <label for="city" class="col-sm-12 col-form-label">City *</label>
                                <select class="required form-control" id="city" required>
                                <option value="" selected> -- Select City -- </option>
                                  <option value="Ibadan">Ibadan</option>
                                  <option value="Ogbomoso">Ogbomoso</option>
                                  <option value="Osogbo">Osogbo</option>
                                </select>
                              </div>
                            </div>

                            <div class="form-group row">
                              <div class="col-sm-6">
                                <label for="emailAddress" class="col-sm-12 col-form-label">Email Address *</label>
                                <input id="emailAddress" type="email" class="required form-control" value="<?php echo $User->email; ?>" required>
                              </div>
                              <div class="col-sm-6">
                                <label for="phoneNumber" class="col-sm-12 col-form-label">Phone Number *</label>
                                <input id="phoneNumber" type="text" class="required form-control" value="<?php echo $User->phone_number; ?>" pattern="[0-9]{11}" required>
                              </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-12 control-label">(*) Mandatory</label>
                            </div>
                            <br> <br>
                        </section>

                        <h3>Pogramme Details</h3>
                        <section style="display: block; overflow: scroll">
                            <div class="form-group row">
                              <div class="col-sm-6">
                                <label for="undergraduateCourse" class="col-sm-12 col-form-label">Undergraduate Course *</label>
                                <input id="undergraduateCourse" type="text" class="required form-control" style="text-transform: capitalize;" placeholder="Follow this format: Computer Science" required>
                              </div>
                              
                              <div class="col-sm-6">
                                <label for="classDegree" class="col-sm-12 col-form-label">Class of Degree *</label>
                                <select class="required form-control" id="classDegree" required>
                                  <option value="" selected> -- Select Degree -- </option>
                                  <option>Third Class</option>
                                  <option>Second Class Lower</option>
                                  <option>Second Class Upper</option>
                                  <option>First Class</option>
                                </select>
                              </div>

                            </div>

                            <div class="form-group">
                              <label for="instituteAtt"> Institution Attended *</label>
                              <input id="instituteAtt" type="text" class="required form-control" autocapitalize="words" style="text-transform: capitalize;" placeholder="Follow this format: Fountain University, Osogbo" required>
                            </div>

                            <div class="form-group d-none">
                              <label for="entryMode"> Mode of Entry *</label>
                              <input id="entryMode" type="text" class="required form-control" required>
                            </div>

                            <div class="form-group">
                              <label for="courseOfStudy"> Course of Study *</label>
                              <!-- <input id="courseOfStudy" type="text" class="required form-control"> -->
                              <select id="courseOfStudy" class="required form-control" required>
                                  <option value="" selected> -- Select Course of Study -- </option>
                                  <?php 
                                  if ($Courses !== false ) {
                                  foreach ($Courses as $Course) {?>
                                  <option value="<?php echo $Course->id;?>"> <?php echo $Course->course_name;?> </option>
                                <?php } }?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-12 control-label">(*) Mandatory</label>
                            </div>
                        </section>

                        <h3>Uploading of Documents</h3>
                        
                          <section style="display: block; overflow: scroll">
                            
                              <div class="form-group">
                                <p class="text-warning"> <strong> 150KB on all uploads, all files must be in jpeg, jpg, png format, except transcript, which can be pdf format. </strong> </p>
                                <label for="oLevel">O Levels *</label>
                                <input id="oLevel" type="file" class="required form-control" accept=".jpg, .jpeg, .png" required>
                                <!-- accept="application/pdf,image/*" -->
                              </div>

                              <div class="form-group">
                                <label for="undergCert">Undergraduate Certificate *</label>
                                <input id="undergCert" type="file" class="required form-control" accept="application/pdf*" required>
                              </div>

                              <div class="form-group">
                                <label for="tranScripts"> Transcripts *</label>
                                <input id="tranScripts" type="file" class="required form-control" accept="application/pdf,image/*" required>
                              </div>

                              <?php if($User->program == '836293'){ $isPHD = 1; ?>
                              <input type="hidden" id="isPhd" value="<?php echo $isPHD; ?>">
                              <div class="form-group">
                                 <label for="masterCerts"> Master's Certificate *</label>
                                 <input id="masterCerts" type="file" class="required form-control <?php if($User->program != '836293'){ echo 'd-none';}?>" accept="application/pdf,image/*">
                              </div>
                              <?php } ?>

                              <input type="hidden" name="" id="isPhd" value="<?php echo $isPHD; ?>">
                              <div class="form-group">
                                <label for="passPorts"> Passport *</label>
                                <input id="passPorts" type="file" class="required form-control" accept="application/pdf,image/*" required>
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

                <button class="btn btn-primary m-1 d-none" data-toggle="modal" data-target="#largesizemodal" id="previewFormBtn">Large Size Modal</button>

                        <div class="modal fade" id="largesizemodal">
                          <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title"><i class="fa fa-star"></i> Preview Form</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                          <form action="../app/function/application_process.php" id="pg_application_form" method="POST" enctype="multipart/form-data">
                              <div class="modal-body pl-4">
                                    <div class="row">
                                    <input type="hidden" name="pgAppToken" value="<?php echo uniqid() ?>">
                                      <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="">Last Name</label>
                                        <input type="text" id="ln" name="lastName" style="border:0px">
                                      </div>
                                      <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="">First Name</label>
                                        <input type="text" id="fn" name="firstName" style="border:0px">
                                      </div>
                                      <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="">Middle Name</label>
                                        <input type="text" id="mn" name="middleName" style="border:0px">
                                      </div>
                                    </div>

                                    <div class="row">
                                      <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="">Gender</label> <br>
                                        <input type="text" id="gn" name="gender" style="border:0px">
                                      </div>
                                      <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="">Religion</label>
                                        <input type="text" id="rl" name="religion" style="border:0px">
                                      </div>
                                      <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="">Date of Birth</label>
                                        <input type="text" id="db" name="birthDate" style="border:0px">
                                      </div>
                                    </div>

                                    <div class="row">
                                      <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="">Address</label>
                                        <input type="text" id="ad" name="address" style="border:0px">
                                      </div>
                                      <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="">Country</label>
                                        <input type="text" id="cr" name="country" style="border:0px">
                                      </div>
                                      <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="">State</label>
                                        <p> <input type="text" id="st" name="state" style="border:0px"> </p>
                                      </div>
                                    </div>

                                    <div class="row">
                                      <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="">City</label>
                                        <p> <input type="text" id="ct" name="city" style="border:0px"> </p>
                                      </div>
                                      <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="">Email Address</label>
                                        <input type="text" id="em" name="emailAddress" style="border:0px">
                                      </div>
                                      <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="">Phone Number</label>
                                        <input type="text" id="pn" name="phoneNumber" style="border:0px">
                                      </div>
                                    </div>
                                    
                                    <div class="row">
                                      <div class="col-12 mt-3">
                                        <h6>Origin</h6>
                                        <hr>
                                      </div>
                                    </div>

                                    <div class="row mt-4">
                                      <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="">Country of Origin</label>
                                        <input type="text" id="co" name="countryOrigin" style="border:0px">
                                      </div>
                                      <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="">State of Origin</label>
                                        <input type="text" id="so" name="stateOrigin" style="border:0px">
                                      </div>
                                      <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="">LGA of Origin</label>
                                        <p> <input type="text" id="lo" name="lgaOrigin" style="border:0px"> </p>
                                      </div>
                                    </div>

                                    
                                    <div class="row">
                                      <div class="col-12 mt-3">
                                        <h6>Academics</h6>
                                        <hr>
                                      </div>
                                    </div>
                                    <div class="row mt-4">
                                      <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="">Undergraduate Course</label>
                                        <input type="text" id="uc" name="undergraduateCourse" style="border:0px">
                                      </div>
                                      <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="">Class of Degree</label>
                                        <input type="text" id="cd" name="classDegree" style="border:0px">
                                      </div>
                                      <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="">Institute Attended</label>
                                        <input type="text" id="ia" name="instituteAtt" style="border:0px">
                                      </div>
                                    </div>

                                    <div class="row mt-4">
                                      <!-- <div class="col-md-4 col-sm-12">
                                        <label for="">Entry Mode</label>
                                        <p> <input type="text" id="rm" name="entryMode" style="border:0px"> </p>
                                      </div> -->
                                      <div class="col-sm-12">
                                        <label for="">Course of Study</label> <br>
                                        <input type="text" id="cs" style="border:0px">
                                        <input type="hidden1" id="csed" name="courseOfStudy" style="border:0px">
                                      </div>
                                    </div>
                                    

                                    <div class="row">
                                      <div class="col-12 mt-3">
                                        <h6>Uploads .................................. </h6>
                                        <br>
                                      </div>
                                    </div>

                                    <div class="row">
                                      <!-- Images -->
                                      
                                      <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="">O'level Upload</label>
                                        <input type="file" name="oLevel" class="d-none1 d-none" id="olu">
                                        <img alt="" id="ol" class="card-img w-100">
                                      </div>
                                      <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="">Undergraduate Certificate</label>
                                        <input type="file" name="undergCert" class="d-none1 d-none" id="upu">
                                        <img alt="" id="up" class="card-img w-100">
                                      </div>
                                      <div class="col-lg-4 col-md-6 col-sm-12">
                                        <label for="">Transcript</label>
                                        <input type="file" name="transcript" class="d-none1 d-none" id="tsu">
                                        <img alt="" id="cpt" class="card-img w-100">
                                      </div>

                                      <div class="col-lg-4 col-md-6 col-sm-12 mt-4">
                                        <label for="">Passport</label>
                                        <input type="file" name="passport" class="d-none1 d-none" id="psu">
                                        <img alt="" id="prr" class="card-img w-100">
                                      </div>
                                      <?php if($User->program == '836293'){ ?>
                                        <div class="col-lg-4 col-md-6 col-sm-12 mt-4">
                                          <label for="">Master Certificate</label>
                                          <input type="file" name="masterCert" class="d-none1 d-none" id="msu">
                                          <img alt="" id="ms" class="card-img w-100">
                                        </div>
                                      <?php } ?>
                                      
                                      <!-- Images -->
                                    </div>
                                
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancelModal"><i class="fa fa-times"></i> Close</button>
                                <button type="submit" class="btn btn-primary" id="submitBtn"><i class="fa fa-check-square-o"></i> Submit Application</button>
                              </div>
                          </form>
                            </div>
                          </div>
                        </div>

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
  <?php require_once 'includes/foot.php'; ?>
	
  <script>
    $(document).ready( ()=> {

      $('#pg_application_form').on('submit', (e)=> {
        e.preventDefault();
        var regForm = $('#pg_application_form');
        var formData = new FormData($('#pg_application_form')[0]); //$("#pg_application_form").serialize();
        $submit_btn = $('#submitBtn');
        $cancel_btn = $("#cancelModal");
        $cancel_btn.click();
        $submit_btn.addClass('disabled');
        $('#overlay').show();
        $('#preloader').show();
        $submit_btn.html('<div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>');

         // Perform AJAX validation
        $.ajax({
          type: 'POST',
          url: '../app/function/application_process.php', 
          data: formData,
          contentType: false,
          processData: false,
          dataType: 'json',				
          success: function (response) {

            if (response.status == 'success') {

              document.getElementById("pg_application_form").reset();
              $submit_btn.html('SUBMIT APPLICATION');
              $submit_btn.removeClass('disabled');
                    
              Lobibox.notify('success', {
                msg: response.message,
                title: "Success !",
                position: 'top right',
                icon: 'glyphicon glyphicon-ok-sign',
                sound: 'sound2.mp3',
                delay: 15000,
                theme: 'minimal',
              });
              
              $('#overlay').hide();
              $('#preloader').hide();

              window.location.href="admission_home";
              
            } else {
              
              $submit_btn.html('SUBMIT APPLICATION');
              $submit_btn.removeClass('disabled');

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
            $('#overlay').hide();
            $('#preloader').hide();
          }
        
        });
        // class: 'lobibox-notify-success',
      });

      $("#cancelModal").on('click', () => {
        $submit_btn = $('#submitBtn');
        $submit_btn.removeClass('disabled');
        $submit_btn.html("SUBMIT APPLICATION");
      })

      // Get country State and LGA .................
      
      $('#countryOrigin').change(function(){
          let ctrO = $(this).val();
          $("#stateOrigin").html($('<div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>'));
          // alert(ctr);
          $.ajax({
              method: 'GET',
              url: "start_application.php?ocountry_id="+ctrO,
              success:function(result)
              {
                  if(result.data != ""){
                      $('#stateOrigin').html("<option value='' selected>"+ '-- Select State --' + "</option>");
                      $.each(result.data, function(key, item) {
                        $('#stateOrigin').append(
                          '<option value='+item.id+'>'+ item.name +'</option>'
                        )
                      });
                  }
              }
          })
      });

      // State of origin .........................
      $('#stateOrigin').change(function(){
          let strO = $(this).val();
          $("#lgaOrigin").html($('<div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>'));
          // alert(ctr);
          $.ajax({
              method: 'GET',
              url: "start_application.php?ostate_id="+strO,
              success:function(result)
              {
                  if(result.data != ""){
                      $('#lgaOrigin').html("<option value='' selected>"+ '-- Select LGA --' + "</option>");
                      $.each(result.data, function(key, item) {
                        $('#lgaOrigin').append(
                          '<option value='+item.id+'>'+ item.name +'</option>'
                        )
                      });
                  }
              }
          })
      });

      // Country of residence..................................
      $('#country').change(function(){
          let ctr = $(this).val();
          $("#state").html($('<div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>'));
          // alert(ctr);
          $.ajax({
              method: 'GET',
              url: "start_application.php?country_id="+ctr,
              success:function(result)
              {
                  if(result.data != ""){
                      $('#state').html("<option value='' selected>"+ '-- Select State --' + "</option>");
                      $.each(result.data, function(key, item) {
                        $('#state').append(
                          '<option value='+item.id+'>'+ item.name +'</option>'
                        )
                      });
                  }
              }
          })
      });

      $('#state').change(function(){
          let str = $(this).val();
          $("#city").html($('<div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>'));
          // alert(str);
          $.ajax({
              method: 'GET',
              url: "start_application.php?state_id="+str,
              success:function(result)
              {
                  if(result.data != ""){
                      $('#city').html("<option value='' selected>"+ '-- Select LGA --' + "</option>");
                      $.each(result.data, function(key, item) {
                        $('#city').append(
                          '<option value='+item.id+'>'+ item.name +'</option>'
                        )
                      });
                  }
              }
          })
      });
    
    })
  </script>
</body>

</html>

<?php } ?>