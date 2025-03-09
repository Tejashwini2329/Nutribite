<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    $conn = new mysqli("localhost", "root", "", "task3");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) { // Email exists
        $stmt->close();

        // Generate a secure token
        $token = bin2hex(random_bytes(50));

        // Store the reset token in the database
        $stmt = $conn->prepare("UPDATE users SET reset_token=? WHERE email=?");
        $stmt->bind_param("ss", $token, $email);

        if ($stmt->execute()) {
            // Show the reset link directly on the page
            $reset_link = "http://localhost/nutribite/reset_password.php?token=$token";
            $message = "<p class='message'>Password reset link: <a href='$reset_link'>Click here</a></p>";
        } else {
            $message = "<p class='error'>Error updating reset token.</p>";
        }
        $stmt->close();
    } else {
        $message = "<p class='error'>Email not found.</p>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Forgot Password</h1>
    </header>

    <section>
        <h2>Reset Your Password</h2>
        <form action="forgot_password.php" method="POST">
            <label for="email">Enter your Email:</label>
            <input type="email" id="email" name="email" required>
            <button type="submit">Generate Reset Link</button>
        </form>
        <?php echo $message; ?>
    </section>
</body>
</html>
