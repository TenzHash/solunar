<?php
session_start();
require_once 'config/database.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            if ($row['role'] === 'user') {
                $_SESSION['user_id'] = $row['id'];
                header('Location: home.php');
                exit;
            } else {
                $error = 'Please use the admin login page';
            }
        } else {
            $error = 'Invalid password';
        }
    } else {
        $error = 'Email not found';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login - Solunar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%), url('assets/img/learn-bg.jpg') center center/cover no-repeat;
            position: relative;
        }
        .bg-overlay {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(0, 0, 0, 0.18);
            z-index: 0;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 2.5rem 2rem 2rem 2rem;
            background: rgba(255,255,255,0.85);
            border-radius: 22px;
            box-shadow: 0 8px 32px 0 rgba(31,38,135,0.18);
            backdrop-filter: blur(8px);
            position: relative;
            z-index: 2;     
            animation: fadeInUp 0.8s cubic-bezier(.39,.575,.56,1.000);
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .login-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-logo img {
            width: 110px;
            height: 110px;
            object-fit: cover;
            border-radius: 50%;
            box-shadow: 0 4px 24px rgba(0,123,255,0.12);
            background: #fff;
            padding: 10px;
        }
        .form-control, .form-control:focus {
            border-radius: 2rem;
            box-shadow: none;
            border: 1.5px solid #e0e7ff;
            background: #f8fafc;
            font-size: 1.08rem;
            transition: border-color 0.2s;
        }
        .input-group-text {
            border-radius: 2rem 0 0 2rem;
            background: #f0f4ff;
            border: 1.5px solid #e0e7ff;
        }
        .btn-primary {
            background: linear-gradient(90deg, #007bff 60%, #0d6efd 100%);
            border: none;
            border-radius: 2rem;
            font-weight: 600;
            letter-spacing: 1px;
            box-shadow: 0 2px 8px rgba(0,123,255,0.08);
            transition: background 0.2s, transform 0.2s;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #0d6efd 60%, #007bff 100%);
            transform: translateY(-2px) scale(1.03);
        }
        .show-password {
            cursor: pointer;
            color: #6c757d;
            font-size: 1.2rem;
        }
        .login-footer {
            text-align: center;
            color: #adb5bd;
            font-size: 0.98rem;
            margin-top: 2.5rem;
            letter-spacing: 0.5px;
        }
        @media (max-width: 600px) {
            .login-container { margin: 40px 8px; padding: 1.5rem 0.7rem; }
            .login-logo img { width: 80px; height: 80px; }
        }
    </style>
</head>
<body>
    <div class="bg-overlay"></div>
    <div class="container">
        <div class="login-container shadow-lg">
            <div class="login-logo">
                <img src="assets/images/assets/logo.png" alt="Solunar Logo">
            </div>
            <h4 class="text-center mb-4 fw-bold" style="color:#007bff;">Login</h4>
            <form method="POST" action="" autocomplete="off">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email" required autofocus>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <span class="input-group-text show-password" id="togglePassword"><i class="bi bi-eye"></i></span>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2 mt-2">Login</button>
            </form>
            <div class="mt-3 text-center">
                <p class="mb-2">Don't have an account? <a href="register.php" class="text-primary">Register here</a></p>
                <a href="home.php" class="btn btn-outline-secondary w-100 py-2 mb-2">
                    <i class="bi bi-house-door me-1"></i> Back to Homepage
                </a>
                <a href="admin/login.php" class="btn btn-outline-primary w-100 py-2">
                    <i class="bi bi-shield-lock me-1"></i> Admin Login
                </a>
            </div>
        </div>
        <div class="login-footer">
            &copy; <?php echo date('Y'); ?> SOLUNAR. All Rights Reserved.
        </div>
    </div>

    <!-- Error Modal -->
    <div class="modal fade" id="loginErrorModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title">Login Failed</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-center">
            <i class="bi bi-x-circle-fill text-danger" style="font-size: 3rem; margin-bottom: 1rem;"></i>
            <h5><?php echo $error; ?></h5>
            <p>Please try again.</p>
          </div>
          <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">OK</button>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Show/hide password toggle
    document.getElementById('togglePassword').addEventListener('click', function() {
        const pwd = document.getElementById('password');
        const icon = this.querySelector('i');
        if (pwd.type === 'password') {
            pwd.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            pwd.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    });
    // Show error modal if login failed
    <?php if ($error): ?>
      var errorModal = new bootstrap.Modal(document.getElementById('loginErrorModal'));
      errorModal.show();
    <?php endif; ?>
    </script>
</body>
</html> 