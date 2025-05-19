<?php
// Start session and include database configuration
session_start();
require_once '../config/database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Get total products count
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM products");
$stmt->execute();
$total_products = $stmt->get_result()->fetch_assoc()['count'];

// Initialize counts
$total_reviews = 0;
$approved_reviews = 0;
$error = null;

// Fetch total reviews (from both tables)
try {
    $stmt = $conn->prepare("SELECT (SELECT COUNT(*) FROM testimonials) + (SELECT COUNT(*) FROM reviews) AS count");
    $stmt->execute();
    $result = $stmt->get_result();
    $total_reviews = $result ? (int)$result->fetch_assoc()['count'] : 0;
    $stmt->close();
} catch (Exception $e) {
    $error = "Error fetching total reviews.";
}

// Fetch approved reviews (from both tables)
try {
    $stmt = $conn->prepare("SELECT (SELECT COUNT(*) FROM testimonials WHERE status = ?) + (SELECT COUNT(*) FROM reviews WHERE status = ?) AS count");
    $status = 'approved';
    $stmt->bind_param("ss", $status, $status);
    $stmt->execute();
    $result = $stmt->get_result();
    $approved_reviews = $result ? (int)$result->fetch_assoc()['count'] : 0;
    $stmt->close();
} catch (Exception $e) {
    $error = "Error fetching approved reviews.";
}

