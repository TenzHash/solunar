<?php
session_start();
require_once '../../config/database.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in to continue']);
    exit;
}

// Validate required fields
$required_fields = ['full_name', 'email', 'address', 'city', 'postal_code', 'phone', 'payment_method'];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
        exit;
    }
}

$user_id = $_SESSION['user_id'];

// Start transaction
$conn->begin_transaction();

try {
    // Get customer information
    $stmt = $conn->prepare("SELECT id FROM customers WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $customer = $stmt->get_result()->fetch_assoc();
    
    // If customer doesn't exist, create one
    if (!$customer) {
        $stmt = $conn->prepare("
            INSERT INTO customers (
                user_id, 
                first_name, 
                last_name, 
                phone, 
                address, 
                city, 
                postal_code
            ) VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        $name_parts = explode(' ', $_POST['full_name']);
        $first_name = $name_parts[0];
        $last_name = count($name_parts) > 1 ? implode(' ', array_slice($name_parts, 1)) : '';
        
        $stmt->bind_param(
            "issssss",
            $user_id,
            $first_name,
            $last_name,
            $_POST['phone'],
            $_POST['address'],
            $_POST['city'],
            $_POST['postal_code']
        );
        $stmt->execute();
        $customer_id = $conn->insert_id;
    } else {
        $customer_id = $customer['id'];
        
        // Update customer information
        $stmt = $conn->prepare("
            UPDATE customers 
            SET phone = ?, 
                address = ?, 
                city = ?, 
                postal_code = ? 
            WHERE id = ?
        ");
        $stmt->bind_param(
            "ssssi",
            $_POST['phone'],
            $_POST['address'],
            $_POST['city'],
            $_POST['postal_code'],
            $customer_id
        );
        $stmt->execute();
    }

    // Get cart items
    $stmt = $conn->prepare("
        SELECT c.*, p.name, p.price, p.stock 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $cart_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    if (empty($cart_items)) {
        throw new Exception('Your cart is empty');
    }

    // Calculate total amount
    $total_amount = 0;
    foreach ($cart_items as $item) {
        $total_amount += $item['price'] * $item['quantity'];
        
        // Check stock availability
        if ($item['quantity'] > $item['stock']) {
            throw new Exception("Insufficient stock for {$item['name']}");
        }
    }

    // Create order
    $stmt = $conn->prepare("
        INSERT INTO orders (
            user_id,
            customer_id,
            total_amount, 
            shipping_address, 
            shipping_city, 
            shipping_postal_code, 
            shipping_phone,
            payment_method,
            status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')
    ");
    
    $stmt->bind_param(
        "iidsssss",
        $user_id,
        $customer_id,
        $total_amount,
        $_POST['address'],
        $_POST['city'],
        $_POST['postal_code'],
        $_POST['phone'],
        $_POST['payment_method']
    );
    
    $stmt->execute();
    $order_id = $conn->insert_id;

    // Create order items
    $stmt = $conn->prepare("
        INSERT INTO order_items (
            order_id, 
            product_id, 
            quantity, 
            price,
            subtotal
        ) VALUES (?, ?, ?, ?, ?)
    ");

    foreach ($cart_items as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $stmt->bind_param(
            "iiidi",
            $order_id,
            $item['product_id'],
            $item['quantity'],
            $item['price'],
            $subtotal
        );
        $stmt->execute();

        // Update product stock
        $new_stock = $item['stock'] - $item['quantity'];
        $update_stock = $conn->prepare("UPDATE products SET stock = ? WHERE id = ?");
        $update_stock->bind_param("ii", $new_stock, $item['product_id']);
        $update_stock->execute();
    }

    // Clear cart
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    // Commit transaction
    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Order placed successfully',
        'order_id' => $order_id
    ]);

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 