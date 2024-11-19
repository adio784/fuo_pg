<?php include 'includes/header.php';


if (isset($_POST['searchCourse'])) {
    ob_end_clean();
    $searchSession = $_POST['searchSession'];
    $getSearchedResultsQr = $db->prepare("
                                    SELECT *
                                    FROM student_results
                                    INNER JOIN departmental_courses ON student_results.course_id = departmental_courses.id
                                    WHERE student_results.student_id = ? AND student_results.result_session = ?");
    $getSearchedResultsQr->execute([$uid, $searchSession]);
    $getSearchedResults  = $getSearchedResultsQr->fetchAll(PDO::FETCH_OBJ);

    if (count($getSearchedResults) > 0) {
        $response['status'] = 'success';
        $response['data']   = $getSearchedResults;
    } else {
        $response['status'] = 'error';
        $response['message'] = 'No course found';
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>

?>

<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Breadcrumb-->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title">Results > > ></h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="student_home">Home</a></li>
                    <li class="breadcrumb-item"><a href="javaScript:void();">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Result</li>
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
                                    <span>Total <br>uploaded result</span>
                                </div>
                                <div class="align-self-center w-circle-icon rounded bg-info shadow-info">
                                    <i class="text-white"><?= count($getTotalResults) ?></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-7 col-xl-7 border-light mt-0">
                        <div class="card-body">
                            <form id="filderResultForm" method="POST" class="mt-0">
                                <div class="media">
                                    <div class="media-body text-left">
                                        <h4 class="text-danger">
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
                                </div>
                            </form>
                        </div>

                    </div>

                </div>
            </div>
        </div>



        <!-- End Breadcrumb-->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header"><i class="fa fa-table"></i> Result for the current session ... </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="default-datatable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>Code</th>
                                        <th>Title</th>
                                        <th>Unit</th>
                                        <th>Session</th>
                                        <th>Semester</th>
                                        <th>CA</th>
                                        <th>Exam</th>
                                        <th>Total</th>
                                        <th>Percentage</th>
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
                                        <th>Session</th>
                                        <th>Semester</th>
                                        <th>CA</th>
                                        <th>Exam</th>
                                        <th>Total</th>
                                        <th>Grade</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
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

            $('#filderResultForm').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    url: 'students_result.php',
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
                                    '<td>' + item.result_session + '</td>' +
                                    '<td>' + item.semester + '</td>' +
                                    '<td>' + item.ca_mark + '</td>' +
                                    '<td>' + item.exam_mark + '</td>' +
                                    '<td>' + item.total + '</td>' +
                                    '<td>' + item.sr_perc + '</td>' +
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