<?php
ob_start();

// Fetching Course Details for Approval .............................................................................................................................................................................
if (isset($_POST['viewCourse'])) {
    ob_end_clean();
    $studentId   = decodeId($_GET['studentId']);
    $ListQuery   = $db->prepare("SELECT * FROM course_registration WHERE student_id = ? AND course_session = ?");
    $ListQuery->execute([$studentId, $currentSession]);
    $Courses     = $ListQuery->fetchObject();

    if ($Courses) {
        
        echo '<table class="table table-striped">';
        echo '<thead><tr><th>Course Code</th><th>Title</th><th>Status</th><th>Unit</th><th>Semester</th></tr></thead><tbody>';
        foreach ($Courses as $course) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($course->course_code) . '</td>';
            echo '<td>' . htmlspecialchars($course->course_title) . '</td>';
            echo '<td>' . htmlspecialchars($course->course_status) . '</td>';
            echo '<td>' . htmlspecialchars($course->course_unit) . '</td>';
            echo '<td>' . htmlspecialchars($course->semester) . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
        exit();

    } else {
        echo '<p>No courses found for this student.</p>';
        exit();
    }

}