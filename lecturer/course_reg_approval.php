<?php
include 'includes/header.php';
// Get payment histories
$ListQuery   = $db->prepare("
                            SELECT
                            students.matric_no,
                            students.application_id,
                            students.last_name,
                            students.first_name,
                            students.middle_name,
                            students.created_at,
                            programme.programme_title,
                            program_course.course_name,
                            course_registration.course_session,
                            course_registration.created_at,
                            course_registration_approval.course_approval
                            FROM students
                            INNER JOIN programme ON programme.program_id = students.program
                            INNER JOIN program_course ON program_course.id = students.course
                            INNER JOIN course_registration ON students.application_id = course_registration.student_id
                            INNER JOIN course_registration_approval ON course_registration_approval.student_id = students.application_id
                            WHERE students.department = ?
                            GROUP BY course_registration.student_id");
$ListQuery->execute([$departmentId]);



//  Approving Course Registration from here ................................................................................................................................... -->
if (isset($_POST['approveCourse'])) {

    ob_end_clean();
    $studentID = $_POST['approveCourse'];
    $Data = ["approved_by" => $uid, "course_approval" => "approved", "approved_on" => date('Y-m-d H:i:s')];
    $checkCourseExist  = $Crud->update("course_registration_approval", "student_id", $studentID, $Data);

    if ($checkCourseExist) {

        $response['status']   = 'success';
        $response['message']  = "Course successfully approved !!!";
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
// Approving Course registration processing ends here ....................................................................................................................................... -->



//  Approving Course Registration from here ................................................................................................................................... -->
if (isset($_POST['rejectCourse'])) {

    ob_end_clean();
    $studentID = $_POST['rejectCourse'];
    $note = $_POST['editNote'];
    $Data = ["edit_note" => $note, "course_approval" => "edit"];
    $checkCourseExist  = $Crud->update("course_registration_approval", "student_id", $studentID, $Data);

    if ($checkCourseExist) {

        $response['status']   = 'success';
        $response['message']  = "Course successfully approved !!!";
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
// Approving Course registration processing ends here ....................................................................................................................................... -->


// Fetching Course Details for Approval .............................................................................................................................................................................
if (isset($_POST['studentId'])) {
    ob_end_clean(); // Clear any previous output

    $studentId = $_POST['studentId'];

    // Prepare the query
    $ListQuery = $db->prepare("SELECT 
                               departmental_courses.course_code, 
                               departmental_courses.course_title, 
                               departmental_courses.course_status, 
                               departmental_courses.course_unit, 
                               departmental_courses.semester 
                               FROM course_registration 
                               INNER JOIN departmental_courses
                               ON departmental_courses.id = course_registration.course_id
                               WHERE student_id = ? AND course_session = ?
                               ORDER BY departmental_courses.semester ASC");
    $ListQuery->execute([$studentId, $current_session]);

    // Fetch all results as objects
    $Courses = $ListQuery->fetchAll(PDO::FETCH_OBJ);

    // Check if any courses were found
    if ($Courses) {
        echo '<table class="table table-striped table-bordered ">';
        echo '<thead><tr><th>Course Code</th><th>Title</th><th>Status</th><th>Unit</th><th>Semester</th></tr></thead><tbody>';
        foreach ($Courses as $course) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($course->course_code) . '</td>';
            echo '<td>' . htmlspecialchars($course->course_title) . '</td>';
            echo '<td>' . htmlspecialchars($course->course_status) . '</td>';
            echo '<td>' . htmlspecialchars($course->course_unit) . '</td>';
            echo '<td>' . htmlspecialchars($course->semester) . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<p>No courses found for this student.</p>';
    }

    exit(); // Ensure no further processing occurs
}


?>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row pt-3 pb-2">
            <div class="col-sm-12">
                <h4 class="page-title">Course Registration</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="lecturer_home.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Course Registration Approval</li>
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
                                <th>Matric Number</th>
                                <th>Full Name</th>
                                <th>Program</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Placeholder Rows for Testing -->
                            <?php
                            $count = 0;
                            if ($ListQuery->rowcount() < 1) {
                                echo "<tr> <td align='center' colspan='5'> <strong> No record found !!! </strong> </td> </tr>";
                            } else {
                                while ($record = $ListQuery->fetchObject()) {
                                    $count++;
                                    $matricNo       =  $record->matric_no;
                                    $studentId      =  $record->application_id;
                                    $surname        =  $record->last_name;
                                    $firstname      =  $record->first_name;
                                    $middlename     =  $record->middle_name;
                                    $fullname       =  strtoupper($surname) . ' ' . ucfirst($firstname) . ' ' . ucfirst($middlename);
                                    $program        =  ucfirst($record->course_name);
                                    $date           =  $record->created_at;
                                    $courseApproval = $record->course_approval;
                                    $status         =  $record->course_approval == 'approved'
                                        ? 'gradient-quepal text-white'
                                        : ($record->course_approval == 'pending'
                                            ? 'bg-success text-white'
                                            : 'bg-danger text-white');
                            ?>
                                    <tr>
                                        <td><?= $count ?></td>
                                        <td><?= $matricNo ?></td>
                                        <td><?= $fullname ?></td>
                                        <td><?= $program ?></td>
                                        <td><span class="badge <?= $status ?> p-2"><?= ucfirst($courseApproval) ?></span></td>
                                        <td><?= formatDate($date) ?></td>
                                        <td>
                                            <button type="button" id="<?= $studentId ?>" data-id="<?= $studentId ?>" class="viewCourse btn btn-info shadow-info waves-effect waves-light m-1"><i class="fa fa-eye font-lg"></i> View </button>
                                            <button type="button" id="<?= $studentId ?>" data-id="<?= $studentId ?>" class="approveCourse btn btn-primary shadow-info waves-effect waves-light m-1"><i class="fa fa-arrow-up font-lg"></i> Approve</button>
                                            <button type="button" class="btn btn-danger shadow-info waves-effect waves-light m-1" data-toggle="modal" data-target="#defaultsizemodal"><i class="fa fa-arrow-down font-lg"></i> Reject </button>
                                        </td>FPG2426782
                                    </tr>

                                    <div class="modal fade" id="defaultsizemodal">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"><i class="fa fa-star"></i> Course Correction </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="message">Edit Note</label>
                                                        <textarea type="text" class="form-control" id="editNote"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                                                    <button type="button" id="<?= $studentId ?>" data-id="<?= $studentId ?>" class="rejectCourse btn btn-primary"><i class="fa fa-check-square-o"></i> Continue</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php }
                            } ?>
                            <!-- More rows will be dynamically generated -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewCourseModal" tabindex="-1" role="dialog" aria-labelledby="viewCourseModalLabel" aria-hidden="true">
    <div class="modal-lg modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewCourseModalLabel"><i class="fa fa-star"></i> Course Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="courseDetails" class="table-responsive"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
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

            console.log('Filters Applied:', {
                program,
                year,
                search
            });
        });

        $('.approveCourse').on('click', function(e) {
            e.preventDefault();

            // Get form data
            var dataId = $(this).attr('data-id');
            // alert(dataId);
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

                        // Perform AJAX validation
                        $.ajax({
                            type: 'POST',
                            url: 'course_reg_approval.php',
                            dataType: 'json',
                            data: {
                                approveCourse: dataId
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
                        $login_btn.html('<i class="fa fa-arrow-up font-lg"></i> Approve');
                        $login_btn.removeClass('disabled');
                        swal("You cancel approval!");
                    }
                });

        });


        $('.rejectCourse').on('click', function(e) {
            e.preventDefault();

            // Get form data
            var dataId = $(this).attr('data-id');
            var editNote = $("#editNote").val();
            alert(editNote);
            $login_btn = $('#' + dataId);
            $login_btn.addClass('disabled');

            $('#overlay').show();
            $('#preloader').show();
            $login_btn.addClass('disabled');
            $login_btn.html('<div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>');

            // Perform AJAX validation
            $.ajax({
                type: 'POST',
                url: 'course_reg_approval.php',
                dataType: 'json',
                data: {
                    rejectCourse: dataId,
                    editNote: editNote
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
        });


        $('.viewCourse').on('click', function() {
            var studentId = $(this).data('id');
            // alert(studentId);
            $('#courseDetails').html('');
            $('#viewCourseModal .modal-footer .btn-primary').remove();

            // Fetch course details via AJAX
            $.ajax({
                url: 'course_reg_approval.php',
                type: 'POST',
                data: {
                    studentId: studentId
                },
                success: function(response) {

                    console.log(response);
                    $('#courseDetails').html(response);

                    // var approveButton = `<button type="button" class="btn btn-success approveCourse" data-id="${studentId}"><i class="fa fa-check"></i> Approve</button>`;
                    // var rejectButton = `<button type="button" class="btn btn-danger rejectCourse" data-id="${studentId}"><i class="fa fa-times"></i> Reject</button>`;
                    // $('#viewCourseModal .modal-footer').prepend(approveButton + rejectButton);

                },
                error: function() {
                    $('#courseDetails').html('<p style="color: red;">Failed to fetch course details. Please try again later.</p>');
                }
            });

            $('#viewCourseModal').modal('show');
        });
    });
</script>