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
        .sidebar {
            min-height: 100vh;
            background: #343a40;
            color: white;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
        }
        .sidebar a:hover {
            color: #f8f9fa;
        }
        .main-content {
            padding: 20px;
        }
        .category-badge {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8em;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Manage Category Cards</h2>
                </div>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">
                        Category card settings updated successfully!
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
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
                                            <td><?php echo htmlspecialchars($row['category']); ?></td>
                                            <td>₱<?php echo number_format($row['price'], 2); ?></td>
                                            <td><?php echo $row['stock']; ?></td>
                                            <td>
                                                <?php if ($row['category_card']): ?>
                                                    <span class="category-badge"><?php echo ucfirst(str_replace('_', ' ', $row['category_card'])); ?></span>
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