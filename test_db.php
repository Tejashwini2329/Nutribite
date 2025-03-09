<?php
include 'db_connect.php';

if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}

$sql = "SHOW TABLES LIKE 'users'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "✅ Database connected successfully! 'users' table found.";
} else {
    echo "⚠️ Database connected, but 'users' table is missing!";
}

$conn->close();
?>
