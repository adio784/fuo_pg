<?php include 'header.php'; ?>

<div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumb-->
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
		    <h4 class="page-title">Data Tables</h4>
		    <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="admission_home">Home</a></li>
            <li class="breadcrumb-item"><a href="javaScript:void();">Application</a></li>
            <li class="breadcrumb-item active" aria-current="page">All</li>
         </ol>
	   </div>
	   <div class="col-sm-3">
       <div class="btn-group float-sm-right">
        <button type="button" class="btn btn-outline-primary waves-effect waves-light"><i class="fa fa-cog mr-1"></i> Setting</button>
        <button type="button" class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split waves-effect waves-light" data-toggle="dropdown">
        <span class="caret"></span>
        </button>
        <div class="dropdown-menu">
          <a href="javaScript:void();" class="dropdown-item">Action</a>
          <a href="javaScript:void();" class="dropdown-item">Another action</a>
          <a href="javaScript:void();" class="dropdown-item">Something else here</a>
          <div class="dropdown-divider"></div>
          <a href="javaScript:void();" class="dropdown-item">Separated link</a>
        </div>
      </div>
     </div>
     </div>
    <!-- End Breadcrumb-->
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header"><i class="fa fa-table"></i> Data Table Example</div>
            <div class="card-body">
              <div class="table-responsive">
              <table id="default-datatable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>***</th>
                        <th>Application Number</th>
                        <th>Name</th>
                        <!-- <th>Programme</th>
                        <th>Course</th> -->
                        <th>---</th>
                    </tr>
                </thead>
                <tbody>

                        <!-- Iterate thru the existing data -->
                        <?php 
                            $count = 0;
        
                            if ( $getStudents->rowCount() < 1) {

                                echo "<tr> <td align='center'> <strong> No record found !!! </strong> </td> </tr>";

                            } else {
                                while ( $recent = $getStudents->fetchObject() ){
                                    $count ++;
                                    $name   =   strtoupper($recent->last_name) . ' ' . $recent->first_name . ' ' . $recent->middle_name;
                                    $appID  =   $recent->application_id;
                                    $matric =   $recent->matric_no;
                                    $email  =   $recent->email;
                                    $date   =   $recent->created_at;
                        ?>

                        <tr>
                            <td> <?= $count ?> </td>
                            <td> <?= $matric ?></td>
                            <td> <?= $appID ?> </td>
                            <td> <?= $name ?> </td>
                            
                            <td> 
                                <?php if ($matric == "***"){ ?> 
                                <form action="post" id="generateMatric">
                                    <input type="text" class="form-control" name="matricId">
                                    <input type="hidden" class="form-control" name="appId" value="<?= $appID ?>">
                                    <input type="hidden" class="form-control" name="addMatric" value="<?= uniqid() ?>">
                                    <button type="submit" class="btn btn-info" id="getMatricBtn"> <i class="icon-plus"></i> Add</button>
                                </form>
                                <?php } ?>
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
                        <!-- <th>Programme</th>
                        <th>Course</th> -->
                        <th>---</th>
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
            $('#generateMatric').on('submit', function (e) {
                e.preventDefault();

                // Get form data
                var formData = $(this).serialize();
                // alert(dataId);
                $login_btn = $('#getMatricBtn');
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
                        type: 'POST',
                        url: '../app/function/admit_student.php',
                        data: formData,
                        dataType: 'json',
                        success: function (response) {
                       
                        if (response.status == 'success') {
                                    
                            $login_btn.html('Add');
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

                          setTimeout( function(){// wait for 5 secs(2)
                              location.reload(); // then reload the page.(3)
                          }, 5000); 

                        } else {
                            
                            $login_btn.html('Add');
                            $login_btn.removeClass('disabled');
                            $('#overlay').hide();
                            $('#preloader').hide();
                        // console.log(response)
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
                          }, 2000); 

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
        });
    </script>
	
</body>

</html>
