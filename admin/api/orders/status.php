<?php
session_start();
require_once '../../../config/database.php';

header('Content-Type: application/json');

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Validate input
if (!isset($_POST['order_id']) || !isset($_POST['status'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit;
}

$order_id = (int)$_POST['order_id'];
$status = $_POST['status'];

// Validate status
$valid_statuses = ['pending', 'processing', 'completed', 'cancelled'];
if (!in_array($status, $valid_statuses)) {
    echo json_encode(['success' => false, 'message' => 'Invalid status']);
    exit;
}

try {
    // Update order status
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);
    
    if ($stmt->execute()) {
        // Log activity
        $admin_id = $_SESSION['admin_id'];
        $action = "Updated order #$order_id status to $status";
        $stmt = $conn->prepare("INSERT INTO activity_logs (admin_id, action) VALUES (?, ?)");
        $stmt->bind_param("is", $admin_id, $action);
        $stmt->execute();
        
        echo json_encode(['success' => true, 'message' => 'Order status updated successfully']);
    } else {
        throw new Exception("Failed to update order status");
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
}

$stmt->close();
$conn->close(); 