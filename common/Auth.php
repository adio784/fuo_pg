<?php
// common/Auth.php
class Auth {
    private $db;

    public function __construct($database) {
        $this->db = $database->getConnection();
    }

    public function register($username, $password) {
        // Sanitize input to prevent SQL injection
        $username = htmlspecialchars($username);

        // Check if the username is already taken
        $stmt = $this->db->prepare('SELECT id FROM users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            // Username is already taken
            return false;
        }

        // Hash the password (you should use a strong password hashing library)
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert the new user into the database
        $stmt = $this->db->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
        $result = $stmt->execute([$username, $hashedPassword]);

        return $result; // Return true if registration is successful, or false if there was an error
    }


    public function login($username, $password) {
        // Sanitize input to prevent SQL injection
        $username = htmlspecialchars($username);
        $password = htmlspecialchars($password);

        // Fetch user data from the database
        $stmt = $this->db->prepare('SELECT id, username, password FROM users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Password is correct, create a session or token to indicate successful login
                $_SESSION['user_id'] = $user['id'];
                return true;
            }
        }

        // Login failed
        return false;
    }
    

    public function passwordReset($email) {
        // Generate a unique reset token (you can use a library for this)
        $resetToken = uniqid();

        // Store the token in the database along with the user's email and a timestamp
        $stmt = $this->db->prepare('INSERT INTO password_resets (email, token, created_at) VALUES (?, ?, NOW())');
        $result = $stmt->execute([$email, $resetToken]);

        if ($result) {
            // Send a password reset email to the user with a link containing the token
            $resetLink = 'https://example.com/reset-password?token=' . $resetToken;

            // You should use a library or service to send the email
            // Example using PHP's built-in mail function (not recommended for production):
            $to = $email;
            $subject = 'Password Reset';
            $message = 'Click the following link to reset your password: ' . $resetLink;
            $headers = 'From: your_email@example.com' . "\r\n" .
                       'Reply-To: your_email@example.com' . "\r\n" .
                       'X-Mailer: PHP/' . phpversion();

            $mailSent = mail($to, $subject, $message, $headers);

            if ($mailSent) {
                return true; // Password reset initiated successfully
            } else {
                return false; // Failed to send the email
            }
        }

        return false; // Failed to insert the reset token into the database
    }

    public function emailVerification($email) {

        // Generate a unique verification token (you can use a library for this)
        $verificationToken = uniqid();

        // Store the token in the database along with the user's email
        $stmt = $this->db->prepare('INSERT INTO email_verifications (email, token, created_at) VALUES (?, ?, NOW())');
        $result = $stmt->execute([$email, $verificationToken]);

        if ($result) {
            // Send an email to the user with the verification link
            $verificationLink = 'https://example.com/verify-email?token=' . $verificationToken;

            // You should use a library or service to send the email
            // Example using PHP's built-in mail function (not recommended for production):
            $to = $email;
            $subject = 'Email Verification';
            $message = 'Click the following link to verify your email address: ' . $verificationLink;
            $headers = 'From: your_email@example.com' . "\r\n" .
                       'Reply-To: your_email@example.com' . "\r\n" .
                       'X-Mailer: PHP/' . phpversion();

            $mailSent = mail($to, $subject, $message, $headers);

            if ($mailSent) {
                return true; // Email verification initiated successfully
            } else {
                return false; // Failed to send the email
            }
        }

        return false; // Failed to insert the verification token into the database
    }


    public function logout() {
        // Check if the user is logged in (authenticated)
        if ($this->isAuthenticated()) {
            // Unset or destroy the session variables, effectively logging the user out
            session_unset();
            session_destroy();
            return true; // Logout successful
        }

        return false; // Logout failed (user was not authenticated)
    }


    public function isAuthenticated() {
        // Check if a session variable or token exists that indicates the user is authenticated
        if (isset($_SESSION['user_id'])) {
            return true; // User is authenticated
        }

        return false; // User is not authenticated
    }

    // You can add more methods for features like password reset, user management, etc.
}
?>
