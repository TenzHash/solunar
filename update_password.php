<?php
require_once 'config/database.php';

// Generate new password hash
$password = "admin123";
$hash = password_hash($password, PASSWORD_DEFAULT);

// Update the admin password
$stmt = $conn->prepare("UPDATE admin_accounts SET password = ? WHERE username = 'admin'");
$stmt->bind_param("s", $hash);

if ($stmt->execute()) {
    echo "<h2>Password Updated Successfully</h2>";
    echo "<p>New password hash: " . $hash . "</p>";
    
    // Verify the new password
    if (password_verify($password, $hash)) {
        echo "<p style='color: green;'>Password verification successful!</p>";
    } else {
        echo "<p style='color: red;'>Password verification failed!</p>";
    }
} else {
    echo "Error updating password: " . $stmt->error;
}

$stmt->close();
mysqli_close($conn);
?> 