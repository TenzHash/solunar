<?php
require_once '../config/database.php';
header('Content-Type: application/json');

$sql = "SELECT appointment_date FROM appointments WHERE status = 'confirmed'";
$result = $conn->query($sql);

$dates = [];
while ($row = $result->fetch_assoc()) {
    $dates[] = $row['appointment_date'];
}

echo json_encode($dates);
$conn->close();
?>