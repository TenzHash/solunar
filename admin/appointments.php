<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Debug database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['appointment_id'])) {
    $appointment_id = $_POST['appointment_id'];
    $action = $_POST['action'];
    
    if ($action === 'approve' || $action === 'reject') {
        $status = $action === 'approve' ? 'approved' : 'rejected';
        $stmt = $conn->prepare("UPDATE appointments SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $appointment_id);
        $stmt->execute();
    } elseif ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM appointments WHERE id = ?");
        $stmt->bind_param("i", $appointment_id);
        $stmt->execute();
    }
}

// Check if appointments table exists
$table_check = $conn->query("SHOW TABLES LIKE 'appointments'");
if ($table_check->num_rows == 0) {
    // Create appointments table if it doesn't exist
    $create_table = "CREATE TABLE IF NOT EXISTS appointments (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        appointment_date DATETIME NOT NULL,
        service_type VARCHAR(50) NOT NULL,
        location VARCHAR(50) NOT NULL,
        message TEXT,
        status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $conn->query($create_table);
}

// Fetch appointments with error handling
$query = "SELECT * FROM appointments ORDER BY created_at DESC";
$result = $conn->query($query);

if (!$result) {
    die("Error fetching appointments: " . $conn->error);
}

// Debug information
$debug_info = "";
if (isset($_GET['debug'])) {
    $debug_info = "Total appointments: " . $result->num_rows;
    $debug_info .= "<br>Database: " . $conn->database;
    $debug_info .= "<br>Table exists: " . ($table_check->num_rows > 0 ? 'Yes' : 'No');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Management - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
        .appointment-card {
            transition: all 0.3s ease;
        }
        .appointment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .status-badge {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .pending { background-color: #ffc107; }
        .approved { background-color: #28a745; }
        .rejected { background-color: #dc3545; }
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
                    <h2>Appointment Management</h2>
                    <div>
                        <?php if ($debug_info): ?>
                            <div class="alert alert-info">
                                <?php echo $debug_info; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row">
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card appointment-card">
                                    <div class="card-body">
                                        <span class="badge status-badge <?php echo $row['status']; ?>">
                                            <?php echo ucfirst($row['status']); ?>
                                        </span>
                                        
                                        <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                                        <p class="card-text">
                                            <strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?><br>
                                            <strong>Phone:</strong> <?php echo htmlspecialchars($row['phone']); ?><br>
                                            <strong>Date:</strong> <?php echo date('F j, Y g:i A', strtotime($row['appointment_date'])); ?><br>
                                            <strong>Service:</strong> <?php echo htmlspecialchars($row['service_type']); ?><br>
                                            <strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?><br>
                                            <?php if ($row['message']): ?>
                                                <strong>Message:</strong><br>
                                                <?php echo nl2br(htmlspecialchars($row['message'])); ?>
                                            <?php endif; ?>
                                        </p>

                                        <?php if ($row['status'] === 'pending'): ?>
                                            <div class="d-flex justify-content-end gap-2">
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                                                    <input type="hidden" name="action" value="approve">
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="bi bi-check-circle"></i> Approve
                                                    </button>
                                                </form>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                                                    <input type="hidden" name="action" value="reject">
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="bi bi-x-circle"></i> Reject
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-danger btn-sm" onclick="showDeleteModal(<?php echo $row['id']; ?>)">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </div>
                                        <?php else: ?>
                                            <div class="d-flex justify-content-end">
                                                <button type="button" class="btn btn-danger btn-sm" onclick="showDeleteModal(<?php echo $row['id']; ?>)">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-footer text-muted">
                                        Received: <?php echo date('F j, Y g:i A', strtotime($row['created_at'])); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info">
                                No appointments found. 
                                <?php if (isset($_GET['debug'])): ?>
                                    <br>Debug: Table exists: <?php echo ($table_check->num_rows > 0 ? 'Yes' : 'No'); ?>
                                    <br>Database: <?php echo $conn->database; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this appointment? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" class="d-inline">
                        <input type="hidden" name="appointment_id" id="deleteAppointmentId">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to show delete confirmation modal
        function showDeleteModal(appointmentId) {
            document.getElementById('deleteAppointmentId').value = appointmentId;
            const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            modal.show();
        }

        fetch('forms/get_appointments.php')
          .then(response => response.json())
          .then(bookedDates => {
            flatpickr("#appointment-date", {
              dateFormat: "Y-m-d",
              disable: bookedDates, // disables booked dates
              minDate: "today",     // only allow future dates
              // Optionally, highlight available/booked dates differently with plugins or custom CSS
            });
          });
    </script>
</body>
</html> 