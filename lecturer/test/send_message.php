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
                <h4 class="page-title">Send Message</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="lecturer_home.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Send Message</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Message Content -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title">Message Students</h5>
                        <p class="card-text">Select a class or individual students to send a message.</p>

                        <!-- Message Form -->
                        <form action="send_message_process.php" method="POST">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="class" class="form-label">Class</label>
                                    <select class="form-control" id="class" name="class">
                                        <option value="">-- Select Class (Optional) --</option>
                                        <option value="BSc Computer Science">BSc Computer Science</option>
                                        <option value="BSc Mathematics">BSc Mathematics</option>
                                        <!-- Add more classes dynamically from the database -->
                                    </select>
                                    <small class="text-muted">Leave blank if you want to select individual students.</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="student" class="form-label">Individual Student(s)</label>
                                    <select class="form-control" id="student" name="students[]" multiple>
                                        <!-- Dynamically fetch students -->
                                        <?php
                                        $query = "SELECT matric_number, first_name, last_name FROM students";
                                        $result = $conn->query($query);
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row['matric_number'] . "'>" . $row['first_name'] . " " . $row['last_name'] . " (" . $row['matric_number'] . ")</option>";
                                        }
                                        ?>
                                    </select>
                                    <small class="text-muted">Hold down Ctrl (Windows) or Command (Mac) to select multiple students.</small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Send Message</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End container-fluid -->
</div> <!-- End content-wrapper -->

<?php include 'includes/footer.php'; ?>
