<?php
require_once __DIR__ . '/../../config/database.php';
// Get the current page name for active state
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="col-md-3 col-lg-2 px-0 sidebar position-sticky top-0" style="backdrop-filter: blur(8px); background: rgba(36, 58, 99, 0.92); min-height: 100vh; box-shadow: 2px 0 16px rgba(0,0,0,0.07);">
    <div class="d-flex flex-column h-100">
        <div class="p-4 pb-2 text-center">
            <img src="/Solunar/assets/images/assets/logo.png" alt="Solunar Logo" style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%; box-shadow: 0 2px 8px rgba(0,123,255,0.12); background: #fff;">
            <h4 class="mt-2 mb-0 fw-bold" style="color: #fff; letter-spacing: 1px; font-size: 1.25rem;">SOLUNAR</h4>
            <span class="text-light small">Admin Panel</span>
        </div>
        <hr class="my-0" style="border-color: #ffffff22;">
        <ul class="nav flex-column mt-3 mb-0">
            <li class="nav-item mb-2">
                <a class="nav-link d-flex align-items-center gap-2 px-4 py-2 rounded-pill text-white" href="/Solunar/home.php" target="_blank" style="transition: background 0.2s;">
                    <i class="bi bi-house"></i> <span>Back to Homepage</span>
                </a>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center gap-2 px-4 py-2 rounded-pill <?php echo $current_page === 'index.php' ? 'active' : ''; ?>" href="/Solunar/admin/index.php">
                    <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center gap-2 px-4 py-2 rounded-pill <?php echo $current_page === 'products.php' ? 'active' : ''; ?>" href="/Solunar/admin/products.php">
                    <i class="bi bi-box"></i> <span>Products</span>
                </a>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center gap-2 px-4 py-2 rounded-pill <?php echo $current_page === 'orders.php' ? 'active' : ''; ?>" href="/Solunar/admin/orders.php">
                    <i class="bi bi-bag"></i> <span>Orders</span>
                </a>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center gap-2 px-4 py-2 rounded-pill <?php echo $current_page === 'featured_products.php' ? 'active' : ''; ?>" href="/Solunar/admin/featured_products.php">
                    <i class="bi bi-star"></i> <span>Featured Products</span>
                </a>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center gap-2 px-4 py-2 rounded-pill <?php echo $current_page === 'category_cards.php' ? 'active' : ''; ?>" href="/Solunar/admin/category_cards.php">
                    <i class="bi bi-grid"></i> <span>Category Cards</span>
                </a>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center gap-2 px-4 py-2 rounded-pill <?php echo $current_page === 'reviews.php' ? 'active' : ''; ?>" href="/Solunar/admin/reviews.php">
                    <i class="bi bi-chat"></i> <span>Reviews</span>
                </a>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center gap-2 px-4 py-2 rounded-pill <?php echo $current_page === 'admins.php' ? 'active' : ''; ?>" href="/Solunar/admin/admins.php">
                    <i class="bi bi-people"></i> <span>Admin Accounts</span>
                </a>
            </li>
            <li class="nav-item mb-1">
                <a class="nav-link d-flex align-items-center gap-2 px-4 py-2 rounded-pill <?php echo $current_page === 'appointments.php' ? 'active' : ''; ?>" href="/Solunar/admin/appointments.php">
                    <i class="bi bi-calendar-check"></i> <span>Appointments</span>
                </a>
            </li>
            <li class="nav-item mt-2">
                <a class="nav-link d-flex align-items-center gap-2 px-4 py-2 rounded-pill text-white bg-danger" href="/Solunar/admin/logout.php" style="transition: background 0.2s;">
                    <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
</div>
<style>
.sidebar .nav-link {
    color: #e0e7ff;
    font-weight: 500;
    opacity: 0.92;
    transition: background 0.2s, color 0.2s;
}
.sidebar .nav-link.active, .sidebar .nav-link:hover, .sidebar .nav-link:focus {
    background: linear-gradient(90deg, #007bff 60%, #0d6efd 100%);
    color: #fff !important;
    opacity: 1;
    box-shadow: 0 2px 8px rgba(0,123,255,0.10);
}
.sidebar .nav-link.bg-danger {
    background: linear-gradient(90deg, #dc3545 60%, #ff6b6b 100%) !important;
    color: #fff !important;
}
@media (max-width: 991px) {
    .sidebar { min-height: auto; }
}
</style> 