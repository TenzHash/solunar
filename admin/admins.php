<?php
session_start();
require_once '../config/database.php';


// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Get current admin's role
$stmt = $conn->prepare("SELECT role FROM admin_accounts WHERE id = ?");
$stmt->bind_param("i", $_SESSION['admin_id']);
$stmt->execute();
$current_admin = $stmt->get_result()->fetch_assoc();

// Only super admins can perform management actions
$is_super_admin = ($current_admin['role'] === 'super_admin');

// Handle admin account creation
if (isset($_POST['create_admin'])) {
    if (!$is_super_admin) {
        header('Location: admins.php?error=Access denied. Only super admins can create admin accounts.');
        exit;
    }
    
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    // Validate input
    $errors = [];
    if (empty($username)) $errors[] = "Username is required";
    if (empty($email)) $errors[] = "Email is required";
    if (empty($password)) $errors[] = "Password is required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format";
    
    if (empty($errors)) {
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT id FROM admin_accounts WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors[] = "Username or email already exists";
        } else {
            // Create new admin account
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO admin_accounts (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);
            
            if ($stmt->execute()) {
                // Log activity
                $admin_id = $stmt->insert_id;
                $stmt = $conn->prepare("INSERT INTO activity_logs (admin_id, action, entity_type, entity_id, details) VALUES (?, 'create', 'admin', ?, ?)");
                $details = "Created new admin account: $username";
                $stmt->bind_param("iis", $_SESSION['admin_id'], $admin_id, $details);
                $stmt->execute();
                
                header('Location: admins.php?message=Admin account created successfully');
                exit;
            } else {
                $errors[] = "Failed to create admin account";
            }
        }
    }
}

// Handle admin account update
if (isset($_POST['update_admin'])) {
    if (!$is_super_admin) {
        header('Location: admins.php?error=Access denied. Only super admins can update admin accounts.');
        exit;
    }
    
    $admin_id = (int)$_POST['admin_id'];
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $password = trim($_POST['password']);
    
    // Validate input
    $errors = [];
    if (empty($username)) $errors[] = "Username is required";
    if (empty($email)) $errors[] = "Email is required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format";
    
    if (empty($errors)) {
        // Check if username or email already exists (excluding current admin)
        $stmt = $conn->prepare("SELECT id FROM admin_accounts WHERE (username = ? OR email = ?) AND id != ?");
        $stmt->bind_param("ssi", $username, $email, $admin_id);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors[] = "Username or email already exists";
        } else {
            // Update admin account
            if (!empty($password)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE admin_accounts SET username = ?, email = ?, password = ?, role = ? WHERE id = ?");
                $stmt->bind_param("ssssi", $username, $email, $hashed_password, $role, $admin_id);
            } else {
                $stmt = $conn->prepare("UPDATE admin_accounts SET username = ?, email = ?, role = ? WHERE id = ?");
                $stmt->bind_param("sssi", $username, $email, $role, $admin_id);
            }
            
            if ($stmt->execute()) {
                // Log activity
                $stmt = $conn->prepare("INSERT INTO activity_logs (admin_id, action, entity_type, entity_id, details) VALUES (?, 'update', 'admin', ?, ?)");
                $details = "Updated admin account: $username";
                $stmt->bind_param("iis", $_SESSION['admin_id'], $admin_id, $details);
                $stmt->execute();
                
                header('Location: admins.php?message=Admin account updated successfully');
                exit;
            } else {
                $errors[] = "Failed to update admin account";
            }
        }
    }
}

// Handle admin account deletion
if (isset($_POST['delete_admin'])) {
    if (!$is_super_admin) {
        header('Location: admins.php?error=Access denied. Only super admins can delete admin accounts.');
        exit;
    }
    
    $admin_id = (int)$_POST['admin_id'];
    
    // Prevent deleting self
    if ($admin_id === $_SESSION['admin_id']) {
        header('Location: admins.php?error=Cannot delete your own account');
        exit;
    }
    
    $stmt = $conn->prepare("DELETE FROM admin_accounts WHERE id = ?");
    $stmt->bind_param("i", $admin_id);
    if ($stmt->execute()) {
        // Log activity
        $stmt = $conn->prepare("INSERT INTO activity_logs (admin_id, action, entity_type, entity_id) VALUES (?, 'delete', 'admin', ?)");
        $stmt->bind_param("ii", $_SESSION['admin_id'], $admin_id);
        $stmt->execute();
        
        header('Location: admins.php?message=Admin account deleted successfully');
        exit;
    }
}

// Get all admin accounts
$stmt = $conn->prepare("SELECT id, username, email, role, last_login, created_at FROM admin_accounts ORDER BY created_at DESC");
$stmt->execute();
$admins = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Accounts - Solunar</title>
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
        .admin-badge {
            font-size: 0.9em;
            padding: 0.35em 0.65em;
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
                    <h2>Admin Accounts</h2>
                    <?php if ($is_super_admin): ?>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAdminModal">
                        <i class="bi bi-plus-circle"></i> Add Admin
                    </button>
                    <?php endif; ?>
                </div>

                <?php if (isset($_GET['message'])): ?>
                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($_GET['message']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($_GET['error']); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Admin Accounts Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Last Login</th>
                                        <th>Created At</th>
                                        <?php if ($is_super_admin): ?>
                                        <th>Actions</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($admins as $admin): ?>
                                    <tr>
                                        <td><?php echo $admin['id']; ?></td>
                                        <td><?php echo htmlspecialchars($admin['username']); ?></td>
                                        <td><?php echo htmlspecialchars($admin['email']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $admin['role'] === 'super_admin' ? 'danger' : 'primary'; ?> admin-badge">
                                                <?php echo ucfirst(str_replace('_', ' ', $admin['role'])); ?>
                                            </span>
                                        </td>   
                                        <td><?php echo $admin['last_login'] ? date('M d, Y H:i', strtotime($admin['last_login'])) : 'Never'; ?></td>
                                        <td><?php echo date('M d, Y H:i', strtotime($admin['created_at'])); ?></td>
                                        <?php if ($is_super_admin): ?>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editAdminModal<?php echo $admin['id']; ?>">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <?php if ($admin['id'] !== $_SESSION['admin_id']): ?>
                                                <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this admin account?');">
                                                    <input type="hidden" name="admin_id" value="<?php echo $admin['id']; ?>">
                                                    <button type="submit" name="delete_admin" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-trash"></i> 
                                                    </button>
                                                </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <?php endif; ?>
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

    <?php if ($is_super_admin): ?>
    <!-- Create Admin Modal -->
    <div class="modal fade" id="createAdminModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Admin Account</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select class="form-select" name="role" required>
                                <option value="admin">Admin</option>
                                <option value="super_admin">Super Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="create_admin" class="btn btn-primary">Create Admin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Admin Modals -->
    <?php foreach ($admins as $admin): ?>
    <div class="modal fade" id="editAdminModal<?php echo $admin['id']; ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <input type="hidden" name="admin_id" value="<?php echo $admin['id']; ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Admin Account</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($admin['username']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Leave blank to keep current password">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select class="form-select" name="role" required>
                                <option value="admin" <?php echo $admin['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                <option value="super_admin" <?php echo $admin['role'] === 'super_admin' ? 'selected' : ''; ?>>Super Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_admin" class="btn btn-primary">Update Admin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>