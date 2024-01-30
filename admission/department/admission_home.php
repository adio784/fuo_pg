<?php include 'header.php'; ?>

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
                                <button class="btn btn-primary admissionBtn" type="button" id="<?= $appID ?>"> Admit </button>
                                <!-- <a href="../app/function/admit_student.php?admit=<?= $appID ?>" class="btn btn-primary">Admit</a>  -->
                                
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
                // if (result.true) {

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

                            setTimeout( function(){// wait for 5 secs(2)
                              location.reload(); // then reload the page.(3)
                          }, 5000); 

                          }
                        },
                        error: function (xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                
                // } else {
                //   console.log("You clicked cancel")
                // }

                });
            

        
        
            });
        });
    </script>
	
</body>

</html>
