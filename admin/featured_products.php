<?php
session_start();
require_once '../config/database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Handle featured status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = (int)$_POST['product_id'];
    $featured = isset($_POST['featured']) ? (int)$_POST['featured'] : 0;
    
    $stmt = $conn->prepare("UPDATE products SET featured = ? WHERE id = ?");
    $stmt->bind_param("ii", $featured, $product_id);
    
    if ($stmt->execute()) {
        // Log the activity
        $stmt = $conn->prepare("INSERT INTO activity_logs (admin_id, action, entity_type, entity_id) VALUES (?, ?, 'product', ?)");
        $action = $featured ? 'feature_product' : 'unfeature_product';
        $stmt->bind_param("isi", $_SESSION['admin_id'], $action, $product_id);
        $stmt->execute();
        
        header('Location: featured_products.php?success=1');
        exit();
    }
}

// Get all products with their featured status
$query = "SELECT id, name, price, category, stock, featured FROM products ORDER BY featured DESC, name ASC";
$result = mysqli_query($conn, $query);

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
    <title>Manage Featured Products - Solunar Admin</title>
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
        .btn-primary, .btn-success, .btn-danger {
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
        .btn-success {
            background: linear-gradient(90deg, #28a745 60%, #51e67a 100%);
            border: none;
        }
        .btn-success:hover {
            background: linear-gradient(90deg, #51e67a 60%, #28a745 100%);
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
        .featured-badge {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8em;
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
                    <h2 class="mb-0 fw-bold" style="color:#007bff;">Manage Featured Products</h2>
                </div>

                <!-- Feedback Modal -->
                <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="successModalLabel">Success</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        Featured products updated successfully!
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
                      </div>
                    </div>
                  </div>
                </div>
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    <?php if (isset($_GET['success'])): ?>
                        var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                        successModal.show();
                    <?php endif; ?>
                });
                </script>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Featured</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                                            <td><?php echo formatCategoryName($row['category']); ?></td>
                                            <td>₱<?php echo number_format($row['price'], 2); ?></td>
                                            <td><?php echo $row['stock']; ?></td>
                                            <td>
                                                <?php if ($row['featured']): ?>
                                                    <span class="featured-badge">Featured</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                                    <input type="hidden" name="featured" value="<?php echo $row['featured'] ? '0' : '1'; ?>">
                                                    <button type="submit" class="btn btn-sm <?php echo $row['featured'] ? 'btn-danger' : 'btn-success'; ?>">
                                                        <?php echo $row['featured'] ? 'Remove from Featured' : 'Add to Featured'; ?>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 