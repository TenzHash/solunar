<?php
session_start();
require_once '../config/database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Handle category card updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = (int)$_POST['product_id'];
    $category_card = $_POST['category_card'];
    
    $stmt = $conn->prepare("UPDATE products SET category_card = ? WHERE id = ?");
    $stmt->bind_param("si", $category_card, $product_id);
    
    if ($stmt->execute()) {
        // Log the activity
        $stmt = $conn->prepare("INSERT INTO activity_logs (admin_id, action, entity_type, entity_id) VALUES (?, ?, 'product', ?)");
        $action = 'update_category_card';
        $stmt->bind_param("isi", $_SESSION['admin_id'], $action, $product_id);
        $stmt->execute();
        
        header('Location: category_cards.php?success=1');
        exit();
    }
}

// Get all products with their category card status
$query = "SELECT id, name, price, category, stock, category_card FROM products ORDER BY category_card ASC, name ASC";
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
    <title>Manage Category Cards - Solunar Admin</title>
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
        .btn-primary, .btn-success {
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
        .category-badge {
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
            <?php include 'includes/sidebar.php'; ?>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <div class="topbar d-flex flex-wrap justify-content-between align-items-center mb-4">
                    <h2 class="mb-0 fw-bold" style="color:#007bff;">Manage Category Cards</h2>
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
                        Category card settings updated successfully!
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
                                        <th>Category Card</th>
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
                                                <?php if ($row['category_card']): ?>
                                                    <span class="category-badge"><?php echo formatCategoryName($row['category_card']); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                                    <select name="category_card" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                                                        <option value="">No Category Card</option>
                                                        <option value="solar_panel" <?php echo $row['category_card'] === 'solar_panel' ? 'selected' : ''; ?>>Solar Panel</option>
                                                        <option value="battery" <?php echo $row['category_card'] === 'battery' ? 'selected' : ''; ?>>Battery</option>
                                                        <option value="inverter" <?php echo $row['category_card'] === 'inverter' ? 'selected' : ''; ?>>Inverter</option>
                                                        <option value="accessories" <?php echo $row['category_card'] === 'accessories' ? 'selected' : ''; ?>>Accessories</option>
                                                    </select>
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