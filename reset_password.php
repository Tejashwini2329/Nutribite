<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = "";

// Ensure token is present
$token = isset($_GET['token']) ? $_GET['token'] : (isset($_POST['token']) ? $_POST['token'] : "");

if (empty($token)) {
    die("<p class='error'>Invalid request. Missing reset token.</p>");
}

// Generate CSRF token if not set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("<p class='error'>CSRF token validation failed.</p>");
    }

    $new_password = trim($_POST['new_password']);

    // Password validation
    if (strlen($new_password) < 8) {
        $message = "<p class='error'>Password must be at least 8 characters long.</p>";
    } elseif (!preg_match('/[A-Z]/', $new_password) || !preg_match('/[0-9]/', $new_password)) {
        $message = "<p class='error'>Password must contain at least one uppercase letter and one number.</p>";
    } else {
        $conn = new mysqli("localhost", "root", "", "task3");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if token exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? LIMIT 1");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {
            $message = "<p class='error'>Invalid reset token. Please request a new one.</p>";
        } else {
            $stmt->bind_result($user_id);
            $stmt->fetch();
            $stmt->close();

            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update password and remove reset token
            $stmt = $conn->prepare("UPDATE users SET password=?, reset_token=NULL WHERE id=?");
            $stmt->bind_param("si", $hashed_password, $user_id);

            if ($stmt->execute()) {
                unset($_SESSION['csrf_token']); // Clear CSRF token after use
                $message = "<p class='message'>Password reset successful! <a href='login.php'>Login here</a></p>";
            } else {
                $message = "<p class='error'>Error updating password.</p>";
            }
            $stmt->close();
        }
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Reset Password</h1>
    </header>

    <section>
        <h2>Enter New Password</h2>
        <form action="reset_password.php" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>
            <button type="submit">Reset Password</button>
        </form>
        <?php echo $message; ?>
    </section>
</body>
</html>
