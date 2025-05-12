<?php
// Database configuration
$host = "localhost";
$db_name = "solunar_db";
$username = "root";
$password = ""; // Default XAMPP password is empty

// First, try to connect without database
$conn = new mysqli($host, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $db_name";
if ($conn->query($sql) === FALSE) {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($db_name);

// Set charset to utf8
$conn->set_charset("utf8");
?>