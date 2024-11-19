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
                                            $courseid = $recent->id;
                                            $courseCode = $recent->course_code;
                                            $courseTitle = $recent->course_title;
                                            $courseUnit = $recent->course_unit;
                                            $status = $recent->course_status;
                                            $sem = $recent->semester;
                                            $date   =   $recent->created_at;
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
                                                    <button type="button" id="<?= $courseid ?>" class="removeCourseBtn btn btn-warning shadow-warning waves-effect waves-light m-1"><i class="fa fa-trash font-lg"></i> Remove</a>
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
            var dataId = $(this).attr('id');
            // alert(dataId);
            $login_btn = $('#' + dataId);
            $login_btn.addClass('disabled');

            swal({
                title: 'Do you want Continue ?',
                text: "You are sure to delete this course",
                icon: 'warning',
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

                // } else {
                //   console.log("You clicked cancel")
                // }

            });




        });
    });
</script>




</body>

</html>