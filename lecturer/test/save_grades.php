<?php
// session_start();

// Check if the lecturer is logged in
// if (!isset($_SESSION['lecturer_id'])) {
//     header("Location: login.php");
//     exit();
// }

// Include database connection
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate the grades input
    if (isset($_POST['grades']) && is_array($_POST['grades'])) {
        $grades = $_POST['grades'];
        $course = $_SESSION['course']; // Retrieve course code from session

        // Prepare SQL for updating grades
        $query = "INSERT INTO grades (matric_number, course_code, grade) 
                  VALUES (?, ?, ?)
                  ON DUPLICATE KEY UPDATE grade = VALUES(grade)";
        $stmt = $conn->prepare($query);

        // Process each grade
        foreach ($grades as $matric_number => $grade) {
            // Validate input
            if (!empty($matric_number) && !empty($course)) {
                $grade = strtoupper(trim($grade)); // Ensure grades are in uppercase (e.g., A, B, C)
                $stmt->bind_param("sss", $matric_number, $course, $grade);
                $stmt->execute();
            }
        }

        $stmt->close();
        $_SESSION['success_message'] = "Grades saved successfully!";
    } else {
        $_SESSION['error_message'] = "No grades were provided.";
    }

    // Redirect back to the grades page
    header("Location: grades.php");
    exit();
}
?>
