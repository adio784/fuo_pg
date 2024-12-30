<?php
// session_start();

// Check if the lecturer is logged in
// if (!isset($_SESSION['lecturer_id'])) {
//     header("Location: login.php");
//     exit();
// }

// Include database connection
include 'includes/header.php';
?>

<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row py-3">
            <div class="col-sm-12">
                <h4 class="page-title">Student Performance Analytics</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="lecturer_home.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Performance Analytics</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Analytics Content -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title">Analyze Student Performance</h5>
                        <p class="card-text">View insights into grades and attendance data for better student monitoring.</p>

                        <!-- Performance Filters -->
                        <form action="student_performance.php" method="GET">
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
                                    <label for="course" class="form-label">Course</label>
                                    <select class="form-control" id="course" name="course" required>
                                        <option value="">-- Select Course --</option>
                                        <option value="CS101">CS101 - Introduction to Programming</option>
                                        <option value="MTH201">MTH201 - Calculus II</option>
                                        <!-- Add more courses dynamically if needed -->
                                    </select>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">View Analytics</button>
                                </div>
                            </div>
                        </form>

                        <!-- Analytics Results -->
                        <?php if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['class'], $_GET['course'])): 
                            $class = $_GET['class'];
                            $course = $_GET['course'];

                            // Fetch analytics data from database
                            $query = "SELECT s.first_name, s.last_name, s.matric_number, g.grade, a.attendance_percentage 
                                      FROM students s
                                      LEFT JOIN grades g ON s.matric_number = g.matric_number AND g.course_code = ?
                                      LEFT JOIN attendance a ON s.matric_number = a.matric_number AND a.course_code = ?
                                      WHERE s.class = ?";
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("sss", $course, $course, $class);
                            $stmt->execute();
                            $result = $stmt->get_result();
                        ?>
                        <div class="table-responsive mt-4">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Matric Number</th>
                                        <th>Student Name</th>
                                        <th>Grade</th>
                                        <th>Attendance (%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['matric_number']) ?></td>
                                        <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                                        <td><?= htmlspecialchars($row['grade'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($row['attendance_percentage'] ?? 'N/A') ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End container-fluid -->
</div> <!-- End content-wrapper -->

<?php include 'includes/footer.php'; ?>
