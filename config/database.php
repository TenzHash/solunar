<?php
// Database configuration
$host = "localhost";
$dbname = "solunar_db";
$username = "root";
$password = ""; // Default XAMPP password is empty

// First, try to connect without database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === FALSE) {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($dbname);

// Set charset to utf8
$conn->set_charset("utf8");
?>