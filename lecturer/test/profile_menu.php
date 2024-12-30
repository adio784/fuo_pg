<?php include 'includes/header.php'; ?>

<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row py-3">
            <div class="col-sm-12">
                <h4 class="page-title">Profile Menu</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="lecturer_home.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Profile</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Profile Menu -->
        <div class="row">
            <!-- Personal Information -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-person-circle text-primary mb-3" style="font-size: 2rem;"></i>
                        <h5 class="card-title">Personal Information</h5>
                        <p class="card-text">View or update your personal details.</p>
                        <a href="personal_info.php" class="btn btn-primary btn-sm">Update Info</a>
                    </div>
                </div>
            </div>

            <!-- Change Password -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-lock-fill text-success mb-3" style="font-size: 2rem;"></i>
                        <h5 class="card-title">Change Password</h5>
                        <p class="card-text">Ensure your account is secure by updating your password.</p>
                        <a href="change_password.php" class="btn btn-success btn-sm">Change Password</a>
                    </div>
                </div>
            </div>

            <!-- Profile Picture -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-image text-warning mb-3" style="font-size: 2rem;"></i>
                        <h5 class="card-title">Profile Picture</h5>
                        <p class="card-text">Upload or update your profile picture.</p>
                        <a href="profile_picture.php" class="btn btn-warning btn-sm text-white">Manage Picture</a>
                    </div>
                </div>
            </div>

            <!-- Teaching Schedule -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-calendar2-week-fill text-info mb-3" style="font-size: 2rem;"></i>
                        <h5 class="card-title">Teaching Schedule</h5>
                        <p class="card-text">View your teaching schedule and assigned courses.</p>
                        <a href="teaching_schedule.php" class="btn btn-info btn-sm">View Schedule</a>
                    </div>
                </div>
            </div>

            <!-- Notifications -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-bell-fill text-danger mb-3" style="font-size: 2rem;"></i>
                        <h5 class="card-title">Notifications</h5>
                        <p class="card-text">Manage your notification preferences.</p>
                        <a href="notifications.php" class="btn btn-danger btn-sm">Manage Notifications</a>
                    </div>
                </div>
            </div>

            <!-- Activity Log -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <i class="bi bi-clock-history text-secondary mb-3" style="font-size: 2rem;"></i>
                        <h5 class="card-title">Activity Log</h5>
                        <p class="card-text">View your recent activity on the portal.</p>
                        <a href="activity_log.php" class="btn btn-secondary btn-sm">View Log</a>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End container-fluid -->
</div> <!-- End content-wrapper -->

<!-- Footer -->
<?php include 'includes/footer.php'; ?>
