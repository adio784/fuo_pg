<?php include 'includes/header.php'; ?>
<?php
//  Get list of courses for this student in this program .................................
// $getAdminFee           = $Crud->readAllByThree("departmental_courses", "is_active", 1, "AND", "department", $departmentId, 'AND', 'pg_course', $courseId);

// Get list of school fee and administartive fee for this student in this program .................................
$getSchoolFees          = $Crud->readAllByThree("payments_history", "payment_status", 1, "AND", "payment_desc", 'School fee', 'AND', 'payment_session', $current_session);
$getAdminFees           = $Crud->readAllByThree("payments_history", "payment_status", 1, "AND", "payment_desc", 'Administrative fee', 'AND', 'payment_session', $current_session);
$getProgramFees         = $Crud->readByTwo("programme", "is_active", 1, "AND", "program_id", "$programId");

$sumSchoolFee = 0;
$sumAdminFee = 0;

foreach ($getSchoolFees as $schoolFee) {
  $sumSchoolFee += $schoolFee->amount_paid;
}

foreach ($getAdminFees as $adminFee) {
  $sumAdminFee += $adminFee->amount_paid;
}
var_export($totalFeesToPay);
// $totalFeesToPay  = $getProgramFees->school_fee;
// $percentagePaid = ($totalFeesPaid / $totalExpectedFees) * 100;

// Get all registered courses for the currect session .............................................................................
$getRegisteredQuery   = $db->prepare("
                        SELECT *
                        FROM course_registration
                        INNER JOIN departmental_courses ON course_registration.course_id = departmental_courses.id
                        WHERE course_registration.student_id = ? AND course_registration.course_session = ?");
                        $getRegisteredQuery->execute([$uid, $current_session]);
                        $getRegisteredCourses  = $getRegisteredQuery->fetchAll(PDO::FETCH_OBJ);
// ..................................................................................................................................

?>




<div class="clearfix"></div>

<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumb-->
    <div class="row pt-2 pb-2">
      <div class="col-sm-9">
        <h4 class="page-title">Course Registration > > > <?= $course ?></h4>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="student_home">Home</a></li>
          <li class="breadcrumb-item"><a href="javaScript:void();">Students</a></li>
          <li class="breadcrumb-item active" aria-current="page">Course registration</li>
        </ol>
      </div>
    </div>

    <!-- Processing Course Registration from here ................................................................................................................................... -->
    <?php
    // echo $uid;
    if (isset($_POST['courseForm'])) {
      ob_end_clean();
      $courseID = implode(',', ($_POST['courseId']));
      foreach ($_POST['courseId'] as $key => $value) {
        $course_Value = $value;
        $checkCourseExist  = $Crud->readAllByThree("course_registration", "student_id", $uid, "AND", "course_id", $course_Value, 'AND', 'course_session', $current_session);

        if (count($checkCourseExist) > 0) {

          $response['status']   = 'error';
          $response['message']  = "Some course(s) already registered for this session";
        } else {

          $Data         = [
            "student_id"      => $uid,
            "course_id"       => $course_Value,
            "course_session"  => $current_session
          ];

          $createPayment      =  $Crud->create('course_registration', $Data);
          $response['status'] = 'success';
          $response['message'] = 'Course successfully registered';
        }
      }

      // Returning JavaScript Object Notation As Response ...............
      header('Content-Type: application/json');
      echo json_encode($response);
      exit();
      // ................................................................

    }
    ?>
    <!-- Course registration processing ends here ....................................................................................................................................... -->
    
    <?php if (isset($_GET['error'])) { ?>

      <!-- Error course begins from here ------------------------------------------------------------------------ -->
      <div class="card mt-3 shadow-none border border-light">
        <div class="card-content">
          <div class="row row-group m-0">
            <div class="col-12 col-lg-2 col-xl-2 border-light">
              <div class="card-body">
                <div class="media">
                  <div class="align-self-center w-circle-icon rounded bg-danger shadow-danger">
                    <i class="icon-caution text-white"></i>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-lg-10 col-xl-10 border-light">
              <div class="card-body">
                <div class="media">
                  <div class="media-body text-center">
                    <p>You need to make at least 25% of your school fee and administrative fee of N25,000 only to enable you access the course registration</p>
                    <a href="pre_payments" class="btn btn-info shadow-info waves-effect waves-light ml-1 p-3"><i class="fa fa-plus text-white"></i> Click here to make payment </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Error course ends here ................................................................................. -->

      <?php }else{ var_export($sumSchoolFee)?>


      <!-- Course registration begins from here ------------------------------------------------------------------------ -->
    <form method="post" action="" id="courseForm">
      <input type="hidden" name="courseForm" value="<?= uniqid() ?>">
      <div class="card mt-3 shadow-none border border-light">
        <div class="card-content">
          <div class="row row-group m-0">
            <div class="col-12 col-lg-5 col-xl-3 border-light">
              <div class="card-body">
                <div class="media">
                  <div class="media-body text-left">
                    <h4 class="text-info" id="totalCourse">0</h4>
                    <span>Total Selection</span>
                  </div>
                  <div class="align-self-center w-circle-icon rounded bg-info shadow-info">
                    <i class="icon-basket-loaded text-white"></i>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-lg-7 col-xl-7 border-light">
              <div class="card-body">
                <div class="media">
                  <div class="media-body text-left">
                    <button type="submit" id="addCourseBtn" class="btn btn-info shadow-info waves-effect waves-light ml-1 p-3"><i class="fa fa-plus text-white"></i> Add Courses </button>
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
            <div class="card-header"><i class="fa fa-table"></i> Course Registration</div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th width="70px"><input type="checkbox" id="checkAll" class="form-control"> </th>
                      <th>Code</th>
                      <th>Title </th>
                      <th>Unit</th>
                      <th>Status</th>
                      <th>Semester</th>
                    </tr>
                  </thead>
                  <tbody>

                    <!-- Iterate thru the existing data -->
                    <?php

                    if ($getCourse < 1) {

                      echo "<tr> <td align='center' colspan=6> <strong> No record found !!! </strong> </td> </tr>";
                   
                    } else {
                      foreach ($getCourse as $recent) {
                        $courseId     =   $recent->id;
                        $courseCode   =   $recent->course_code;
                        $courseTitle  =   $recent->course_title;
                        $status       =   $recent->course_status;
                        $courseUnit   =   $recent->course_unit;
                        $semester     =   $recent->semester;
                        $date         =   $recent->created_at;
                    ?>

                        <tr>
                          <td> <input type="checkbox" class="courseId form-control" name="courseId[]" data-id="<?= 'course' . $courseId ?>" value="<?= $courseId ?>"> </td>
                          <td> <?= $courseCode ?> </td>
                          <td> <?= $courseTitle ?> </td>
                          <td> <?= $courseUnit ?></td>
                          <td> <?= $status ?></td>
                          <td> <?= $semester ?> </td>


                        </tr>

                    <?php }
                    } ?>

                    <!-- iteration ends here -->
                  </tbody>
                  <tfoot>
                    <tr>
                      <th>*</th>
                      <th>Code</th>
                      <th>Title </th>
                      <th>Unit</th>
                      <th>Status</th>
                      <th>Semester</th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div><!-- End Row-->

    </form>
    <!-- Course registration ends here -->
     <?php } ?>

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


    var table = $('#example').DataTable({
      lengthChange: false,
      buttons: ['copy', 'excel', 'pdf', 'print', 'colvis']
    });

    table.buttons().container()
      .appendTo('#example_wrapper .col-md-6:eq(0)');

  });
