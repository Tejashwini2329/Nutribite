

<?php


session_start();
include 'db_connect.php';  // Ensure correct database connection

$message = "";  // To display errors

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
    $password = isset($_POST["password"]) ? $_POST["password"] : "";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<p class='error'>❌ Invalid email format.</p>";
    }
    

    if (empty($email) || empty($password)) {
        $message = "<p class='error'>❌ Please fill in all fields.</p>";
    } 
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<p class='error'>❌ Invalid email format.</p>";
    }else {
        // Check if the email exists in the database
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        
        $stmt->store_result();
        $stmt->bind_result($id, $username, $hashed_password);
        $stmt->fetch();

        if ($stmt->num_rows > 0) {
            // Verify the entered password against the hashed password
            if (password_verify($password, $hashed_password)) {
                session_regenerate_id(true);
                $_SESSION["user_id"] = $id;
                $_SESSION["username"] = $username;
                header("Location: home.php"); // Redirect to dashboard
                exit();
            } else {
                $message = "<p class='error'>❌ Invalid email or password.</p>";
            }
        } else {
            $message = "<p class='error'>❌ No account found with that email.</p>";
        }

        $stmt->close();
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Signup - Nutri Bite</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Nutri Bite</h1>
        <nav>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="signup.php">Signup</a></li>
            </ul>
        </nav>
    </header>
    
    <section id="login">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <input type="text" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <p><a href="forgot_password.php">Forgot Password?</a></p>

        <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
    </section>
    
   
    
    <footer>
        <p>&copy; 2025 Nutri Bite. All Rights Reserved.</p>
    </footer>
</body>
</html>
