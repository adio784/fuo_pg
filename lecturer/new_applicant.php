<?php
include 'includes/header.php';

// Get User data .............................
if (isset($_GET['view_student'])) {
    $student_id  = $_GET['view_student'];
    ob_clean();
    if ($student_id != "") {
        // Get data ..........................................................................................
        $query  = $database->getConnection()->prepare("
                        SELECT *, 
                        user_credentials.application_id as appId
                        FROM `application`
                        INNER JOIN user_credentials ON user_credentials.application_id = application.application_id
                        LEFT JOIN program_course ON application.course = program_course.id
                        WHERE application.application_id = ? LIMIT 1");
        $query->execute([$student_id]);
        $students = $query->fetchObject();
        // .....................................................................................................

        $response['status']   = 'success';
        $response['message']  = "Successfully fetch data";
        $response['data']     = $students;
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Row counts ...............................................................................................................
$Admitted   = $Crud->countByThree('application', 'application_status', 'admitted', 'application_session', $current_session, 'department', $departmentId);
$NotAdmitted = $Crud->countByThree('application', 'application_status', 'registered', 'application_session', $current_session, 'department', $departmentId);
$TApp       = $Crud->countByTwo('application', 'application_session', $current_session, 'department', $departmentId);
$Allapp     = $Crud->countByOne('application', 'department', $departmentId);
// ..........................................................................................................................
//  Get recent applicants ....................................................................................................
$rquery = $db->prepare("SELECT
                                application.first_name, application.last_name, application.middle_name,
                                application.created_at, application.application_id, user_credentials.passport,
                                application.email, programme.programme_title, program_course.course_name
                            FROM 
                                application
                            INNER JOIN
                                programme ON programme.program_id = application.program
                            INNER JOIN
                                program_course ON program_course.id = application.course
                            INNER JOIN 
                                user_credentials ON application.application_id = user_credentials.application_id
                            WHERE 
                                application.application_session = ?
                                AND application.department = ?
                                AND application.application_status = ?
                            GROUP BY
                            	application.application_id
                            ORDER BY 
                                application.application_id DESC");
    $rquery->execute([$current_session, $departmentId, 'registered']);
// $rquery->execute([$current_session, $departmentId, 'registered']);
$rcount     = $rquery->rowCount();

// ..............................................................................................................................


$getAllApplicant = $db->prepare("SELECT  
                    application.first_name, application.last_name, application.middle_name,
                    application.created_at, application.application_id, user_credentials.passport,
                    application.email, programme.programme_title, program_course.course_name
                    FROM application
                    INNER JOIN programme
                    ON application.program = programme.program_id
                    INNER JOIN program_course
                    ON programme.program_id = program_course.program_id
                    INNER JOIN user_credentials
                    ON application.application_id = user_credentials.application_id
                    WHERE application.department = ?
                    GROUP BY application.application_id
                    ORDER BY application.id DESC ");
$getAllApplicant->execute([$departmentId]);


// Rejection of submission ....................................................................

?>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row pt-3 pb-2">
            <div class="col-sm-12">
                <h4 class="page-title">Admission Record</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="lecturer_home.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">New Applicants</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card mt-3 shadow-none border border-light">
            <div class="card-content">
                <div class="row row-group m-0">
                    <div class="col-12 col-lg-6 col-xl-4 border-light">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body text-left">
                                    <h4 class="text-danger"><?= $NotAdmitted->num ?></h4>
                                    <span>Not Admitted</span>
                                </div>
                                <div class="align-self-center w-circle-icon rounded bg-danger shadow-danger">
                                    <i class="icon-wallet text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 col-xl-4 border-light">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body text-left">
                                    <h4 class="text-success"><?= $Admitted->num ?></h4>
                                    <span>Admitted</span>
                                </div>
                                <div class="align-self-center w-circle-icon rounded bg-success shadow-success">
                                    <i class="icon-pie-chart text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 col-xl-4 border-light">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body text-left">
                                    <h4 class="text-warning"><?= $Allapp->num ?></h4>
                                    <span>All Applicants</span>
                                </div>
                                <div class="align-self-center w-circle-icon rounded bg-warning shadow-warning">
                                    <i class="icon-user text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students Table -->
        <div class="card shadow-sm border-0">
            <div class="card">
                <div class="card-header"><i class="fa fa-table"></i> New Applicants</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="default-datatable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>***</th>
                                    <th>Application Number</th>
                                    <th>Name</th>
                                    <th>Programme</th>
                                    <th>Course</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                <!-- Iterate thru the existing data -->
                                <?php
                                $count = 0;

                                if ($rcount < 1) {

                                    echo "<tr> <td align='center'> <strong> No record found !!! </strong> </td> </tr>";
                                } else {
                                    while ($recent = $rquery->fetch(PDO::FETCH_OBJ)) {
                                        $count++;
                                        $name   =   strtoupper($recent->last_name) . ' ' . $recent->first_name . ' ' . $recent->middle_name;
                                        $appID  =   $recent->application_id;
                                        $appID  =   $recent->application_id;
                                        $email  =   $recent->email;
                                        $prog   =   $recent->programme_title;
                                        $cours  =   $recent->course_name;
                                        $date   =   $recent->created_at;
                                        $img    =   $recent->passport;
                                ?>

                                        <tr>
                                            <td> <?= $count ?> </td>
                                            <td><img src="../../admission_portal/admissionUploads/<?= $img ?>" class="product-img" alt="applicant img"></td>
                                            <td> <?= $appID ?> </td>
                                            <td> <?= $name ?> </td>
                                            <td> <span class="badge gradient-quepal text-white shadow p-2"><?= $prog ?></span></td>
                                            <td> <?= $cours ?> </td>
                                            <td> <?= formatDate($date) ?> </td>
                                            <td>
                                                <button class="btn btn-primary admissionBtn" type="button" id="<?= $appID ?>"> <i class="icon-login text-white"></i> Recommend </button>
                                                <button id="<?= $appID ?>" class="view_student btn btn-info"> <i class="icon-eye text-white"></i> View</button>
                                                <button type="button" class="btn btn-danger shadow-info waves-effect waves-light m-1" data-toggle="modal" data-target="#defaultsizemodal"><i class="fa fa-arrow-down font-lg"></i> Reject </button>
                                            </td>

                                        </tr>

                                        <div class="modal fade" id="defaultsizemodal">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"><i class="fa fa-star"></i> Rejection </h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="message">Rejection Note</label>
                                                            <textarea type="text" class="form-control" id="editNote"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                                                        <button type="button" id="<?= $appID ?>" data-id="<?= $appID ?>" class="rejectApproval btn btn-primary"><i class="fa fa-check-square-o"></i> Continue</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                <?php }
                                } ?>

                                <!-- iteration ends here -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>SN</th>
                                    <th>***</th>
                                    <th>Application Number</th>
                                    <th>Name</th>
                                    <th>Programme</th>
                                    <th>Course</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>


<!-- ------------ MODAL TO DISPLAY USER DETAILS BEFORE RECOMMENDATION BY THE DEPARTMENTAL ADMISSION OFFICER ----------- -->
<button class="btn btn-primary m-1 d-none" data-toggle="modal" data-target="#largesizemodal" id="previewFormBtn">Large Size Modal</button>

<div class="modal fade" id="largesizemodal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-star"></i> Preview Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body pl-4">
                <div class="row">
                    <input type="hidden" name="pgAppToken" value="<?php echo uniqid() ?>">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label for="">Last Name</label>
                        <input type="text" id="ln" name="lastName" style="border:0px">
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label for="">First Name</label>
                        <input type="text" id="fn" name="firstName" style="border:0px">
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12" id="mddiv">
                        <label for="">Middle Name</label>
                        <input type="text" id="mn" name="middleName" style="border:0px">
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label for="">Gender</label> <br>
                        <input type="text" id="gn" name="gender" style="border:0px">
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label for="">Religion</label>
                        <input type="text" id="rl" name="religion" style="border:0px">
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label for="">Date of Birth</label>
                        <input type="text" id="db" name="birthDate" style="border:0px">
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label for="">Address</label>
                        <input type="text" id="ad" name="address" style="border:0px">
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label for="">Country</label>
                        <input type="text" id="cr" name="country" style="border:0px">
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label for="">State</label>
                        <p> <input type="text" id="st" name="state" style="border:0px"> </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label for="">City</label>
                        <p> <input type="text" id="ct" name="city" style="border:0px"> </p>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label for="">Email Address</label>
                        <input type="text" id="em" name="emailAddress" style="border:0px">
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label for="">Phone Number</label>
                        <input type="text" id="pn" name="phoneNumber" style="border:0px">
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mt-3">
                        <h6>Origin</h6>
                        <hr>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label for="">Country of Origin</label>
                        <input type="text" id="co" name="countryOrigin" style="border:0px">
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label for="">State of Origin</label>
                        <input type="text" id="so" name="stateOrigin" style="border:0px">
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label for="">LGA of Origin</label>
                        <p> <input type="text" id="lo" name="lgaOrigin" style="border:0px"> </p>
                    </div>
                </div>


                <div class="row">
                    <div class="col-12 mt-3">
                        <h6>Academics</h6>
                        <hr>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label for="">Undergraduate Course</label>
                        <input type="text" id="uc" name="undergraduateCourse" style="border:0px">
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label for="">Class of Degree</label>
                        <input type="text" id="cd" name="classDegree" style="border:0px">
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <label for="">Institute Attended</label>
                        <input type="text" id="ia" name="instituteAtt" style="border:0px">
                    </div>
                </div>

                <div class="row mt-4">
                    <!-- <div class="col-md-4 col-sm-12">
                                        <label for="">Entry Mode</label>
                                        <p> <input type="text" id="rm" name="entryMode" style="border:0px"> </p>
                                      </div> -->
                    <div class="col-sm-12">
                        <label for="">Course of Study</label> <br>
                        <input type="text" id="cs" style="border:0px">
                        <input type="hidden" id="csed" name="courseOfStudy" style="border:0px">
                    </div>
                </div>


                <div class="row">
                    <div class="col-12 mt-3">
                        <h6>Uploads .................................. </h6>
                        <br>
                    </div>
                </div>

                <div class="row">
                    <!-- Images -->

                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <label for="">O'level Upload</label>
                        <p id="ol"> </p>

                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <label for="">Undergraduate Certificate</label>
                        <p id="up"> </p>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 mt-4">
                        <label for="">Passport</label>
                        <p id="appPass"> </p>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 mt-4 msCert2">
                        <label for="" id="mastLabel">Master Certificate</label>
                        <p id="msCert"> </p>
                    </div>

                    <!-- Images -->
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancelModal"><i class="fa fa-times"></i> Close</button>
            </div>

        </div>
    </div>
</div>

<!-- ------------------------------------------------------------------------------------------------------------------------------------- -->

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
    });
</script>

<script>
    $(document).ready(function() {


        $('.admissionBtn').on('click', function(e) {
            e.preventDefault();

            // Get form data
            var dataId = $(this).attr('data-id');
            $login_btn = $('#' + dataId);
            $login_btn.addClass('disabled');

            swal({
                    title: 'Do you want Continue ?',
                    text: "You are sure to admit the student",
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
                            type: 'GET',
                            url: '../../app/function/admit_student.php?recommend=' + dataId,
                            dataType: 'json',
                            success: function(response) {
                                console.log(response);
                                if (response.status == 'success') {

                                    $login_btn.html('Recommend');
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
                                    setTimeout(function() { // wait for 5 secs(2)
                                        location.reload(); // then reload the page.(3)
                                    }, 5000);

                                } else {

                                    $login_btn.html('Recommend');
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

                                    setTimeout(function() { // wait for 5 secs(2)
                                        location.reload(); // then reload the page.(3)
                                    }, 5000);

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



        // View student before recommeding .....................
        $('.view_student').on('click', function(e) {
            e.preventDefault();

            var dataId = $(this).attr('id');
            // alert(dataId);
            $login_btn = $('#' + dataId);
            $login_btn.addClass('disabled');

            $('#overlay').show();
            $('#preloader').show();
            $login_btn.html('<div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>');

            // Perform AJAX validation
            $.ajax({
                type: 'GET',
                url: "new_applicant.php?view_student=" + dataId,
                dataType: 'json',
                success: function(response) {
                    if (response.status == 'success') {
                        console.log(dataId);
                        $login_btn.html('Recommend');
                        $login_btn.removeClass('disabled');
                        $('#overlay').hide();
                        $('#preloader').hide();

                        var dt = response.data;
                        $("#previewFormBtn").click();
                        $('#fn').val(dt.first_name);
                        if (dt.middle_name == "") $('#mn').hide();
                        $('#mddiv').hide();
                        $('#ln').val(dt.last_name);
                        $('#gn').val(dt.gender);
                        $('#rl').val(dt.religion);
                        $('#db').val(dt.date_of_birth);
                        $('#ad').val(dt.address);
                        $('#cr').val(dt.country);
                        $('#st').val(dt.state);
                        $('#ct').val(dt.lga);

                        $('#co').val(dt.country_of_origin);
                        $('#so').val(dt.state_of_origin);
                        $('#lo').val(dt.local_government);

                        $('#em').val(dt.email);
                        $('#pn').val(dt.phone_number);
                        $('#uc').val(dt.course_studied);
                        $('#cd').val(dt.class_degree);
                        $('#ia').val(dt.institute_attended);
                        $('#cs').val(dt.course_name);
                        $('#appPass').html("<img alt='' class='card-img w-100' src='../../admission_portal/admissionUploads/" + dt.passport + "'>");
                        $('#ol').html("<img alt='' class='card-img w-100' src='../../admission_portal/admissionUploads/" + dt.o_level + "'>");
                        $('#up').html("<img alt='' class='card-img w-100' src='../../admission_portal/admissionUploads/" + dt.undergraduate + "'>");


                        if (dt.program == "836293") {

                            $('#msCert').html("<img alt='' class='card-img w-100' src='../../admission_portal/admissionUploads/" + dt.masters + "'>");

                        } else {

                            $("#msCert2").html("");
                            $("#mastLabel").html("");

                        }


                    } else {

                        $login_btn.html('Recommend');
                        $login_btn.removeClass('disabled');
                        $('#overlay').hide();
                        $('#preloader').hide();

                        setTimeout(function() {
                            location.reload();
                        }, 5000);

                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });

        });

        $('.rejectApproval').on('click', function(e) {
            e.preventDefault();

            // Get form data
            var dataId = $(this).attr('data-id');
            var editNote = $("#editNote").val();
            $login_btn = $('#' + dataId);
            $login_btn.addClass('disabled');

            $('#overlay').show();
            $('#preloader').show();
            $login_btn.addClass('disabled');
            $login_btn.html('<div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div>');

            // Perform AJAX validation
            $.ajax({
                type: 'POST',
                url: '../app/function/admit_student.php',
                dataType: 'json',
                data: {
                    rejectApproval: dataId,
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

    });
</script>