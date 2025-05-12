<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = ""; // Default XAMPP password is empty
$db_name = "solunar_db";

// Create connection
$conn = new mysqli($host, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected to MySQL successfully.\n";

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $db_name";
if ($conn->query($sql) === FALSE) {
    die("Error creating database: " . $conn->error);
}

echo "Database '$db_name' created or already exists.\n";

// Select the database
$conn->select_db($db_name);
echo "Selected database '$db_name'.\n";

// Read and execute the schema file
$schema = file_get_contents(__DIR__ . '/admin_schema.sql');
if ($schema === false) {
    die("Error reading schema file");
}

echo "Read schema file successfully.\n";

// Split the schema into individual queries
$queries = array_filter(array_map('trim', explode(';', $schema)));

// Execute each query
$success = true;
foreach ($queries as $query) {
    if (!empty($query)) {
        if ($conn->query($query) === FALSE) {
            echo "Error executing query: " . $conn->error . "\n";
            echo "Query: " . $query . "\n\n";
            $success = false;
        } else {
            echo "Successfully executed query.\n";
        }
    }
}

if ($success) {
    echo "\nDatabase schema imported successfully!\n";
    echo "You can now log in with:\n";
    echo "Username: admin\n";
    echo "Password: admin123\n";
} else {
    echo "\nThere were some errors while importing the schema.\n";
    echo "Please check the error messages above.\n";
}

$conn->close();
?> 