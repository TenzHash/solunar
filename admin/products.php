<?php
session_start();
require_once '../config/database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Handle product deletion
if (isset($_POST['delete_product'])) {
    $product_id = (int)$_POST['product_id'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    if ($stmt->execute()) {
        // Log activity
        $stmt = $conn->prepare("INSERT INTO activity_logs (admin_id, action, entity_type, entity_id) VALUES (?, 'delete', 'product', ?)");
        $stmt->bind_param("ii", $_SESSION['admin_id'], $product_id);
        $stmt->execute();
        
        header('Location: products.php?message=Product deleted successfully');
        exit;
    }
}

// Get all products with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

$where_clause = [];
$params = [];
$types = '';

if ($search) {
    $where_clause[] = "(name LIKE ? OR description LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= 'ss';
}

if ($category) {
    $where_clause[] = "category = ?";
    $params[] = $category;
    $types .= 's';
}

$where_sql = $where_clause ? 'WHERE ' . implode(' AND ', $where_clause) : '';

// Get total count for pagination
$count_sql = "SELECT COUNT(*) as count FROM products $where_sql";
if ($params) {
    $stmt = $conn->prepare($count_sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $total_products = $stmt->get_result()->fetch_assoc()['count'];
} else {
    $total_products = $conn->query($count_sql)->fetch_assoc()['count'];
}

$total_pages = ceil($total_products / $per_page);

// Get products
$sql = "SELECT * FROM products $where_sql ORDER BY created_at DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
if ($params) {
    $params[] = $per_page;
    $params[] = $offset;
    $types .= 'ii';
    $stmt->bind_param($types, ...$params);
} else {
    $stmt->bind_param('ii', $per_page, $offset);
}
$stmt->execute();
$products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

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
    <title>Products Management - Solunar</title>
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
        .card, .table {
            border-radius: 16px !important;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        }
        .card-header {
            border-radius: 16px 16px 0 0 !important;
            background: #f4f8ff;
            font-weight: 600;
        }
        .btn-primary, .btn-danger {
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
        .btn-danger {
            background: linear-gradient(90deg, #dc3545 60%, #ff6b6b 100%);
            border: none;
        }
        .btn-danger:hover {
            background: linear-gradient(90deg, #ff6b6b 60%, #dc3545 100%);
            transform: translateY(-2px) scale(1.03);
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
                    <h2 class="mb-0 fw-bold" style="color:#007bff;">Products Management</h2>
                    <a href="add_product.php" class="btn btn-primary">
                        <i class="bi bi-plus"></i> Add New Product
                    </a>
                </div>

                <?php if (isset($_GET['message'])): ?>
                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($_GET['message']); ?>
                    </div>
                <?php endif; ?>

                <!-- Filters -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" name="category">
                                    <option value="">All Categories</option>
                                    <option value="solar_panel" <?php echo $category === 'solar_panel' ? 'selected' : ''; ?>><?php echo formatCategoryName('solar_panel'); ?></option>
                                    <option value="battery" <?php echo $category === 'battery' ? 'selected' : ''; ?>><?php echo formatCategoryName('battery'); ?></option>
                                    <option value="inverter" <?php echo $category === 'inverter' ? 'selected' : ''; ?>><?php echo formatCategoryName('inverter'); ?></option>
                                    <option value="accessories" <?php echo $category === 'accessories' ? 'selected' : ''; ?>><?php echo formatCategoryName('accessories'); ?></option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Filter</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Products Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><?php echo $product['id']; ?></td>
                                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                                        <td><?php echo formatCategoryName($product['category']); ?></td>
                                        <td>₱<?php echo number_format($product['price'], 2); ?></td>
                                        <td><?php echo $product['stock']; ?></td>
                                        <td>
                                            <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                                <button type="submit" name="delete_product" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?php if ($total_pages > 1): ?>
                        <nav class="mt-4">
                            <ul class="pagination justify-content-center">
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?><?php if ($search) echo '&search=' . urlencode($search); ?><?php if ($category) echo '&category=' . urlencode($category); ?>"><?php echo $i; ?></a>
                                </li>
                                <?php endfor; ?>
                            </ul>
                        </nav>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 