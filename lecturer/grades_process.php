<?php
session_start();
include 'includes/header.php'; 

// Retrieve session data for grades
$students_data = $_SESSION['students_data'] ?? [];
$class = $_SESSION['class'] ?? '';
$course = $_SESSION['course'] ?? '';
?>

<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row py-3">
            <div class="col-sm-12">
                <h4 class="page-title">Grades Management</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="lecturer_home.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Grades</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Display Grades -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title">Grades for <?= htmlspecialchars($class) ?> - <?= htmlspecialchars($course) ?></h5>

                        <!-- Table -->
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Matric Number</th>
                                    <th>Full Name</th>
                                    <th>Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($students_data)) { ?>
                                    <?php foreach ($students_data as $index => $student) { ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= htmlspecialchars($student['matric_number']) ?></td>
                                            <td><?= htmlspecialchars($student['full_name']) ?></td>
                                            <td>
                                                <input type="text" class="form-control" name="grades[<?= $student['matric_number'] ?>]" value="<?= htmlspecialchars($student['grade'] ?? '') ?>" />
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No students found for this class and course.</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <!-- Save Button -->
                        <form action="save_grades.php" method="POST">
                            <button type="submit" class="btn btn-success w-100">Save Grades</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End container-fluid -->
</div> <!-- End content-wrapper -->

<?php include 'includes/footer.php'; ?>
