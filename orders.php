<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: customer-login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get user's orders
$stmt = $conn->prepare("
    SELECT o.*, 
           COUNT(oi.id) as total_items
    FROM orders o
    LEFT JOIN order_items oi ON o.id = oi.order_id
    WHERE o.user_id = ?
    GROUP BY o.id
    ORDER BY o.created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>My Orders - Solunar</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

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
    </style>
</head>
<body>
    <header id="header" class="header sticky-top">
        <div class="branding d-flex align-items-center">
            <div class="container position-relative d-flex align-items-center justify-content-between">
                <a href="home.php" class="logo d-flex align-items-center me-auto">
                    <h1 style="color: black;">SOLUNAR</h1>
                </a>

                <nav id="navmenu" class="navmenu">
                    <ul>
                        <li><a href="home.php">Home</a></li>
                        <li><a href="home.php#about">About</a></li>
                        <li><a href="home.php#products">Products</a></li>
                        <li><a href="home.php#appointment">Appointment</a></li>
                        <li><a href="home.php#testimonials">Testimonials</a></li>
                        <li><a href="home.php#learn">Learn</a></li>
                        <li><a href="home.php#faq">FAQ</a></li>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li><a href="profile.php">
                                <i class="bi bi-person"></i> Profile
                            </a></li>
                            <li><a href="cart.php" class="position-relative">
                                <i class="bi bi-cart3"></i>
                                <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?php
                                    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM cart WHERE user_id = ?");
                                    $stmt->bind_param("i", $_SESSION['user_id']);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $count = $result->fetch_assoc()['count'];
                                    echo $count;
                                    ?>
                                </span>
                            </a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle"></i> Account
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="profile.php">
                                        <i class="bi bi-person me-2"></i> My Profile
                                    </a></li>
                                    <li><a class="dropdown-item" href="orders.php">
                                        <i class="bi bi-bag me-2"></i> My Orders
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="logout.php">
                                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                                    </a></li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li><a href="customer-login.php">Login</a></li>
                        <?php endif; ?>
                    </ul>
                    <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
                </nav>

                <a class="cta-btn d-none d-sm-block" href="home.php#appointment">Make an Appointment</a>
            </div>
        </div>
    </header>

    <div class="container py-5">
        <br><br><br><br>
        <h1 class="mb-4">My Orders</h1>
        
        <?php if (empty($orders)): ?>
            <div class="alert alert-info">
                You haven't placed any orders yet. <a href="home.php#products">Start shopping</a>
            </div>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <div class="card order-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <h5 class="card-title mb-0">Order #<?php echo $order['id']; ?></h5>
                                <small class="text-muted">
                                    <?php echo date('F j, Y', strtotime($order['created_at'])); ?>
                                </small>
                            </div>
                            <div class="col-md-3">
                                <div class="order-details">
                                    <strong>Total Items:</strong> <?php echo $order['total_items']; ?><br>
                                    <strong>Total Amount:</strong> ₱<?php echo number_format($order['total_amount'], 2); ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="order-details">
                                    <strong>Payment Method:</strong><br>
                                    <?php echo ucwords(str_replace('_', ' ', $order['payment_method'])); ?>
                                </div>
                            </div>
                            <div class="col-md-3 text-end">
                                <span class="badge status-badge bg-<?php 
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
                                <br>
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
                                
                                <?php if ($order['tracking_number']): ?>
                                    <div class="mt-3">
                                        <h6>Tracking Information</h6>
                                        <p class="mb-0">Tracking Number: <?php echo htmlspecialchars($order['tracking_number']); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>
</body>
</html> 