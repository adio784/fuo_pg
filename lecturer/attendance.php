<?php
session_start();

include 'includes/header.php'; 
?>

<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row py-3">
            <div class="col-sm-12">
                <h4 class="page-title">Attendance Management</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="lecturer_home.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Attendance</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Attendance Content -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title">Mark Attendance</h5>
                        <p class="card-text">Select a class and date to manage attendance records.</p>

                        <!-- Attendance Form -->
                        <form action="attendance_process.php" method="POST">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="class" class="form-label">Class</label>
                                    <select class="form-control" id="class" name="class" required>
                                        <option value="">-- Select Class --</option>
                                        <option value="BSc Computer Science">BSc Computer Science</option>
                                        <option value="BSc Mathematics">BSc Mathematics</option>
                                        <!-- Add more classes dynamically if needed -->
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" class="form-control" id="date" name="date" required>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">View Attendance</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End container-fluid -->
</div> <!-- End content-wrapper -->

<?php include 'includes/footer.php'; ?>
