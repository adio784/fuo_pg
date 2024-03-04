<?php include 'header.php'; ?>
<?php
// Get User data .............................
if( isset($_GET['view_student']) )
{
    $student_id  = $_GET['view_student'];
    ob_clean();
    if( $student_id != "" )
    {    
        // Get data ..........................................................................................
        $query  = $database->getConnection()->prepare("
                  SELECT *, 
                  user_credentials.application_id as appId
                  FROM `application`
                  INNER JOIN user_credentials ON user_credentials.application_id = application.application_id
                  LEFT JOIN program_course ON application.course = program_course.id
                  WHERE application.application_id = ? LIMIT 1");
        $query->execute([$student_id]);
        $students = $query->fetchObject();
        // .....................................................................................................

        $response['status']   = 'success';
        $response['message']  = "Successfully fetch data";
        $response['data']     = $students;
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

?>


<div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumb-->
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
		    <h4 class="page-title">Dashboard > > ></h4>
		    <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="admission_home">Home</a></li>
            <li class="breadcrumb-item"><a href="javaScript:void();">Application</a></li>
            <li class="breadcrumb-item active" aria-current="page">Recent</li>
         </ol>
	   </div>
     </div>

     <div class="card mt-3 shadow-none border border-light">
	    <div class="card-content">
        <div class="row row-group m-0">
          <div class="col-12 col-lg-6 col-xl-3 border-light">
              <div class="card-body">
                <div class="media">
                  <div class="media-body text-left">
                    <h4 class="text-info"><?= $TApp->num ?></h4>
                    <span>Total Applicant</span>
                    <small>2023/2024 - session</small>
                  </div>
                  <div class="align-self-center w-circle-icon rounded bg-info shadow-info">
                    <i class="icon-basket-loaded text-white"></i>
                  </div>
                </div>
              </div>
          </div>
          <div class="col-12 col-lg-6 col-xl-3 border-light">
            <div class="card-body">
                <div class="media">
                  <div class="media-body text-left">
                    <h4 class="text-danger"><?= $NotAdmitted->num ?></h4>
                    <span>Not Admitted</span>
                  </div>
                  <div class="align-self-center w-circle-icon rounded bg-danger shadow-danger">
                    <i class="icon-wallet text-white"></i>
                  </div>
                </div>
            </div>
          </div>
          <div class="col-12 col-lg-6 col-xl-3 border-light">
            <div class="card-body">
                <div class="media">
                  <div class="media-body text-left">
                    <h4 class="text-success"><?= $Admitted->num ?></h4>
                    <span>Admitted</span>
                  </div>
                  <div class="align-self-center w-circle-icon rounded bg-success shadow-success">
                    <i class="icon-pie-chart text-white"></i>
                  </div>
                </div>
            </div>
          </div>
          <div class="col-12 col-lg-6 col-xl-3 border-light">
            <div class="card-body">
                <div class="media">
                  <div class="media-body text-left">
                    <h4 class="text-warning"><?= $Allapp->num ?></h4>
                    <span>All Applicant</span> <br>
                    <small> So far</small>
                  </div>
                  <div class="align-self-center w-circle-icon rounded bg-warning shadow-warning">
                    <i class="icon-user text-white"></i>
                  </div>
                </div>
            </div>
          </div>
        </div>
		  </div>
	  </div>


    <!-- End Breadcrumb-->
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header"><i class="fa fa-table"></i> New Applicants</div>
            <div class="card-body">
              <div class="table-responsive">
              <table id="default-datatable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>***</th>
                        <th>Application Number</th>
                        <th>Name</th>
                        <th>Programme</th>
                        <th>Course</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                        <!-- Iterate thru the existing data -->
                        <?php 
                                $count = 0;

                                if ($rcount < 1) {

                                    echo "<tr> <td align='center'> <strong> No record found !!! </strong> </td> </tr>";

                                } else {
                                    while($recent = $rquery->fetch(PDO::FETCH_OBJ)){
                                        $count ++;
                                        $name   =   strtoupper($recent->last_name) . ' ' . $recent->first_name . ' ' . $recent->middle_name;
                                        $appID  =   $recent->application_id;
                                        $email  =   $recent->email;
                                        $prog   =   $recent->programme_title;
                                        $cours  =   $recent->course_name;
                                        $date   =   $recent->created_at;
                                        $img    =   $recent->passport;
                        ?>

                        <tr>
                            <td> <?= $count ?> </td>
                            <td><img src="../admission_portal/admissionUploads/<?= $img ?>" class="product-img" alt="applicant img"></td>
                            <td> <?= $appID ?> </td>
                            <td> <?= $name ?> </td>
                            <td> <span class="badge gradient-quepal text-white shadow p-2"><?= $prog ?></span></td>
                            <td> <?= $cours ?> </td>
                            <td> <?= formatDate($date) ?> </td>
                            <td> 
                                <button class="btn btn-info admissionBtn shadow-info" type="button" id="<?= $appID ?>">  <i class="icon-login text-white"></i> Admit </button>
                                <button class="btn btn-danger rejectBtn shadow-danger" type="button" id="<?= $appID ?>">  <i class="icon-logout text-white"></i> Reject </button>
                                <button id="<?= $appID ?>" class="view_student btn btn-secondary shadow-secondary"> <i class="icon-eye text-white"></i> View</button> 
                                
                            </td>
                            


                        </tr>

                        <?php } } ?>

                    <!-- iteration ends here -->
                </tbody>
                <tfoot>
                    <tr>
                        <th>SN</th>
                        <th>***</th>
                        <th>Application Number</th>
                        <th>Name</th>
                        <th>Programme</th>
                        <th>Course</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
               </table>
            </div>
            </div>
          </div>
        </div>
      </div><!-- End Row-->

    </div>
    <!-- End container-fluid-->
    
    </div><!--End content-wrapper-->
   <!--Start Back To Top Button-->
    <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
    <!--End Back To Top Button-->
	

     <!-- ------------ MODAL TO DISPLAY USER DETAILS BEFORE RECOMMENDATION BY THE DEPARTMENTAL ADMISSION OFFICER ----------- -->
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
              <div class="col-lg-4 col-md-6 col-sm-12" id="mddiv">
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
                <input type="hidden" id="csed" name="courseOfStudy" style="border:0px">
              </div>
            </div>
            

            <div class="row">
              <div class="col-12 mt-3">
                <h6>Uploads</h6>
                <hr>
                <br>
              </div>
            </div>

            <div class="row">
              <!-- Images -->
              
              <div class="col-lg-4 col-md-6 col-sm-12">
                <label for="">O'level Upload</label>
                <p id="ol"> </p>
                
              </div>
              <div class="col-lg-4 col-md-6 col-sm-12">
                <label for="">Undergraduate Certificate</label>
                <p id="up"> </p>
              </div>
              <div class="col-lg-4 col-md-6 col-sm-12">
                <label for="">Transcript</label>
                <p id="cpt"> </p>
              </div>

              <div class="col-lg-4 col-md-6 col-sm-12 mt-4">
                <label for="">Passport</label>
                <p id="prr"> </p>
              </div>
             
              <div class="col-lg-4 col-md-6 col-sm-12 mt-4 msCert2">
                <label for="">Master Certificate</label>
                <p id="msCert"> </p>
              </div>
              
              <!-- Images -->
            </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancelModal"><i class="fa fa-times"></i> Close</button>
      </div>

    </div>
  </div>
</div>

<!-- ------------------------------------------------------------------------------------------------------------------------------------- -->



    <?php include "footer.php" ?>

    <script>
     $(document).ready(function() {
      //Default data table
       $('#default-datatable').DataTable();


       var table = $('#example').DataTable( {
        lengthChange: false,
        buttons: [ 'copy', 'excel', 'pdf', 'print', 'colvis' ]
      } );
 
     table.buttons().container()
        .appendTo( '#example_wrapper .col-md-6:eq(0)' );
      
      } );

    </script>

    <script>
        $(document).ready(function () {
            $('.admissionBtn').on('click', function (e) {
                e.preventDefault();

                // Get form data
                var dataId = $(this).attr('id');
                // alert(dataId);
                $login_btn = $('#'+dataId);
                $login_btn.addClass('disabled');

                swal({
                    title: 'Do you want Continue ?',
                    text: "You are sure to admit the student",
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonColor: '#C64EB2',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    buttons: {
                    cancel: true,
                    confirm: true,
                    },

                    


                }).then((result) => {
                if (result == true) {

                    $('#overlay').show();
                    $('#preloader').show();
                    $login_btn.html('<div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>');

                    // Perform AJAX validation
                    $.ajax({
                        type: 'GET',
                        url: '../app/function/admit_student.php?admit='+dataId,
                        dataType: 'json',
                        success: function (response) {
                        console.log(response);
                        if (response.status == 'success') {
                                    
                            $login_btn.html('Admit');
                            $login_btn.removeClass('disabled');
                            $('#overlay').hide();
                            $('#preloader').hide();

                            Lobibox.notify('success', {
                            msg: response.message,
                            class: 'lobibox-notify-success',
                            title: "Success !",
                            position: 'top right',
                            // icon: true,
                            // icon: 'glyphicon glyphicon-ok-sign',
                            delay: 15000,
                            theme: 'minimal',
                            });

                            // $(document).ajaxStop(function(){
                            //     window.location.reload();
                            // });

                          setTimeout( function(){// wait for 5 secs(2)
                              location.reload(); // then reload the page.(3)
                          }, 5000); 

                        } else {
                            
                            $login_btn.html('Admit');
                            $login_btn.removeClass('disabled');
                            $('#overlay').hide();
                            $('#preloader').hide();
                        
                            Lobibox.notify('error', {
                            msg: response.message,
                            class: 'lobibox-notify-error',
                            title: 'Error!',
                            showClass: 'fadeInDown',
                            hideClass: 'fadeUpDown',
                            icon: true,
                            icon: 'glyphicon glyphicon-remove-sign',
                            position: 'top right',
                            theme: 'minimal',
                            });

                          //   setTimeout( function(){// wait for 5 secs(2)
                          //     location.reload(); // then reload the page.(3)
                          // }, 5000); 

                          }
                        },
                        error: function (xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                
                } else {
                  console.log("You clicked cancel")
                  swal("You Clicked Cancel");
                    $login_btn.html('Admit');
                    $login_btn.removeClass('disabled');
                    setTimeout( function(){
                      location.reload();
                  }, 5000);
                }

                });
            

        
        
            });
            
            // View student before recommeding .....................
            $('.view_student').on('click', function(e) {
              e.preventDefault();

              var dataId = $(this).attr('id');
                // alert(dataId);
              $login_btn = $('#'+dataId);
              $login_btn.addClass('disabled');

              $('#overlay').show();
              $('#preloader').show();
              $login_btn.html('<div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>');

              // Perform AJAX validation
              $.ajax({
                  type: 'GET',
                  url: "admission_home.php?view_student="+dataId,
                  dataType: 'json',
                  success: function (response) {
                    if (response.status == 'success') {
                      console.log(dataId);
                      $login_btn.html('Admit');
                      $login_btn.removeClass('disabled');
                      $('#overlay').hide();
                      $('#preloader').hide();
                      
                      var dt = response.data;
                      $("#previewFormBtn").click();
                      $('#fn').val( dt.first_name);
                      if (dt.middle_name == "") $('#mn').hide(); $('#mddiv').hide();
                      $('#ln').val( dt.last_name );
                      $('#gn').val( dt.gender);
                      $('#rl').val( dt.religion );
                      $('#db').val( dt.date_of_birth );
                      $('#ad').val( dt.address );
                      $('#cr').val( dt.country );
                      $('#st').val( dt.state );
                      $('#ct').val( dt.lga );

                      $('#co').val( dt.country_of_origin );
                      $('#so').val( dt.state_of_origin );
                      $('#lo').val( dt.local_government );
                      
                      $('#em').val( dt.email );
                      $('#pn').val( dt.phone_number );
                      $('#uc').val( dt.course_studied );
                      $('#cd').val( dt. class_degree );
                      $('#ia').val( dt.institute_attended );
                      $('#cs').val( dt.course_name   );
                      $('#ol').html("<img alt='' class='card-img w-100' src='../admission_portal/admissionUploads/"+ dt.o_level +"'>");
                      $('#up').html("<img alt='' class='card-img w-100' src='../admission_portal/admissionUploads/"+ dt.undergraduate +"'>");
                      $('#prr').html("<img alt='' class='card-img w-100' src='../admission_portal/admissionUploads/"+ dt.passport +"'>");
                      $('#cpt').html("<iframe class='card-img w-100' height='300px' src='../admission_portal/admissionUploads/"+ dt.transcript +"'> </iframe");
                      if (dt.program == "836293") { 
                        $('#msCert').html("<img alt='' class='card-img w-100' src='../admission_portal/admissionUploads/"+ dt.masters +"'>");
                      } else {
                        $("#msCert2").hide();
                      }
                       
                      
                    } else {
                        
                        $login_btn.html('Admit');
                        $login_btn.removeClass('disabled');
                        $('#overlay').hide();
                        $('#preloader').hide();

                        setTimeout( function(){
                          location.reload();
                      }, 5000); 

                      }
                  },
                  error: function (xhr, status, error) {
                      console.error(xhr.responseText);
                  }
              });

            });

            // Reject Admission ....................................
            $('.rejectBtn').on('click', function (e) {
                e.preventDefault();

                // Get form data
                var dataId = $(this).attr('id');
                $login_btn = $('#'+dataId);

                swal({
                    title: 'Do you want Continue ?',
                    text: "You are sure to reject the student",
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonColor: '#C64EB2',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    buttons: {
                    cancel: true,
                    confirm: true
                    },
              }).then((result) => {
                  if (result == true) { 
                   
                    $('#overlay').show();
                    $('#preloader').show();
                    $login_btn.html('<div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>');

                    // Perform AJAX validation
                    $.ajax({
                        type: 'GET',
                        url: '../app/function/admit_student.php?reject='+dataId,
                        dataType: 'json',
                        success: function (response) {
                        if (response.status == 'success') {
                                    
                            $login_btn.html('Admit');
                            $login_btn.removeClass('disabled');
                            $('#overlay').hide();
                            $('#preloader').hide();

                            Lobibox.notify('success', {
                            msg: response.message,
                            class: 'lobibox-notify-success',
                            title: "Success !",
                            position: 'top right',
                            // icon: true,
                            // icon: 'glyphicon glyphicon-ok-sign',
                            delay: 15000,
                            theme: 'minimal',
                            });

                            // $(document).ajaxStop(function(){
                            //     window.location.reload();
                            // });

                          setTimeout( function(){// wait for 5 secs(2)
                              location.reload(); // then reload the page.(3)
                          }, 5000); 

                        } else {
                            
                            $login_btn.html('Admit');
                            $login_btn.removeClass('disabled');
                            $('#overlay').hide();
                            $('#preloader').hide();
                        
                            Lobibox.notify('error', {
                            msg: response.message,
                            class: 'lobibox-notify-error',
                            title: 'Error!',
                            showClass: 'fadeInDown',
                            hideClass: 'fadeUpDown',
                            icon: true,
                            icon: 'glyphicon glyphicon-remove-sign',
                            position: 'top right',
                            theme: 'minimal',
                            });

                            setTimeout( function(){// wait for 5 secs(2)
                              location.reload(); // then reload the page.(3)
                          }, 5000); 

                          }
                        },
                        error: function (xhr, status, error) {
                            console.error(xhr.responseText);
                            $login_btn.html('Admit');
                            $login_btn.removeClass('disabled');
                            setTimeout( function(){
                              location.reload();
                            }, 5000); 
                        }
                    });

                  } 
                  else {

                    swal("You Clicked Cancel");
                    $login_btn.html('Admit');
                    $login_btn.removeClass('disabled');
                    setTimeout( function(){
                      location.reload();
                    }, 5000);


                  }
              });
        
            });
        });
    </script>
	
</body>

</html>
