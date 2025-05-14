<?php
require_once 'config/database.php';

// Get approved reviews from both tables
$sql = "
    SELECT 
        'product' as review_type,
        t.id,
        t.user_name,
        t.rating,
        t.comment,
        t.created_at,
        p.name as product_name,
        NULL as service
    FROM testimonials t 
    LEFT JOIN products p ON t.product_id = p.id 
    WHERE t.status = 'approved'
    UNION ALL
    SELECT 
        'service' as review_type,
        r.id,
        r.name as user_name,
        r.rating,
        r.review as comment,
        r.created_at,
        NULL as product_name,
        r.service
    FROM reviews r
    WHERE r.status = 'approved'
    ORDER BY created_at DESC
";

$result = $conn->query($sql);
$reviews = $result->fetch_all(MYSQLI_ASSOC);
?> 