</script>

<script>
  $(document).ready(function() {
    $('.admissionBtn').on('click', function(e) {
      e.preventDefault();

      // Get form data
      var dataId = $(this).attr('id');
      // alert(dataId);
      $login_btn = $('#' + dataId);
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
          url: '../app/function/admit_student.php?admit=' + dataId,
          dataType: 'json',
          success: function(response) {
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
          error: function(xhr, status, error) {
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

<script>
  $(document).ready(function() {
    $('.courseId').on('change', function() {

      var mycheck_val = $(this).attr('data-id');
      var existingCourse = parseInt($('#totalCourse').val()) || 0;
      var total_course = existingCourse;
      var checkedBox = $('#' + mycheck_val);
      updateCounterFee();

    });


    $("#checkAll").click(function() {
      $("input[type=checkbox]").prop('checked', $(this).prop('checked'));
      updateCounterFee();
    });


    function updateCounterFee() {
      var checkedFees = $('.courseId:checked').length;
      $('#totalCourse').text(checkedFees);
    }

  })
</script>

<script>
  $(document).ready(function() {

    // $("#noticeModal").click();
    $('#courseForm').submit(function(e) {
      e.preventDefault();

      // Get form data
      var formData = $(this).serialize();
      $login_btn = $('#addCourseBtn');
      $login_btn.addClass('disabled');
      $login_btn.html('<div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>');

      // Perform AJAX validation
      $.ajax({
        type: 'POST',
        url: 'course_registration.php',
        data: formData,
        success: function(response) {
        // console.log(response);
          if (response.status == 'success') {

            document.getElementById("courseForm").reset();
            $login_btn.html('Add Courses');

            Lobibox.notify('success', {
              msg: response.message,
              class: 'lobibox-notify-success',
              title: "Success !",
              position: 'top right',
              icon: false,
              icon: 'glyphicon glyphicon-ok-sign',
              sound: 'sound2.mp3',
              delay: 15000,
              theme: 'minimal',
            });

            var nextLogin = response.user_role;

          } else {

            $login_btn.html('Add Courses');
            $login_btn.removeClass('disabled');

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
        }
      });
    });
  });
</script>

</body>

</html>