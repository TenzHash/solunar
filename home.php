<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Solunar</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <style>
    .product-popup {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }

    .popup-content {
      background-color: white;
      padding: 2rem;
      border-radius: 10px;
      max-width: 800px;
      width: 90%;
    }

    .popup-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
    }

    .close-popup {
      cursor: pointer;
      font-size: 1.5rem;
    }

    .products-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 2rem;
    }

    .product-card {
      text-align: center;
      padding: 1rem;
      border: 1px solid #ddd;
      border-radius: 8px;
    }

    .product-card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 4px;
    }

    .product-actions {
      display: flex;
      gap: 10px;
      margin-top: 10px;
    }

    .product-actions .btn {
      flex: 1;
    }

    .product-actions .learn-more {
      background: none;
      border: 1px solid #000;
      color: #000;
    }

    .cart-popup {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }

    .cart-content {
      background-color: white;
      padding: 2rem;
      border-radius: 10px;
      max-width: 800px;
      width: 90%;
    }

    .cart-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
    }

    .cart-items {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .cart-item {
      display: flex;
      align-items: center;
      padding: 1rem;
      border-bottom: 1px solid #f0f0f0;
      transition: background-color 0.2s;
    }

    .item-details {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
    }

    .item-name {
      font-weight: bold;
      margin-bottom: 0.5rem;
    }

    .item-price {
      font-size: 1.2rem;
      color: #666;
    }

    .item-quantity {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .quantity-btn {
      width: 2rem;
      height: 2rem;
      border: none;
      border-radius: 50%;
      background-color: #007bff;
      color: white;
      cursor: pointer;
    }

    .quantity {
      font-size: 1.2rem;
      font-weight: bold;
    }

    .remove-item {
      background-color: #dc3545;
      color: white;
      border: none;
      padding: 0.5rem 1rem;
      border-radius: 4px;
      cursor: pointer;
    }

    .cart-summary {
      margin-top: 2rem;
      padding: 1rem;
      border: 1px solid #ddd;
      border-radius: 8px;
    }

    .cart-total {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
    }

    .total-label {
      font-weight: bold;
      font-size: 1.2rem;
    }

    .total-amount {
      font-size: 1.2rem;
      font-weight: bold;
      color: #007bff;
    }

    .checkout-btn {
      background-color: #007bff;
      color: white;
      border: none;
      padding: 0.5rem 1rem;
      border-radius: 4px;
      cursor: pointer;
    }

    .review-form .card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .review-form .card-title {
      color: #2c3e50;
      font-weight: 600;
    }

    .review-form .form-control {
      border-radius: 8px;
      padding: 0.75rem;
      border: 1px solid #e0e0e0;
      color: #000000;
    }

    .review-form .form-control:focus {
      border-color: #007bff;
      box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
      color: #000000;
    }

    .review-form .form-select {
      border-radius: 8px;
      padding: 0.75rem;
      border: 1px solid #e0e0e0;
      color: #000000;
    }

    .review-form .form-select:focus {
      border-color: #007bff;
      box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
      color: #000000;
    }

    .review-form .btn-primary {
      background-color: #007bff;
      border-color: #007bff;
      padding: 0.75rem 1.5rem;
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    .review-form .btn-primary:hover {
      background-color: #0056b3;
      border-color: #0056b3;
    }

    .review-form .btn-secondary {
      background-color: #6c757d;
      border-color: #6c757d;
      padding: 0.75rem 1.5rem;
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    .review-form .btn-secondary:hover {
      background-color: #5a6268;
      border-color: #5a6268;
    }

    #addReviewBtn {
      background-color: #007bff;
      border: none;
      padding: 1rem 2rem;
      border-radius: 8px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    #addReviewBtn:hover {
      background-color: #0056b3;
      transform: translateY(-2px);
    }

    .solar-estimate .card {
      border: none;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .solar-estimate .card-body {
      padding: 2rem;
    }

    .solar-estimate h4 {
      color: #2c3e50;
      font-weight: 600;
    }

    .solar-estimate .form-label {
      font-weight: 500;
      color: #343a40;
    }

    .solar-estimate .form-control {
      border-radius: 0.5rem;
      padding: 0.75rem;
    }

    .solar-estimate .form-control:focus {
      box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    .solar-estimate .input-group-text {
      background-color: #f8f9fa;
      border: 1px solid #dee2e6;
      border-radius: 0.5rem 0 0 0.5rem;
    }

    .solar-estimate .btn-primary {
      padding: 0.75rem 2rem;
      border-radius: 0.5rem;
      font-weight: 500;
    }

    .solar-estimate .list-unstyled li {
      padding: 1rem 0;
      border-bottom: 1px solid #e9ecef;
    }

    .solar-estimate .list-unstyled li:last-child {
      border-bottom: none;
    }

    .solar-estimate .bi-check-circle {
      font-size: 1.25rem;
    }

    .departments .card {
      border: none;
      box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .departments .card-body {
      padding: 2rem;
    }

    .departments h4 {
      color: #2c3e50;
      font-weight: 600;
    }

    .departments .form-label {
      font-weight: 500;
      color: #343a40;
    }

    .departments .form-control {
      border-radius: 0.5rem;
      padding: 0.75rem;
    }

    .departments .form-control:focus {
      box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    .departments .input-group-text {
      background-color: #f8f9fa;
      border: 1px solid #dee2e6;
      border-radius: 0.5rem 0 0 0.5rem;
    }

    .departments .btn-primary {
      padding: 0.75rem 2rem;
      border-radius: 0.5rem;
      font-weight: 500;
    }

    .departments .list-unstyled li {
      padding: 1rem 0;
      border-bottom: 1px solid #e9ecef;
    }

    .departments .list-unstyled li:last-child {
      border-bottom: none;
    }

    .departments .bi-check-circle {
      font-size: 1.25rem;
    }

    .departments .badge {
      padding: 0.5rem 1rem;
      font-size: 0.875rem;
    }

    .departments .nav-tabs {
      border: none;
    }

    .departments .nav-link {
      border: none;
      padding: 1rem;
      margin-bottom: 0.5rem;
      border-radius: 0.5rem;
      transition: all 0.3s ease;
    }

    .departments .nav-link:hover {
      background-color: #f8f9fa;
    }

    .departments .nav-link.active {
      background-color: #0d6efd;
      color: white;
    }

    .product-actions {
      display: flex;
      gap: 10px;
      margin-top: 10px;
    }
    .product-actions .btn {
      flex: 1;
    }
    .product-actions .learn-more {
      background: none;
      border: 1px solid #000;
      color: #000;
    }

    /* Cart Item Styles */
    .cart-item {
      display: flex;
      align-items: center;
      padding: 1rem;
      border-bottom: 1px solid #f0f0f0;
      transition: background-color 0.2s;
    }
    
    .cart-item:hover {
      background-color: #f8f9fa;
    }
    
    .cart-item-img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 4px;
      margin-right: 1rem;
      border: 1px solid #eee;
    }
    
    .cart-item-details {
      flex: 1;
    }
    
    .cart-item-title {
      font-weight: 600;
      margin-bottom: 0.25rem;
      color: #333;
    }
    
    .cart-item-price {
      color: #0d6efd;
      font-weight: 600;
      font-size: 1.1rem;
    }
    
    .quantity-controls {
      display: flex;
      align-items: center;
      margin-top: 0.5rem;
    }
    
    .quantity-btn {
      width: 30px;
      height: 30px;
      border: 1px solid #dee2e6;
      background: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      user-select: none;
      transition: all 0.2s;
    }
    
    .quantity-btn:hover {
      background-color: #f8f9fa;
    }
    
    .quantity-input {
      width: 40px;
      height: 30px;
      text-align: center;
      border: 1px solid #dee2e6;
      border-left: none;
      border-right: none;
    }
    
    .remove-item {
      color: #dc3545;
      cursor: pointer;
      font-size: 1.1rem;
      margin-left: 1rem;
      transition: color 0.2s;
    }
    
    .remove-item:hover {
      color: #bb2d3b;
    }
    
    .cart-summary {
      background-color: #f8f9fa;
    }
    
    /* Animation for cart updates */
    @keyframes slideIn {
      from { transform: translateY(10px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
    
    .cart-item {
      animation: slideIn 0.3s ease-out;
    }

    /* Featured Products Card Image */
    .card-img-top {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 4px 4px 0 0;
    }

    /* Product Card Container */
    .product-card {
      text-align: center;
      padding: 1rem;
      border: 1px solid #ddd;
      border-radius: 8px;
      height: 100%;
      display: flex;
      flex-direction: column;
    }

    .product-card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 4px;
      margin-bottom: 1rem;
    }

    .product-card h3 {
      font-size: 1.1rem;
      margin-bottom: 0.5rem;
      height: 2.4em;
      overflow: hidden;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
    }

    .product-card p {
      font-size: 0.9rem;
      color: #666;
      margin-bottom: 1rem;
      height: 3em;
      overflow: hidden;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
    }

    .product-card .product-actions {
      margin-top: auto;
    }

    /* Products Grid Layout */
    .products-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 2rem;
      padding: 1rem;
    }

    /* Featured Products Grid */
    #featured-products .card {
      height: 100%;
      display: flex;
      flex-direction: column;
    }

    #featured-products .card-body {
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    #featured-products .card-title {
      font-size: 1.1rem;
      margin-bottom: 0.5rem;
      height: 2.4em;
      overflow: hidden;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
    }

    #featured-products .card-text {
      font-size: 0.9rem;
      color: #666;
      margin-bottom: 1rem;
      height: 3em;
      overflow: hidden;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
    }

    #featured-products .mt-3 {
      margin-top: auto !important;
    }

    /* Add these styles to your existing CSS */
    .star-rating {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .star-rating .stars {
        display: flex;
        gap: 0.25rem;
    }

    .star-rating .stars .star {
        font-size: 1.5rem;
        color: #ccc;
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .star-rating .stars .star.selected {
        color: #ffd700;
    }

    .star-rating .stars .star.hovered {
        color: #ffd700;
    }

    .rating-value {
        font-size: 1.2rem;
        font-weight: bold;
    }

    /* Form validation styles */
    .form-control:invalid,
    .form-select:invalid {
        border-color: #dc3545;
    }

    .form-control:valid,
    .form-select:valid {
        border-color: #198754;
    }
  </style>

</head>

<body class="index-page">

  <header id="header" class="header sticky-top">

    <div class="branding d-flex align-items-center">

      <div class="container position-relative d-flex align-items-center justify-content-between">
        <a href="index.php" class="logo d-flex align-items-center me-auto">
          <!-- Uncomment the line below if you also wish to use an image logo -->
          <!-- <img src="assets/img/logo.png" alt=""> -->
          <h1 style="color: black;">SOLUNAR</h1>
        </a>

        <nav id="navmenu" class="navmenu">
          <ul>
            <li><a href="#hero">Home<br></a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#products">Products</a></li>
            <li><a href="#appointment">Appointment</a></li>
            <li><a href="#testimonials">Testimonials</a></li>
            <li><a href="#learn">Learn</a></li>
            <li><a href="#faq">FAQ</a></li>
            <li><a href="admin/index.php" id="adminLoginLink">Login</a></li>
          </ul>
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        <a class="cta-btn d-none d-sm-block" href="#appointment">Make an Appointment</a>
<br>
<br>
      </div>

    </div>

  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section light-background">

      <img src="assets/img/hero-bg.png" alt="" data-aos="fade-in">

      <div class="container position-relative">

        <div class="welcome position-relative" data-aos="fade-down" data-aos-delay="100">
          <h2 style="color: white; text-align: center; margin: 6%;">WELCOME TO SOLUNAR</h2>
        </div>

        <div class="content row gy-4">
          <div class="col-lg-4 d-flex align-items-stretch">
            <div class="why-box" data-aos="zoom-out" data-aos-delay="200">
              <h3>Why Choose SOLUNAR?</h3>
              <p style="text-align: justify;">
               At SOLUNAR, we're passionate about empowering Filipino households and businesses with clean, reliable, and cost-effective solar energy solutions. Located in Taysan, Philippines, we specialize in designing, installing, and maintaining top-tier solar power systems tailored to your needs.
              </p>
              <div class="text-center">
                <a href="#about" class="more-btn"><span>Learn More</span> <i class="bi bi-chevron-right"></i></a>
              </div>
            </div>
          </div><!-- End Why Box -->

          <div class="col-lg-8 d-flex align-items-stretch">
            <div class="d-flex flex-column justify-content-center">
              <div class="row gy-4">

                <div class="col-xl-4 d-flex align-items-stretch">
                  <div class="icon-box" data-aos="zoom-out" data-aos-delay="300">
                    <i class="bi bi-clipboard-data"></i>
                    <p>Over 5 years of proven experience in solar installations, with a team trained 
by TESDA-certified professionals.</p>
                  </div>
                </div><!-- End Icon Box -->

                <div class="col-xl-4 d-flex align-items-stretch">
                  <div class="icon-box" data-aos="zoom-out" data-aos-delay="400">
                    <i class="bi bi-gem"></i>
                    <p>Premium Quality
We use only the best components, including Deye Hybrid Inverters , Lifepo4 batteries , and high-efficiency solar panels tested for Philippine conditions.</p>
                  </div>
                </div><!-- End Icon Box -->

                <div class="col-xl-4 d-flex align-items-stretch">
                  <div class="icon-box" data-aos="zoom-out" data-aos-delay="500">
                    <i class="bi bi-inboxes"></i>
                    <p>Honesty & Transparency
No shortcuts. We provide clear ROI calculations (average 4.7 years ) and ensure systems are designed to maximize lifespan and savings.</p>
                </div>
              </div>
            </div>
          </div>
        </div><!-- End  Content-->

      </div>

    </section><!-- /Hero Section -->

    <!-- About Section -->
    <section id="about" class="about section py-5 bg-light">
      <!-- About Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>About Us</h2>
        <p>SOLUNAR is your trusted partner for clean, reliable, and cost-effective solar energy solutions in the Philippines. We empower homes and businesses to embrace sustainability and energy independence.</p>
      </div>
      <!-- About Section Title -->
      <div class="container">
        <div class="row g-4 justify-content-center">
          <!-- Card 1: What We Offer -->
          <div class="col-lg-4 col-md-6 d-flex">
            <div class="card h-100 shadow-sm border-0 w-100">
              <div class="card-body d-flex flex-column justify-content-center align-items-start px-4 py-4" style="min-height:340px;">
                <div class="mb-3">
                  <i class="bi bi-box-seam text-warning" style="font-size:2rem;">What We Offer</i>

                </div>
                <ul class="list-unstyled mb-0 w-100">
                  <li class="mb-3 d-flex align-items-start"><i class="bi bi-sun-fill text-warning me-3 mt-1" style="font-size:1.3rem;"></i><div><strong>Solar Panels:</strong> Efficient, durable, and built for Philippine conditions.</div></li>
                  <li class="mb-3 d-flex align-items-start"><i class="bi bi-battery-charging text-success me-3 mt-1" style="font-size:1.3rem;"></i><div><strong>Solar Batteries:</strong> Advanced storage for power day and night.</div></li>
                  <li class="d-flex align-items-start"><i class="bi bi-lightning-charge-fill text-primary me-3 mt-1" style="font-size:1.3rem;"></i><div><strong>Inverters:</strong> Seamless energy conversion for your needs.</div></li>
                </ul>
              </div>
            </div>
          </div>
          <!-- Card 2: Our Services -->
          <div class="col-lg-4 col-md-6 d-flex">
            <div class="card h-100 shadow-sm border-0 w-100">
              <div class="card-body d-flex flex-column justify-content-center align-items-start px-4 py-4" style="min-height:340px;">
                <div class="mb-3">
                  <i class="bi bi-gear-wide-connected text-info" style="font-size:2rem;">Our Services</i>
                  </div>
                <ul class="list-unstyled mb-0 w-100">
                  <li class="mb-3 d-flex align-items-start"><i class="bi bi-person-check-fill text-info me-3 mt-1" style="font-size:1.3rem;"></i><div><strong>Consultation & Site Assessment:</strong> Personalized guidance and system design.</div></li>
                  <li class="mb-3 d-flex align-items-start"><i class="bi bi-tools text-danger me-3 mt-1" style="font-size:1.3rem;"></i><div><strong>Installation:</strong> TESDA-certified professionals ensure safe, efficient setup.</div></li>
                  <li class="d-flex align-items-start"><i class="bi bi-shield-check text-success me-3 mt-1" style="font-size:1.3rem;"></i><div><strong>Maintenance:</strong> Ongoing support to keep your system at peak performance.</div></li>
                </ul>
              </div>
            </div>
          </div>
          <!-- Card 3: Why Choose SOLUNAR? -->
          <div class="col-lg-4 col-md-12 d-flex">
            <div class="card h-100 shadow-sm border-0 w-100">
              <div class="card-body d-flex flex-column justify-content-center align-items-start px-4 py-4" style="min-height:340px;">
                <div class="mb-3">
                  <i class="bi bi-star-fill text-primary" style="font-size:2rem;">Why Choose SOLUNAR?</i>
                </div>
                <ul class="list-unstyled mb-0 w-100">
                  <li class="mb-3 d-flex align-items-start"><i class="bi bi-award-fill text-warning me-3 mt-1" style="font-size:1.3rem;"></i><div><strong>Experience:</strong> 5+ years in the solar industry.</div></li>
                  <li class="mb-3 d-flex align-items-start"><i class="bi bi-gem text-primary me-3 mt-1" style="font-size:1.3rem;"></i><div><strong>Quality:</strong> Premium components and proven results.</div></li>
                  <li class="d-flex align-items-start"><i class="bi bi-heart-fill text-danger me-3 mt-1" style="font-size:1.3rem;"></i><div><strong>Integrity:</strong> Transparent ROI, honest advice, and customer-first service.</div></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- End of About Section -->

    <!-- Products Section -->
    <section id="products" class="products section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Products</h2>
        <p>Explore our range of high-quality solar products designed for seamless installation and tailored to your energy needs.</p>
      </div><!-- End Section Title -->

      <div class="container">
        <div class="row gy-4">

          <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="products-item position-relative">
              <div class="icon">
                <i class="fas fa-solar-panel"></i>
              </div>
              <a href="#" class="stretched-link" id="solarPanelLink">
                <h3>Solar Panels</h3>
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="products-item position-relative">
              <div class="icon">
                <i class="fas fa-battery"></i>
              </div>
              <a href="#" class="stretched-link" id="batteryLink">
                <h3>Battery</h3>
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="products-item position-relative">
              <div class="icon">
                <i class="fas fa-cogs"></i>
              </div>
              <a href="#" class="stretched-link" id="inverterLink">
                <h3>Inverter</h3>
              </a>
            </div>
          </div>

          <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
            <div class="products-item position-relative">
              <div class="icon">
                <i class="fas fa-tools"></i>
              </div>
              <a href="#" class="stretched-link" id="accessoriesLink">
                <h3>Accessories</h3>
              </a>
            </div>
          </div>

        </div>

        <!-- Featured Products Section -->
        <div class="mt-5">
          <h3 class="text-center mb-4">Featured Products</h3>
          <div class="row" id="featured-products">
            <!-- Featured products will be loaded dynamically -->
          </div>
        </div>

      </div>

    </section>
    <!-- End of Products Section -->

    <!-- Solar Panel Products Popup -->
    <div id="solar_panelPopup" class="product-popup" style="display: none;">
      <div class="popup-content">
        <div class="popup-header">
          <h2>Solar Panel Products</h2>
          <span class="close-popup">×</span>
        </div>
        <div class="products-grid">
          <!-- Products will be loaded dynamically from get_category_products.php -->
          </div>
      </div>
    </div>

    <!-- Battery Products Popup -->
    <div id="batteryPopup" class="product-popup" style="display: none;">
  <div class="popup-content">
      <div class="popup-header">
          <h2>Battery Products</h2>
          <span class="close-popup">×</span>
      </div>
      <div class="products-grid">
          <!-- Products will be loaded dynamically from get_category_products.php -->
          </div>
  </div>
</div>

<!-- Inverter Products Popup -->
    <div id="inverterPopup" class="product-popup" style="display: none;">
  <div class="popup-content">
      <div class="popup-header">
          <h2>Inverter Products</h2>
          <span class="close-popup">×</span>
      </div>
      <div class="products-grid">
          <!-- Products will be loaded dynamically from get_category_products.php -->
          </div>
          </div>
          </div>

   <!-- Accessories Products Popup -->
    <div id="accessoriesPopup" class="product-popup" style="display: none;">
  <div class="popup-content">
      <div class="popup-header">
          <h2>Accessories</h2>
          <span class="close-popup">×</span>
      </div>
      <div class="products-grid">
          <!-- Products will be loaded dynamically from get_category_products.php -->
          </div>
  </div>
</div>

    <!-- Appointment Section -->
    <section id="appointment" class="appointment section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Appointment</h2>
        <p>We'll help you figure out your solar needs!</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <form action="forms/appointment.php" method="post" role="form" class="php-email-form">
          <div class="row">
            <div class="col-md-4 form-group">
              <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required="">
            </div>

            <div class="col-md-4 form-group mt-3 mt-md-0">
              <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" required="">
            </div>

            <div class="col-md-4 form-group mt-3 mt-md-0">
              <input type="tel" class="form-control" name="phone" id="phone" placeholder="Your Phone" required="">
            </div>
          </div>
          <div class="row">
            <div class="col-md-4 form-group mt-3">
              <input type="datetime-local" name="date" class="form-control datepicker" id="date" placeholder="Appointment Date" required="">
            </div>
            <div class="col-md-4 form-group mt-3">
              <select name="appointment" id="appointment" class="form-select" required="">
                <option value="">Services</option>
                <option value="cleaning">Cleaning</option>
                <option value="installation">Installation</option>
                <option value="repair">Repair</option>
              </select>
            </div>
            <div class="col-md-4 form-group mt-3">
              <select name="location" class="form-select" required="">
                <option value="">Location</option>
                <option value="albay">Albay(Main)</option>
                <option value="legazpi">Legazpi</option>
                <option value="daraga">Daraga</option>>
                <option value="taysan">Taysan</option>
                <option value="camalig">Camalig</option>>
                <option value="guinobatan">Guinobatan</option>
                <option value="ligao">Ligao</option>
                <option value="polangui">Polangui</option>
              </select>
            </div>
          </div>

          <div class="form-group mt-3">
            <textarea class="form-control" name="message" rows="5" placeholder="Message (Optional)"></textarea>
          </div>
          <div class="mt-3">
            <div class="error-message"></div>
            <div class="text-center"><button type="submit">Make an Appointment</button></div>
          </div>
        </form>
      </div>

    </section>
    <!-- End of Appointment Section -->

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Success</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-center">
            <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem; margin-bottom: 1rem;"></i>
            <h5>Appointment Request Submitted</h5>
            <p>Your appointment request has been submitted successfully!</p>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
          </div>
        </div>
      </div>
    </div>

    <script>
    // Handle appointment form submission
    document.querySelector('.php-email-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const errorMessage = form.querySelector('.error-message');
        errorMessage.style.display = 'none';
        
        // Get form data
        const formData = new FormData(form);
        
        // Send form data
        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Reset form
                form.reset();
                // Show success modal
                const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
            } else {
                // Show error message
                errorMessage.textContent = data.message || 'An error occurred. Please try again.';
                errorMessage.style.display = 'block';
            }
        })
        .catch(error => {
            errorMessage.textContent = 'An error occurred. Please try again.';
            errorMessage.style.display = 'block';
            console.error('Error:', error);
        });
    });
    </script>

    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Testimonials</h2>
        <p>What our customers say about our products and services</p>
      </div><!-- End Section Title -->

      <div class="container">
        <div class="row gy-4">
          <?php
          require_once 'includes/get_reviews.php';
          
          // Debug information
          if (empty($reviews)) {
              echo '<div class="col-12 text-center">';
              echo '<p>No reviews available at the moment.</p>';
              echo '</div>';
          } else {
              foreach ($reviews as $index => $review): 
          ?>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 100; ?>">
            <div class="team-member d-flex align-items-start">
              <div class="member-info">
                <h4><?php echo htmlspecialchars($review['user_name']); ?></h4>
                <span><?php echo $review['review_type'] === 'product' ? htmlspecialchars($review['product_name']) : htmlspecialchars($review['service']); ?></span>
                <div class="rating mb-2">
                  <?php for ($i = 1; $i <= 5; $i++): ?>
                    <i class="bi bi-star<?php echo $i <= $review['rating'] ? '-fill' : ''; ?>"></i>
                  <?php endfor; ?>
                </div>
                <p><?php echo htmlspecialchars($review['comment']); ?></p>
                <small class="text-muted"><?php echo date('F j, Y', strtotime($review['created_at'])); ?></small>
              </div>
            </div>
          </div>
          <?php 
              endforeach;
          }
          ?>
        </div>

        <!-- Add Review Form -->
        <div class="review-form mb-5 mt-5">
          <div class="row justify-content-center">
            <div class="col-lg-12 col-xl-12">
              <div class="card shadow-lg border-0" style="border-radius: 18px; background: #f8f9fa;">
                <div class="card-body p-5">
                  <h4 class="card-title mb-4 text-center fw-bold" style="color: #007bff; letter-spacing: 1px;">Share Your Experience</h4>
                  <form id="reviewForm" class="php-email-form" autocomplete="off">
                    <div class="row g-3 align-items-center mb-3">
                      <div class="col-md-6">
                        <label for="reviewName" class="form-label text-muted">Name (optional)</label>
                        <div class="input-group">
                          <span class="input-group-text bg-white border-0"><i class="bi bi-person-circle text-primary"></i></span>
                          <input type="text" name="name" class="form-control rounded-pill shadow-sm" id="reviewName" placeholder="Your Name" style="font-size: 1rem;">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <label for="reviewEmail" class="form-label text-muted">Email (optional)</label>
                        <div class="input-group">
                          <span class="input-group-text bg-white border-0"><i class="bi bi-envelope text-primary"></i></span>
                          <input type="email" class="form-control rounded-pill shadow-sm" name="email" id="reviewEmail" placeholder="Your Email" style="font-size: 1rem;">
                        </div>
                      </div>
                    </div>
                    <div class="form-group mb-4">
                      <label class="form-label text-muted">How would you rate us?</label>
                      <div class="star-rating d-flex align-items-center gap-2" style="font-size: 2rem;">
                        <div class="stars">
                          <span class="star" data-rating="1">★</span>
                          <span class="star" data-rating="2">★</span>
                          <span class="star" data-rating="3">★</span>
                          <span class="star" data-rating="4">★</span>
                          <span class="star" data-rating="5">★</span>
                        </div>
                        <span class="rating-value text-primary fw-bold" style="font-size: 1.2rem;">0</span>
                      </div>
                      <input type="hidden" name="rating" id="ratingInput" required>
                    </div>
                    <div class="form-group mb-4">
                      <label for="reviewText" class="form-label text-muted">Your Review <span class="text-danger">*</span></label>
                      <textarea class="form-control rounded-4 shadow-sm" name="review" id="reviewText" rows="4" maxlength="300" placeholder="Write your review here..." required style="resize: none; font-size: 1.05rem; transition: box-shadow 0.3s;"></textarea>
                      <div class="d-flex justify-content-between mt-1">
                        <small class="text-muted">Max 300 characters</small>
                        <small id="charCount" class="text-muted">0/300</small>
                      </div>
                    </div>
                    <div class="form-group mb-4">
                      <label for="reviewService" class="form-label text-muted">Service</label>
                      <select name="service" class="form-select rounded-pill shadow-sm" id="reviewService" required style="font-size: 1rem;">
                        <option value="">Select Service</option>
                        <option value="installation">Solar Panel Installation</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="repair">Repair Services</option>
                        <option value="consultation">Consultation</option>
                      </select>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                      <button type="reset" class="btn btn-light border shadow-sm px-4 py-2 rounded-pill" style="transition: background 0.2s;">Reset</button>
                      <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill fw-bold shadow" style="font-size: 1.1rem; letter-spacing: 1px; transition: background 0.2s, transform 0.2s;">Submit Review</button>
                    </div>
                  </form>
                  <!-- Thank You Animation/Message -->
                  <div id="reviewThankYou" class="text-center mt-5 d-none">
                    <div class="mb-3">
                      <i class="bi bi-emoji-smile text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-bold text-success mb-2">Thank you for your review!</h5>
                    <p class="text-muted">We appreciate your feedback. Your review will be visible after approval.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <style>
        /* Review Form Enhancements */
        .review-form .form-control:focus, .review-form .form-select:focus {
          box-shadow: 0 0 0 0.2rem #b6d4fe;
          border-color: #0d6efd;
          background: #e9f3ff;
        }
        .review-form .star-rating .star {
          color: #e3eafc;
          cursor: pointer;
          transition: color 0.2s, transform 0.2s;
        }
        .review-form .star-rating .star.selected,
        .review-form .star-rating .star.hovered {
          color: #0d6efd;
          transform: scale(1.15);
        }
        .review-form .btn-primary {
          background: linear-gradient(90deg, #007bff 60%, #0d6efd 100%);
          border: none;
        }
        .review-form .btn-primary:hover {
          background: linear-gradient(90deg, #0d6efd 60%, #007bff 100%);
          transform: translateY(-2px) scale(1.04);
        }
        .review-form .btn-light:hover {
          background: #e3eafc;
        }
        @media (max-width: 767px) {
          .review-form .card-body {
            padding: 2rem 1rem !important;
          }
        }
        </style>

        <script>
        // Star rating functionality
        document.addEventListener('DOMContentLoaded', function() {
          const stars = document.querySelectorAll('.review-form .star');
          const ratingValue = document.querySelector('.review-form .rating-value');
          const ratingInput = document.getElementById('ratingInput');
          let selectedRating = 0;

          stars.forEach(star => {
            star.addEventListener('mouseover', function() {
              const rating = this.getAttribute('data-rating');
              updateStars(rating);
            });
            star.addEventListener('mouseout', function() {
              updateStars(selectedRating);
            });
            star.addEventListener('click', function() {
              selectedRating = this.getAttribute('data-rating');
              ratingInput.value = selectedRating;
              updateStars(selectedRating);
            });
          });
          function updateStars(rating) {
            stars.forEach(star => {
              const starRating = star.getAttribute('data-rating');
              if (starRating <= rating) {
                star.classList.add('selected');
              } else {
                star.classList.remove('selected');
              }
            });
            ratingValue.textContent = rating;
          }

          // Character count for textarea
          const reviewText = document.getElementById('reviewText');
          const charCount = document.getElementById('charCount');
          reviewText.addEventListener('input', function() {
            charCount.textContent = `${this.value.length}/300`;
          });

          // Animated transitions for focus
          document.querySelectorAll('.review-form .form-control, .review-form .form-select').forEach(input => {
            input.addEventListener('focus', function() {
              this.style.transition = 'box-shadow 0.3s, background 0.3s';
            });
          });

          // Form submission with thank you animation
          const reviewForm = document.getElementById('reviewForm');
          const thankYou = document.getElementById('reviewThankYou');
          reviewForm.addEventListener('submit', function(e) {
            e.preventDefault();
            // Validate rating
            if (!ratingInput.value) {
              // alert('Please select a rating');
              return;
            }
            // Simulate AJAX submission
            reviewForm.classList.add('d-none');
            thankYou.classList.remove('d-none');
            setTimeout(() => {
              reviewForm.reset();
              selectedRating = 0;
              updateStars(0);
              ratingInput.value = '';
              charCount.textContent = '0/300';
              thankYou.classList.add('d-none');
              reviewForm.classList.remove('d-none');
            }, 3500);
          });
        });
        </script>
      </div>
    </section>

    <!-- Learn Section -->
    <section id="learn" class="learn section position-relative">
      <div class="container position-relative" style="z-index: 2;">
        <div class="container section-title" data-aos="fade-up">
          <h2>Learn</h2>
          <p>Explore our interactive solar tools and simulations to better understand solar energy</p>
        </div>
        <div class="row">
          <!-- Solar Calculator -->
          <div class="col-lg-4 mb-4">
            <div class="card h-100">
              <div class="card-body">
                <h5 class="card-title">Solar Calculator</h5>
                <p class="card-text">Calculate your potential solar savings and system requirements</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#solarCalculatorModal">
                  Calculate Solar Power
                </button>
              </div>
            </div>
          </div>
          <!-- Solar Estimation -->
          <div class="col-lg-4 mb-4">
            <div class="card h-100">
              <div class="card-body">
                <h5 class="card-title">Solar Estimation</h5>
                <p class="card-text">Estimate your solar panel requirements</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#solarEstimateModal">
                  Estimate Solar System
                </button>
              </div>
            </div>
          </div>
          <!-- Solar Power Simulation -->
          <div class="col-lg-4 mb-4">
            <div class="card h-100">
              <div class="card-body">
                <h5 class="card-title">Solar Power Simulation</h5>
                <p class="card-text">Simulate your solar power generation</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#solarSimulationModal">
                  Run Simulation
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- End of Learn Section -->

    <!-- Solar Calculator Modal -->
    <div class="modal fade" id="solarCalculatorModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Solar Power Calculator</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form id="solarCalculatorForm" class="row g-3">
              <div class="col-md-6">
                <label for="calculatorMonthlyBill" class="form-label">Monthly Electricity Bill (₱)</label>
                <input type="number" class="form-control" id="calculatorMonthlyBill" required>
              </div>

              <div class="col-md-6">
                <label for="calculatorUsageType" class="form-label">Usage Type</label>
                <select class="form-select" id="calculatorUsageType" required>
                  <option value="">Select Usage Type</option>
                  <option value="household">Household</option>
                  <option value="commercial">Commercial</option>
                  <option value="industrial">Industrial</option>
                </select>
                <small class="text-muted">Select the type of usage for your solar system</small>
              </div>
              <div class="col-md-6">
                <label for="calculatorPanelSize" class="form-label">Panel Size (Watts)</label>
                <select class="form-select" id="calculatorPanelSize" required>
                  <option value="">Select Panel Size</option>
                  <option value="250">250W</option>
                  <option value="300" selected>300W (Standard)</option>
                  <option value="350">350W</option>
                  <option value="400">400W</option>
                  <option value="450">450W</option>
                  <option value="500">500W</option>
                </select>
                <small class="text-muted">Select the wattage of your solar panels</small>
              </div>
              <div class="col-md-6">
                <label for="calculatorPanelCount" class="form-label">Number of Panels</label>
                <input type="number" class="form-control" id="calculatorPanelCount" min="1" value="1" required>
                <small class="text-muted">Enter the number of panels you want to install</small>
              </div>
              <div class="col-md-6">
                <label for="calculatorBackupDays" class="form-label">Backup Days</label>
                <input type="number" class="form-control" id="calculatorBackupDays" value="3" required>
              </div>
              <div class="col-md-6">
                <label for="calculatorBackupHours" class="form-label">Backup Hours per Day</label>
                <input type="number" class="form-control" id="calculatorBackupHours" value="8" required>
              </div>
              <div class="col-md-6">
                <label for="calculatorLocation" class="form-label">Location (Bicol Area)</label>
                <select class="form-select" id="calculatorLocation" required>
                  <option value="">Select Location</option>
                  <option value="naga">Naga City (3.5 sun hours)</option>
                  <option value="legazpi">Legazpi City (3.5 sun hours)</option>
                  <option value="iriga">Iriga City (4 sun hours)</option>
                  <option value="tabaco">Tabaco City (4 sun hours)</option>
                  <option value="sorsogon">Sorsogon City (4.5 sun hours)</option>
                  <option value="masbate">Masbate City (4.5 sun hours)</option>
                </select>
                <small class="text-muted">Select your city in Bicol Area</small>
              </div>
              <div class="col-md-6">
                <label for="calculatorSystemType" class="form-label">System Type</label>
                <select class="form-select" id="calculatorSystemType" required>
                  <option value="">Select System Type</option>
                  <option value="grid-tied">Grid-Tied System</option>
                  <option value="off-grid">Off-Grid System</option>
                  <option value="hybrid">Hybrid System</option>
                </select>
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-primary">Calculate</button>
                <button type="button" class="btn btn-secondary" onclick="resetCalculator()">Reset</button>
              </div>
            </form>
            <div id="calculatorResults" class="mt-3"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Solar Estimation Modal -->
    <div class="modal fade" id="solarEstimateModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Solar System Estimator</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form id="solarEstimateForm">
              <div class="mb-3">
                <label for="estimateMonthlyBill" class="form-label">Monthly Electricity Bill (₱)</label>
                <input type="number" class="form-control" id="estimateMonthlyBill" min="0" required>
              </div>
              <div class="mb-3">
                <label for="estimateRoofArea" class="form-label">Available Roof Area (sqm)</label>
                <input type="number" class="form-control" id="estimateRoofArea" min="0" required>
              </div>
              <div class="mb-3">
                <label for="estimateUsageHours" class="form-label">Average Daily Usage Hours</label>
                <input type="number" class="form-control" id="estimateUsageHours" min="0" max="24" required>
              </div>
              <div class="mb-3">
                <label for="estimateLocation" class="form-label">Location (Bicol Area)</label>
                <select class="form-select" id="estimateLocation" required>
                  <option value="">Select Location</option>
                  <option value="naga">Naga City (3.5 sun hours)</option>
                  <option value="legazpi">Legazpi City (3.5 sun hours)</option>
                  <option value="iriga">Iriga City (4 sun hours)</option>
                  <option value="tabaco">Tabaco City (4 sun hours)</option>
                  <option value="sorsogon">Sorsogon City (4.5 sun hours)</option>
                  <option value="masbate">Masbate City (4.5 sun hours)</option>
                </select>
                <small class="text-muted">Select your city in Bicol Area</small>
              </div>
              <div class="mb-3">
                <label for="estimateSystemType" class="form-label">System Type</label>
                <select class="form-select" id="estimateSystemType" required>
                  <option value="">Select System</option>
                  <option value="grid-tied">Grid-Tied</option>
                  <option value="off-grid">Off-Grid</option>
                  <option value="hybrid">Hybrid</option>
                </select>
              </div>
              <button type="submit" class="btn btn-primary">Estimate</button>
              <button type="button" class="btn btn-secondary" onclick="resetEstimator()">Reset</button>
            </form>
            <div id="estimateResults" class="mt-3"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Solar Power Simulation Modal -->
    <div class="modal fade" id="solarSimulationModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Solar Power Simulation</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form id="simulationForm">
              <div class="mb-3">
                <label for="panelCount" class="form-label">Number of Panels</label>
                <input type="number" class="form-control" id="panelCount" value="10" required>
                <small class="text-muted">Number of solar panels in your system</small>
              </div>
              <div class="mb-3">
                <label for="panelWattage" class="form-label">Panel Wattage (W)</label>
                <select class="form-select" id="panelWattage" required>
                  <option value="250">250W (Standard)</option>
                  <option value="300">300W (Premium)</option>
                  <option value="350">350W (High-Efficiency)</option>
                  <option value="400">400W (Ultra-Efficient)</option>
                </select>
                <small class="text-muted">Select your panel wattage</small>
              </div>
              <div class="mb-3">
                <label for="location" class="form-label">Location (Bicol Area)</label>
                <select class="form-select" id="location" required>
                  <option value="">Select Location</option>
                  <option value="naga">Naga City (3.5 sun hours)</option>
                  <option value="legazpi">Legazpi City (3.5 sun hours)</option>
                  <option value="iriga">Iriga City (4 sun hours)</option>
                  <option value="tabaco">Tabaco City (4 sun hours)</option>
                  <option value="sorsogon">Sorsogon City (4.5 sun hours)</option>
                  <option value="masbate">Masbate City (4.5 sun hours)</option>
                </select>
                <small class="text-muted">Select your city in Bicol Area</small>
              </div>
              <div class="mb-3">
                <label for="systemEfficiency" class="form-label">System Efficiency (%)</label>
                <input type="number" class="form-control" id="systemEfficiency" value="85" required>
                <small class="text-muted">Typical efficiency: 80-90%</small>
              </div>
              <div class="mb-3">
                <label for="monthlyBill" class="form-label">Monthly Electricity Bill (₱)</label>
                <input type="number" class="form-control" id="monthlyBill" value="1000" required>
                <small class="text-muted">Your current monthly electricity bill</small>
              </div>
              <div class="mb-3">
                <label for="usagePattern" class="form-label">Usage Pattern</label>
                <select class="form-select" id="usagePattern" required>
                  <option value="">Select Usage Pattern</option>
                  <option value="even">Even Usage (Day/Night)</option>
                  <option value="day-heavy">Day Heavy (70% Day)</option>
                  <option value="night-heavy">Night Heavy (70% Night)</option>
                </select>
                <small class="text-muted">Select your typical usage pattern</small>
              </div>
              <div class="mb-3">
                <label for="systemType" class="form-label">System Type</label>
                <select class="form-select" id="systemType" required>
                  <option value="">Select System Type</option>
                  <option value="grid-tied">Grid-Tied</option>
                  <option value="off-grid">Off-Grid</option>
                  <option value="hybrid">Hybrid</option>
                </select>
                <small class="text-muted">Select your system configuration</small>
              </div>
              <button type="submit" class="btn btn-primary">Run Simulation</button>
              <button type="button" class="btn btn-secondary" onclick="resetSimulation()">Reset</button>
            </form>
            <div id="simulationResults" class="mt-3"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Faq Section -->
    <section id="faq" class="faq section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Frequently Asked Questions</h2>
        <p>Find answers to common questions about our solar products and services</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row justify-content-center">

          <div class="col-lg-10" data-aos="fade-up" data-aos-delay="100">

            <div class="faq-container">

              <div class="faq-item faq-active">
                <h3>What are the benefits of installing solar panels?</h3>
                <div class="faq-content">
                  <p>Installing solar panels offers numerous benefits including significant reduction in electricity bills, increased property value, environmental sustainability, energy independence, and protection against rising energy costs. Our solar systems typically provide a return on investment within 4-7 years.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>How long do solar panels last?</h3>
                <div class="faq-content">
                  <p>Our high-quality solar panels are designed to last 25-30 years with proper maintenance. While their efficiency may slightly decrease over time (typically 0.5% per year), they continue to generate significant power throughout their lifespan. We provide comprehensive warranties to ensure long-term performance.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>What maintenance is required for solar systems?</h3>
                <div class="faq-content">
                  <p>Solar systems require minimal maintenance. We recommend annual professional inspections to ensure optimal performance. Regular cleaning of panels (2-4 times per year) and monitoring of system performance through our mobile app are the main maintenance tasks. Our service team is available for any required maintenance or repairs.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>How do I know if my home is suitable for solar panels?</h3>
                <div class="faq-content">
                  <p>Most homes are suitable for solar installation. Key factors include roof orientation (south-facing is ideal), roof condition, shading, and available space. Our experts conduct a thorough site assessment to determine the optimal system design and potential energy production for your specific location.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>What happens during power outages?</h3>
                <div class="faq-content">
                  <p>With our hybrid solar systems that include battery storage, you can continue to power essential appliances during grid outages. The system automatically switches to battery power, providing backup electricity for critical loads. The duration of backup power depends on your battery capacity and energy usage.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>What financing options are available?</h3>
                <div class="faq-content">
                  <p>We offer flexible financing options including cash purchase, solar loans, and lease agreements. Our team will help you choose the best option based on your financial situation and goals. We also assist with available government incentives and tax credits to maximize your savings.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

            </div>

          </div><!-- End Faq Column-->

        </div>

      </div>

    </section>
<!-- /Faq Section -->

  </main>

  <footer id="footer" class="footer light-background">
    <div class="container copyright text-center mt-4">
        <p> <span>Copyright</span> <strong class="px-1 sitename">SOLUNAR</strong> <span>All Rights Reserved</span></p>
    </div>
</footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Admin Login Modal -->
  <div class="modal fade" id="adminLoginModal" tabindex="-1" aria-labelledby="adminLoginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="adminLoginModalLabel">Admin Login</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="adminLoginForm">
            <div class="mb-3">
              <label for="adminUsername" class="form-label">Username</label>
              <input type="text" class="form-control" id="adminUsername" required>
            </div>
            <div class="mb-3">
              <label for="adminPassword" class="form-label">Password</label>
              <input type="password" class="form-control" id="adminPassword" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

  <script>


    // Shopping Cart Modal
    const cartModal = document.getElementById('cartModal');
    const cartCount = document.querySelector('.cart-count');
    let cart = [];

    // Function to update cart count
    function updateCartCount() {
      cartCount.textContent = cart.length;
    }

    // Function to update cart total
    function updateCartTotal() {
      const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
      document.getElementById('cart-total').textContent = `₱${total.toFixed(2)}`;
    }

    // Function to add item to cart
    function addToCart(productId, productName, productPrice) {
      const existingItem = cart.find(item => item.id === productId);
      let addedItem;
      
      if (existingItem) {
        existingItem.quantity += 1;
        addedItem = existingItem;
      } else {
        addedItem = {
          id: productId,
          name: productName,
          price: parseFloat(productPrice),
          quantity: 1
        };
        cart.push(addedItem);
      }

      updateCartCount();
      updateCartTotal();
      updateCartDisplay();
      
      // Show the confirmation modal
      showCartConfirmation(addedItem);
    }
    
    // Function to show cart confirmation popup
    function showCartConfirmation(product) {
      const modalElement = document.getElementById('cartConfirmationModal');
      const modal = new bootstrap.Modal(modalElement);
      
      // Update product name in the modal
      document.getElementById('addedProductName').textContent = product.name;
      
      // Remove any existing event listeners
      const revertBtn = document.getElementById('revertAddToCart');
      const viewCartBtn = document.getElementById('viewCartBtn');
      
      const newRevertBtn = revertBtn.cloneNode(true);
      const newViewCartBtn = viewCartBtn.cloneNode(true);
      
      revertBtn.parentNode.replaceChild(newRevertBtn, revertBtn);
      viewCartBtn.parentNode.replaceChild(newViewCartBtn, viewCartBtn);
      
      // Set up new event listeners
      newRevertBtn.addEventListener('click', function() {
        // Remove the item or decrease quantity
        if (product.quantity > 1) {
          product.quantity -= 1;
        } else {
          const itemIndex = cart.findIndex(item => item.id === product.id);
          if (itemIndex > -1) {
            cart.splice(itemIndex, 1);
          }
        }
        
        updateCartCount();
        updateCartTotal();
        updateCartDisplay();
        
        // Close the modal
        const modalInstance = bootstrap.Modal.getInstance(modalElement);
        if (modalInstance) {
          modalInstance.hide();
        }
      });
      
      newViewCartBtn.addEventListener('click', function() {
        // Close the current modal
        modal.hide();
        
        // Show the cart modal after a short delay to allow the current modal to close
        setTimeout(() => {
          const cartModal = new bootstrap.Modal(document.getElementById('cartModal'));
          cartModal.show();
        }, 150);
      });
      
      // Show the modal
      modal.show();
    }

    // Function to remove item from cart
    function removeFromCart(productId) {
      const itemIndex = cart.findIndex(item => item.id === productId);
      if (itemIndex > -1) {
        cart.splice(itemIndex, 1);
        updateCartCount();
        updateCartTotal();
        updateCartDisplay();
      }
    }

    // Function to update cart display
    function updateCartDisplay() {
      const cartItems = document.getElementById('cart-items');
      const emptyCartMessage = document.getElementById('empty-cart-message');
      
      if (cart.length === 0) {
        cartItems.innerHTML = `
          <div id="empty-cart-message" class="text-center py-5">
            <i class="bi bi-cart-x text-muted" style="font-size: 3rem;"></i>
            <p class="mt-3 text-muted">Your cart is empty</p>
          </div>`;
        return;
      }
      
      let itemsHTML = '';
      cart.forEach(item => {
        itemsHTML += `
          <div class="cart-item" data-id="${item.id}">
            <img src="${item.image || 'assets/img/placeholder.jpg'}" alt="${item.name}" class="cart-item-img">
            <div class="cart-item-details">
              <div class="cart-item-title">${item.name}</div>
              <div class="cart-item-price">₱${item.price.toFixed(2)}</div>
              <div class="quantity-controls">
                <div class="quantity-btn decrease-quantity">-</div>
                <input type="number" class="quantity-input" value="${item.quantity}" min="1">
                <div class="quantity-btn increase-quantity">+</div>
              </div>
            </div>
            <div class="ms-auto text-end">
              <div class="cart-item-price">₱${(item.price * item.quantity).toFixed(2)}</div>
              <div class="remove-item" data-id="${item.id}" title="Remove item">
                <i class="bi bi-trash"></i>
              </div>
            </div>
          </div>`;
      });
      
      cartItems.innerHTML = itemsHTML;
      
      // Add event listeners for quantity controls
      document.querySelectorAll('.decrease-quantity').forEach(btn => {
        btn.addEventListener('click', function() {
          const productId = this.closest('.cart-item').dataset.id;
          const item = cart.find(item => item.id === productId);
          if (item && item.quantity > 1) {
            item.quantity--;
            updateCartDisplay();
            updateCartCount();
            updateCartTotal();
          }
        });
      });
      
      document.querySelectorAll('.increase-quantity').forEach(btn => {
        btn.addEventListener('click', function() {
          const productId = this.closest('.cart-item').dataset.id;
          const item = cart.find(item => item.id === productId);
          if (item) {
            item.quantity++;
            updateCartDisplay();
            updateCartCount();
            updateCartTotal();
          }
        });
      });
      
      // Add event listeners for remove buttons
      document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', function(e) {
          e.stopPropagation();
          const productId = this.getAttribute('data-id');
          removeFromCart(productId);
        });
      });
      
      // Update quantity when input changes
      document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
          const productId = this.closest('.cart-item').dataset.id;
          const item = cart.find(item => item.id === productId);
          if (item) {
            const newQuantity = parseInt(this.value) || 1;
            item.quantity = Math.max(1, newQuantity);
            updateCartDisplay();
            updateCartCount();
            updateCartTotal();
          }
        });
      });
    }

    // Initialize event listeners
    document.addEventListener('DOMContentLoaded', function() {
      const addToCartButtons = document.querySelectorAll('.add-to-cart');
      addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
          const productId = this.dataset.productId;
          const productName = this.dataset.productName;
          const productPrice = this.dataset.productPrice;
          addToCart(productId, productName, productPrice);
        });
      });

      // Close popup functionality
      const closeButtons = document.querySelectorAll('.close-popup');
      const popups = document.querySelectorAll('.product-popup');

      closeButtons.forEach(button => {
        button.addEventListener('click', function() {
          const popup = this.closest('.product-popup');
          popup.style.display = 'none';
        });
      });

      // Click outside to close
      popups.forEach(popup => {
        popup.addEventListener('click', function(e) {
          if (e.target === this) {
            this.style.display = 'none';
          }
        });
      });

      // Open popup functionality
      const productLinks = {
        'solarPanelLink': 'solar_panelPopup',
        'batteryLink': 'batteryPopup',
        'inverterLink': 'inverterPopup',
        'accessoriesLink': 'accessoriesPopup'
      };

      Object.entries(productLinks).forEach(([linkId, popupId]) => {
        const link = document.getElementById(linkId);
        const popup = document.getElementById(popupId);
        
        if (link && popup) {
          link.addEventListener('click', function(e) {
            e.preventDefault();
            popup.style.display = 'flex';
          });
        }
      });
    });

    // Solar Calculator Functions
    function calculateSolarSystem() {
      const monthlyBill = parseFloat(document.getElementById('calculatorMonthlyBill').value);
      const usageType = document.getElementById('calculatorUsageType').value;
      const panelSize = parseInt(document.getElementById('calculatorPanelSize').value);
      const panelCount = parseInt(document.getElementById('calculatorPanelCount').value);
      const backupDays = parseInt(document.getElementById('calculatorBackupDays').value);
      const backupHours = parseInt(document.getElementById('calculatorBackupHours').value);
      const location = document.getElementById('calculatorLocation').value;
      const systemType = document.getElementById('calculatorSystemType').value;

      // Validate inputs
      if (isNaN(monthlyBill) || !usageType || isNaN(panelSize) || isNaN(panelCount) || 
          isNaN(backupDays) || isNaN(backupHours) || !location || !systemType) {
        alert('Please fill in all required fields');
        return;
      }

      // Constants
      const panelArea = 1.6; // Standard panel area in sqm
      const daysInMonth = new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0).getDate();
      
      // Location-based sun hours
      const sunHours = {
        'naga': 3.5,  // Naga City
        'legazpi': 3.5,  // Legazpi City
        'iriga': 4,  // Iriga City
        'tabaco': 4,   // Tabaco City
        'sorsogon': 4.5,   // Sorsogon City
        'masbate': 4.5   // Masbate City
      };

      // Usage type parameters
      const usageParams = {
        'household': {
          efficiency: 0.80,
          defaultBackupDays: 3,
          defaultBackupHours: 8,
          costMultiplier: 1.0
        },
        'commercial': {
          efficiency: 0.75,
          defaultBackupDays: 2,
          defaultBackupHours: 12,
          costMultiplier: 1.1
        },
        'industrial': {
          efficiency: 0.70,
          defaultBackupDays: 1,
          defaultBackupHours: 24,
          costMultiplier: 1.2
        }
      };

      // Get usage type parameters
      const params = usageParams[usageType];
      const systemEfficiency = params.efficiency;
      const finalBackupDays = backupDays || params.defaultBackupDays;
      const finalBackupHours = backupHours || params.defaultBackupHours;

      // Calculate daily energy consumption
      const dailyKWh = monthlyBill / daysInMonth;
      const dailyWh = dailyKWh * 1000;

      // Calculate system size based on user input
      const systemSize = panelCount * panelSize;
      const totalArea = panelCount * panelArea;

      // Calculate battery requirements
      const dailyBackupEnergy = dailyKWh * finalBackupHours / 24;
      const totalBackupEnergy = dailyBackupEnergy * finalBackupDays;
      const batteryCapacity = Math.ceil(totalBackupEnergy * 1000 * 1.2); // Add 20% buffer

      // Calculate system cost with usage type multiplier
      const panelCost = 25000; // Cost per panel in ₱
      const inverterCost = systemType === 'off-grid' ? 80000 : (systemType === 'hybrid' ? 65000 : 50000);
      const installationCost = 30000 + (panelCount * 1000); // Base cost + per panel cost
      const batteryCost = systemType === 'off-grid' ? 50000 : (systemType === 'hybrid' ? 35000 : 0);
      
      const totalCost = (panelCount * panelCost * params.costMultiplier) + 
                       (inverterCost * params.costMultiplier) + 
                       (installationCost * params.costMultiplier) + 
                       (batteryCost * params.costMultiplier);

      // Calculate monthly savings based on system type
      let monthlySavings;
      if (systemType === 'grid-tied') {
        monthlySavings = dailyKWh * daysInMonth * 0.5; // 50% reduction
      } else if (systemType === 'off-grid') {
        monthlySavings = dailyKWh * daysInMonth * 0.9; // 90% reduction
      } else { // hybrid
        monthlySavings = dailyKWh * daysInMonth * 0.7; // 70% reduction
      }
      const paybackPeriod = Math.ceil(totalCost / (monthlySavings * 12));

      // Calculate energy production
      const energyProduction = Math.round(systemSize * sunHours[location] * daysInMonth * 
                                         systemEfficiency);

      // Display results
      const results = document.getElementById('calculatorResults');
      results.innerHTML = `
        <div class="alert alert-success">
          <h5>System Requirements:</h5>
          <p><strong>Usage Type:</strong> ${usageType.charAt(0).toUpperCase() + usageType.slice(1)}</p>
          <p><strong>System Size:</strong> ${systemSize}W (${panelCount} x ${panelSize}W panels)</p>
          <p><strong>Total Area Required:</strong> ${totalArea.toFixed(1)} sqm</p>
          <p><strong>Battery Capacity:</strong> ${Math.round(batteryCapacity)}Wh</p>
          <p><strong>Estimated Cost:</strong> ₱${totalCost.toLocaleString()}</p>
          <p><strong>Monthly Savings:</strong> ₱${monthlySavings.toFixed(0)}</p>
          <p><strong>Payback Period:</strong> ${Math.min(paybackPeriod, 10)} years</p>
          <p><strong>System Efficiency:</strong> ${Math.round(systemEfficiency * 100)}%</p>
          <p><strong>Estimated Monthly Production:</strong> ${energyProduction} kWh</p>
          <p><strong>Days in Month:</strong> ${daysInMonth} days</p>
        </div>
      `;
    }

    // Solar Estimation Functions
    function estimateSolarSystem() {
      const monthlyBill = parseFloat(document.getElementById('estimateMonthlyBill').value);
      const roofArea = parseFloat(document.getElementById('estimateRoofArea').value);
      const usageHours = parseFloat(document.getElementById('estimateUsageHours').value);
      const location = document.getElementById('estimateLocation').value;
      const systemType = document.getElementById('estimateSystemType').value;

      // Validate inputs
      if (isNaN(monthlyBill) || isNaN(roofArea) || isNaN(usageHours) || !location || !systemType) {
        alert('Please fill in all required fields');
        return;
      }

      // Constants
      const panelSize = 300; // Standard panel size in watts
      const panelArea = 1.6; // Standard panel area in sqm
      const daysInMonth = new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0).getDate();
      
      // Location-based sun hours
      const sunHours = {
        'naga': 4.5,  // Naga City
        'legazpi': 4.5,  // Legazpi City
        'iriga': 5.0,  // Iriga City
        'tabaco': 4.5,   // Tabaco City
        'sorsogon': 5.0,   // Sorsogon City
        'masbate': 5.5   // Masbate City
      };

      // Calculate maximum possible panels based on roof area
      const maxPanels = Math.floor(roofArea / panelArea);

      // Calculate energy requirements
      const dailyKWh = monthlyBill / daysInMonth;
      const requiredWatts = Math.ceil(dailyKWh * 1000 / (sunHours[location] * 0.75));
      
      // Calculate recommended system size
      const recommendedPanels = Math.min(Math.ceil(requiredWatts / panelSize), maxPanels);
      const systemSize = recommendedPanels * panelSize;

      // Calculate battery requirements for off-grid systems
      let batteryCapacity = 0;
      if (systemType === 'off-grid') {
        const dailyBackupEnergy = dailyKWh * (usageHours / 24);
        batteryCapacity = Math.ceil(dailyBackupEnergy * 1000 * 1.2); // 20% buffer
      } else if (systemType === 'hybrid') {
        const dailyBackupEnergy = dailyKWh * (usageHours / 24) * 0.5; // Half the capacity for hybrid
        batteryCapacity = Math.ceil(dailyBackupEnergy * 1000 * 1.2); // 20% buffer
      }

      // Calculate estimated energy production in kWh
      const estimatedProduction = (systemSize * sunHours[location] * daysInMonth * 0.75) / 1000;

      // Calculate system cost (updated with more accurate pricing)
      const panelCost = 15000; // Cost per 300W panel in ₱
      const inverterCost = systemType === 'off-grid' ? 80000 : (systemType === 'hybrid' ? 65000 : 50000);
      const installationCost = 20000 + (recommendedPanels * 1000); // Base + per panel
      const batteryCost = batteryCapacity > 0 ? Math.ceil(batteryCapacity * 15) : 0; // ~₱15/Wh
      
      const totalCost = (recommendedPanels * panelCost) + inverterCost + installationCost + batteryCost;

      // Calculate monthly savings based on system type
      let monthlySavings;
      if (systemType === 'grid-tied') {
        monthlySavings = monthlyBill * 0.75; // 75% reduction
      } else if (systemType === 'off-grid') {
        monthlySavings = monthlyBill * 0.9; // 90% reduction
      } else { // hybrid
        monthlySavings = monthlyBill * 0.85; // 85% reduction
      }
      const yearlySavings = monthlySavings * 12;

      // Calculate payback period in years
      const paybackPeriod = totalCost / yearlySavings;

      // Calculate system coverage (percentage of roof used)
      const systemCoverage = ((recommendedPanels * panelArea) / roofArea) * 100;

      // Display results
      const results = document.getElementById('estimateResults');
      results.innerHTML = `
        <div class="alert alert-success">
          <h5>Solar System Estimation Results:</h5>
          <div class="row">
            <div class="col-md-6">
              <p><strong>System Size:</strong> ${systemSize}W (${recommendedPanels} x ${panelSize}W panels)</p>
              <p><strong>Battery Capacity:</strong> ${batteryCapacity > 0 ? Math.round(batteryCapacity/1000) + 'kWh' : 'Not Required'}</p>
              <p><strong>Roof Coverage:</strong> ${(recommendedPanels * panelArea).toFixed(1)} sqm (${systemCoverage.toFixed(1)}% of available)</p>
              <p><strong>Estimated Production:</strong> ${estimatedProduction.toFixed(0)} kWh/month</p>
            </div>
            <div class="col-md-6">
              <p><strong>Estimated Cost:</strong> ₱${totalCost.toLocaleString()}</p>
              <p><strong>Monthly Savings:</strong> ₱${monthlySavings.toFixed(0)}</p>
              <p><strong>Yearly Savings:</strong> ₱${yearlySavings.toFixed(0)}</p>
              <p><strong>Payback Period:</strong> ${paybackPeriod.toFixed(1)} years</p>
            </div>
          </div>
          <div class="mt-3">
            <p class="mb-1"><small class="text-muted">Note: These are estimates. Actual results may vary based on installation conditions and actual usage patterns.</small></p>
          </div>
        </div>
      `;
    }

    // Solar Simulation Functions
    function simulateSolarPower() {
      const panelCount = parseInt(document.getElementById('panelCount').value);
      const panelWattage = parseInt(document.getElementById('panelWattage').value);
      const location = document.getElementById('location').value;
      const systemEfficiency = parseFloat(document.getElementById('systemEfficiency').value) / 100;
      const monthlyBill = parseFloat(document.getElementById('monthlyBill').value);
      const usagePattern = document.getElementById('usagePattern').value;

      // Validate inputs
      if (isNaN(panelCount) || isNaN(panelWattage) || !location || isNaN(systemEfficiency) || 
          isNaN(monthlyBill) || !usagePattern) {
        alert('Please fill in all required fields');
        return;
      }

      // Constants
      const panelArea = 1.6; // Standard panel area in sqm
      const daysInMonth = new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0).getDate();
      
      // Location-based sun hours
      const sunHours = {
        'naga': 3.5,  // Naga City
        'legazpi': 3.5,  // Legazpi City
        'iriga': 4,  // Iriga City
        'tabaco': 4,   // Tabaco City
        'sorsogon': 4.5,   // Sorsogon City
        'masbate': 4.5   // Masbate City
      };

      // Calculate system specifications
      const systemSize = panelCount * panelWattage;
      const totalArea = panelCount * panelArea;

      // Calculate monthly energy production with seasonal variation
      const monthlyProduction = [];
      const monthlySunHours = [3.5, 3.5, 4, 4.5, 5, 5.5, 5.5, 5, 4.5, 4, 3.5, 3.5]; // Based on Philippine solar data

      for (let month = 0; month < 12; month++) {
        const monthlyEnergy = Math.round((systemSize * monthlySunHours[month] * daysInMonth * 
                            systemEfficiency * 0.75) / 1000); // Include 25% system losses
        monthlyProduction.push(monthlyEnergy);
      }

      // Calculate yearly production
      const yearlyEnergy = monthlyProduction.reduce((sum, value) => sum + value, 0);

      // Calculate savings based on usage pattern and system type
      let monthlySavings = 0;
      let yearlySavings = 0;
      const dailyKWh = monthlyBill / daysInMonth;

      const systemType = document.getElementById('systemType').value;
      let baseSavings;
      
      switch(systemType) {
        case 'grid-tied':
          baseSavings = 0.5; // 50% base reduction
          break;
        case 'off-grid':
          baseSavings = 0.9; // 90% base reduction
          break;
        case 'hybrid':
          baseSavings = 0.7; // 70% base reduction
          break;
        default:
          baseSavings = 0.5;
      }

      switch(usagePattern) {
        case 'even':
          monthlySavings = dailyKWh * daysInMonth * baseSavings;
          break;
        case 'day-heavy':
          monthlySavings = dailyKWh * daysInMonth * (baseSavings + 0.1); // Add 10% for day-heavy usage
          break;
        case 'night-heavy':
          monthlySavings = dailyKWh * daysInMonth * (baseSavings - 0.1); // Subtract 10% for night-heavy usage
          break;
      }
      yearlySavings = monthlySavings * 12;

      // Calculate energy production
      const energyProduction = Math.round(systemSize * sunHours[location] * daysInMonth * 
                                         systemEfficiency);

      // Display results
      const results = document.getElementById('simulationResults');
      results.innerHTML = `
        <div class="alert alert-success">
          <h5>Simulation Results:</h5>
          <p><strong>System Size:</strong> ${systemSize}W</p>
          <p><strong>Total Area:</strong> ${totalArea.toFixed(1)} sqm</p>
          <p><strong>Monthly Production:</strong> ${monthlyProduction[0]} kWh</p>
          <p><strong>Yearly Production:</strong> ${yearlyEnergy} kWh</p>
          <p><strong>Monthly Savings:</strong> ₱${monthlySavings.toFixed(0)}</p>
          <p><strong>Yearly Savings:</strong> ₱${yearlySavings.toFixed(0)}</p>
          <p><strong>System Efficiency:</strong> ${Math.round(systemEfficiency * 100)}%</p>
          <p><strong>Days in Month:</strong> ${daysInMonth} days</p>
        </div>
      `;
    }

    // Add event listeners
    document.getElementById('solarCalculatorForm').addEventListener('submit', function(e) {
      e.preventDefault();
      calculateSolarSystem();
    });

    document.getElementById('solarEstimateForm').addEventListener('submit', function(e) {
      e.preventDefault();
      estimateSolarSystem();
    });

    document.getElementById('simulationForm').addEventListener('submit', function(e) {
      e.preventDefault();
      simulateSolarPower();
    });

    // Reset functions
    function resetCalculator() {
      document.getElementById('solarCalculatorForm').reset();
      document.getElementById('calculatorResults').innerHTML = '';
    }

    function resetEstimator() {
      document.getElementById('solarEstimateForm').reset();
      document.getElementById('estimateResults').innerHTML = '';
    }

    function resetSimulation() {
      document.getElementById('simulationForm').reset();
      document.getElementById('simulationResults').innerHTML = '';
    }
  </script>

  <!-- Shopping Cart Modal -->
  <div class="modal fade" id="cartModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">
            <i class="bi bi-cart3 me-2"></i>Your Shopping Cart
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-0">
          <div id="cart-items" class="cart-items">
            <!-- Empty cart message -->
            <div id="empty-cart-message" class="text-center py-5">
              <i class="bi bi-cart-x text-muted" style="font-size: 3rem;"></i>
              <p class="mt-3 text-muted">Your cart is empty</p>
            </div>
            <!-- Cart items will be dynamically added here -->
          </div>
          <div class="cart-summary p-4 border-top">
            <div class="d-flex justify-content-between mb-2">
              <span>Subtotal:</span>
              <span id="cart-subtotal" class="fw-bold">₱0.00</span>
            </div>
            <div class="d-flex justify-content-between mb-3">
              <span>Shipping:</span>
              <span class="text-success">Calculated at checkout</span>
            </div>
            <div class="d-flex justify-content-between border-top pt-2">
              <h5 class="mb-0">Total:</h5>
              <h4 class="mb-0 text-primary" id="cart-total">₱0.00</h4>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="bi bi-arrow-left me-1"></i> Continue Shopping
          </button>
          <button type="button" class="btn btn-primary" id="proceedToCheckout">
            Proceed to Checkout <i class="bi bi-arrow-right ms-1"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Cart Confirmation Modal -->
  <div id="cartConfirmationModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Item Added to Cart</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center">
          <i class="bi bi-cart-check-fill text-success" style="font-size: 3rem; margin-bottom: 1rem;"></i>
          <h5 id="addedProductName"></h5>
          <p>Successfully added to your cart!</p>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-outline-secondary" id="revertAddToCart">Undo</button>
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Continue Shopping</button>
          <button type="button" class="btn btn-success" id="viewCartBtn">
            View Cart & Checkout
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Function to remove modal backdrop and enable page scrolling
    function removeModalBackdrop() {
      const backdrops = document.querySelectorAll('.modal-backdrop');
      backdrops.forEach(backdrop => backdrop.remove());
      document.body.classList.remove('modal-open');
      document.body.style.overflow = '';
      document.body.style.paddingRight = '';
    }

    // Function to show cart confirmation popup
    function showCartConfirmation(product) {
      const modalElement = document.getElementById('cartConfirmationModal');
      const modal = new bootstrap.Modal(modalElement);
      
      // Update product name in the modal
      document.getElementById('addedProductName').textContent = product.name;
      
      // Remove any existing event listeners
      const revertBtn = document.getElementById('revertAddToCart');
      const viewCartBtn = document.getElementById('viewCartBtn');
      
      const newRevertBtn = revertBtn.cloneNode(true);
      const newViewCartBtn = viewCartBtn.cloneNode(true);
      
      revertBtn.parentNode.replaceChild(newRevertBtn, revertBtn);
      viewCartBtn.parentNode.replaceChild(newViewCartBtn, viewCartBtn);
      
      // Set up new event listeners
      newRevertBtn.addEventListener('click', function revertHandler() {
        // Remove the item or decrease quantity
        if (product.quantity > 1) {
          product.quantity -= 1;
        } else {
          const itemIndex = cart.findIndex(item => item.id === product.id);
          if (itemIndex > -1) {
            cart.splice(itemIndex, 1);
          }
        }
        
        updateCartCount();
        updateCartTotal();
        updateCartDisplay();
        
        // Close the modal
        const modalInstance = bootstrap.Modal.getInstance(modalElement);
        if (modalInstance) {
          modalInstance.hide();
        }
      });
      
      newViewCartBtn.addEventListener('click', function() {
        // Close the current modal
        modal.hide();
        
        // Show the cart modal after a short delay to allow the current modal to close
        setTimeout(() => {
          const cartModal = new bootstrap.Modal(document.getElementById('cartModal'));
          cartModal.show();
        }, 150);
      });
      
      // Handle modal hidden event to clean up
      modalElement.addEventListener('hidden.bs.modal', function() {
        removeModalBackdrop();
      });
      
      // Show the modal
      modal.show();
    }
  </script>

  <!-- Admin Panel JavaScript -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Admin login functionality
      const adminLoginLink = document.getElementById('adminLoginLink');
      const adminLoginModal = new bootstrap.Modal(document.getElementById('adminLoginModal'));
      const adminPanel = document.getElementById('adminPanel');
      const adminLogoutBtn = document.getElementById('adminLogoutBtn');
      const adminLoginForm = document.getElementById('adminLoginForm');
      
      // Check if user is already logged in
      if (localStorage.getItem('adminLoggedIn') === 'true') {
        adminPanel.style.display = 'block';
      }

      // Handle logout
      adminLogoutBtn.addEventListener('click', function() {
        localStorage.removeItem('adminLoggedIn');
        adminPanel.style.display = 'none';
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });

      // Admin panel button handlers
      document.getElementById('manageProductsBtn').addEventListener('click', function() {
        alert('Product management functionality will be implemented here');
        // Add your product management logic here
      });

      document.getElementById('viewOrdersBtn').addEventListener('click', function() {
        alert('Order management functionality will be implemented here');
        // Add your order management logic here
      });

      document.getElementById('viewAnalyticsBtn').addEventListener('click', function() {
        alert('Analytics functionality will be implemented here');
        // Add your analytics logic here
      });
    });
  </script>

  <script>
    // Function to load featured products
    function loadFeaturedProducts() {
        fetch('get_featured_products.php')
            .then(response => response.json())
            .then(products => {
                const productsContainer = document.getElementById('featured-products');
                productsContainer.innerHTML = '';
                
                if (products.length === 0) {
                    productsContainer.innerHTML = `
                        <div class="col-12 text-center">
                            <p class="text-muted">No featured products available at the moment.</p>
                        </div>`;
                    return;
                }
                
                products.forEach(product => {
                    const productCard = `
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <img src="${product.image_url || 'assets/img/placeholder.jpg'}" class="card-img-top" alt="${product.name}">
                                <div class="card-body">
                                    <h5 class="card-title">${product.name}</h5>
                                    <p class="card-text">${product.description || 'No description available'}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-primary fw-bold">₱${product.price}</span>
                                        <span class="badge bg-secondary">${product.category}</span>
                                    </div>
                                    <div class="mt-3">
                                        <!-- Add to Cart button removed -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    productsContainer.innerHTML += productCard;
                });
            })
            .catch(error => {
                console.error('Error loading featured products:', error);
                const productsContainer = document.getElementById('featured-products');
                productsContainer.innerHTML = `
                    <div class="col-12 text-center">
                        <p class="text-danger">Error loading featured products. Please try again later.</p>
                    </div>`;
            });
    }

    // Load featured products when the page loads
    document.addEventListener('DOMContentLoaded', loadFeaturedProducts);
  </script>

  <script>
    // Function to load category products
    function loadCategoryProducts(category) {
        console.log('Loading products for category:', category);
        
        fetch(`get_category_products.php?category=${category}`)
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(products => {
                console.log('Received products:', products);
                
                const popupId = `${category}Popup`;
                const popup = document.getElementById(popupId);
                
                if (!popup) {
                    console.error(`Popup element with ID ${popupId} not found`);
                    return;
                }
                
                const productsGrid = popup.querySelector('.products-grid');
                if (!productsGrid) {
                    console.error('Products grid element not found');
                    return;
                }
                
                productsGrid.innerHTML = '';
                
                if (!products || products.length === 0) {
                    productsGrid.innerHTML = `
                        <div class="col-12 text-center">
                            <p class="text-muted">No products available in this category.</p>
                        </div>`;
                    return;
                }
                
                products.forEach(product => {
                    const productCard = `
                        <div class="product-card">
                            <img src="${product.image_url || 'assets/img/placeholder.jpg'}" alt="${product.name}">
                            <h3>${product.name}</h3>
                            <p>${product.description || 'No description available'}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-primary fw-bold">₱${product.price}</span>
                                <span class="badge bg-secondary">${product.category}</span>
                            </div>
                        </div>
                    `;
                    productsGrid.innerHTML += productCard;
                });
            })
            .catch(error => {
                console.error('Error loading category products:', error);
                const popupId = `${category}Popup`;
                const popup = document.getElementById(popupId);
                if (popup) {
                    const productsGrid = popup.querySelector('.products-grid');
                    if (productsGrid) {
                        productsGrid.innerHTML = `
                            <div class="col-12 text-center">
                                <p class="text-danger">Error loading products. Please try again later.</p>
                            </div>`;
                    }
                }
            });
    }

    // Add click event listeners to category links
    document.addEventListener('DOMContentLoaded', function() {
        // Solar Panel category
        document.getElementById('solarPanelLink').addEventListener('click', function(e) {
            e.preventDefault();
            loadCategoryProducts('solar_panel');
            const popup = document.getElementById('solar_panelPopup');
            if (popup) {
                popup.style.display = 'flex';
            }
        });

        // Battery category
        document.getElementById('batteryLink').addEventListener('click', function(e) {
            e.preventDefault();
            loadCategoryProducts('battery');
            const popup = document.getElementById('batteryPopup');
            if (popup) {
                popup.style.display = 'flex';
            }
        });

        // Inverter category
        document.getElementById('inverterLink').addEventListener('click', function(e) {
            e.preventDefault();
            loadCategoryProducts('inverter');
            const popup = document.getElementById('inverterPopup');
            if (popup) {
                popup.style.display = 'flex';
            }
        });

        // Accessories category
        document.getElementById('accessoriesLink').addEventListener('click', function(e) {
            e.preventDefault();
            loadCategoryProducts('accessories');
            const popup = document.getElementById('accessoriesPopup');
            if (popup) {
                popup.style.display = 'flex';
            }
        });

        // Close popup when clicking the close button
        document.querySelectorAll('.close-popup').forEach(button => {
            button.addEventListener('click', function() {
                const popup = this.closest('.product-popup');
                if (popup) {
                    popup.style.display = 'none';
                }
            });
        });

        // Close popup when clicking outside
        document.querySelectorAll('.product-popup').forEach(popup => {
            popup.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.style.display = 'none';
                }
            });
      });
    });
  </script>

  <script>
    // Add this to your existing JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        const reviewForm = document.getElementById('reviewForm');
        const stars = document.querySelectorAll('.star');
        const ratingValue = document.querySelector('.rating-value');
        const ratingInput = document.getElementById('ratingInput');
        let selectedRating = 0;

        // Star rating functionality
        stars.forEach(star => {
            star.addEventListener('mouseover', function() {
                const rating = this.getAttribute('data-rating');
                updateStars(rating);
            });

            star.addEventListener('mouseout', function() {
                updateStars(selectedRating);
            });

            star.addEventListener('click', function() {
                selectedRating = this.getAttribute('data-rating');
                ratingInput.value = selectedRating;
                updateStars(selectedRating);
            });
        });

        function updateStars(rating) {
            stars.forEach(star => {
                const starRating = star.getAttribute('data-rating');
                if (starRating <= rating) {
                    star.classList.add('selected');
                } else {
                    star.classList.remove('selected');
                }
            });
            ratingValue.textContent = rating;
        }

        // Form submission
        reviewForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Validate rating
            if (!ratingInput.value) {
                // Show error modal for missing rating
                const errorModal = new bootstrap.Modal(document.getElementById('reviewErrorModal'));
                document.getElementById('reviewErrorMsg').textContent = 'Please select a rating.';
                errorModal.show();
                return;
            }

            const formData = new FormData(this);

            fetch('forms/review.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Reset form
                    reviewForm.reset();
                    selectedRating = 0;
                    updateStars(0);
                    ratingInput.value = '';
                    
                    // Show success modal
                    const successModal = new bootstrap.Modal(document.getElementById('reviewSuccessModal'));
                    successModal.show();
                } else {
                    // Show error modal with message
                    const errorModal = new bootstrap.Modal(document.getElementById('reviewErrorModal'));
                    document.getElementById('reviewErrorMsg').textContent = data.message || 'An error occurred. Please try again.';
                    errorModal.show();
                }
            })
            .catch(error => {
                // Show error modal for network/server errors
                const errorModal = new bootstrap.Modal(document.getElementById('reviewErrorModal'));
                document.getElementById('reviewErrorMsg').textContent = 'An error occurred. Please try again.';
                errorModal.show();
            });
        });
    });
  </script>

  <!-- Review Success Modal -->
  <div class="modal fade" id="reviewSuccessModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title">Success</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center">
          <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem; margin-bottom: 1rem;"></i>
          <h5>Review Submitted</h5>
          <p>Thank you for your review! It will be visible after approval.</p>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Review Error Modal -->
  <div class="modal fade" id="reviewErrorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title">Error</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center">
          <i class="bi bi-x-circle-fill text-danger" style="font-size: 3rem; margin-bottom: 1rem;"></i>
          <h5>Submission Failed</h5>
          <p id="reviewErrorMsg">An error occurred. Please try again.</p>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Handle navigation links
    document.addEventListener('DOMContentLoaded', function() {
      const navLinks = document.querySelectorAll('#navmenu a');
      const header = document.querySelector('.header');
      const headerHeight = header ? header.offsetHeight : 0;

      // Function to handle smooth scrolling
      function scrollToSection(e) {
        e.preventDefault();
        const targetId = this.getAttribute('href').substring(1);
        const targetSection = document.getElementById(targetId);
        
        if (targetSection) {
          const targetPosition = targetSection.offsetTop - headerHeight;
          window.scrollTo({
            top: targetPosition,
            behavior: 'smooth'
          });
        }
      }

      // Function to update active link based on scroll position
      function updateActiveLink() {
        const scrollPosition = window.scrollY + headerHeight + 50; // Reduced offset for more precise detection
        let currentSection = null;
        let minDistance = Infinity;

        // Find the section closest to the current scroll position
        document.querySelectorAll('section[id]').forEach(section => {
          const sectionTop = section.offsetTop;
          const sectionBottom = sectionTop + section.offsetHeight;
          const distance = Math.abs(scrollPosition - sectionTop);

          // Check if we're within the section's boundaries
          if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {
            if (distance < minDistance) {
              minDistance = distance;
              currentSection = section.id;
            }
          }
        });

        // Update active state for navigation links
        navLinks.forEach(link => {
          const targetId = link.getAttribute('href').substring(1);
          if (targetId === currentSection) {
            link.classList.add('active');
          } else {
            link.classList.remove('active');
          }
        });

        // Debug output
        console.log('Current Section:', currentSection);
        console.log('Scroll Position:', scrollPosition);
      }

      // Add click event listeners to all navigation links
      navLinks.forEach(link => {
        link.addEventListener('click', scrollToSection);
      });

      // Add scroll event listener to update active link
      window.addEventListener('scroll', updateActiveLink);
      
      // Initial call to set active link on page load
      updateActiveLink();
    });

    // Shopping Cart Modal
    const cartModal = document.getElementById('cartModal');
    const cartCount = document.querySelector('.cart-count');
    let cart = [];

    // Function to update cart count
    function updateCartCount() {
      cartCount.textContent = cart.length;
    }

    // Function to update cart total
    function updateCartTotal() {
      const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
      document.getElementById('cart-total').textContent = `₱${total.toFixed(2)}`;
    }

    // Function to add item to cart
    function addToCart(productId, productName, productPrice) {
      const existingItem = cart.find(item => item.id === productId);
      let addedItem;
      
      if (existingItem) {
        existingItem.quantity += 1;
        addedItem = existingItem;
      } else {
        addedItem = {
          id: productId,
          name: productName,
          price: parseFloat(productPrice),
          quantity: 1
        };
        cart.push(addedItem);
      }

      updateCartCount();
      updateCartTotal();
      updateCartDisplay();
      
      // Show the confirmation modal
      showCartConfirmation(addedItem);
    }
    
    // Function to show cart confirmation popup
    function showCartConfirmation(product) {
      const modalElement = document.getElementById('cartConfirmationModal');
      const modal = new bootstrap.Modal(modalElement);
      
      // Update product name in the modal
      document.getElementById('addedProductName').textContent = product.name;
      
      // Remove any existing event listeners
      const revertBtn = document.getElementById('revertAddToCart');
      const viewCartBtn = document.getElementById('viewCartBtn');
      
      const newRevertBtn = revertBtn.cloneNode(true);
      const newViewCartBtn = viewCartBtn.cloneNode(true);
      
      revertBtn.parentNode.replaceChild(newRevertBtn, revertBtn);
      viewCartBtn.parentNode.replaceChild(newViewCartBtn, viewCartBtn);
      
      // Set up new event listeners
      newRevertBtn.addEventListener('click', function() {
        // Remove the item or decrease quantity
        if (product.quantity > 1) {
          product.quantity -= 1;
        } else {
          const itemIndex = cart.findIndex(item => item.id === product.id);
          if (itemIndex > -1) {
            cart.splice(itemIndex, 1);
          }
        }
        
        updateCartCount();
        updateCartTotal();
        updateCartDisplay();
        
        // Close the modal
        const modalInstance = bootstrap.Modal.getInstance(modalElement);
        if (modalInstance) {
          modalInstance.hide();
        }
      });
      
      newViewCartBtn.addEventListener('click', function() {
        // Close the current modal
        modal.hide();
        
        // Show the cart modal after a short delay to allow the current modal to close
        setTimeout(() => {
          const cartModal = new bootstrap.Modal(document.getElementById('cartModal'));
          cartModal.show();
        }, 150);
      });
      
      // Show the modal
      modal.show();
    }

    // Function to remove item from cart
    function removeFromCart(productId) {
      const itemIndex = cart.findIndex(item => item.id === productId);
      if (itemIndex > -1) {
        cart.splice(itemIndex, 1);
        updateCartCount();
        updateCartTotal();
        updateCartDisplay();
      }
    }

    // Function to update cart display
    function updateCartDisplay() {
      const cartItems = document.getElementById('cart-items');
      const emptyCartMessage = document.getElementById('empty-cart-message');
      
      if (cart.length === 0) {
        cartItems.innerHTML = `
          <div id="empty-cart-message" class="text-center py-5">
            <i class="bi bi-cart-x text-muted" style="font-size: 3rem;"></i>
            <p class="mt-3 text-muted">Your cart is empty</p>
          </div>`;
        return;
      }
      
      let itemsHTML = '';
      cart.forEach(item => {
        itemsHTML += `
          <div class="cart-item" data-id="${item.id}">
            <img src="${item.image || 'assets/img/placeholder.jpg'}" alt="${item.name}" class="cart-item-img">
            <div class="cart-item-details">
              <div class="cart-item-title">${item.name}</div>
              <div class="cart-item-price">₱${item.price.toFixed(2)}</div>
              <div class="quantity-controls">
                <div class="quantity-btn decrease-quantity">-</div>
                <input type="number" class="quantity-input" value="${item.quantity}" min="1">
                <div class="quantity-btn increase-quantity">+</div>
              </div>
            </div>
            <div class="ms-auto text-end">
              <div class="cart-item-price">₱${(item.price * item.quantity).toFixed(2)}</div>
              <div class="remove-item" data-id="${item.id}" title="Remove item">
                <i class="bi bi-trash"></i>
              </div>
            </div>
          </div>`;
      });
      
      cartItems.innerHTML = itemsHTML;
      
      // Add event listeners for quantity controls
      document.querySelectorAll('.decrease-quantity').forEach(btn => {
        btn.addEventListener('click', function() {
          const productId = this.closest('.cart-item').dataset.id;
          const item = cart.find(item => item.id === productId);
          if (item && item.quantity > 1) {
            item.quantity--;
            updateCartDisplay();
            updateCartCount();
            updateCartTotal();
          }
        });
      });
      
      document.querySelectorAll('.increase-quantity').forEach(btn => {
        btn.addEventListener('click', function() {
          const productId = this.closest('.cart-item').dataset.id;
          const item = cart.find(item => item.id === productId);
          if (item) {
            item.quantity++;
            updateCartDisplay();
            updateCartCount();
            updateCartTotal();
          }
        });
      });
      
      // Add event listeners for remove buttons
      document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', function(e) {
          e.stopPropagation();
          const productId = this.getAttribute('data-id');
          removeFromCart(productId);
        });
      });
      
      // Update quantity when input changes
      document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
          const productId = this.closest('.cart-item').dataset.id;
          const item = cart.find(item => item.id === productId);
          if (item) {
            const newQuantity = parseInt(this.value) || 1;
            item.quantity = Math.max(1, newQuantity);
            updateCartDisplay();
            updateCartCount();
            updateCartTotal();
          }
        });
      });
    }

    // Initialize event listeners
    document.addEventListener('DOMContentLoaded', function() {
      const addToCartButtons = document.querySelectorAll('.add-to-cart');
      addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
          const productId = this.dataset.productId;
          const productName = this.dataset.productName;
          const productPrice = this.dataset.productPrice;
          addToCart(productId, productName, productPrice);
        });
      });

      // Close popup functionality
      const closeButtons = document.querySelectorAll('.close-popup');
      const popups = document.querySelectorAll('.product-popup');

      closeButtons.forEach(button => {
        button.addEventListener('click', function() {
          const popup = this.closest('.product-popup');
          popup.style.display = 'none';
        });
      });

      // Click outside to close
      popups.forEach(popup => {
        popup.addEventListener('click', function(e) {
          if (e.target === this) {
            this.style.display = 'none';
          }
        });
      });

      // Open popup functionality
      const productLinks = {
        'solarPanelLink': 'solar_panelPopup',
        'batteryLink': 'batteryPopup',
        'inverterLink': 'inverterPopup',
        'accessoriesLink': 'accessoriesPopup'
      };

      Object.entries(productLinks).forEach(([linkId, popupId]) => {
        const link = document.getElementById(linkId);
        const popup = document.getElementById(popupId);
        
        if (link && popup) {
          link.addEventListener('click', function(e) {
            e.preventDefault();
            popup.style.display = 'flex';
          });
        }
      });
    });

    // Solar Calculator Functions
    function calculateSolarSystem() {
      const monthlyBill = parseFloat(document.getElementById('calculatorMonthlyBill').value);
      const usageType = document.getElementById('calculatorUsageType').value;
      const panelSize = parseInt(document.getElementById('calculatorPanelSize').value);
      const panelCount = parseInt(document.getElementById('calculatorPanelCount').value);
      const backupDays = parseInt(document.getElementById('calculatorBackupDays').value);
      const backupHours = parseInt(document.getElementById('calculatorBackupHours').value);
      const location = document.getElementById('calculatorLocation').value;
      const systemType = document.getElementById('calculatorSystemType').value;

      // Validate inputs
      if (isNaN(monthlyBill) || !usageType || isNaN(panelSize) || isNaN(panelCount) || 
          isNaN(backupDays) || isNaN(backupHours) || !location || !systemType) {
        alert('Please fill in all required fields');
        return;
      }

      // Constants
      const panelArea = 1.6; // Standard panel area in sqm
      const daysInMonth = new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0).getDate();
      
      // Location-based sun hours
      const sunHours = {
        'naga': 3.5,  // Naga City
        'legazpi': 3.5,  // Legazpi City
        'iriga': 4,  // Iriga City
        'tabaco': 4,   // Tabaco City
        'sorsogon': 4.5,   // Sorsogon City
        'masbate': 4.5   // Masbate City
      };

      // Usage type parameters
      const usageParams = {
        'household': {
          efficiency: 0.80,
          defaultBackupDays: 3,
          defaultBackupHours: 8,
          costMultiplier: 1.0
        },
        'commercial': {
          efficiency: 0.75,
          defaultBackupDays: 2,
          defaultBackupHours: 12,
          costMultiplier: 1.1
        },
        'industrial': {
          efficiency: 0.70,
          defaultBackupDays: 1,
          defaultBackupHours: 24,
          costMultiplier: 1.2
        }
      };

      // Get usage type parameters
      const params = usageParams[usageType];
      const systemEfficiency = params.efficiency;
      const finalBackupDays = backupDays || params.defaultBackupDays;
      const finalBackupHours = backupHours || params.defaultBackupHours;

      // Calculate daily energy consumption
      const dailyKWh = monthlyBill / daysInMonth;
      const dailyWh = dailyKWh * 1000;

      // Calculate system size based on user input
      const systemSize = panelCount * panelSize;
      const totalArea = panelCount * panelArea;

      // Calculate battery requirements
      const dailyBackupEnergy = dailyKWh * finalBackupHours / 24;
      const totalBackupEnergy = dailyBackupEnergy * finalBackupDays;
      const batteryCapacity = Math.ceil(totalBackupEnergy * 1000 * 1.2); // Add 20% buffer

      // Calculate system cost with usage type multiplier
      const panelCost = 25000; // Cost per panel in ₱
      const inverterCost = systemType === 'off-grid' ? 80000 : (systemType === 'hybrid' ? 65000 : 50000);
      const installationCost = 30000 + (panelCount * 1000); // Base cost + per panel cost
      const batteryCost = systemType === 'off-grid' ? 50000 : (systemType === 'hybrid' ? 35000 : 0);
      
      const totalCost = (panelCount * panelCost * params.costMultiplier) + 
                       (inverterCost * params.costMultiplier) + 
                       (installationCost * params.costMultiplier) + 
                       (batteryCost * params.costMultiplier);

      // Calculate monthly savings based on system type
      let monthlySavings;
      if (systemType === 'grid-tied') {
        monthlySavings = dailyKWh * daysInMonth * 0.5; // 50% reduction
      } else if (systemType === 'off-grid') {
        monthlySavings = dailyKWh * daysInMonth * 0.9; // 90% reduction
      } else { // hybrid
        monthlySavings = dailyKWh * daysInMonth * 0.7; // 70% reduction
      }
      const paybackPeriod = Math.ceil(totalCost / (monthlySavings * 12));

      // Calculate energy production
      const energyProduction = Math.round(systemSize * sunHours[location] * daysInMonth * 
                                         systemEfficiency);

      // Display results
      const results = document.getElementById('calculatorResults');
      results.innerHTML = `
        <div class="alert alert-success">
          <h5>System Requirements:</h5>
          <p><strong>Usage Type:</strong> ${usageType.charAt(0).toUpperCase() + usageType.slice(1)}</p>
          <p><strong>System Size:</strong> ${systemSize}W (${panelCount} x ${panelSize}W panels)</p>
          <p><strong>Total Area Required:</strong> ${totalArea.toFixed(1)} sqm</p>
          <p><strong>Battery Capacity:</strong> ${Math.round(batteryCapacity)}Wh</p>
          <p><strong>Estimated Cost:</strong> ₱${totalCost.toLocaleString()}</p>
          <p><strong>Monthly Savings:</strong> ₱${monthlySavings.toFixed(0)}</p>
          <p><strong>Payback Period:</strong> ${Math.min(paybackPeriod, 10)} years</p>
          <p><strong>System Efficiency:</strong> ${Math.round(systemEfficiency * 100)}%</p>
          <p><strong>Estimated Monthly Production:</strong> ${energyProduction} kWh</p>
          <p><strong>Days in Month:</strong> ${daysInMonth} days</p>
        </div>
      `;
    }

    // Solar Estimation Functions
    function estimateSolarSystem() {
      const monthlyBill = parseFloat(document.getElementById('estimateMonthlyBill').value);
      const roofArea = parseFloat(document.getElementById('estimateRoofArea').value);
      const usageHours = parseFloat(document.getElementById('estimateUsageHours').value);
      const location = document.getElementById('estimateLocation').value;
      const systemType = document.getElementById('estimateSystemType').value;

      // Validate inputs
      if (isNaN(monthlyBill) || isNaN(roofArea) || isNaN(usageHours) || !location || !systemType) {
        alert('Please fill in all required fields');
        return;
      }

      // Constants
      const panelSize = 300; // Standard panel size in watts
      const panelArea = 1.6; // Standard panel area in sqm
      const daysInMonth = new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0).getDate();
      
      // Location-based sun hours
      const sunHours = {
        'naga': 4.5,  // Naga City
        'legazpi': 4.5,  // Legazpi City
        'iriga': 5.0,  // Iriga City
        'tabaco': 4.5,   // Tabaco City
        'sorsogon': 5.0,   // Sorsogon City
        'masbate': 5.5   // Masbate City
      };

      // Calculate maximum possible panels based on roof area
      const maxPanels = Math.floor(roofArea / panelArea);

      // Calculate energy requirements
      const dailyKWh = monthlyBill / daysInMonth;
      const requiredWatts = Math.ceil(dailyKWh * 1000 / (sunHours[location] * 0.75));
      
      // Calculate recommended system size
      const recommendedPanels = Math.min(Math.ceil(requiredWatts / panelSize), maxPanels);
      const systemSize = recommendedPanels * panelSize;

      // Calculate battery requirements for off-grid systems
      let batteryCapacity = 0;
      if (systemType === 'off-grid') {
        const dailyBackupEnergy = dailyKWh * (usageHours / 24);
        batteryCapacity = Math.ceil(dailyBackupEnergy * 1000 * 1.2); // 20% buffer
      } else if (systemType === 'hybrid') {
        const dailyBackupEnergy = dailyKWh * (usageHours / 24) * 0.5; // Half the capacity for hybrid
        batteryCapacity = Math.ceil(dailyBackupEnergy * 1000 * 1.2); // 20% buffer
      }

      // Calculate estimated energy production in kWh
      const estimatedProduction = (systemSize * sunHours[location] * daysInMonth * 0.75) / 1000;

      // Calculate system cost (updated with more accurate pricing)
      const panelCost = 15000; // Cost per 300W panel in ₱
      const inverterCost = systemType === 'off-grid' ? 80000 : (systemType === 'hybrid' ? 65000 : 50000);
      const installationCost = 20000 + (recommendedPanels * 1000); // Base + per panel
      const batteryCost = batteryCapacity > 0 ? Math.ceil(batteryCapacity * 15) : 0; // ~₱15/Wh
      
      const totalCost = (recommendedPanels * panelCost) + inverterCost + installationCost + batteryCost;

      // Calculate monthly savings based on system type
      let monthlySavings;
      if (systemType === 'grid-tied') {
        monthlySavings = monthlyBill * 0.75; // 75% reduction
      } else if (systemType === 'off-grid') {
        monthlySavings = monthlyBill * 0.9; // 90% reduction
      } else { // hybrid
        monthlySavings = monthlyBill * 0.85; // 85% reduction
      }
      const yearlySavings = monthlySavings * 12;

      // Calculate payback period in years
      const paybackPeriod = totalCost / yearlySavings;

      // Calculate system coverage (percentage of roof used)
      const systemCoverage = ((recommendedPanels * panelArea) / roofArea) * 100;

      // Display results
      const results = document.getElementById('estimateResults');
      results.innerHTML = `
        <div class="alert alert-success">
          <h5>Solar System Estimation Results:</h5>
          <div class="row">
            <div class="col-md-6">
              <p><strong>System Size:</strong> ${systemSize}W (${recommendedPanels} x ${panelSize}W panels)</p>
              <p><strong>Battery Capacity:</strong> ${batteryCapacity > 0 ? Math.round(batteryCapacity/1000) + 'kWh' : 'Not Required'}</p>
              <p><strong>Roof Coverage:</strong> ${(recommendedPanels * panelArea).toFixed(1)} sqm (${systemCoverage.toFixed(1)}% of available)</p>
              <p><strong>Estimated Production:</strong> ${estimatedProduction.toFixed(0)} kWh/month</p>
            </div>
            <div class="col-md-6">
              <p><strong>Estimated Cost:</strong> ₱${totalCost.toLocaleString()}</p>
              <p><strong>Monthly Savings:</strong> ₱${monthlySavings.toFixed(0)}</p>
              <p><strong>Yearly Savings:</strong> ₱${yearlySavings.toFixed(0)}</p>
              <p><strong>Payback Period:</strong> ${paybackPeriod.toFixed(1)} years</p>
            </div>
          </div>
          <div class="mt-3">
            <p class="mb-1"><small class="text-muted">Note: These are estimates. Actual results may vary based on installation conditions and actual usage patterns.</small></p>
          </div>
        </div>
      `;
    }

    // Solar Simulation Functions
    function simulateSolarPower() {
      const panelCount = parseInt(document.getElementById('panelCount').value);
      const panelWattage = parseInt(document.getElementById('panelWattage').value);
      const location = document.getElementById('location').value;
      const systemEfficiency = parseFloat(document.getElementById('systemEfficiency').value) / 100;
      const monthlyBill = parseFloat(document.getElementById('monthlyBill').value);
      const usagePattern = document.getElementById('usagePattern').value;

      // Validate inputs
      if (isNaN(panelCount) || isNaN(panelWattage) || !location || isNaN(systemEfficiency) || 
          isNaN(monthlyBill) || !usagePattern) {
        alert('Please fill in all required fields');
        return;
      }

      // Constants
      const panelArea = 1.6; // Standard panel area in sqm
      const daysInMonth = new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0).getDate();
      
      // Location-based sun hours
      const sunHours = {
        'naga': 3.5,  // Naga City
        'legazpi': 3.5,  // Legazpi City
        'iriga': 4,  // Iriga City
        'tabaco': 4,   // Tabaco City
        'sorsogon': 4.5,   // Sorsogon City
        'masbate': 4.5   // Masbate City
      };

      // Calculate system specifications
      const systemSize = panelCount * panelWattage;
      const totalArea = panelCount * panelArea;

      // Calculate monthly energy production with seasonal variation
      const monthlyProduction = [];
      const monthlySunHours = [3.5, 3.5, 4, 4.5, 5, 5.5, 5.5, 5, 4.5, 4, 3.5, 3.5]; // Based on Philippine solar data

      for (let month = 0; month < 12; month++) {
        const monthlyEnergy = Math.round((systemSize * monthlySunHours[month] * daysInMonth * 
                            systemEfficiency * 0.75) / 1000); // Include 25% system losses
        monthlyProduction.push(monthlyEnergy);
      }

      // Calculate yearly production
      const yearlyEnergy = monthlyProduction.reduce((sum, value) => sum + value, 0);

      // Calculate savings based on usage pattern and system type
      let monthlySavings = 0;
      let yearlySavings = 0;
      const dailyKWh = monthlyBill / daysInMonth;

      const systemType = document.getElementById('systemType').value;
      let baseSavings;
      
      switch(systemType) {
        case 'grid-tied':
          baseSavings = 0.5; // 50% base reduction
          break;
        case 'off-grid':
          baseSavings = 0.9; // 90% base reduction
          break;
        case 'hybrid':
          baseSavings = 0.7; // 70% base reduction
          break;
        default:
          baseSavings = 0.5;
      }

      switch(usagePattern) {
        case 'even':
          monthlySavings = dailyKWh * daysInMonth * baseSavings;
          break;
        case 'day-heavy':
          monthlySavings = dailyKWh * daysInMonth * (baseSavings + 0.1); // Add 10% for day-heavy usage
          break;
        case 'night-heavy':
          monthlySavings = dailyKWh * daysInMonth * (baseSavings - 0.1); // Subtract 10% for night-heavy usage
          break;
      }
      yearlySavings = monthlySavings * 12;

      // Calculate energy production
      const energyProduction = Math.round(systemSize * sunHours[location] * daysInMonth * 
                                         systemEfficiency);

      // Display results
      const results = document.getElementById('simulationResults');
      results.innerHTML = `
        <div class="alert alert-success">
          <h5>Simulation Results:</h5>
          <p><strong>System Size:</strong> ${systemSize}W</p>
          <p><strong>Total Area:</strong> ${totalArea.toFixed(1)} sqm</p>
          <p><strong>Monthly Production:</strong> ${monthlyProduction[0]} kWh</p>
          <p><strong>Yearly Production:</strong> ${yearlyEnergy} kWh</p>
          <p><strong>Monthly Savings:</strong> ₱${monthlySavings.toFixed(0)}</p>
          <p><strong>Yearly Savings:</strong> ₱${yearlySavings.toFixed(0)}</p>
          <p><strong>System Efficiency:</strong> ${Math.round(systemEfficiency * 100)}%</p>
          <p><strong>Days in Month:</strong> ${daysInMonth} days</p>
        </div>
      `;
    }

    // Add event listeners
    document.getElementById('solarCalculatorForm').addEventListener('submit', function(e) {
      e.preventDefault();
      calculateSolarSystem();
    });

    document.getElementById('solarEstimateForm').addEventListener('submit', function(e) {
      e.preventDefault();
      estimateSolarSystem();
    });

    document.getElementById('simulationForm').addEventListener('submit', function(e) {
      e.preventDefault();
      simulateSolarPower();
    });

    // Reset functions
    function resetCalculator() {
      document.getElementById('solarCalculatorForm').reset();
      document.getElementById('calculatorResults').innerHTML = '';
    }

    function resetEstimator() {
      document.getElementById('solarEstimateForm').reset();
      document.getElementById('estimateResults').innerHTML = '';
    }

    function resetSimulation() {
      document.getElementById('simulationForm').reset();
      document.getElementById('simulationResults').innerHTML = '';
    }
  </script>

</body>

</html>