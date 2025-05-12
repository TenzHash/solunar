<?php
require_once 'config/database.php';

// Get featured products
$query = "SELECT id, name, description, price, category, image_url FROM products WHERE featured = 1 ORDER BY name ASC";
$result = mysqli_query($conn, $query);

$products = array();
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = array(
        'id' => $row['id'],
        'name' => htmlspecialchars($row['name']),
        'description' => htmlspecialchars($row['description']),
        'price' => number_format($row['price'], 2),
        'category' => htmlspecialchars($row['category']),
        'image_url' => htmlspecialchars($row['image_url'])
    );
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($products);
?> 