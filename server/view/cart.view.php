<?php
function display_cart(array $cartItems)
{
  $total = 0;

  echo <<<HTML
<div class="cart-page">
  <h2 class="cart-title">Your Shopping Cart</h2>
  <div class="cart-container">
HTML;


  foreach ($cartItems as $index => $item) {
    $id = $item['cart_item_id'];
    $name = htmlspecialchars($item['product_name']);
    $desc = htmlspecialchars($item['description']);
    $price = number_format($item['price'], 2);
    $qty = (int)$item['quantity']; //imperium/public/
    $image = htmlspecialchars((string)($item['image_url'] ?? ''));
    $total += $item['price'] * $qty;

    echo <<<ITEM
    <div class="cart-item" data-id="{$id}">
      <img src="{$image}" alt="{$name}" class="cart-item-image">
      <div class="cart-item-details">
        <h3 class="item-name">{$name}</h3>
        <p class="item-desc">{$desc}</p>
        <p class="item-price">€{$price}</p>
        <div class="item-actions">
          <label>Qty:</label>
          <span class="qty-display">{$qty}</span>
          <button class="remove-item" onclick="removeFromCart({$item['cart_item_id']})">Remove</button>
        </div>
      </div>
    </div>
ITEM;
  }

  $totalFormatted = number_format($total, 2);

  echo <<<SUMMARY
    <div class="cart-summary">
      <h3>Cart Total: <span class="total-price">€{$totalFormatted}</span></h3>
      <button class="checkout-button" onclick="buy_cart()">Proceed to Checkout</button>
    </div>
  </div>
</div>
SUMMARY;
}
