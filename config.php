<?php
$host = "localhost";      // usually localhost
$user = "root";           // your DB username
$pass = "";               // your DB password (empty for XAMPP default)
$db   = "employee_management";  // your database name

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
