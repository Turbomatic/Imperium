<?php

require_once "../../config/session.php";

startSession();

if ($_SERVER['REQUEST_METHOD'] != 'POST') die();

$raw_data = file_get_contents("php://input");
error_log("RAW INPUT: " . $raw_data);
$postData = json_decode($raw_data, true);
error_log("Parsed POST Data: " . print_r($postData, true));
$headers = getallheaders();
$requestType = $headers['Request-Type'] ?? null;

if (!$requestType) {
    error_log("TYPE not set!");
    http_response_code(400);
    exit;
}

require '../model/Cart.php';
require '../../config/db.php';

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    http_response_code(401);
    exit;
}

$cart = new Cart($conn, $userId);

switch ($requestType) {

    case 'remove_item':
        removeItem($cart, $userId, $postData);
        break;
    case 'add_product':
        addProductToCart($cart, $userId, $postData);
        break;
    default:
        http_response_code(400);
        break;
}

function removeItem($cart, $userId, $postData)
{
    if (!isset($postData['itemId'])) {
        http_response_code(400);
        return;
    }

    $itemId = $postData['itemId'];

    if ($cart->removeCartItemById($userId, $itemId)) {
        echo json_encode(['success' => 'Item removed from cart']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => 'Failed to remove item from cart']);
    }
}
function addProductToCart($cart, $userId, $postData)
{

    if (!isset($postData['product_id'])) {
        http_response_code(400);
        return;
    }

    $productId = $postData['product_id'];
    $quantity = $postData['quantity'] ?? 1;

    if ($cart->addSavedItemToCart($userId, $productId, $quantity)) {
        echo json_encode(['success' => 'Product added to cart']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => 'Failed to add product to cart']);
    }
}



$conn = null;
