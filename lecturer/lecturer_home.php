<?php include 'includes/header.php'; ?>

<div class="clearfix"></div>

<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumb-->
    <div class="row pt-2 pb-2">
      <div class="col-sm-9">
        <h4 class="page-title">Lecturer Dashboard</h4>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="lecturer_home.php">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
      </div>
    </div>
    <!-- End Breadcrumb -->

    <!-- Welcome Card -->
    <div class="row">
      <div class="col-12 col-lg-8">
        <div class="card">
          <div class="card-header">Welcome Back, <?php echo $fullname; ?>!</div>
          <div class="card-body">
            <div class="row">
              <!-- Profile Picture and Basic Info -->
              <div class="col-lg-4">
                <div class="text-center">
                  <img src="<?php echo $passport; ?>" alt="Profile Picture" class="img-fluid rounded-circle mb-3" style="max-width: 150px;">
                </div>
                <h5 class="text-center"><?php echo $fullname; ?></h5>
                <p class="text-center"><?php echo $email; ?></p>
              </div>

              <!-- Additional Information -->
              <div class="col-lg-8">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <h6>Phone:</h6>
                      <p><?php echo $phone; ?></p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <h6>Gender:</h6>
                      <p><?php echo ucfirst($User->gender); ?></p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <h6>Department:</h6>
                      <p><?php echo $department; ?></p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <h6>Courses Coordinated:</h6>
                      <ul>
                        <?php
                        // foreach ($courses as $course) {
                        //   echo "<li>{$course->course_name}</li>";
                        // }
                        ?>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-header">Courses You Teach</div>
          <div class="card-body">
            <ul class="list-group">
              <?php
              if (!empty($courses)) {
                foreach ($courses as $course) {
                  echo "<li class='list-group-item'>
                            <strong>{$course->course_name}</strong> 
                            <small>({$course->course_code})</small>
                            <span class='float-right'>Semester: {$course->semester}</span>
                          </li>";
                }
              } else {
                echo "<p>No courses assigned yet.</p>";
              }
              ?>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="card mt-3">
          <div class="card-header">Upcoming Lectures</div>
          <div class="card-body">
            <ul class="list-group">
              <?php
              if (!empty($lectures)) {
                foreach ($lectures as $lecture) {
                  echo "<li class='list-group-item'>
                              <strong>{$lecture->course_name}</strong> - {$lecture->topic}
                              <small class='float-right'>{$lecture->date} at {$lecture->time}</small>
                            </li>";
                }
              } else {
                echo "<p>No scheduled lectures.</p>";
              }
              ?>
            </ul>
          </div>
        </div>

        <!-- Right Column: Notifications and Events -->

        <div class="card">
          <div class="card-header">Notifications</div>
          <!-- <div class="card-body">
            <ul class="list-group">
              <li class="list-group-item">Faculty meeting scheduled for next week.</li>
              <li class="list-group-item">Grade submission deadline approaching.</li>
              <li class="list-group-item">New course material uploaded for CSC101.</li>
            </ul>
          </div> -->
        </div>

        <div class="card mt-3">
          <div class="card-header">Upcoming Events</div>
          <div class="card-body">
            <strong>Orientation Programme: 2025-01-07</strong>
            <!-- <p>Graduation Ceremony: 2024-02-20</p> -->
          </div>
        </div>

        <!-- End Right Column -->
      </div>
      <!-- End Welcome Card -->
    </div>


  </div>
  <!-- End Container-Fluid -->
</div>
<!-- End Content-Wrapper -->

<!-- Back To Top Button -->
<a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i></a>

<?php include "includes/footer.php"; ?>

</body>

</html>