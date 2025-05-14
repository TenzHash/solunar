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

if (!$result) {
    // Log the error
    error_log("Database error: " . $conn->error);
    $reviews = [];
} else {
    $reviews = $result->fetch_all(MYSQLI_ASSOC);
    
    // Log the number of reviews found
    error_log("Number of reviews found: " . count($reviews));
    
    // If no reviews found, check if tables exist and have data
    if (empty($reviews)) {
        $check_tables = $conn->query("SHOW TABLES LIKE 'testimonials'");
        $testimonials_exists = $check_tables->num_rows > 0;
        
        $check_tables = $conn->query("SHOW TABLES LIKE 'reviews'");
        $reviews_exists = $check_tables->num_rows > 0;
        
        error_log("Tables exist - testimonials: " . ($testimonials_exists ? 'yes' : 'no') . ", reviews: " . ($reviews_exists ? 'yes' : 'no'));
        
        if ($testimonials_exists) {
            $count = $conn->query("SELECT COUNT(*) as count FROM testimonials")->fetch_assoc()['count'];
            error_log("Number of testimonials: " . $count);
        }
        
        if ($reviews_exists) {
            $count = $conn->query("SELECT COUNT(*) as count FROM reviews")->fetch_assoc()['count'];
            error_log("Number of reviews: " . $count);
        }
    }
}
?> 