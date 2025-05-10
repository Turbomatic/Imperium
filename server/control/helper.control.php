<?php
require_once "../../config/session.php";

function showHome(){
    require_once 'view/home.view.php';
    display_home();
}
function showProfile(){
    require_once 'view/profile.view.php';
    display_profile();
}
function showLoginForm(){
    require_once 'view/login.view.php';
    display_login();
}
function showForgotPassword(){
    require_once 'view/forgotPassword.view.php';
    display_forgot();
}
function showChangePassword(){
    require_once 'view/changePassword.view.php';
    display_change_password();   
}
function showProducts(){
    require_once 'view/products.view.php';
    display_products();
}
function logoutUser()
{
    destroySession();
}
function getGoogleUserId(){
    startSession();
    return $_SESSION['user_google_id'] ?? null;
}
function getLocalUserId(){
    startSession();
    return $_SESSION['user_id'] ?? null;
}
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
function validateNewPassword($newPass, $confirmPass) {
    $errors = [];

    if (empty($newPass)) {
        $errors['password'] = "Password is required";
    } else {
        if (strlen($newPass) < 8 ||
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