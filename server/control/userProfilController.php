<?php
require_once "../config/session.php";
require "./model/User.php";
require_once "helper.control.php";

function viewProfile()
{
    require '../../config/databa.php';
    $user = new User($conn);

    if (getLocalUserId()) {
        $userId = getLocalUserId();
        $userData = $user->getUserById($userId);
        if (empty($userData)) {
            echo "User not found";
            return;
        } else {
            echo json_encode($userData);
        }
    } else {
        http_response_code(401);
        echo "Unauthorized";
        return;
    }
    $conn = null;
}
function updateProfile($postData)
{

    startSession();

    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
        http_response_code(401);
        echo "Unauthorized";
        return;
    }
    require '../../config/databa.php';
    $user = new User($conn);

    if (getLocalUserId()) {
        $userId = getLocalUserId();
        $userData = $user->getUserById($userId);
        if (empty($userData)) {
            http_response_code(404);
            echo "User not found";
            return;
        } else {
            $user->updateUser($userId, $_POST['name'], $_POST['surname'], $_POST['avatar_url']);
            echo json_encode($user->getUserById($userId));
        }
    }
    $conn = null;
}
function verifyCurrentPasword()
{
    startSession();

    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
        echo json_encode(["errors" => "Unauthorized"]);
        return;
    }

    $currentPassword = $_POST['current_password'] ?? '';

    if (empty($currentPassword)) {
        echo json_encode(["error_password" => "Please enter your current password."]);
        return;
    }

    require '../../config/databa.php';
    $user = new User($conn);
    $userData = $user->getUserById($userId);

    if (!$userData || !password_verify($currentPassword, $userData['password'])) {
        echo (["error_password" => "Incorrect password."]);
        return;
    } else {
        echo json_encode(["success" => "Password verified."]);
    }
    $conn = null;
}

function changePassword()
{

    startSession();

    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
        echo json_encode(["errors" => "Unauthorized"]);
        return;
    }

    $oldPass = $_POST['old_password'] ?? '';
    $newPass = $_POST['new_password'] ?? '';
    $confirmPass = $_POST['confirm_password'] ?? '';

    $validationErrors = validateNewPassword($newPass, $confirmPass);
    if ($validationErrors) {
        echo json_encode(["error" => $validationErrors]);
        return;
    }

    require '../../config/db.php';
    $user = new User($conn);
    $success = $user->changePassword($userId, $oldPass, $newPass);

    if ($success) {
        echo json_encode(["success" => "Password changed successfully."]);
    } else {
        echo json_encode(["error" => "Failed to update password."]);
    }

    $conn = null;
}

function getCompleteUserProfile(): ?array
{
    $userId = $_SESSION['user_id'] ?? null;

    if (!$userId) {
        http_response_code(401);
        echo json_encode(["error" => "Unauthorized"]);
        return null;
    }

    require '../config/db.php'; // Make sure the path is correct
    $user = new User($conn);

    $userData = $user->getUser($userId);
    if (empty($userData)) {
        http_response_code(404);
        echo json_encode(["error" => "User not found"]);
        return null;
    }

    // Get related data
    $orderHistory = $user->getOrderHistory($userId);
    $savedItems = $user->getSavedItems($userId);

    // Combine into one structured response
    return [
        'user' => $userData,
        'order_history' => $orderHistory,
        'saved_items' => $savedItems
    ];
    $conn = null;
}
