<?php
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

// Get and sanitize input data
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$review = filter_input(INPUT_POST, 'review', FILTER_SANITIZE_STRING);
$service = filter_input(INPUT_POST, 'service', FILTER_SANITIZE_STRING);
$rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);

// Validate required fields (name and email are optional)
if (!$review || !$service || !$rating) {
    echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields.']);
    exit;
}

// If email is provided, validate it
if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email format']);
    exit;
}

// Validate rating (1-5)
if ($rating < 1 || $rating > 5) {
    echo json_encode(['status' => 'error', 'message' => 'Rating must be between 1 and 5']);
    exit;
}

try {
    // Prepare and execute the insert query
    $stmt = $conn->prepare("INSERT INTO reviews (name, email, review, service, rating) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $name, $email, $review, $service, $rating);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Review submitted successfully']);
    } else {
        throw new Exception("Error executing query");
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to submit review. Please try again later.']);
}

$stmt->close();
$conn->close();
?> 