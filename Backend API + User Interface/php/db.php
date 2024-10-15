<?php
$host = 'localhost';  // Database host
$db = 'db_name';    // Database name
$user = 'db_user';       // Database user
$pass = 'User_pass';           // Database password

// Create a connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>