<?php
require_once __DIR__ . '/../../config/database.php';
// Get the current page name for active state
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="col-md-3 col-lg-2 px-0 sidebar">
    <div class="p-3">
        <h4>Solunar Admin</h4>
        <hr>
        <ul class="nav flex-column">
            <li class="nav-item mb-3">
                <a class="nav-link" href="/Solunar/home.php" target="_blank">
                    <i class="bi bi-house"></i> Back to Homepage
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page === 'index.php' ? 'active' : ''; ?>" href="/Solunar/admin/index.php">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page === 'products.php' ? 'active' : ''; ?>" href="/Solunar/admin/products.php">
                    <i class="bi bi-box"></i> Products
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page === 'featured_products.php' ? 'active' : ''; ?>" href="/Solunar/admin/featured_products.php">
                    <i class="bi bi-star"></i> Featured Products
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page === 'category_cards.php' ? 'active' : ''; ?>" href="/Solunar/admin/category_cards.php">
                    <i class="bi bi-grid"></i> Category Cards
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page === 'reviews.php' ? 'active' : ''; ?>" href="/Solunar/admin/reviews.php">
                    <i class="bi bi-chat"></i> Reviews
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page === 'admins.php' ? 'active' : ''; ?>" href="/Solunar/admin/admins.php">
                    <i class="bi bi-people"></i> Admin Accounts
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $current_page === 'appointments.php' ? 'active' : ''; ?>" href="/Solunar/admin/appointments.php">
                    <i class="bi bi-calendar-check"></i> Appointments
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/Solunar/admin/logout.php">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</div> 