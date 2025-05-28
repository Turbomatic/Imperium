<?php
function showHome($filters)
{
    require_once 'view/home.view.php';
    require_once 'products.control.php';
    $data = getHomeProducts($filters);
    display_home($data['bestSelling'], $data['newProducts']);
}
function showProfile()
{
    require '../config/session.php';
    startSession();
    if (!isset($_SESSION['user_id'])) {
        showLoginForm();
        die();
    }

    require_once 'view/profile.view.php';
    require_once 'userProfilController.php';
    $data = getCompleteUserProfile();
    display_profile($data['user'], $data['order_history'], $data['saved_items']);
}
function showLoginForm()
{
    require_once 'view/login.view.php';
    display_login();
}

function showForgotPassword()
{
    require_once 'view/forgotPassword.view.php';
    display_forgot();
}

function showChangePassword()
{
    require_once 'view/changePassword.view.php';
    display_change_password();
}
function showProducts($filters, $category)
{
    require_once 'view/products.view.php';
    require_once 'products.control.php';

    if($filters) $filters = json_decode($filters, true);
    else $filters = ['category' => $category, 'page' => 1, 'sort' => 'popularity', 'order' => 'descending', 'min_price' => 0, 'max_price' => 200000];
    $data = getProducts($filters);
    display_products($data['products'], $data['newFilters']);
}
function showSpecificProduct($product_id)
{
    require_once 'view/specificProduct.view.php';
    require_once 'products.control.php';
    require '../config/db.php';
    $p = new Product($conn);
    $data = $p->getProductById($product_id);
    display_specific_product($data);
}
function showAboutUs()
{
    require_once 'view/about_us.view.php';
    display_about_us();
}
function showCart()
{
    require '../config/session.php';
    startSession();
    if (!isset($_SESSION['user_id'])) {
        showLoginForm();
        die();
    }

    require 'model/Cart.php';
    require '../config/db.php';
    require_once "../config/session.php";
    $userId = $_SESSION['user_id'] ?? null;

    if (!$userId) {
        http_response_code(401);
        exit;
    }

    $cart = new Cart($conn, $userId);
    $cart_items = $cart->getUserItems();
    if (empty($cart_items)) {
        http_response_code(404);
        echo json_decode('No items found in cart');
        return [];
    }
    require_once 'view/cart.view.php';
    display_cart($cart_items);
    $conn = null;
}
function logoutUser()
{
    require '../config/session.php';
    destroySession();
}
function getGoogleUserId()
{
    require '../config/session.php';
    startSession();
    return $_SESSION['user_google_id'] ?? null;
}
function getLocalUserId()
{
    require '../config/session.php';
    startSession();
    return $_SESSION['user_id'] ?? null;
}
function getAuthenticatedUserId()
{
    $googleId = getGoogleUserId();
    if ($googleId !== null) {
        return $googleId;
    }

    return getLocalUserId();
}
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
function validateNewPassword($newPass, $confirmPass)
{
    $errors = [];

    if (empty($newPass)) {
        $errors['password'] = "Password is required";
    } else {
        if (
            strlen($newPass) < 8 ||
            !preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[^A-Za-z0-9]).+$/', $newPass)
        ) {
            $errors['password'] = "Password must be at least 8 characters long and contain at least one letter, one number, and one special character.";
        }
    }

    if (empty($confirmPass)) {
        $errors['password_again'] = "Repeated password is required";
    } else {
        if ($newPass !== $confirmPass) {
            $errors['password_again'] = "Passwords do not match.";
        }
    }

    if (!empty($errors)) {
        echo json_encode($errors);
        die();
    }
}
