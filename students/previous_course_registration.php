<?php
include 'includes/header.php';

if (isset($_POST['searchCourse'])) {
    ob_end_clean();
    $searchSession = $_POST['searchSession'];
    $getSearchedCoursesQr = $db->prepare("
                                    SELECT *
                                    FROM course_registration
                                    INNER JOIN departmental_courses ON course_registration.xourse_id = departmental_courses.id
                                    WHERE course_registration.student_id = ? AND course_registration.course_session = ?");
    $getSearchedCoursesQr->execute([$uid, $searchSession]);
    $getSearchedCourses  = $getSearchedCoursesQr->fetchAll(PDO::FETCH_OBJ);

    if (count($getSearchedCourses) > 0) {
        $response['status'] = 'success';
        $response['data'] = $getSearchedCourses;
    } else {
        $response['status'] = 'error';
        $response['message'] = 'No course found';
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>

<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid mb-4">
        <!-- Breadcrumb-->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title">Previous Registered Courses > > ></h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="student_home">Home</a></li>
                    <li class="breadcrumb-item"><a href="javaScript:void();">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Registered courses</li>
                </ol>
            </div>
        </div>

        <div class="card mt-3 shadow-none border border-light">
            <div class="card-content">
                <div class="row row-group m-0">
                    <div class="col-12 col-lg-5 col-xl-3 border-light">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body text-left">
                                    <span>Total <br>Registered</span>
                                </div>
                                <div class="align-self-center w-circle-icon rounded bg-info shadow-info">
                                    <i class="text-white"><?= count($getRegisteredCourseTotal) ?></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-7 col-xl-7 border-light">
                        <div class="card-body">
                            <div class="media">

                                <div class="media-body text-left">
                                    <h4 class="text-danger">
                                        <form id="filderCourseForm" method="POST">
                                            <select name="searchSession" id="" class="form-control">
                                                <option value="" selected> -- - --</option>
                                                <?php
                                                for ($payYr = 2024; $payYr <= date('Y'); $payYr++) {
                                                    $paySession = $payYr - 1 . '/' . $payYr;
                                                ?>
                                                    <option value="<?= $paySession ?>"> <?= $paySession ?></option>

                                                <?php  }
                                                ?>
                                            </select>
                                    </h4>
                                    <strong>Filter Sessions </strong>
                                </div>
                                <input type="hidden" name="searchCourse" value="<?= uniqid() ?>">
                                <button type="submit" class="btn btn-info shadow-info waves-effect waves-light ml-1" id="filterBtn"> Proceed </button>
                                </form>
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
                                    </tr>
                                </thead>
                                <tbody>

                                    <!-- Iterate thru the existing data -->
                                    <?php
                                    $count = 0;

                                    // if ($rcount < 1) {

                                    //     echo "<tr> <td align='center'> <strong> No record found !!! </strong> </td> </tr>";
                                    // } else {
                                    //     while ($recent = $rquery->fetch(PDO::FETCH_OBJ)) {
                                    //         $count++;
                                    //         $name   =   strtoupper($recent->last_name) . ' ' . $recent->first_name . ' ' . $recent->middle_name;
                                    //         $appID  =   $recent->application_id;
                                    //         $email  =   $recent->email;
                                    //         $prog   =   $recent->programme_title;
                                    //         $cours  =   $recent->course_name;
                                    //         $date   =   $recent->created_at;
                                    //         $img    =   $recent->passport;
                                    ?>

                                    <?php
                                    //     }
                                    //} 
                                    ?>

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

        // Load courses base on selected session .........................................

        $('#filderCourseForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: 'previous_course_registration.php',
                type: 'POST',
                dataType: 'json',
                data: formData,
                success: function(response) {
                    console.log(response);
                    if (response.status === 'success') {

                        // Clear the existing table body
                        var payload = response.data;
                        $('#default-datatable tbody').empty();
                        payload.forEach(function(item, index) {
                            var row = '<tr>' +
                                '<td>' + (index + 1) + '</td>' +
                                '<td>' + item.course_code + '</td>' +
                                '<td>' + item.course_title + '</td>' +
                                '<td>' + item.course_unit + '</td>' +
                                '<td>' + item.course_status + '</td>' +
                                '<td>' + item.semester + '</td>' +
                                '</tr>';
                            $('#default-datatable tbody').append(row);
                        });
                    } else {
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
        });


    });
</script>

</body>

</html>