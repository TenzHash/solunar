<?php
session_start();
require_once '../../config/database.php';

// Set CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Disable error display
ini_set('log_errors', 1); // Enable error logging

// Function to send JSON response
function sendJsonResponse($success, $message, $redirect = null) {
    $response = [
        'success' => $success,
        'message' => $message
    ];
    if ($redirect !== null) {
        $response['redirect'] = $redirect;
    }
    echo json_encode($response);
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in to continue']);
    exit;
}

// Check if orders table exists
$result = $conn->query("SHOW TABLES LIKE 'orders'");
if ($result->num_rows == 0) {
    // Create orders table
    $sql = "CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        first_name VARCHAR(100) NOT NULL,
        last_name VARCHAR(100) NOT NULL,
        email VARCHAR(255) NOT NULL,
        total_amount DECIMAL(10,2) NOT NULL,
        address VARCHAR(255) NOT NULL,
        city VARCHAR(100) NOT NULL,
        postal_code VARCHAR(20) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        payment_method VARCHAR(50) NOT NULL,
        status VARCHAR(20) NOT NULL DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )";
    
    if (!$conn->query($sql)) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
        exit;
    }
} else {
    // Check and add missing columns
    $columns = [
        'first_name' => 'VARCHAR(100) NOT NULL',
        'last_name' => 'VARCHAR(100) NOT NULL',
        'email' => 'VARCHAR(255) NOT NULL'
    ];
    
    foreach ($columns as $column => $definition) {
        $result = $conn->query("SHOW COLUMNS FROM orders LIKE '$column'");
        if ($result->num_rows == 0) {
            $sql = "ALTER TABLE orders ADD COLUMN $column $definition";
            if (!$conn->query($sql)) {
                echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
                exit;
            }
        }
    }
    
    // Add foreign key if it doesn't exist
    $result = $conn->query("
        SELECT COUNT(*) as count 
        FROM information_schema.KEY_COLUMN_USAGE 
        WHERE TABLE_NAME = 'orders' 
        AND COLUMN_NAME = 'user_id' 
        AND REFERENCED_TABLE_NAME = 'users'
    ");
    if ($result->fetch_assoc()['count'] == 0) {
        $sql = "ALTER TABLE orders ADD FOREIGN KEY (user_id) REFERENCES users(id)";
        if (!$conn->query($sql)) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
            exit;
        }
    }
}

// Check if order_items table exists
$result = $conn->query("SHOW TABLES LIKE 'order_items'");
if ($result->num_rows == 0) {
    // Create order_items table
    $sql = "CREATE TABLE IF NOT EXISTS order_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        product_id INT NOT NULL,
        quantity INT NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        FOREIGN KEY (order_id) REFERENCES orders(id),
        FOREIGN KEY (product_id) REFERENCES products(id)
    )";
    
    if (!$conn->query($sql)) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
        exit;
    }
}

// Validate required fields
$required_fields = ['first_name', 'last_name', 'email', 'address', 'city', 'postal_code', 'phone', 'payment_method'];
$missing_fields = [];

foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        $missing_fields[] = $field;
    }
}

if (!empty($missing_fields)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Start transaction
    $conn->begin_transaction();

    // Get cart items
    $stmt = $conn->prepare("
        SELECT c.*, p.name, p.price, p.stock 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?
    ");
    
    if (!$stmt) {
        throw new Exception("Database error: " . $conn->error);
    }
    
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
            first_name,
            last_name,
            email,
            total_amount,
            shipping_address,
            payment_method,
            payment_status,
            status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', 'pending')
    ");
    
    if (!$stmt) {
        throw new Exception("Database error: " . $conn->error);
    }
    
    // Combine address fields into shipping_address
    $shipping_address = $_POST['address'] . ', ' . $_POST['city'] . ', ' . $_POST['postal_code'];

    $stmt->bind_param(
        "isssdss",
        $user_id,
        $_POST['first_name'],
        $_POST['last_name'],
        $_POST['email'],
        $total_amount,
        $shipping_address,
        $_POST['payment_method']
    );
    
    if (!$stmt->execute()) {
        throw new Exception("Database error: " . $stmt->error);
    }
    
    $order_id = $conn->insert_id;

    // Create order items and update stock
    foreach ($cart_items as $item) {
        // Add order item
        $stmt = $conn->prepare("
            INSERT INTO order_items (
                order_id,
                product_id,
                quantity,
                price
            ) VALUES (?, ?, ?, ?)
        ");
        
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        
        $stmt->bind_param(
            "iiid",
            $order_id,
            $item['product_id'],
            $item['quantity'],
            $item['price']
        );
        
        if (!$stmt->execute()) {
            throw new Exception("Database error: " . $stmt->error);
        }

        // Update stock
        $new_stock = $item['stock'] - $item['quantity'];
        $stmt = $conn->prepare("UPDATE products SET stock = ? WHERE id = ?");
        
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }
        
        $stmt->bind_param("ii", $new_stock, $item['product_id']);
        
        if (!$stmt->execute()) {
            throw new Exception("Database error: " . $stmt->error);
        }
    }

    // Clear cart
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    
    if (!$stmt) {
        throw new Exception("Database error: " . $conn->error);
    }
    
    $stmt->bind_param("i", $user_id);
    
    if (!$stmt->execute()) {
        throw new Exception("Database error: " . $stmt->error);
    }

    // Commit transaction
    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Order placed successfully!'
    ]);

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 