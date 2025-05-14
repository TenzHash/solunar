<?php
require_once __DIR__ . '/../config/database.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set JSON response header
header('Content-Type: application/json');

// Check database connection
if (!isset($conn) || $conn->connect_error) {
    logError("Database connection failed: " . ($conn->connect_error ?? 'Connection not established'));
    echo json_encode(['status' => 'error', 'message' => 'Database connection error. Please try again later.']);
    exit;
}

// Log function
function logError($message) {
    $log_dir = __DIR__ . '/../logs';
    if (!file_exists($log_dir)) {
        mkdir($log_dir, 0777, true);
    }
    error_log(date('[Y-m-d H:i:s] ') . $message . "\n", 3, $log_dir . "/appointment_errors.log");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Log the incoming request
    logError("Received appointment request: " . print_r($_POST, true));

    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $date = $_POST['date'] ?? '';
    $service = $_POST['appointment'] ?? '';
    $location = $_POST['location'] ?? '';
    $message = $_POST['message'] ?? '';

    // Validate required fields
    if (empty($name) || empty($email) || empty($phone) || empty($date) || empty($service) || empty($location)) {
        logError("Missing required fields");
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields']);
        exit;
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        logError("Invalid email format: " . $email);
        echo json_encode(['status' => 'error', 'message' => 'Please enter a valid email address']);
        exit;
    }

    try {
        // Check if appointments table exists
        $table_check = $conn->query("SHOW TABLES LIKE 'appointments'");
        if ($table_check->num_rows == 0) {
            // Create appointments table if it doesn't exist
            $create_table = "CREATE TABLE IF NOT EXISTS appointments (
                id INT PRIMARY KEY AUTO_INCREMENT,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL,
                phone VARCHAR(20) NOT NULL,
                appointment_date DATETIME NOT NULL,
                service_type VARCHAR(50) NOT NULL,
                location VARCHAR(50) NOT NULL,
                message TEXT,
                status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";
            
            if (!$conn->query($create_table)) {
                throw new Exception("Error creating table: " . $conn->error);
            }
        }

        // Prepare and execute the SQL statement
        $stmt = $conn->prepare("INSERT INTO appointments (name, email, phone, appointment_date, service_type, location, message) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("sssssss", $name, $email, $phone, $date, $service, $location, $message);

        if ($stmt->execute()) {
            // Return success response
            echo json_encode(['status' => 'success']);
        } else {
            throw new Exception("Failed to save appointment: " . $stmt->error);
        }

        $stmt->close();
    } catch (Exception $e) {
        logError("Error saving appointment: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Error submitting appointment request. Please try again later.']);
    }
} else {
    logError("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
