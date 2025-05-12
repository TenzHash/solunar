<?php
require_once __DIR__ . '/../config/database.php';

// Read the SQL file
$sql = file_get_contents(__DIR__ . '/add_featured_column.sql');

// Execute the SQL
if ($conn->multi_query($sql)) {
    echo "Featured column added successfully!";
} else {
    echo "Error adding featured column: " . $conn->error;
}

$conn->close();
?>