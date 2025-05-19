<?php
require_once 'config/database.php';

// Read the SQL file
$sql = file_get_contents('database.sql');

// Split the SQL file into individual queries
$queries = array_filter(array_map('trim', explode(';', $sql)));

// Execute each query
$success = true;
$errors = [];

foreach ($queries as $query) {
    if (!empty($query)) {
        try {
            if (!$conn->query($query)) {
                $success = false;
                $errors[] = "Error executing query: " . $conn->error;
            }
        } catch (Exception $e) {
            $success = false;
            $errors[] = "Exception: " . $e->getMessage();
        }
    }
}

// Output results
if ($success) {
    echo "<h2>Database setup completed successfully!</h2>";
    echo "<p>The following tables have been created:</p>";
    echo "<ul>";
    echo "<li>users - For user authentication</li>";
    echo "<li>customers - For customer profiles</li>";
    echo "<li>cart - For shopping cart items</li>";
    echo "<li>orders - For order information</li>";
    echo "<li>order_items - For individual items in orders</li>";
    echo "</ul>";
    echo "<p>Default admin account created:</p>";
    echo "<ul>";
    echo "<li>Username: admin</li>";
    echo "<li>Email: admin@solunar.com</li>";
    echo "<li>Password: admin123</li>";
    echo "</ul>";
} else {
    echo "<h2>Database setup encountered errors:</h2>";
    echo "<ul>";
    foreach ($errors as $error) {
        echo "<li>" . htmlspecialchars($error) . "</li>";
    }
    echo "</ul>";
}
?> 