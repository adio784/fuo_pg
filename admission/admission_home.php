<?php include 'header.php'; ?>

    <div class="clearfix"></div>
	
  <div class="content-wrapper">
    <div class="container-fluid">

      <!--Start Dashboard Content-->
	  
	  <div class="card bg-transparent mt-3 shadow-none border border-light">
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
                <i class="icon-basket-loaded text-white"></i></div>
            </div>
            </div>
			</div>
			<div class="col-12 col-lg-6 col-xl-3 border-light">
			  <div class="card-body">
              <div class="media">
               <div class="media-body text-left">
                <h4 class="text-danger"><?= $Admitted->num ?></h4>
                <span>Not Admitted</span>
              </div>
               <div class="align-self-center w-circle-icon rounded bg-danger shadow-danger">
                <i class="icon-wallet text-white"></i></div>
            </div>
            </div>
			</div>
			<div class="col-12 col-lg-6 col-xl-3 border-light">
			  <div class="card-body">
              <div class="media">
              <div class="media-body text-left">
                <h4 class="text-success"><?= $NotAdmitted->num ?></h4>
                <span>Admitted</span>
              </div>
              <div class="align-self-center w-circle-icon rounded bg-success shadow-success">
                <i class="icon-pie-chart text-white"></i></div>
            </div>
            </div>
			</div>
			<div class="col-12 col-lg-6 col-xl-3 border-light">
			  <div class="card-body">
              <div class="media">
              <div class="media-body text-left">
                <h4 class="text-warning"><?= $Allapp->num ?></h4>
                <span>All Applicant</span>
                <small>session: <?= $current_session ?></small>
              </div>
              <div class="align-self-center w-circle-icon rounded bg-warning shadow-warning">
                <i class="icon-user text-white"></i></div>
            </div>
            </div>
			</div>
		  </div>
		</div>
	  </div>


      <div class="row">
        <div class="col-lg-12">
          <div class="card bg-transparent shadow-none border border-light">
		  <div class="card-header bg-transparent border-0">
                Recent Applicants
				<div class="card-action">
				 <div class="dropdown">
				 <a href="javascript:void();" class="dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown">
				  <i class="icon-options"></i>
				 </a>
				    <div class="dropdown-menu dropdown-menu-right">
						<a class="dropdown-item" href="javascript:void();">All New Students</a>
						<a class="dropdown-item" href="javascript:void();">Admitted</a>
						<a class="dropdown-item" href="javascript:void();">Not Admitted</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="javascript:void();">Separated link</a>
					 </div>
				  </div>
                 </div>
                </div>
               <div class="table-responsive">
                 <table class="table">
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
                        <td><img src="../admission_portal/admissionUploads/<?= $img ?>" class="product-img" alt="product img"></td>
                        <td> <?= $appID ?> </td>
                        <td> <?= $name ?> </td>
                        <td> <span class="badge gradient-quepal text-white shadow p-2"><?= $prog ?></span></td>
                        <td> <?= $cours ?> </td>
                        <td> <?= $date ?> </td>
                        <td> 
                            <button class="btn btn-primary admissionBtn" type="button" id="<?= $appID ?>"> Admit </button> 
                            <!-- <button class="btn btn-primary m-1" data-toggle="modal" data-target="#defaultsizemodal">Default Size Modal</button> -->
                        </td>
                        <!-- Modal -->
                        <!-- <div class="modal fade" id="defaultsizemodal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><i class="fa fa-star"></i> Modal title</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias, dicta. Voluptate cumque odit quam velit maiores sint rerum, dolore impedit commodi. Tempora eveniet odit vero rem blanditiis, tenetur laudantium cumque.</p>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias, dicta. Voluptate cumque odit quam velit maiores sint rerum, dolore impedit commodi. Tempora eveniet odit vero rem blanditiis, tenetur laudantium cumque.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                                <button type="button" class="btn btn-primary"><i class="fa fa-check-square-o"></i> Save changes</button>
                            </div>
                            </div>
                        </div>
                        </div> -->


                    </tr>

                    <?php } } ?>

                    <!-- iteration ends here -->
                   </tbody>


                 </table>
               </div>
          </div>
        </div>
      </div><!--End Row-->

       <!--End Dashboard Content-->

    </div>
    <!-- End container-fluid-->
    
    </div><!--End content-wrapper-->
   <!--Start Back To Top Button-->
    <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
    <!--End Back To Top Button-->
	
	<?php include 'footer.php' ?>

    <script>
    $(document).ready(function () {
        $('.admissionBtn').on('click', function (e) {
            e.preventDefault();

            // Get form data
            var dataId = $(this).attr('id');
            alert(dataId);
            $login_btn = $('#'+dataId);
            $login_btn.addClass('disabled');
            $('#overlay').show();
            $('#preloader').show();
            $login_btn.html('<div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>');

            // Perform AJAX validation
            // $.ajax({
            //     type: 'GET',
            //     url: '../app/function/application_process.php?admit='+, 
			// 	data: formData,
			// 	dataType: 'json',				
            //     success: function (response) {

            //         if (response.status == 'success') {
                       
			// 			$login_btn.html('Admit');
						
            //             Lobibox.notify('success', {
            //                 msg: response.message,
			// 				class: 'lobibox-notify-success',
			// 				title: "Success !",
            //                 position: 'top right',
			// 				icon: 'glyphicon glyphicon-ok-sign',
			// 				sound: 'sound2.mp3',
			// 				delay: 15000,
			// 				theme: 'minimal',
            //             });
                       
            //         } else {
						
            //             $login_btn.html('Admit');
            //             Lobibox.notify('error', {
            //                 msg: response.message,
			// 				class: 'lobibox-notify-error',
			// 				title: 'Error!',
			// 				icon: 'glyphicon glyphicon-remove-sign',
        	// 				sound: 'sound4.mp3',
            //                 position: 'top right',
			// 				theme: 'minimal'
            //             });
						
            //         }
            //     },
			// 	error: function (xhr, status, error) {
			// 			console.error(xhr.responseText);
			// 		}
			// 	});
       
       
            });
    });
</script>
  
</body>

</html>
