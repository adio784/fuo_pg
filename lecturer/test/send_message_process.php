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
    $class = $_POST['class'];
    $students = isset($_POST['students']) ? $_POST['students'] : [];
    $message = trim($_POST['message']);

    // Validate message
    if (empty($message)) {
        $_SESSION['error'] = "Message cannot be empty.";
        header("Location: send_message.php");
        exit();
    }

    // Determine recipients
    $recipients = [];
    if (!empty($class)) {
        // Fetch all students in the selected class
        $query = "SELECT email FROM students WHERE class = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $class);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $recipients[] = $row['email'];
        }
        $stmt->close();
    }

    if (!empty($students)) {
        // Fetch emails of selected students
        $placeholders = implode(',', array_fill(0, count($students), '?'));
        $types = str_repeat('s', count($students));
        $query = "SELECT email FROM students WHERE matric_number IN ($placeholders)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param($types, ...$students);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            if (!in_array($row['email'], $recipients)) {
                $recipients[] = $row['email'];
            }
        }
        $stmt->close();
    }

    // Send messages (simulate by outputting email addresses)
    if (!empty($recipients)) {
        foreach ($recipients as $email) {
            // Simulate email sending (use mail() in a real-world scenario)
            // mail($email, "Message from Lecturer", $message, "From: no-reply@university.com");
            echo "Message sent to: $email<br>";
        }
        $_SESSION['success'] = "Message sent to " . count($recipients) . " recipient(s).";
    } else {
        $_SESSION['error'] = "No recipients found.";
    }

    header("Location: send_message.php");
    exit();
}
?>
