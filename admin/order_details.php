<?php
require_once '../config/database.php';
require_once 'includes/auth_check.php';

// Check if order ID is provided
if (!isset($_GET['id'])) {
    header('Location: orders.php');
    exit;
}

$order_id = $_GET['id'];

// Get order details with user information
$stmt = $conn->prepare("
    SELECT o.*, 
           u.email as user_email
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.id
    WHERE o.id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

// If order doesn't exist, redirect to orders page
if (!$order) {
    header('Location: orders.php');
    exit;
}

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $status = $_POST['status'];
    $tracking_number = $_POST['tracking_number'] ?? null;
    
    $stmt = $conn->prepare("UPDATE orders SET status = ?, tracking_number = ? WHERE id = ?");
    $stmt->bind_param("ssi", $status, $tracking_number, $order_id);
    $stmt->execute();
    
    // Redirect to prevent form resubmission
    header("Location: order_details.php?id=$order_id&success=1");
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
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Order Details - Solunar Admin</title>

    <!-- Favicons -->
    <link href="../assets/img/favicon.png" rel="icon">
    <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Vendor CSS Files -->
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="../assets/css/main.css" rel="stylesheet">

    <style>
        .order-details {
            font-size: 0.9rem;
        }
        .status-badge {
            font-size: 0.85rem;
            padding: 0.35rem 0.65rem;
        }
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-1">Order #<?php echo $order['id']; ?></h2>
                        <p class="text-muted mb-0">
                            Placed on <?php echo date('F j, Y g:i A', strtotime($order['created_at'])); ?>
                        </p>
                    </div>
                    <a href="orders.php" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Orders
                    </a>
                </div>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Order status updated successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <!-- Order Information -->
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title mb-4">Order Information</h5>
                                
                                <div class="mb-3">
                                    <h6>Customer Details</h6>
                                    <p class="mb-1">
                                        <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?>
                                    </p>
                                    <p class="mb-1"><?php echo htmlspecialchars($order['user_email']); ?></p>
                                </div>

                                <div class="mb-3">
                                    <h6>Shipping Address</h6>
                                    <p class="mb-0"><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
                                </div>

                                <div class="mb-3">
                                    <h6>Payment Method</h6>
                                    <p class="mb-0"><?php echo ucwords(str_replace('_', ' ', $order['payment_method'])); ?></p>
                                </div>

                                <div class="mb-3">
                                    <h6>Payment Status</h6>
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

                                <form method="POST" class="mt-4">
                                    <div class="mb-3">
                                        <label class="form-label">Order Status</label>
                                        <select name="status" class="form-select">
                                            <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="processing" <?php echo $order['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                            <option value="completed" <?php echo $order['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                            <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tracking Number</label>
                                        <input type="text" name="tracking_number" class="form-control" 
                                               value="<?php echo htmlspecialchars($order['tracking_number'] ?? ''); ?>"
                                               placeholder="Enter tracking number">
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Update Order</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-4">Order Items</h5>
                                
                                <div class="table-responsive">
                                    <table class="table">
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
                                                            <img src="<?php echo htmlspecialchars($item['image_url'] ? '../' . $item['image_url'] : '../assets/img/no-image.jpg'); ?>" 
                                                                 alt="<?php echo htmlspecialchars($item['name']); ?>"
                                                                 class="product-image me-3"
                                                                 onerror="this.src='../assets/img/no-image.jpg'">
                                                            <div>
                                                                <h6 class="mb-0"><?php echo htmlspecialchars($item['name']); ?></h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>₱<?php echo number_format($item['price'], 2); ?></td>
                                                    <td><?php echo $item['quantity']; ?></td>
                                                    <td>₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" class="text-end"><strong>Total Amount:</strong></td>
                                                <td><strong>₱<?php echo number_format($order['total_amount'], 2); ?></strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vendor JS Files -->
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/php-email-form/validate.js"></script>

    <!-- Main JS File -->
    <script src="../assets/js/main.js"></script>
</body>
</html> 