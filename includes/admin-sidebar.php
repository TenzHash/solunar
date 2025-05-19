<?php
// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: admin-login.php');
    exit;
}
?>
<div class="col-md-3 col-lg-2 admin-sidebar">
    <h3 class="mb-4">Admin Panel</h3>
    <div class="nav flex-column nav-pills">
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'admin-dashboard.php' ? 'active' : ''; ?>" href="admin-dashboard.php">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'admin-products.php' ? 'active' : ''; ?>" href="admin-products.php">
            <i class="bi bi-box me-2"></i> Products
        </a>
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'admin-orders.php' ? 'active' : ''; ?>" href="admin-orders.php">
            <i class="bi bi-bag me-2"></i> Orders
        </a>
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'admin-appointments.php' ? 'active' : ''; ?>" href="admin-appointments.php">
            <i class="bi bi-calendar-check me-2"></i> Appointments
        </a>
        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'admin-users.php' ? 'active' : ''; ?>" href="admin-users.php">
            <i class="bi bi-people me-2"></i> Users
        </a>
        <a class="nav-link text-danger" href="logout.php">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
        </a>
    </div>
</div> 