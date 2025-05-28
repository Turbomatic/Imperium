<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') exit();
require '../config/session.php';
startSession();
if (!isset($_SESSION['user_id'])) {
  echo json_encode(['login' => 'Session is closed']);
  exit();
}
$userId = $_SESSION['user_id'];

require 'control/checkout.control.php';
$raw_data = file_get_contents("php://input");
$post_data = json_decode($raw_data, true);
$headers = getallheaders();
$requestType = $headers['Request-Type'] ?? null;

$products = [];

switch ($requestType) {
  case 'single_item':
    if (!isset($post_data['product_id']) || !isset($post_data['quantity'])) {
      echo json_encode(['error' => 'Purchase was not processed!']);
      exit();
    }
    $products[0] = getProduct($post_data['product_id'], $post_data['quantity']);
    $products[0]['quantity'] = $post_data['quantity'];
    break;
  case 'cart_item':
    $products = getCartProducts();
    if (!isset($products[0])) {
      $products = [$products];
    }
    break;
  default:
    exit();
    break;
}

foreach ($products as $product) {
  $line_items[] = [
    'price_data' => [
      'currency' => 'eur',
      'unit_amount' => $product['price'] * 100, // must be in cents
      'product_data' => [
        'name' => $product['product_name'],
      ]
    ],
    'quantity' => $product['quantity'],
  ];
}


require_once '../vendor/autoload.php';
require_once '../config/checkout.config.php';

\Stripe\Stripe::setApiKey($stripeSecretKey);
header('Content-Type: application/json');

$YOUR_DOMAIN = 'http://localhost/imperium';

$checkout_session = \Stripe\Checkout\Session::create([
  'line_items' => $line_items,
  'mode' => 'payment',
  'success_url' => $YOUR_DOMAIN . '/public/',
  'cancel_url' => $YOUR_DOMAIN . '/public/',
  'metadata' => [
    'user_id' => $userId,
    'product_ids' => implode(',', array_column($products, 'product_id')),
  ],
]);

echo json_encode(['url' => $checkout_session->url]);
