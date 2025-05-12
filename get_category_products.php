<?php
require_once 'config/database.php';

// Get the category card from the request
$category_card = isset($_GET['category']) ? $_GET['category'] : null;

if (!$category_card) {
    http_response_code(400);
    echo json_encode(['error' => 'Category card not specified']);
    exit;
}

// Get products for the specified category card
$stmt = $conn->prepare("
    SELECT id, name, description, price, category, image_url 
    FROM products 
    WHERE category_card = ? 
    ORDER BY name ASC
");
$stmt->bind_param("s", $category_card);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    // Escape special characters for HTML output
    $row['name'] = htmlspecialchars($row['name']);
    $row['description'] = htmlspecialchars($row['description']);
    $row['category'] = htmlspecialchars($row['category']);
    $products[] = $row;
}

// Return the products as JSON
header('Content-Type: application/json');
echo json_encode($products);

$stmt->close();
$conn->close();
?> 