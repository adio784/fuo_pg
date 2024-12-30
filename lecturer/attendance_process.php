

<?php include 'includes/header.php'; ?>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row py-3">
            <div class="col-sm-12">
                <h4 class="page-title">Attendance Records</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="lecturer_home.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="attendance.php">Attendance</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Attendance Records</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Display Attendance Records -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title">Attendance for <?php echo htmlspecialchars($class); ?> on <?php echo htmlspecialchars($date); ?></h5>
                        <form action="save_attendance.php" method="POST">
                            <input type="hidden" name="class" value="<?php echo htmlspecialchars($class); ?>">
                            <input type="hidden" name="date" value="<?php echo htmlspecialchars($date); ?>">

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>#</th>
                                            <th>Matric Number</th>
                                            <th>Full Name</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($attendanceRecords)) {
                                            $i = 1;
                                            foreach ($attendanceRecords as $record) {
                                                echo "<tr>
                                                    <td>{$i}</td>
                                                    <td>" . htmlspecialchars($record['matric_number']) . "</td>
                                                    <td>" . htmlspecialchars($record['full_name']) . "</td>
                                                    <td>
                                                        <select name='status[{$record['matric_number']}]' class='form-control'>
                                                            <option value='Present'" . ($record['status'] === 'Present' ? ' selected' : '') . ">Present</option>
                                                            <option value='Absent'" . ($record['status'] === 'Absent' ? ' selected' : '') . ">Absent</option>
                                                        </select>
                                                    </td>
                                                </tr>";
                                                $i++;
                                            }
                                        } else {
                                            echo "<tr><td colspan='4'>No students found for this class on the selected date.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <button type="submit" class="btn btn-success mt-3">Save Attendance</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
