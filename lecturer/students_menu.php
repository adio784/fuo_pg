<?php 
    include 'includes/header.php'; 
?>

<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row py-3">
            <div class="col-sm-12">
                <h4 class="page-title">Student Menu</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="lecturer_home.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Student Menu</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Student Menu Cards -->
        <div class="row">
            <!-- Student Records -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-people-fill text-primary mb-3" style="font-size: 2rem;"></i>
                        <h5 class="card-title">Student Records</h5>
                        <p class="card-text text-muted">View and manage student profiles and academic records.</p>
                        <a href="student_records.php" class="btn btn-primary btn-sm">View Records</a>
                    </div>
                </div>
            </div>

            <!-- Attendance -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-calendar-check-fill text-success mb-3" style="font-size: 2rem;"></i>
                        <h5 class="card-title">Attendance</h5>
                        <p class="card-text text-muted">Track and manage student attendance efficiently.</p>
                        <a href="attendance.php" class="btn btn-success btn-sm">Manage Attendance</a>
                    </div>
                </div>
            </div>

            <!-- Grades -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-file-earmark-text-fill text-warning mb-3" style="font-size: 2rem;"></i>
                        <h5 class="card-title">Grades & Assessments</h5>
                        <p class="card-text text-muted">Record, update, and view student grades.</p>
                        <a href="grades.php" class="btn btn-warning btn-sm text-white">Manage Grades</a>
                    </div>
                </div>
            </div>

            <!-- Class List -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-list-ul text-info mb-3" style="font-size: 2rem;"></i>
                        <h5 class="card-title">Class List</h5>
                        <p class="card-text text-muted">View and download the list of students in your class.</p>
                        <a href="class_list.php" class="btn btn-info btn-sm">View Class List</a>
                    </div>
                </div>
            </div>

            <!-- Message Students -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-envelope-fill text-danger mb-3" style="font-size: 2rem;"></i>
                        <h5 class="card-title">Message Students</h5>
                        <p class="card-text text-muted">Communicate directly with students via messages.</p>
                        <a href="send_message.php" class="btn btn-danger btn-sm">Send Message</a>
                    </div>
                </div>
            </div>

            <!-- Performance Analytics -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-bar-chart-fill text-secondary mb-3" style="font-size: 2rem;"></i>
                        <h5 class="card-title">Performance Analytics</h5>
                        <p class="card-text text-muted">Analyze student performance using advanced tools.</p>
                        <a href="student_performance.php" class="btn btn-secondary btn-sm">View Analytics</a>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End container-fluid -->
</div> <!-- End content-wrapper -->

<!-- Back to Top Button -->
<a href="javascript:void(0);" class="back-to-top"><i class="bi bi-arrow-up-circle-fill"></i></a>

<!-- Footer -->
<?php include "includes/footer.php"; ?>

</body>
</html>
