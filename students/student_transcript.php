<?php include 'includes/header.php'; ?>

<div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumb-->
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
		    <h4 class="page-title">Transcript > > ></h4>
		    <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="student_home">Home</a></li>
            <li class="breadcrumb-item"><a href="javaScript:void();">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Transcript</li>
         </ol>
	   </div>
     </div>
    


    <!-- End Breadcrumb-->
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header"><i class="fa fa-table"></i> Transcript for the all session  ... </div>
            <div class="card-body">
              <div class="table-responsive">
              <table id="default-datatable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Code</th>
                        <th>Title</th>
                        <th>Unit</th>
                        <th>Semester</th>
                        <th>Session</th>
                        <th>Score</th>
                        <th>Grade</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <th>SN</th>
                        <th>Code</th>
                        <th>Title</th>
                        <th>Unit</th>
                        <th>Semester</th>
                        <th>Session</th>
                        <th>Score</th>
                        <th>Grade</th>
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
	


    <?php include "includes/footer.php" ?>

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
