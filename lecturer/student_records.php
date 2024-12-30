<?php
include 'includes/header.php';
$ListQuery   = $db->prepare("
                            SELECT
                            students.matric_no,
                            students.last_name,
                            students.first_name,
                            students.middle_name,
                            students.email,
                            students.mobile_no,
                            students.gender,
                            students.religion,
                            students.admission_session,
                            students.student_status,
                            students.created_at,
                            programme.programme_title,
                            program_course.course_name
                            FROM students
                            INNER JOIN programme ON programme.program_id = students.program
                            INNER JOIN program_course ON program_course.id = students.course
                            WHERE students.department = ?");
$ListQuery->execute([$departmentId]);
?>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row pt-3 pb-2">
            <div class="col-sm-12">
                <h4 class="page-title">Students Records</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="lecturer_home.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Students Records</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="row mb-3">
            <div class="col-md-3">
                <select class="form-control" id="filterProgram">
                    <option value="">-- Select Program --</option>
                    <option value="BSc Computer Science">BSc Computer Science</option>
                    <option value="BSc Mathematics">BSc Mathematics</option>
                    <!-- Add more options as needed -->
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-control" id="filterYear">
                    <option value="">-- Select Year --</option>
                    <option value="2023">2023</option>
                    <option value="2022">2022</option>
                    <option value="2021">2021</option>
                    <!-- Add more options as needed -->
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="searchInput" placeholder="Search by Matric Number or Name">
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary btn-block" id="applyFilters">Apply Filters</button>
            </div>
        </div>

        <!-- Students Table -->
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="studentsTable">
                        <thead class="table-primary text-white">
                            <tr>
                                <th>#</th>
                                <th>Matric Number</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>Admission Session</th>
                                <th>Program</th>
                                <th>Student Status</th>
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
                                    $matricNo       =  $record->matric_no;
                                    $surname        =  $record->last_name;
                                    $firstname      =  $record->first_name;
                                    $middlename     =  $record->middle_name;
                                    $fullname       =  strtoupper($surname) . ' ' . ucfirst($firstname) . ' ' . ucfirst($middlename);
                                    $email          =  $record->email;
                                    $gender         =  ucfirst($record->gender);
                                    $religion       =  ucfirst($record->religion);
                                    $admyear        =  ucfirst($record->admission_session);
                                    $program        =  ucfirst($record->course_name);
                                    $phone          = $record->mobile_no;
                                    $status         =  ucfirst($record->student_status);
                                    $bagStatus      =  ($record->student_status == 'active') ? 'gradient-quepal text-white' : 'bg-danger text-white';
                                    $date           =  $record->created_at;
                            ?>
                                    <tr>
                                        <td><?= $count ?></td>
                                        <td><?= $matricNo ?></td>
                                        <td><?= $fullname ?></td>
                                        <td><?= $email ?></td>
                                        <td><a href="tel:?= $phone ?>"> <?= $phone ?> </a></td>
                                        <td><?= $gender ?></td>
                                        <td><?= $admyear ?></td>
                                        <td><?= $program ?></td>
                                        <td><span class="badge <?= $bagStatus ?>"><?= $status ?> </span></td>
                                    </tr>
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

<?php include 'includes/footer.php'; ?>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#studentsTable').DataTable({
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

            // Implement server-side filtering if needed
        });
    });
</script>