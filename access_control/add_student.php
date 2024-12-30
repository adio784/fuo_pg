<?php include 'includes/header.php'; ?>

<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row pt-3 pb-2">
            <div class="col-sm-12">
                <h4 class="page-title">Add Student</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="lecturer_home.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="students_records.php">Students Records</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Student</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Add Student Form -->
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="add_student_action.php" method="POST">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="studentID" class="form-label">Student ID</label>
                            <input type="text" id="studentID" name="student_id" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="studentName" class="form-label">Name</label>
                            <input type="text" id="studentName" name="student_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="studentEmail" class="form-label">Email</label>
                            <input type="email" id="studentEmail" name="student_email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="studentDepartment" class="form-label">Department</label>
                            <input type="text" id="studentDepartment" name="student_department" class="form-control" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Student</button>
                </form>
            </div>
        </div>
    </div><!-- End container-fluid -->
</div><!-- End content-wrapper -->

<?php include 'includes/footer.php'; ?>
