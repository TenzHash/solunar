<?php
// Database configuration
$host = "localhost";
$db_name = "solunar_db";
$username = "root";
$password = "Tj09129337422@";

// Create connection
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8
$conn->set_charset("utf8");
?> 