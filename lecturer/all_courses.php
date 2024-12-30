<?php 
include 'includes/header.php'; 
  // Get payment histories
  $ListQuery   = $db->prepare("
                            SELECT
                            departmental_courses.course_title, 
                            departmental_courses.course_code, 
                            departmental_courses.course_unit, 
                            departmental_courses.course_status, 
                            departmental_courses.semester, 
                            departmental_courses.is_active, 
                            departmental_courses.created_at, 
                            departmental_courses.id,
                            program_course.course_name
                            FROM departmental_courses 
                            INNER JOIN program_course
                            ON departmental_courses.pg_course = program_course.id
                            WHERE departmental_courses.department_id = ?");
  $ListQuery->execute([$departmentId]);

//  Delete course starts from here ................................................................................................................................... -->
if (isset($_POST['deleteCourse'])) {

    ob_end_clean();
    $requestID = $_POST['deleteCourse'];
    $checkCourseExist  = $Crud->delete('departmental_courses', 'id', $requestID);

    if ($checkCourseExist) {

        $response['status']   = 'success';
        $response['message']  = "Course successfully deleted !!!";
    } else {

        $response['status'] = 'error';
        $response['message'] = 'Error approving course !!!';
    }

    // Returning JavaScript Object Notation As Response ...............
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
    // ................................................................

}
// Delete course ends here ....................................................................................................................................... -->

  
?>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row pt-3 pb-2">
            <div class="col-sm-12">
                <h4 class="page-title">Course Record</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="lecturer_home.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Course List</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Students Table -->
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="lecturerTable">
                        <thead class="table-primary">
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Code</th>
                                <th>Status</th>
                                <th>Unit</th>
                                <th>Semester</th>
                                <th>Program</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Placeholder Rows for Testing -->
                            <?php
                                $count = 0;
                                if ($ListQuery->rowcount() < 1) {
                                    echo "<tr> <td align='center'> <strong> No record found !!! </strong> </td> </tr>";
                                } else {
                                    while ($record = $ListQuery->fetchObject()) {
                                        $count++;
                                        $courseId   =  $record->id;
                                        $title   =  $record->course_title;
                                        $code  =  $record->course_code;
                                        $unit     =  $record->course_unit;
                                        $stat     =  $record->course_status;
                                        $semester     =  ucfirst($record->semester);
                                        $program         =  $record->course_name;
                                        $status      =  ($record->is_active == 1) ? 'Active' : 'Inactive';
                                        $smStatus      =  $record->is_active == 'first' 
                                                            ? 'gradient-quepal text-white' 
                                                            : ($record->is_active == 'second' 
                                                                ? 'bg-success text-white' 
                                                                : 'bg-danger text-white');
                                    
                                        // $smStatus      =  ($record->is_active == 1) ? 'gradient-quepal text-white' : 'bg-danger text-white';
                                        $date           =  $record->created_at;
                                ?>
                            <tr>
                                <td><?= $count ?></td>
                                <td><?= $title ?></td>
                                <td><?= $code ?></td>
                                <td><?= $stat ?></td>
                                <td><?= $unit ?></td>
                                <td><span class="badge <?=$bagStatus?>"> <?= $semester ?> </span></td>
                                <td><?= $program ?></td>
                                <td>
                                    <a href="edit_course.php?course_id=<?= encodeId($courseId) ?>" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i> Edit</a>
                                    <button type="button" id="<?= $courseId ?>" data-id="<?=$courseId ?>" class="deleteCourse btn btn-sm btn-danger shadow-info waves-effect waves-light m-1"><i class="fa fa-trash font-lg"></i> Delete</button>
                                </td>
                            </tr>
                            <?php } } ?>
                            <!-- More rows will be dynamically generated -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#lecturerTable').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true
        });

        // Filter logic (future enhancement with AJAX)
        $('#applyFilters').on('click', function() {
            const program = $('#filterProgram').val();
            const year = $('#filterYear').val();
            const search = $('#searchInput').val();

            console.log('Filters Applied:', { program, year, search });
        });

        // Delete Course ....................................
        $('.deleteCourse').on('click', function(e) {
            e.preventDefault();

            // Get form data
            var dataId = $('.deleteCourse').data('id');
            $login_btn = $('#' + dataId);
            $login_btn.addClass('disabled');

                swal({
                    title: "Are you sure?",
                    text: "You will not be able to undo changes!",
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

                        // Perform Course Deletion .....................
                        $.ajax({
                            type: 'POST',
                            url: 'all_courses.php',
                            dataType: 'json',
                            data: {
                                deleteCourse: dataId,
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
                        $login_btn.html('<i class="fa fa-arrow-up font-lg"></i> Delete');
                        $login_btn.removeClass('disabled');
                        swal("You cancel deleteing!");
                    }
                });
        });
    });
</script>
