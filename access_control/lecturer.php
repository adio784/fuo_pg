<?php include "includes/header.php"; ?>

<?php
// echo $page_path;
// ........ LECTURER SESSION .............................................................................
// $lecturer_session    = isset($_POST['lecturer_session']) ? $_POST['lecturer_session'] : $current_session;
// if (!isset($_POST['lecturer_session']) || isset($_POST['lecturer_session']) == "" && $page_path == "lecturer.php") {
//     echo "<script> alert('Lecturer session empty'); window.location = 'lecturer'; </script>";
// }

if (isset($_POST['addLecturer'])) {
    ob_end_clean();

    $staffId        = $Sanitizer->sanitizeInput($_POST['staff_id']);
    $title          = $Sanitizer->sanitizeInput($_POST['title']);
    $surname        = $Sanitizer->sanitizeInput($_POST['surname']);
    $onames         = $Sanitizer->sanitizeInput($_POST['onames']);
    $email          = $Sanitizer->sanitizeInput($_POST['email']);
    $phone          = $Sanitizer->sanitizeInput($_POST['phone']);
    $gender         = $Sanitizer->sanitizeInput($_POST['gender']);
    $departmentId   = $Sanitizer->sanitizeInput($_POST['department']);
    $isHod          = $Sanitizer->sanitizeInput($_POST['isHod']);

    $chLecturerExist = $Crud->readAllBy("lecturer", "email", $email);

    if (count($chLecturerExist) > 0) {

        $response['status']   = 'error';
        $response['message']  = "Lecturer already exists !!!";
    } else {

        $Data         = [
            "title"         => $title,
            "staff_id"      => $staffId,
            "surname"       => $surname,
            "othernames"    => $onames,
            "email"         => $email,
            "phone"         => $phone,
            "gender"        => $gender,
            "department_id" => $departmentId,
            "is_hod"        => $isHod
        ];

        $createLecturer         =  $Crud->create('lecturer', $Data);
        $createLecturer         =  $Crud->create('users', ['username' => $email, 'password' => $surname, 'email' => $email, 'role' => 'lecturer', 'status' => 'active']);
        $response['status']     = 'success';
        $response['message']    = 'Lecturer successfully profiled';
    }

    // Returning JavaScript Object Notation As Response ...............
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
    // ................................................................

}
?>

<div class="row pt-2 pb-2">
    <div class="col-sm-9">
        <h4 class="page-title">Lecturers </h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javaScript:void();">Home</a></li>
            <li class="breadcrumb-item"><a href="javaScript:void();">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Lecturer</li>
        </ol>
    </div>
    <div class="col-sm-3">
        <div class="btn-group float-sm-right">
            <button type="button" class="btn btn-outline-primary waves-effect waves-light" data-toggle="modal" data-target="#successmodal"><i class="fa fa-user mr-1"></i> Add New </button>
        </div>
    </div>
</div>
<!-- End Breadcrumb-->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header"><i class="fa fa-table"></i> Data Table Example</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="default-datatable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Name</th>
                                <th>Staff ID</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>Department</th>
                                <th>Is HOD</th>
                                <th>Date Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ( count($Lecturers) < 1) {
                                echo "<p class='text-center'>No Record Found</p>";
                            } else {
                                foreach ($Lecturers as $key => $value) {
                                    $key++;
                                    ($value->is_hod == 1) ? $value->is_hod = 'Yes' : $value->is_hod = 'No';
                                    ($value->is_hod == 'Yes') ? $badge = 'success' : $badge = 'danger';
                        
                                    echo "<tr>
                                        <td>$key</td>
                                        <td>{$value->surname} {$value->othernames}</td>
                                        <td>{$value->staff_id}</td>
                                        <td>{$value->email}</td>
                                        <td>{$value->phone}</td>
                                        <td>{$value->gender}</td>
                                        <td>{$value->department_id}</td>
                                        <td>
                                            <span class='badge badge-{$badge} p-2'>{$value->is_hod}</span>
                                        </td>
                                        <td>{$value->created_at}</td> ";
                                }
                            }
                            ?>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th>SN</th>
                                <th>Name</th>
                                <th>Staff ID</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>Department</th>
                                <th>Is HOD</th>
                                <th>Date Created</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div><!-- End Row-->

<!--Add Lecturer  Modal -->
<div class="modal fade" id="successmodal">
    <div class="modal-dialog">
        <form method="post" id="addLecturerForm">
            <div class="modal-content border-success">
                <div class="modal-header bg-success">
                    <h5 class="modal-title text-white"><i class="fa fa-star"></i> Add New Lecturer</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="staffId"> Staff ID </label>
                        <input type="hidden" name="addLecturer" value="<?= uniqid() ?>">
                        <input type="text" name="staff_id" id="staffId" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="title"> Title </label>
                        <select name="title" id="title" class="form-control">
                            <option value="" selected> -- - -- </option>
                            <option value="mr"> Mr. </option>
                            <option value="mrs"> Mrs.</option>
                            <option value="miss"> Miss. </option>
                            <option value="dr"> Dr. </option>
                            <option value="Prof."> Prof. </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="surname"> Surname </label>
                        <input type="text" name="surname" id="surname" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="onames"> Other Names </label>
                        <input type="text" name="onames" id="onames" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="email"> Email Address </label>
                        <input type="email" name="email" id="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="phone"> Phone Number </label>
                        <input type="tel" name="phone" id="phone" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="gender"> Gender </label>
                        <select name="gender" id="gender" class="form-control">
                            <option value="" selected> -- - -- </option>
                            <option value="male"> Male </option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="department"> Department </label>
                        <select name="department" id="department" class="form-control">
                            <option value="" selected> -- - -- </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="isHod"> Is HOD ? </label>
                        <select name="isHod" id="isHod" class="form-control">
                            <option value="" selected> -- - -- </option>
                            <option value="1"> Yes </option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-inverse-success" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    <button type="submit" class="btn btn-success" id="addLecturerBtn"><i class="fa fa-check-square-o"></i> Submit</button>
                </div>
            </div>
        </form>
    </div>
</div><!--End Modal -->

<?php include "includes/footer.php"; ?>

<script>
    $(document).ready(function() {

        // $("#noticeModal").click();
        $('#addLecturerForm').submit(function(e) {
            e.preventDefault();

            // Get form data
            var formData = $(this).serialize();
            $login_btn = $('#addLecturerBtn');
            $login_btn.addClass('disabled');
            $login_btn.html('<div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>');

            // Perform AJAX validation
            $.ajax({
                type: 'POST',
                url: 'lecturer.php',
                data: formData,
                success: function(response) {
                    // console.log(response);
                    if (response.status == 'success') {

                        document.getElementById("courseForm").reset();
                        $login_btn.html('Add Lecturer');

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

                        $login_btn.html('Add Lecturer');
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