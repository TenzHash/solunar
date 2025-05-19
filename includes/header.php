<?php
session_start();
?>
<header id="header" class="header fixed-top d-flex align-items-center">
    <div class="container d-flex align-items-center justify-content-between">
        <a href="home.php" class="logo d-flex align-items-center me-auto me-lg-0">
            <img src="assets/img/logo.png" alt="Solunar Logo">
            <h1>Solunar<span>.</span></h1>
        </a>

        <nav id="navbar" class="navbar">
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="home.php#products">Products</a></li>
                <li><a href="home.php#about">About</a></li>
                <li><a href="home.php#contact">Contact</a></li>
            </ul>
        </nav>

        <div class="d-flex align-items-center">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="cart.php" class="btn-cart position-relative me-3">
                    <i class="bi bi-cart3"></i>
                    <span class="cart-count position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?php
                        require_once 'config/database.php';
                        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM cart WHERE user_id = ?");
                        $stmt->bind_param("i", $_SESSION['user_id']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $count = $result->fetch_assoc()['count'];
                        echo $count;
                        ?>
                    </span>
                </a>
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="profile.php">My Profile</a></li>
                        <li><a class="dropdown-item" href="orders.php">My Orders</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="customer-login.php" class="btn btn-outline-primary me-2">Login</a>
                <a href="customer-login.php#register" class="btn btn-primary">Register</a>
            <?php endif; ?>
        </div>
    </div>
</header> 