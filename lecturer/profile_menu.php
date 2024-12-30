<?php
include 'includes/header.php';
// Get payment histories
$ListQuery   = $db->prepare("
                            SELECT *,
                            program_course.course_name
                            FROM departmental_courses 
                            INNER JOIN program_course
                            ON departmental_courses.pg_course = program_course.id
                            WHERE departmental_courses.department_id = ?");
$ListQuery->execute([$departmentId]);

$Programs = $Crud->readAll('programme');
$Courses  = $Crud->readAllBy('program_course', 'department_id', $departmentId);
?>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row pt-3 pb-2">
            <div class="col-sm-12">
                <h4 class="page-title">Profile</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="lecturer_home.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">My Account</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Students Table -->
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Profile</h4>
                            </div>
                            <div class="card-body">
                                <form action="" method="post" id="updateProfileForm">
                                    <div class="form-group">
                                        <label for="surname">Surname</label>
                                        <input type="hidden" name="lecturerID" value="<?= $lecturerId ?>">
                                        <input type="text" name="surname" id="surname" class="form-control" value="<?= $usurname ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="otherNames">Other Names</label>
                                        <input type="text" name="otherNames" id="otherNames" class="form-control" value="<?= $uothernames ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="text" name="email" id="email" class="form-control" value="<?= $email ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Phone Number</label>
                                        <input type="text" name="phone" id="phone" class="form-control" value="<?= $phone ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="gender">Gender</label>
                                        <select name="gender" id="gender" class="form-control" required>
                                            <option value="" selected>-- -- </option>
                                            <option value="male" <?php if ($gender == 'male') {
                                                                        echo 'selected';
                                                                    } ?>>Male</option>
                                            <option value="female" <?php if ($gender == 'female') {
                                                                        echo 'selected';
                                                                    } ?>>Female</option>
                                            <option value="other" <?php if ($gender == 'other') {
                                                                        echo 'selected';
                                                                    } ?>>Other</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="maritalStatus">Marital Status</label>
                                        <select name="maritalStatus" id="maritalStatus" class="form-control" required>
                                            <option value="" selected>-- -- </option>
                                            <option value="single" <?php if ($maritalStatus == 'single') {
                                                                        echo 'selected';
                                                                    } ?>>Single</option>
                                            <option value="married" <?php if ($maritalStatus == 'married') {
                                                                        echo 'selected';
                                                                    } ?>>Married</option>
                                            <option value="divorsed" <?php if ($maritalStatus == 'divorsed') {
                                                                            echo 'selected';
                                                                        } ?>>Divorsed</option>
                                        </select>
                                    </div>


                                    <div class="form-group">
                                        <input type="hidden" name="updateProfile" value="<?php echo rand() ?>">
                                        <button type="submit" id="updateProfileBtn" class="btn btn-primary wave-effect effect-light"> <i class="fa fa-arrow-right"></i> Update Profile</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Update Password</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-12">
                                        <form method="post" id="changePasswordForm">
                                            <div class="form-group">
                                                <label for="oldPassword" class="mb-4">Old Password</label>
                                                <input type="password" name="oldPassword" id="oldPassword" class="form-control">
                                                <input type="hidden" name="userID" value="<?= $uid ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="newPassword" class="mb-4">New Password</label>
                                                <input type="password" name="newPassword" id="newPassword" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="confirmPassword" class="mb-4">Confirm Password</label>
                                                <input type="password" name="confirmPassword" id="confirmPassword" class="form-control">
                                            </div>

                                            <div class="form-group mt-4">
                                                <input type="hidden" name="changePassword" value="<?php echo rand() ?>">
                                                <button type="submit" class="btn btn-secondary" id="changePasswordBtn"><i class="fa fa-arrow-up"></i> Change Password</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
    $(document).ready(function() {
        $('#updateProfileForm').submit(function(e) {
            e.preventDefault();

            // Get form data
            var formData = $(this).serialize();
            $login_btn = $('#updateProfileBtn');
            $login_btn.addClass('disabled');
            $login_btn.html('<div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>');

            // Perform AJAX validation
            $.ajax({
                type: 'POST',
                url: '../app/function/lecturer_actions.php',
                data: formData,
                dataType: 'json',
                success: function(response) {

                    if (response.status == 'success') {

                        $('#updateProfileForm')[0].reset();
                        $login_btn.removeClass('disabled');
                        $login_btn.html('<i class="fa fa-arrow-right"></i> Add Course');
                        Lobibox.notify('success', {
                            msg: response.message,
                            class: 'lobibox-notify-success',
                            title: "Success !",
                            position: 'top right',
                            icon: 'glyphicon glyphicon-ok-sign',
                            sound: 'sound2.mp3',
                            delay: 15000,
                            theme: 'minimal',
                        });

                    } else {
                        $login_btn.removeClass('disabled');
                        $login_btn.html('<i class="fa fa-arrow-right"></i> Add Course');
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
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        // Upload Course ..............................................
        $('#changePasswordForm').submit(function(e) {
            e.preventDefault();

            // Get form data
            var formData = $(this).serialize();
            $login_btn = $('#changePasswordBtn');
            $login_btn.addClass('disabled');
            $login_btn.html('<div class="spinner-border spinner-border-sm text-secondary" role="status"> <span class="visually-hidden">Loading...</span></div>');

            // Perform AJAX validation
            $.ajax({
                type: 'POST',
                url: '../app/function/lecturer_actions.php',
                data: formData,
                dataType: 'json',
                success: function(response) {

                    if (response.status == 'success') {

                        $('#changePasswordForm')[0].reset();
                        $login_btn.removeClass('disabled');
                        $login_btn.html('<i class="fa fa-arrow-right"></i> Add Course');
                        Lobibox.notify('success', {
                            msg: response.message,
                            class: 'lobibox-notify-success',
                            title: "Success !",
                            position: 'top right',
                            icon: 'glyphicon glyphicon-ok-sign',
                            sound: 'sound2.mp3',
                            delay: 15000,
                            theme: 'minimal',
                        });

                    } else {
                        $login_btn.removeClass('disabled');
                        $login_btn.html('<i class="fa fa-arrow-right"></i> Add Course');
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
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>