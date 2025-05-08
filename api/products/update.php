<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../models/Product.php';

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->id) &&
    !empty($data->name) &&
    !empty($data->price) &&
    !empty($data->category)
) {
    $product->id = $data->id;
    $product->name = $data->name;
    $product->description = $data->description;
    $product->price = $data->price;
    $product->category = $data->category;
    $product->image_url = $data->image_url;
    $product->stock = $data->stock;

    if($product->update()) {
        http_response_code(200);
        echo json_encode(array("message" => "Product was updated."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to update product."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to update product. Data is incomplete."));
}
?> 