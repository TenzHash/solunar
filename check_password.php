<?php
require_once 'config/database.php';

// Query to get the admin password hash
$query = "SELECT username, password FROM admin_accounts WHERE username = 'admin'";
$result = mysqli_query($conn, $query);

if ($result && $row = mysqli_fetch_assoc($result)) {
    echo "<h2>Admin Password Hash:</h2>";
    echo "<p>Username: " . htmlspecialchars($row['username']) . "</p>";
    echo "<p>Password Hash: " . htmlspecialchars($row['password']) . "</p>";
    
    // Test password verification
    $test_password = "admin123";
    if (password_verify($test_password, $row['password'])) {
        echo "<p style='color: green;'>Password verification successful!</p>";
    } else {
        echo "<p style='color: red;'>Password verification failed!</p>";
    }
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?> 