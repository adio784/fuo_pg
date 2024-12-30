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
                <h4 class="page-title">Class List</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="lecturer_home.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Class List</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Class List Content -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title">View and Download Class List</h5>
                        <p class="card-text">Select a class to view the list of enrolled students.</p>

                        <!-- Class Selection Form -->
                        <form action="class_list.php" method="GET">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="class" class="form-label">Class</label>
                                    <select class="form-control" id="class" name="class" required>
                                        <option value="">-- Select Class --</option>
                                        <option value="BSc Computer Science">BSc Computer Science</option>
                                        <option value="BSc Mathematics">BSc Mathematics</option>
                                        <!-- Add more classes dynamically from the database if needed -->
                                    </select>
                                </div>
                                <div class="col-md-6 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">View Class List</button>
                                </div>
                            </div>
                        </form>

                        <!-- Class List Table -->
                        <?php if (isset($_GET['class']) && !empty($_GET['class'])): ?>
                            <?php
                            $class = $_GET['class'];

                            // Fetch students from the database based on the selected class
                            $query = "SELECT matric_number, first_name, last_name, email FROM students WHERE class = ?";
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("s", $class);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            ?>

                            <div class="table-responsive mt-4">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Matric Number</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['matric_number']) ?></td>
                                                <td><?= htmlspecialchars($row['first_name']) ?></td>
                                                <td><?= htmlspecialchars($row['last_name']) ?></td>
                                                <td><?= htmlspecialchars($row['email']) ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Export Button -->
                            <form action="export_class_list.php" method="POST" class="mt-3">
                                <input type="hidden" name="class" value="<?= htmlspecialchars($class) ?>">
                                <button type="submit" class="btn btn-success">Download Class List</button>
                            </form>

                            <?php
                            $stmt->close();
                            ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End container-fluid -->
</div> <!-- End content-wrapper -->

<?php include 'includes/footer.php'; ?>
