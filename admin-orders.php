<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: admin-login.php');
    exit;
}

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $tracking_number = $_POST['tracking_number'] ?? null;
    
    $stmt = $conn->prepare("UPDATE orders SET status = ?, tracking_number = ? WHERE id = ?");
    $stmt->bind_param("ssi", $status, $tracking_number, $order_id);
    $stmt->execute();
    
    // Redirect to prevent form resubmission
    header('Location: admin-orders.php?success=1');
    exit;
}

// Get all orders with user information
$stmt = $conn->prepare("
    SELECT o.*, 
           u.email as user_email,
           COUNT(oi.id) as total_items
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.id
    LEFT JOIN order_items oi ON o.id = oi.order_id
    GROUP BY o.id
    ORDER BY o.created_at DESC
");
$stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Manage Orders - Solunar Admin</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">

    <style>
        .order-card {
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }
        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .status-badge {
            font-size: 0.85rem;
            padding: 0.35rem 0.65rem;
        }
        .order-details {
            font-size: 0.9rem;
        }
        .admin-sidebar {
            min-height: 100vh;
            background: #f8f9fa;
            padding: 20px;
        }
        .admin-content {
            padding: 20px;
        }
        .nav-link {
            color: #333;
        }
        .nav-link:hover {
            background: #e9ecef;
        }
        .nav-link.active {
            background: #0d6efd;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/admin-sidebar.php'; ?>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 admin-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Manage Orders</h2>
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Order status updated successfully!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (empty($orders)): ?>
                    <div class="alert alert-info">
                        No orders found.
                    </div>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <div class="card order-card">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <h5 class="card-title mb-0">Order #<?php echo $order['id']; ?></h5>
                                        <small class="text-muted">
                                            <?php echo date('F j, Y', strtotime($order['created_at'])); ?>
                                        </small>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="order-details">
                                            <strong>Customer:</strong><br>
                                            <?php echo htmlspecialchars($order['user_email']); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="order-details">
                                            <strong>Total Items:</strong> <?php echo $order['total_items']; ?><br>
                                            <strong>Total Amount:</strong> ₱<?php echo number_format($order['total_amount'], 2); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="order-details">
                                            <strong>Payment Method:</strong><br>
                                            <?php echo ucwords(str_replace('_', ' ', $order['payment_method'])); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <form method="POST" class="d-flex align-items-center gap-2">
                                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                            <select name="status" class="form-select form-select-sm" style="width: auto;">
                                                <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                <option value="processing" <?php echo $order['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                                <option value="completed" <?php echo $order['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                                <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                            </select>
                                            <input type="text" name="tracking_number" class="form-control form-control-sm" 
                                                   placeholder="Tracking #" value="<?php echo htmlspecialchars($order['tracking_number'] ?? ''); ?>"
                                                   style="width: 150px;">
                                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                        </form>
                                        <div class="mt-2">
                                            <span class="badge status-badge bg-<?php 
                                                echo match($order['payment_status']) {
                                                    'paid' => 'success',
                                                    'pending' => 'warning',
                                                    'failed' => 'danger',
                                                    default => 'secondary'
                                                };
                                            ?>">
                                                <?php echo ucfirst($order['payment_status']); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-3">
                                    <button class="btn btn-sm btn-outline-primary" type="button" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#orderDetails<?php echo $order['id']; ?>">
                                        View Details
                                    </button>
                                </div>
                                
                                <div class="collapse mt-3" id="orderDetails<?php echo $order['id']; ?>">
                                    <div class="card card-body bg-light">
                                        <h6>Shipping Address</h6>
                                        <p class="mb-3"><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
                                        
                                        <?php
                                        // Get order items
                                        $stmt = $conn->prepare("
                                            SELECT oi.*, p.name, p.image_url
                                            FROM order_items oi
                                            JOIN products p ON oi.product_id = p.id
                                            WHERE oi.order_id = ?
                                        ");
                                        $stmt->bind_param("i", $order['id']);
                                        $stmt->execute();
                                        $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                                        ?>
                                        
                                        <h6>Order Items</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Item</th>
                                                        <th>Price</th>
                                                        <th>Quantity</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($items as $item): ?>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                                                                         alt="<?php echo htmlspecialchars($item['name']); ?>"
                                                                         class="me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                                    <?php echo htmlspecialchars($item['name']); ?>
                                                                </div>
                                                            </td>
                                                            <td>₱<?php echo number_format($item['price'], 2); ?></td>
                                                            <td><?php echo $item['quantity']; ?></td>
                                                            <td>₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>
</body>
</html> 