<?php
session_start();
require_once '../../config/database.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to remove items from cart']);
    exit;
}

// Validate input
if (!isset($_POST['cart_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$cart_id = (int)$_POST['cart_id'];
$user_id = $_SESSION['user_id'];

try {
    // Check if cart item exists and belongs to user
    $stmt = $conn->prepare("SELECT id FROM cart WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cart_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Cart item not found']);
        exit;
    }
    
    // Remove item
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
    $stmt->bind_param("i", $cart_id);
    
    if ($stmt->execute()) {
        // Get updated cart count
        $stmt = $conn->prepare("SELECT SUM(quantity) as total FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $total = $stmt->get_result()->fetch_assoc()['total'];
        
        echo json_encode([
            'success' => true, 
            'message' => 'Item removed from cart',
            'cart_count' => $total
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to remove item from cart']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'An error occurred']);
}

$stmt->close();
$conn->close(); 