// Get order statistics
$stmt = $conn->prepare("
    SELECT 
        COUNT(*) as total_orders,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_orders,
        SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_orders,
        SUM(total_amount) as total_sales
    FROM orders
");
$stmt->execute();
$order_stats = $stmt->get_result()->fetch_assoc();

// Get recent orders
$stmt = $conn->prepare("
    SELECT o.*, u.username, u.email
    FROM orders o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.created_at DESC
    LIMIT 5
");
$stmt->execute();
$recent_orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get recent activities
$stmt = $conn->prepare("
    SELECT al.*, aa.username as admin_name 
    FROM activity_logs al 
    LEFT JOIN admin_accounts aa ON al.admin_id = aa.id 
    ORDER BY al.created_at DESC 
    LIMIT 10
");
$stmt->execute();
$recent_activities = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get recent reviews
$stmt = $conn->prepare("
    SELECT t.*, p.name as product_name 
    FROM testimonials t 
    LEFT JOIN products p ON t.product_id = p.id 
    ORDER BY t.created_at DESC 
    LIMIT 5
");
$stmt->execute();
$recent_reviews = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get low stock products
$stmt = $conn->prepare("
    SELECT * FROM products 
    WHERE stock <= 5 
    ORDER BY stock ASC 
    LIMIT 5
");
$stmt->execute();
$low_stock_products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Start output buffering
ob_start();

function formatCategoryName($cat) {
    $cat = preg_replace('/[^a-zA-Z0-9 ]/', ' ', $cat); // Remove symbols
    $cat = ucwords(strtolower(str_replace('_', ' ', $cat)));
    return trim(preg_replace('/\s+/', ' ', $cat));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Solunar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .main-content {
            padding: 32px 24px 24px 24px;
            min-height: 100vh;
        }
        .topbar {
            position: sticky;
            top: 0;
            z-index: 10;
            background: #fff;
            box-shadow: 0 2px 12px rgba(0,123,255,0.04);
            border-radius: 0 0 18px 18px;
            padding: 18px 24px 12px 24px;
            margin-bottom: 32px;
        }
        .stat-card {
            border-radius: 18px;
            box-shadow: 0 2px 16px rgba(0,123,255,0.07);
            background: linear-gradient(90deg, #e3f0ff 60%, #f8f9fa 100%);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-4px) scale(1.01);
            box-shadow: 0 6px 24px rgba(0,123,255,0.13);
        }
        .card, .table {
            border-radius: 16px !important;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        }
        .card-header {
            border-radius: 16px 16px 0 0 !important;
            background: #f4f8ff;
            font-weight: 600;
        }
        .btn-primary, .btn-secondary {
            border-radius: 2rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(0,123,255,0.08);
            transition: background 0.2s, transform 0.2s;
        }
        .btn-primary {
            background: linear-gradient(90deg, #007bff 60%, #0d6efd 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #0d6efd 60%, #007bff 100%);
            transform: translateY(-2px) scale(1.03);
        }
        .btn-secondary {
            background: #e3f0ff;
            color: #007bff;
            border: none;
        }
        .btn-secondary:hover {
            background: #d0e3ff;
            color: #0056b3;
        }
        .badge {
            border-radius: 1rem;
            font-size: 0.95em;
            padding: 0.4em 0.9em;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .table-hover tbody tr:hover {
            background: #e3f0ff;
        }
        .rating {
            color: #ffc107;
        }
        @media (max-width: 991px) {
            .main-content { padding: 18px 4px; }
            .topbar { padding: 12px 8px; margin-bottom: 18px; }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php include 'includes/sidebar.php'; ?>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <div class="topbar d-flex flex-wrap justify-content-between align-items-center mb-4">
                    <h2 class="mb-0 fw-bold" style="color:#007bff;">Dashboard</h2>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="products.php" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Add Product
                        </a>
                        <a href="reviews.php" class="btn btn-secondary">
                            <i class="bi bi-star"></i> View Reviews
                        </a>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4 g-3">
                    <div class="col-md-4">
                        <div class="card stat-card text-center">
                            <div class="card-body py-4">
                                <div class="mb-2"><i class="bi bi-box-seam" style="font-size:2.2rem;color:#007bff;"></i></div>
                                <h5 class="card-title">Total Products</h5>
                                <h2 class="mb-0 fw-bold" style="color:#007bff;"><?php echo $total_products; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stat-card text-center">
                            <div class="card-body py-4">
                                <div class="mb-2"><i class="bi bi-star" style="font-size:2.2rem;color:#ffc107;"></i></div>
                                <h5 class="card-title">Total Reviews</h5>
                                <h2 class="mb-0 fw-bold" style="color:#ffc107;">
                                    <?php echo $error ? '<span class="text-danger">--</span>' : $total_reviews; ?>
                                </h2>
                                <?php if (!$error && $total_reviews == 0): ?>
                                    <div class="text-muted mt-2">No reviews yet.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stat-card text-center">
                            <div class="card-body py-4">
                                <div class="mb-2"><i class="bi bi-check-circle" style="font-size:2.2rem;color:#28a745;"></i></div>
                                <h5 class="card-title">Approved Reviews</h5>
                                <h2 class="mb-0 fw-bold" style="color:#28a745;">
                                    <?php echo $error ? '<span class="text-danger">--</span>' : $approved_reviews; ?>
                                </h2>
                                <?php if (!$error && $approved_reviews == 0): ?>
                                    <div class="text-muted mt-2">No approved reviews yet.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card text-center">
                            <div class="card-body py-4">
                                <div class="mb-2"><i class="bi bi-cart" style="font-size:2.2rem;color:#28a745;"></i></div>
                                <h5 class="card-title">Total Orders</h5>
                                <h2 class="mb-0 fw-bold" style="color:#28a745;"><?php echo $order_stats['total_orders']; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card text-center">
                            <div class="card-body py-4">
                                <div class="mb-2"><i class="bi bi-clock" style="font-size:2.2rem;color:#ffc107;"></i></div>
                                <h5 class="card-title">Pending Orders</h5>
                                <h2 class="mb-0 fw-bold" style="color:#ffc107;"><?php echo $order_stats['pending_orders']; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card text-center">
                            <div class="card-body py-4">
                                <div class="mb-2"><i class="bi bi-check-circle" style="font-size:2.2rem;color:#28a745;"></i></div>
                                <h5 class="card-title">Completed Orders</h5>
                                <h2 class="mb-0 fw-bold" style="color:#28a745;"><?php echo $order_stats['completed_orders']; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card text-center">
                            <div class="card-body py-4">
                                <div class="mb-2"><i class="bi bi-currency-dollar" style="font-size:2.2rem;color:#007bff;"></i></div>
                                <h5 class="card-title">Total Sales</h5>
                                <h2 class="mb-0 fw-bold" style="color:#007bff;">₱<?php echo number_format($order_stats['total_sales'], 2); ?></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger mt-3"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <div class="row g-4">
                    <!-- Recent Activities -->
                    <div class="col-lg-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Recent Activities</h5>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    <?php foreach ($recent_activities as $activity): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center rounded-3 mb-2" style="background:#f8f9fa;">
                                        <div>
                                            <strong><?php echo htmlspecialchars($activity['admin_name']); ?></strong>
                                            <?php echo htmlspecialchars($activity['action']); ?>d
                                            <?php echo htmlspecialchars($activity['entity_type']); ?>
                                            <?php if ($activity['details']): ?>
                                                - <?php echo htmlspecialchars($activity['details']); ?>
                                            <?php endif; ?>
                                        </div>
                                        <small class="text-muted">
                                            <?php echo date('M d, Y H:i', strtotime($activity['created_at'])); ?>
                                        </small>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Low Stock Products -->
                    <div class="col-lg-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Low Stock Products</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Category</th>
                                                <th>Price</th>
                                                <th>Stock</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($low_stock_products as $product): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                                <td><?php echo formatCategoryName($product['category']); ?></td>
                                                <td>₱<?php echo number_format($product['price'], 2); ?></td>
                                                <td>
                                                    <span class="badge bg-<?php echo $product['stock'] === 0 ? 'danger' : 'warning'; ?>">
                                                        <?php echo $product['stock']; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Reviews -->
                <div class="row g-4 mt-2">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Recent Reviews</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>User</th>
                                                <th>Product</th>
                                                <th>Rating</th>
                                                <th>Comment</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recent_reviews as $review): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($review['user_name']); ?></td>
                                                <td><?php echo htmlspecialchars($review['product_name']); ?></td>
                                                <td>
                                                    <span class="rating">
                                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                                            <i class="bi bi-star<?php echo $i <= $review['rating'] ? '-fill' : ''; ?>"></i>
                                                        <?php endfor; ?>
                                                    </span>
                                                </td>
                                                <td><?php echo htmlspecialchars($review['comment']); ?></td>
                                                <td><?php echo date('M d, Y H:i', strtotime($review['created_at'])); ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="col-12 mt-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Recent Orders</h5>
                            <a href="orders.php" class="btn btn-primary btn-sm">View All Orders</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Customer</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recent_orders as $order): ?>
                                            <tr>
                                                <td>#<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></td>
                                                <td>
                                                    <div><?php echo htmlspecialchars($order['username']); ?></div>
                                                    <small class="text-muted"><?php echo htmlspecialchars($order['email']); ?></small>
                                                </td>
                                                <td>₱<?php echo number_format($order['total_amount'], 2); ?></td>
                                                <td>
                                                    <span class="badge bg-<?php 
                                                        echo match($order['status']) {
                                                            'pending' => 'warning',
                                                            'processing' => 'info',
                                                            'completed' => 'success',
                                                            'cancelled' => 'danger',
                                                            default => 'secondary'
                                                        };
                                                    ?>">
                                                        <?php echo ucfirst($order['status']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo date('M d, Y H:i', strtotime($order['created_at'])); ?></td>
                                                <td>
                                                    <a href="order_details.php?id=<?php echo $order['id']; ?>" 
                                                       class="btn btn-sm btn-primary">
                                                        <i class="bi bi-eye"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
// End output buffering and flush
ob_end_flush();
?> 