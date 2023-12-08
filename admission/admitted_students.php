<?php include 'header.php'; ?>

<div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumb-->
     <div class="row pt-2 pb-2">
        <div class="col-sm-12">
            <h4 class="page-title">Admitted Students ... </h4>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="admission_home">Home</a></li>
              <li class="breadcrumb-item"><a href="javaScript:void();">Application</a></li>
              <li class="breadcrumb-item active" aria-current="page">Admitted</li>
            </ol>
        </div>
     </div>
    <!-- End Breadcrumb-->
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header"><i class="fa fa-table"></i> List of Admitted Students </div>
            <div class="card-body">
              <div class="table-responsive">
              <table id="default-datatable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>***</th>
                        <th>Application Number</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Programme</th>
                        <th>Course</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>

                        <!-- Iterate thru the existing data -->
                        <?php 
                                $count = 0;

                                if ($rcount < 1) {

                                    echo "<tr> <td align='center'> <strong> No record found !!! </strong> </td> </tr>";

                                } else {
                                    while ($recent = $getAdmitted->fetchObject()) {
                                        $count ++;
                                        $name   =   strtoupper($recent->last_name) . ' ' . $recent->first_name . ' ' . $recent->middle_name;
                                        $appID  =   $recent->application_id;
                                        $email  =   $recent->email;
                                        $status =   $recent->application_status;
                                        $prog   =   $recent->programme_title;
                                        $cours  =   $recent->course_name;
                                        $date   =   $recent->created_at;
                                        $img    =   $recent->passport;
                        ?>

                        <tr>
                            <td> <?= $count ?> </td>
                            <td><img src="../admission_portal/admissionUploads/<?= $img ?>" class="product-img" alt="product img"></td>
                            <td> <?= $appID ?> </td>
                            <td> <?= $name ?> </td>
                            <td> <span class="badge gradient-quepal text-white shadow p-2"><?= $status ?></span></td>
                            <td> <span class="badge gradient-quepal text-white shadow p-2"><?= $prog ?></span></td>
                            <td> <?= $cours ?> </td>
                            <td> <?= formatDate($date) ?> </td>
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
                        <th>Status</th>
                        <th>Programme</th>
                        <th>Course</th>
                        <th>Date</th>
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
	
</body>

<!-- white-version/table-data-tables.html  Wed, 31 Oct 2018 03:48:52 GMT -->
</html>
