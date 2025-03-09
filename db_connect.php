<?php
$servername = "localhost"; // Change if needed
$username = "root"; // Default username for XAMPP/WAMP
$password = ""; // Default password is empty in XAMPP/WAMP
$database = "task3"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


