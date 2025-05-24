<?php
function display_profile(array $userData, array $historyItems, array $savedItems)
{
  echo <<<HTML
<div class="profile-page">
  <aside class="profile-sidebar displace-hide animate-in">
    <img src="assets/pictures/profilepic.png" alt="Profile Picture" class="profile-picture" />
    <h2 class="profile-name">{$userData['name']}</h2>
    <p class="profile-email">{$userData['email']}</p>
    <p class="profile-member-since">Member since: {$userData['created_at']}</p>
    <h3 class="section-title">Account Settings</h3>
    <form class="account-form">
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Enter new username" value="{$userData['name']}">
      </div>
      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="Enter new email" value="{$userData['email']}">
      </div>
      <div class="form-group">
        <label for="change-password">Password</label>
        <a href="/forgot-password.php" class="password-redirect">Change Password</a>
      </div>
      <button type="submit" class="account-submit">Update Account</button>
    </form>
  </aside>

  <main class="profile-main displace-hide animate-in">
    <section class="profile-section">
      <h3 class="section-title">Order History</h3>
      <ul class="item-list">
HTML;

  foreach ($historyItems as $order) {
    echo <<<ORDER
        <li class="item-card">
          <div class="order-item">
            <div class="order-img">
              <img src="assets/pictures/Alienware_x17_Gaming_Laptop.jpeg" alt="{$order['product_name']}">
            </div>
            <div class="order-details">
              <p><strong>Order #{$order['order_id']}</strong></p>
              <p class="item-title">{$order['product_name']}</p>
              <p class="item-info">{$order['description']}</p>
              <p class="item-price">\${$order['price']}</p>
              <p class="item-subtext">Delivered: {$order['created_at']}</p>
              <div class="product-carousel">
                <img src="assets/pictures/Alienware_x17_Gaming_Laptop.jpeg" alt="">
                <img src="assets/pictures/Alienware_x17_Gaming_Laptop.jpeg" alt="">
              </div>
            </div>
          </div>
        </li>
ORDER;
  }

  echo <<<HTML
      </ul>
    </section>

    <section class="profile-section">
      <h3 class="section-title">Saved Items</h3>
      <div class="saved-items-grid displace-hide animate-in">
HTML;

  foreach ($savedItems as $item) {
    echo <<<SAVED
        <div class="item-card">
          <div class="saved-img">
            <img src="{$item['image_url']}" alt="{$item['product_name']}">
          </div>
          <h4 class="item-title">{$item['product_name']}</h4>
          <p class="item-info">{$item['description']}</p>
          <p class="item-price">\${$item['price']}</p>
          <div class="product-carousel">
            <img src="{$item['image_url']}" alt="">
            <img src="{$item['image_url']}" alt="">
          </div>
          <button class="add-to-cart">Add to Cart</button>
        </div>
SAVED;
  }

  echo <<<HTML
      </div>
    </section>
  </main>
</div>
HTML;
}
