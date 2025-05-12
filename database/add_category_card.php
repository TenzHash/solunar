<?php
require_once __DIR__ . '/../config/database.php';

// Read the SQL file
$sql = file_get_contents(__DIR__ . '/add_category_card.sql');

// Execute the SQL
if ($conn->multi_query($sql)) {
    echo "Category card column added successfully!";
} else {
    echo "Error adding category card column: " . $conn->error;
}

$conn->close();
?> 