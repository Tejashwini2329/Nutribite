<?php
include __DIR__ . '/db_connect.php'; // Ensure correct file path

$message = ""; // Message for success or error feedback

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST["username"])); 
$email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);

   
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    if (strlen($password) < 8 || 
    !preg_match('/[A-Z]/', $password) || 
    !preg_match('/[\W]/', $password) || 
    !preg_match('/[0-9]/', $password)) { 
    $message = "<p class='error'>❌ Password must be at least 8 characters, contain one uppercase letter, one number, and one special character!</p>";
}

    // Check for empty fields
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $message = "<p class='error'>❌ All fields are required!</p>";
    } 
    // Check if passwords match
    elseif ($password !== $confirm_password) {
        $message = "<p class='error'>❌ Passwords do not match!</p>";
    } 
    // Enforce strong password rules
    elseif (strlen($password) < 8 || 
    !preg_match('/[A-Z]/', $password) || 
    !preg_match('/[\W]/', $password) || 
    !preg_match('/[0-9]/', $password)) { 
    $message = "<p class='error'>❌ Password must be at least 8 characters, contain one uppercase letter, one number, and one special character!</p>";
}

    else {
        // Check if email already exists
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE LOWER(email) = LOWER(?)");

       
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_stmt->store_result();
        
        if ($check_stmt->num_rows > 0) {
            $message = "<p class='error'>❌ Email already registered! <a href='login.php'>Login here</a></p>";
        } else {
            // Hash the password before storing
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($stmt->execute()) {
                $message = "<p class='success'>✅ Signup successful! <a href='login.php'>Login here</a></p>";
            } else {
                $message = "<p class='error'>❌ Error: " . $stmt->error . "</p>";
            }

            $stmt->close();
        }
        $check_stmt->close();
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - Nutri Bite</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    
    <header>
        <h1>Nutri Bite</h1>
        <nav>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </nav>
    </header>
    
    <section id="signup">
        <h2>Signup</h2>
        <?php echo $message; ?>
        <form action="signup.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Signup</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </section>
    
    <footer>
        <p>&copy; 2025 Nutri Bite. All Rights Reserved.</p>
    </footer>
</body>
</html>
