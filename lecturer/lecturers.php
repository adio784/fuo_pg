<?php 
include 'includes/header.php'; 
  // Get payment histories
  $ListQuery   = $db->prepare("SELECT * FROM lecturers WHERE department_id = ?");
  $ListQuery->execute([$departmentId]);
?>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row pt-3 pb-2">
            <div class="col-sm-12">
                <h4 class="page-title">Lecturers Record</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="lecturer_home.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Lecturers Records</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Students Table -->
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="lecturerTable">
                        <thead class="table-primary">
                            <tr>
                                <th>#</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>Status</th>
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
                                        $surname   =  $record->surname;
                                        $othernames  =  $record->other_names;
                                        $fullname     =  strtoupper($surname) . ' ' . ucfirst($othernames);
                                        $email         =  $record->email;
                                        $gender         =  ucfirst($record->gender);
                                        $phone = $record->phone_number;
                                        $status      =  ($record->is_active == 1) ? 'Active' : 'Inactive';
                                        $bagStatus      =  ($record->is_active == 1) ? 'gradient-quepal text-white' : 'bg-danger text-white';
                                        $date           =  $record->created_at;
                                ?>
                            <tr>
                                <td><?= $count ?></td>
                                <td><?= $fullname ?></td>
                                <td><?= $email ?></td>
                                <td><a href="tel:?= $phone ?>"> <?= $phone ?> </a></td>
                                <td><?= $gender ?></td>
                                <td><span class="badge <?=$bagStatus?>"><?= $status ?> </span></td>
                            </tr>
                            <?php } } ?>
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
        $('#lecturerTable').DataTable({
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

            console.log('Filters Applied:', { program, year, search });
        });
    });
</script>
