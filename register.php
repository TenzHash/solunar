<?php
session_start();
require_once 'config/database.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $city = $_POST['city'] ?? '';
    $postal_code = $_POST['postal_code'] ?? '';

    // Validate passwords match
    if ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $error = 'Email already registered';
        } else {
            // Check if username already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                $error = 'Username already taken';
            } else {
                // Start transaction
                $conn->begin_transaction();

                try {
                    // Create user
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
                    $stmt->bind_param("sss", $username, $email, $hashed_password);
                    $stmt->execute();
                    $user_id = $conn->insert_id;

                    // Create customer profile
                    $stmt = $conn->prepare("INSERT INTO customers (user_id, first_name, last_name, phone, address, city, postal_code) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("issssss", $user_id, $first_name, $last_name, $phone, $address, $city, $postal_code);
                    $stmt->execute();

                    $conn->commit();
                    $success = 'Registration successful! Please login.';
                } catch (Exception $e) {
                    $conn->rollback();
                    $error = 'Registration failed. Please try again.';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Solunar</title>
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
        .register-container {
            max-width: 600px;
            margin: 50px auto;
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
        .register-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .register-logo img {
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
        .register-footer {
            text-align: center;
            color: #adb5bd;
            font-size: 0.98rem;
            margin-top: 2.5rem;
            letter-spacing: 0.5px;
        }
        @media (max-width: 600px) {
            .register-container { margin: 40px 8px; padding: 1.5rem 0.7rem; }
            .register-logo img { width: 80px; height: 80px; }
        }
    </style>
</head>
<body>
    <div class="bg-overlay"></div>
    <div class="container">
        <div class="register-container shadow-lg">
            <div class="register-logo">
                <img src="assets/images/assets/logo.png" alt="Solunar Logo">
            </div>
            <h4 class="text-center mb-4 fw-bold" style="color:#007bff;">Create Account</h4>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php echo $success; ?>
                    <div class="mt-2">
                        <a href="customer-login.php" class="btn btn-primary">Go to Login</a>
                    </div>
                </div>
            <?php else: ?>
                <form method="POST" action="" autocomplete="off">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">First Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" name="first_name" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Last Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" name="last_name" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-phone"></i></span>
                            <input type="tel" class="form-control" name="phone" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                            <textarea class="form-control" name="address" required></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">City</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-building"></i></span>
                                <input type="text" class="form-control" name="city" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Postal Code</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-upc"></i></span>
                                <input type="text" class="form-control" name="postal_code" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control" name="password" required>
                            <span class="input-group-text show-password" onclick="togglePassword(this)"><i class="bi bi-eye"></i></span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control" name="confirm_password" required>
                            <span class="input-group-text show-password" onclick="togglePassword(this)"><i class="bi bi-eye"></i></span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2 mt-2">Register</button>
                </form>
            <?php endif; ?>

            <div class="mt-3 text-center">
                <p class="mb-2">Already have an account? <a href="customer-login.php" class="text-primary">Login here</a></p>
                <a href="home.php" class="btn btn-outline-secondary w-100 py-2">
                    <i class="bi bi-house-door me-1"></i> Back to Homepage
                </a>
            </div>
        </div>
        <div class="register-footer">
            &copy; <?php echo date('Y'); ?> SOLUNAR. All Rights Reserved.
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function togglePassword(element) {
        const input = element.previousElementSibling;
        const icon = element.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
    </script>
</body>
</html> 