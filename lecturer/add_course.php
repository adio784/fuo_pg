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
                <h4 class="page-title">Course Record</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="lecturer_home.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Course</li>
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
                                <h4 class="card-title">Single Entry</h4>   
                            </div>
                            <div class="card-body">
                                <form action="" method="post" id="courseSingleForm">
                                    <div class="form-group">
                                        <label for="courseTitle">Course Title</label>
                                        <input type="text" name="courseTitle" id="courseTitle" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="courseCode">Course Code</label>
                                        <input type="text" name="courseCode" id="courseCode" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="courseUnit">Course Unit</label>
                                        <input type="number" name="courseUnit" id="courseUnit" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="courseStatus">Course Status</label>
                                        <select name="courseStatus" id="courseStatus" class="form-control">
                                            <option value="" selected>-- -- </option>
                                            <option value="C">C</option>
                                            <option value="E">E</option>
                                            <option value="R">R</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="courseSemester">Course Semester</label>
                                        <select name="courseSemester" id="courseSemester" class="form-control">
                                            <option value="" selected>-- -- </option>
                                            <option value="first">First</option>
                                            <option value="second">Second</option>
                                            <option value="third">Third</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="program">Program</label>
                                        <select name="program" id="program" class="form-control">
                                            <option value="" selected>-- -- </option>
                                            <?php
                                                foreach($Programs as $program){?>
                                                <option value="<?= $program->program_id?>"><?=$program->programme_title ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="pgCourse">PG Course</label>
                                        <select name="pgCourse" id="pgCourse" class="form-control">
                                            <option value="" selected>-- -- </option>
                                            <?php
                                                foreach($Courses as $Course){?>
                                                <option value="<?= $Course->id?>"><?=$Course->course_name ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                    <input type="hidden" name="createCourse" value="<?php echo rand() ?>">
                                        <button type="submit" id="addCourseBtn" class="btn btn-primary wave-effect effect-light"> <i class="fa fa-arrow-right"></i> Add Course</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <div class="card">
                            <div class="card-header"> 
                                <h4 class="card-title">Course Upload</h4>   
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <a href="../app/function/lecturer_actions.php?download_template=1" class="btn btn-primary"> <i class="fa fa-download"></i> Download Course Template </a>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <hr/>

                                        <div>
                                            <h6 class="mt-3 m-1">DEPARTMENT</h6>
                                            <span class="badge p-2 badge-info m-1">Department ID : <?=$departmentId ?></span>
                                            
                                            <h6 class="mt-3 m-1">PROGRAM</h6>
                                            <?php 
                                            foreach($Programs as $Prg){?>
                                                <span class="badge p-2 badge-secondary m-1"> <?=$Prg->programme_title ?>: <?=$Prg->program_id ?></span>
                                            <?php }?>
                                            
                                            <h6 class="mt-3 m-1">PG COURSES</h6>
                                            <?php 
                                            foreach($Courses as $Crs){?>
                                                <span class="badge p-2 badge-warning m-1"> <?=$Crs->course_name ?>: <?=$Crs->id ?></span>
                                            <?php }?>
                                        </div>
                                        <hr/>
                                    </div>
                                    
                                    <div class="col-12">
                                        <form method="post" enctype="multipart/form-data" id="importCourseForm">
                                            <div class="form-group">
                                                <label for="fileUpload" class="mb-4">Upload course file</label>
                                                <input type="file" name="fileUpload" id="fileUpload" class="form-control">
                                            </div>

                                            <div class="form-group mt-4">
                                            <input type="hidden" name="importCourse" value="<?php echo rand() ?>">
                                                <button type="submit" class="btn btn-secondary" id="uploadCourseBtn"><i class="fa fa-upload"></i> Upload Course</button>
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
    $(document).ready(function () {
        $('#courseSingleForm').submit(function (e) {
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
                       
						$('#courseSingleForm')[0].reset();
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
				error: function (xhr, status, error) {
						console.error(xhr.responseText);
					}
				});
        });

        // Upload Course ..............................................
        $('#importCourseForm').submit(function (e) {
            e.preventDefault();

            // Get form data
            var formData = new FormData(this);
            $login_btn = $('#uploadCourseBtn');
            $login_btn.addClass('disabled');
            $login_btn.html('<div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>');

            // Perform AJAX validation
            $.ajax({
                type: 'POST',
                url: '../app/function/lecturer_actions.php', 
				data: formData,
				contentType: false,
                processData: false,		
                success: function (response) {

                    if (response.status == 'success') {
                       
						$('#importCourseForm')[0].reset();
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
				error: function (xhr, status, error) {
						console.error(xhr.responseText);
					}
				});
        });
    });
</script>