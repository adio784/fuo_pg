<?php
include 'includes/header.php'; ?>

<!-- Deleting Course Registration from here ................................................................................................................................... -->
<?php
// echo $uid;
if (isset($_POST['deleteCourse'])) {
    echo $_POST['deleteCourse'];
    ob_end_clean();
    $courseID = $_POST['deleteCourse'];
    $checkCourseExist  = $Crud->delete("course_registration", $courseID);

    if ($checkCourseExist) {

        $response['status']   = 'success';
        $response['message']  = "Course successfully deleted !!!";
    } else {

        $response['status'] = 'error';
        $response['message'] = 'Error deleting course !!!';
    }

    // Returning JavaScript Object Notation As Response ...............
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
    // ................................................................

}
// Deleting Course registration processing ends here ....................................................................................................................................... -->

// Get total of all registered course ...............................................................................................
$getRegisteredCourseTotal   = $Crud->readAll("course_registration", "student_id", $uid);
// ...................................................................................................................................

// Get all registered courses for the currect session .............................................................................
$getRegisteredQuery   = $db->prepare("
                        SELECT
                        course_registration.*,
                        departmental_courses.course_code,
                        departmental_courses.course_title,
                        departmental_courses.course_unit,
                        departmental_courses.course_status,
                        departmental_courses.semester
                        FROM course_registration
                        INNER JOIN departmental_courses ON course_registration.course_id = departmental_courses.id
                        WHERE course_registration.student_id = ? AND course_registration.course_session = ?");
$getRegisteredQuery->execute([$uid, $current_session]);
$getRegisteredCourses  = $getRegisteredQuery->fetchAll(PDO::FETCH_OBJ);
// ..................................................................................................................................


$getCourseApproval = $Crud->readByTwo("course_registration_approval", "student_id", "$uid", "AND", "course_session", "$current_session");


?>



<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid mb-4">
        <!-- Breadcrumb-->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title">Registered Courses > > ></h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="student_home">Home</a></li>
                    <li class="breadcrumb-item"><a href="javaScript:void();">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Registered courses</li>
                </ol>
            </div>
        </div>



        <!-- End Breadcrumb-->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header"><i class="fa fa-table"></i> All Registered Courses ... </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="default-datatable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>Code</th>
                                        <th>Title</th>
                                        <th>Unit</th>
                                        <th>Status</th>
                                        <th>Semester</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <!-- Iterate thru the existing data -->
                                    <?php
                                    $count = 0;
                                    if (count($getRegisteredCourses) < 1) {

                                        echo "<tr> <td align='center'> <strong> No record found !!! </strong> </td> </tr>";
                                    } else {

                                        foreach ($getRegisteredCourses as $recent) {
                                            $count++;
                                            $courseid       = $recent->id;
                                            $courseCode     = $recent->course_code;
                                            $courseTitle    = $recent->course_title;
                                            $courseUnit     = $recent->course_unit;
                                            $status         = $recent->course_status;
                                            $sem            = $recent->semester;
                                            $date           = $recent->created_at;
                                    ?>

                                            <tr>
                                                <td> <?= $count ?> </td>
                                                <td> <?= $courseCode ?> </td>
                                                <td> <?= $courseTitle ?> </td>
                                                <td> <?= $courseUnit ?> </td>
                                                <td> <span class="badge gradient-quepal text-white shadow p-2"><?= $status ?></span></td>
                                                <td> <?= $sem ?> </td>
                                                <td> <?= formatDate($date) ?> </td>
                                                <td>
                                                    <button type="button" id="<?= $courseid ?>" data-id="<?= $courseid ?>" class="removeCourseBtn btn btn-warning shadow-warning waves-effect waves-light m-1">
                                                        <i class="fa fa-trash font-lg"></i> Remove
                                                        </a>
                                                </td>



                                            </tr>

                                    <?php }
                                    } ?>

                                    <!-- iteration ends here -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>SN</th>
                                        <th>Code</th>
                                        <th>Title</th>
                                        <th>Unit</th>
                                        <th>Status</th>
                                        <th>Semester</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <?php
                if ($getCourseApproval && $getRegisteredCourses) {
                    if ($getCourseApproval->course_approval === 'pending') { ?>

                        <div class="card mt-3 shadow-none border border-light">
                            <div class="card-content">
                                <div class="row row-group m-0">
                                    <div class="col-12 col-lg-2 col-xl-2 border-light">
                                        <div class="card-body">
                                            <div class="media">
                                                <div class="align-self-center w-circle-icon rounded bg-danger shadow-danger text-white">
                                                    <span class="badge"><i class="fa fa-exclamation-triangle text-white"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-10 col-xl-10 border-light">
                                        <div class="card-body">
                                            <div class="media">
                                                <div class="media-body">
                                                    <a class="btn btn-danger shadow-danger waves-effect waves-light ml-1 p-3 text-white">AWAITING COURSE APPROVAL</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php } else { ?>
                        <div class="card mt-3 shadow-none border border-light">
                            <div class="card-content">
                                <div class="row row-group m-0">
                                    <div class="col-12 col-lg-2 col-xl-2 border-light">
                                        <div class="card-body">
                                            <div class="media">
                                                <div class="align-self-center w-circle-icon rounded bg-success shadow-success text-white">
                                                    <span class="badge"><i class="fa fa-info text-white"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-10 col-xl-10 border-light">
                                        <div class="card-body">
                                            <div class="media">
                                                <div class="media-body text-center">
                                                    <a href="pre_payments" class="btn btn-success shadow-success waves-effect waves-light ml-1 p-3"><i class="fa fa-print text-white"></i> Click here to print course form </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
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
        $('.removeCourseBtn').on('click', function(e) {
            e.preventDefault();

            // Get form data
            var dataId = $(this).attr('data-id');
            // alert(dataId);
            $login_btn = $('#' + dataId);
            $login_btn.addClass('disabled');

            swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this course!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {

                        $('#overlay').show();
                        $('#preloader').show();
                        $login_btn.addClass('disabled');
                        $login_btn.html('<div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>');
                        // swal("Poof! Your imaginary file has been deleted!", {
                        //     icon: "success",
                        // });

                        // Perform AJAX validation
                        $.ajax({
                            type: 'POST',
                            url: 'registered_courses.php',
                            dataType: 'json',
                            data: {
                                deleteCourse: dataId
                            },
                            success: function(response) {
                                console.log(response);
                                if (response.status == 'success') {

                                    $login_btn.html('Remove');
                                    $login_btn.removeClass('disabled');
                                    $('#overlay').hide();
                                    $('#preloader').hide();

                                    Lobibox.notify('success', {
                                        msg: response.message,
                                        class: 'lobibox-notify-success',
                                        title: "Success !",
                                        position: 'top right',
                                        icon: true,
                                        icon: 'glyphicon glyphicon-ok-sign',
                                        delay: 1500,
                                        theme: 'minimal',
                                    });
                                    setInterval(() => {
                                        window.location.reload();
                                    }, 1500);

                                } else {

                                    $login_btn.html('Remove');
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
                    } else {

                        $('#overlay').hide();
                        $('#preloader').hide();
                        $login_btn.html('<i class="fa fa-trash font-lg"></i> Remove');
                        $login_btn.removeClass('disabled');
                        swal("You cancel deleting!");
                    }
                });

        });
    });
</script>




</body>

</html>