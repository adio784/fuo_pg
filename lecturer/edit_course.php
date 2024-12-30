<?php 
include 'includes/header.php'; 
  // Get payment histories
  $courseId = decodeId($_GET['course_id']);
  $ListQuery   = $db->prepare("SELECT * FROM departmental_courses WHERE id = ? LIMIT 1");
  $ListQuery->execute([$courseId]);
  $Course = $ListQuery->fetchObject();

  $Programs = $Crud->readAll('programme');
  $Courses  = $Crud->readAllBy('program_course', 'department_id', $departmentId);




?>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row pt-3 pb-2">
            <div class="col-sm-12">
                <h4 class="page-title">Course Record</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="lecturer_home.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Course</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Students Table -->
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <div class="card">
                            <div class="card-header"> 
                                <h4 class="card-title">Edit <?= $Course->course_title?></h4>   
                            </div>
                            <div class="card-body">
                                <form action="" method="post" id="updateCourseForm">
                                    <div class="form-group">
                                        <label for="courseTitle">Course Title</label>
                                        <input type="hidden" name="courseID" class="form-control" value="<?= $Course->id; ?>">
                                        <input type="text" name="courseTitle" id="courseTitle" class="form-control" value="<?= $Course->course_title; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="courseCode">Course Code</label>
                                        <input type="text" name="courseCode" id="courseCode" class="form-control" value="<?= $Course->course_code; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="courseUnit">Course Unit</label>
                                        <input type="number" name="courseUnit" id="courseUnit" class="form-control" value="<?= $Course->course_unit; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="courseStatus">Course Status</label>
                                        <select name="courseStatus" id="courseStatus" class="form-control">
                                            <option value="" selected>-- -- </option>
                                            <option value="C" <?= $Course->course_status == 'C' ? 'selected' : '' ?>>C</option>
                                            <option value="E" <?= $Course->course_status == 'E' ? 'selected' : '' ?>>E</option>
                                            <option value="R" <?= $Course->course_status == 'R' ? 'selected' : '' ?>>R</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="courseSemester">Course Semester</label>
                                        <select name="courseSemester" id="courseSemester" class="form-control">
                                            <option value="" selected>-- -- </option>
                                            <option value="first" <?= $Course->semester == 'first' ? 'selected' : '' ?>>First</option>
                                            <option value="second" <?= $Course->semester == 'second' ? 'selected' : '' ?>>Second</option>
                                            <option value="third" <?= $Course->semester == 'third' ? 'selected' : '' ?>>Third</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="program">Program</label>
                                        <select name="program" id="program" class="form-control">
                                            <option value="" selected>-- -- </option>
                                            <?php
                                                foreach($Programs as $program){?>
                                                <option value="<?= $program->program_id?>" <?= $Course->program_id == $program->program_id ? 'selected' : '' ?>>
                                                    <?=$program->programme_title ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="pgCourse">PG Course</label>
                                        <select name="pgCourse" id="pgCourse" class="form-control">
                                            <option value="" selected>-- -- </option>
                                            <?php
                                                foreach($Courses as $Cours){?>
                                                <option value="<?= $Cours->id?>" <?= $Course->pg_course == $Cours->id ? 'selected' : '' ?>>
                                                    <?=$Cours->course_name ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="isActive">Is Course Active</label>
                                        <select name="isActive" id="isActive" class="form-control">
                                            <option value="" selected>-- -- </option>
                                            <option value="1" <?= $Course->is_active == '1' ? 'selected' : '' ?>>Yes</option>
                                            <option value="0" <?= $Course->is_active == '0' ? 'selected' : '' ?>>No</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                    <input type="hidden" name="updateCourse" value="<?php echo rand() ?>">
                                        <button type="submit" id="addCourseBtn" class="btn btn-primary wave-effect effect-light"> <i class="fa fa-arrow-right"></i> Save Changes</button>
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

<?php include 'includes/footer.php'; ?>

<script>
    $(document).ready(function () {
        $('#updateCourseForm').submit(function (e) {
            e.preventDefault();

            // Get form data
            var formData = $(this).serialize();
            $login_btn = $('#addCourseBtn');
            $login_btn.addClass('disabled');
            $login_btn.html('<div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>');

            // Perform AJAX validation
            $.ajax({
                type: 'POST',
                url: '../app/function/lecturer_actions.php', 
				data: formData,
				dataType: 'json',				
                success: function (response) {

                    if (response.status == 'success') {
                       
						$('#updateCourseForm')[0].reset();
                        $login_btn.removeClass('disabled');
						$login_btn.html('<i class="fa fa-arrow-right"></i> Save Changes');
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
                        $login_btn.html('<i class="fa fa-arrow-right"></i> Save Changes');
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
				error: function (xhr, status, error) {
                    console.error(error);
						console.error(xhr.responseText);
					}
				});
        });
    });
</script>