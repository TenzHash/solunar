<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Check if order_id is provided
if (!isset($_GET['order_id'])) {
    header('Location: home.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$order_id = $_GET['order_id'];

// Get order details
$stmt = $conn->prepare("
    SELECT o.*, u.username, u.email
    FROM orders o
    JOIN users u ON o.user_id = u.id
    WHERE o.id = ? AND o.user_id = ?
");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    header('Location: home.php');
    exit;
}

// Get order items
$stmt = $conn->prepare("
    SELECT oi.*, p.name, p.image_url
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Solunar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .order-details {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
        }
        .status-badge {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="order-details">
                    <div class="text-center mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                        <h1 class="mt-3">Order Confirmed!</h1>
                        <p class="text-muted">Thank you for your purchase</p>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Order Information</h5>
                            <p class="mb-1">Order #: <?php echo str_pad($order['id'], 8, '0', STR_PAD_LEFT); ?></p>
                            <p class="mb-1">Date: <?php echo date('F j, Y', strtotime($order['created_at'])); ?></p>
                            <p class="mb-1">Status: 
                                <span class="badge bg-<?php echo $order['status'] === 'pending' ? 'warning' : 'success'; ?> status-badge">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h5>Payment Method</h5>
                            <p class="mb-1"><?php echo strtoupper($order['payment_method']); ?></p>
                            <p class="mb-1">Total Amount: ₱<?php echo number_format($order['total_amount'], 2); ?></p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5>Shipping Information</h5>
                        <p class="mb-1"><?php echo htmlspecialchars($order['shipping_address']); ?></p>
                        <p class="mb-1"><?php echo htmlspecialchars($order['shipping_city']); ?>, <?php echo htmlspecialchars($order['shipping_postal_code']); ?></p>
                        <p class="mb-1">Phone: <?php echo htmlspecialchars($order['shipping_phone']); ?></p>
                    </div>

                    <h5 class="mb-3">Order Items</h5>
                    <?php foreach ($order_items as $item): ?>
                        <div class="d-flex align-items-center mb-3">
                            <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                 class="product-image me-3">
                            <div class="flex-grow-1">
                                <h6 class="mb-0"><?php echo htmlspecialchars($item['name']); ?></h6>
                                <small class="text-muted">Quantity: <?php echo $item['quantity']; ?></small>
                            </div>
                            <div class="text-end">
                                <strong>₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></strong>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <strong>₱<?php echo number_format($order['total_amount'], 2); ?></strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping</span>
                        <strong>₱0.00</strong>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-4">
                        <span>Total</span>
                        <strong class="text-primary">₱<?php echo number_format($order['total_amount'], 2); ?></strong>
                    </div>

                    <div class="text-center">
                        <a href="home.php" class="btn btn-primary">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 