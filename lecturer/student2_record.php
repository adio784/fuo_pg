<?php include 'includes/header.php'; ?>
<?php include 'config.php'; ?>

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
                        <thead class="table-primary">
                            <tr>
                                <th>#</th>
                                <th>Matric Number</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Gender</th>
                                <th>Admission Year</th>
                                <th>Program</th>
                                <th>Student Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Replace 'LECTURER_ID' with the lecturer's actual ID or access criteria
                            $lecturer_id = $_SESSION['lecturer_id'];

                            // Fetch students associated with the lecturer's courses/programs
                            $sql = "SELECT matric_number, CONCAT(last_name, ' ', first_name, ' ', middle_name) AS full_name, email, 
                                    gender, admission_year, program, student_status 
                                    FROM students 
                                    WHERE program IN (
                                        SELECT program FROM courses_assigned_to_lecturers WHERE lecturer_id = '$lecturer_id'
                                    )";
                            
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                $i = 1;
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                        <td>{$i}</td>
                                        <td>{$row['matric_number']}</td>
                                        <td>{$row['full_name']}</td>
                                        <td>{$row['email']}</td>
                                        <td>{$row['gender']}</td>
                                        <td>{$row['admission_year']}</td>
                                        <td>{$row['program']}</td>
                                        <td>{$row['student_status']}</td>
                                    </tr>";
                                    $i++;
                                }
                            } else {
                                echo "<tr><td colspan='8'>No records found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
    document.getElementById('applyFilters').addEventListener('click', function() {
        const program = document.getElementById('filterProgram').value;
        const year = document.getElementById('filterYear').value;
        const search = document.getElementById('searchInput').value;

        // Add AJAX logic here to reload the table with filtered data
        console.log({ program, year, search });
    });
</script>


<?php include 'includes/header.php'; ?>

<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Breadcrumb -->
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

        <!-- Action Buttons -->
        <div class="row mb-3">
            <div class="col-md-6">
                <button class="btn btn-primary btn-sm" onclick="location.href='add_student.php'"><i class="bi bi-person-plus"></i> Add Student</button>
                <button class="btn btn-success btn-sm" onclick="location.href='import_students.php'"><i class="bi bi-file-earmark-spreadsheet"></i> Import Students</button>
            </div>
        </div>

        <!-- Students Table -->
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="table-primary">
                            <tr>
                                <th>#</th>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Example data (replace with your database query)
                            $students = [
                                ["id" => 1, "student_id" => "S001", "name" => "John Doe", "email" => "john@example.com", "department" => "Computer Science"],
                                ["id" => 2, "student_id" => "S002", "name" => "Jane Smith", "email" => "jane@example.com", "department" => "Mathematics"],
                            ];

                            foreach ($students as $index => $student) {
                                echo "<tr>
                                    <td>" . ($index + 1) . "</td>
                                    <td>{$student['student_id']}</td>
                                    <td>{$student['name']}</td>
                                    <td>{$student['email']}</td>
                                    <td>{$student['department']}</td>
                                    <td>
                                        <a href='view_student.php?id={$student['id']}' class='btn btn-info btn-sm'><i class='bi bi-eye'></i> View</a>
                                        <a href='edit_student.php?id={$student['id']}' class='btn btn-warning btn-sm text-white'><i class='bi bi-pencil'></i> Edit</a>
                                        <button class='btn btn-danger btn-sm'><i class='bi bi-trash'></i> Delete</button>
                                    </td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div><!-- End container-fluid -->
</div><!-- End content-wrapper -->

<?php include 'includes/footer.php'; ?>
