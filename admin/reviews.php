<?php
session_start();
require_once '../config/database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Handle review status update
if (isset($_POST['update_status'])) {
    $review_id = (int)$_POST['review_id'];
    $status = $_POST['status'];
    $review_type = $_POST['review_type'];
    
    $table = $review_type === 'product' ? 'testimonials' : 'reviews';
    $stmt = $conn->prepare("UPDATE $table SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $review_id);
    if ($stmt->execute()) {
        // Log activity
        $stmt = $conn->prepare("INSERT INTO activity_logs (admin_id, action, entity_type, entity_id, details) VALUES (?, 'update', ?, ?, ?)");
        $details = "Status changed to " . $status;
        $entity_type = $review_type === 'product' ? 'product_review' : 'service_review';
        $stmt->bind_param("isis", $_SESSION['admin_id'], $entity_type, $review_id, $details);
        $stmt->execute();
        
        header('Location: reviews.php?message=Review status updated successfully');
        exit;
    }
}

// Handle review deletion
if (isset($_POST['delete_review'])) {
    $review_id = (int)$_POST['review_id'];
    $review_type = $_POST['review_type'];
    
    $table = $review_type === 'product' ? 'testimonials' : 'reviews';
    $stmt = $conn->prepare("DELETE FROM $table WHERE id = ?");
    $stmt->bind_param("i", $review_id);
    if ($stmt->execute()) {
        // Log activity
        $stmt = $conn->prepare("INSERT INTO activity_logs (admin_id, action, entity_type, entity_id) VALUES (?, 'delete', ?, ?)");
        $entity_type = $review_type === 'product' ? 'product_review' : 'service_review';
        $stmt->bind_param("isi", $_SESSION['admin_id'], $entity_type, $review_id);
        $stmt->execute();
        
        header('Location: reviews.php?message=Review deleted successfully');
        exit;
    }
}

// Get all reviews with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

$status = isset($_GET['status']) ? $_GET['status'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';

$where_clause = [];
$params = [];
$types = '';

if ($status) {
    $where_clause[] = "status = ?";
    $params[] = $status;
    $types .= 's';
}

if ($search) {
    if ($type === 'product' || !$type) {
    $where_clause[] = "(user_name LIKE ? OR comment LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= 'ss';
    }
    if ($type === 'service' || !$type) {
        $where_clause[] = "(name LIKE ? OR review LIKE ?)";
        $search_param = "%$search%";
        $params[] = $search_param;
        $params[] = $search_param;
        $types .= 'ss';
    }
}

$where_sql = $where_clause ? 'WHERE ' . implode(' AND ', $where_clause) : '';

// Get total count for pagination
$count_sql = "
    SELECT COUNT(*) as count FROM (
        SELECT id FROM testimonials $where_sql
        UNION ALL
        SELECT id FROM reviews $where_sql
    ) as combined_reviews
";
if ($params) {
    $stmt = $conn->prepare($count_sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $total_reviews = $stmt->get_result()->fetch_assoc()['count'];
} else {
    $total_reviews = $conn->query($count_sql)->fetch_assoc()['count'];
}

$total_pages = ceil($total_reviews / $per_page);

// Get reviews with product information
$sql = "
    SELECT 
        'product' as review_type,
        t.id,
        t.user_name,
        t.rating,
        t.comment,
        t.status,
        t.created_at,
        p.name as product_name,
        NULL as service
    FROM testimonials t 
    LEFT JOIN products p ON t.product_id = p.id 
    $where_sql 
    UNION ALL
    SELECT 
        'service' as review_type,
        r.id,
        r.name as user_name,
        r.rating,
        r.review as comment,
        r.status,
        r.created_at,
        NULL as product_name,
        r.service
    FROM reviews r
    $where_sql 
    ORDER BY created_at DESC 
    LIMIT ? OFFSET ?
";
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
$reviews = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews Management - Solunar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: #343a40;
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,.8);
        }
        .sidebar .nav-link:hover {
            color: white;
        }
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,.1);
        }
        .main-content {
            padding: 20px;
        }
        .rating {
            color: #ffc107;
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Reviews Management</h2>
                </div>

                <!-- Success Modal -->
                <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title" id="successModalLabel">Success</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                <?php if (isset($_GET['message'])): ?>
                        <?php echo htmlspecialchars($_GET['message']); ?>
                                <?php endif; ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="search" placeholder="Search reviews..." value="<?php echo htmlspecialchars($search); ?>">
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" name="type">
                                    <option value="">All Types</option>
                                    <option value="product" <?php echo $type === 'product' ? 'selected' : ''; ?>>Product Reviews</option>
                                    <option value="service" <?php echo $type === 'service' ? 'selected' : ''; ?>>Service Reviews</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" name="status">
                                    <option value="">All Status</option>
                                    <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="approved" <?php echo $status === 'approved' ? 'selected' : ''; ?>>Approved</option>
                                    <option value="rejected" <?php echo $status === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Filter</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Reviews Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Type</th>
                                        <th>Product/Service</th>
                                        <th>User</th>
                                        <th>Rating</th>
                                        <th>Comment</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reviews as $review): ?>
                                    <tr>
                                        <td><?php echo $review['id']; ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $review['review_type'] === 'product' ? 'primary' : 'info'; ?>">
                                                <?php echo ucfirst($review['review_type']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php 
                                            if ($review['review_type'] === 'product') {
                                                echo htmlspecialchars($review['product_name'] ?? 'N/A');
                                            } else {
                                                echo htmlspecialchars($review['service']);
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($review['user_name']); ?></td>
                                        <td>
                                            <div class="rating">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <i class="bi bi-star<?php echo $i <= $review['rating'] ? '-fill' : ''; ?>"></i>
                                                <?php endfor; ?>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($review['comment']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo $review['status'] === 'approved' ? 'success' : 
                                                    ($review['status'] === 'rejected' ? 'danger' : 'warning'); 
                                            ?>">
                                                <?php echo ucfirst($review['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M d, Y H:i', strtotime($review['created_at'])); ?></td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                    <?php if ($review['status'] !== 'approved'): ?>
                                                <form method="POST" class="d-inline">
                                                            <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                                                    <input type="hidden" name="review_type" value="<?php echo $review['review_type']; ?>">
                                                            <input type="hidden" name="status" value="approved">
                                                    <button type="submit" name="update_status" class="btn btn-sm btn-outline-success" title="Approve">
                                                        <i class="bi bi-check-circle"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                    <?php if ($review['status'] !== 'rejected'): ?>
                                                <form method="POST" class="d-inline">
                                                            <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                                                    <input type="hidden" name="review_type" value="<?php echo $review['review_type']; ?>">
                                                            <input type="hidden" name="status" value="rejected">
                                                    <button type="submit" name="update_status" class="btn btn-sm btn-outline-danger" title="Reject">
                                                        <i class="bi bi-x-circle"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this review?');">
                                                            <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                                                    <input type="hidden" name="review_type" value="<?php echo $review['review_type']; ?>">
                                                    <button type="submit" name="delete_review" class="btn btn-sm btn-outline-danger" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                            </div>
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
                                    <a class="page-link" href="?page=<?php echo $i; ?>&status=<?php echo urlencode($status); ?>&search=<?php echo urlencode($search); ?>">
                                        <?php echo $i; ?>
                                    </a>
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
    <script>
        // Show success modal if there's a message
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($_GET['message'])): ?>
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
            <?php endif; ?>
        });
    </script>
</body>
</html>