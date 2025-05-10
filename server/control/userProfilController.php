<?php
require_once "../../config/session.php";
require_once "../model/User.php";
require_once "./helper.control.php";

function viewProfile()
{

    $user = new User();

    if (getGoogleUserId()) {
        $userId = getGoogleUserId();
        $userData = $user->getUserByGoogleId($userId);
        if (empty($userData)) {
            http_response_code(404);
            echo "User not found";
            return;
        } else {
            echo json_encode($userData);
        }
    } else if (getLocalUserId()) {
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

    $user = new User();

    if (getGoogleUserId()) {
        $userId = getGoogleUserId();
        $userData = $user->getUserByGoogleId($userId);
        if (empty($userData)) {
            http_response_code(404);
            echo "User not found";
            return;
        } else {
            $user->updateUser($userId, $_POST['name'], $_POST['surname'], $_POST['avatar_url']);
            echo json_encode($user->getUserByGoogleId($userId));
        }
    } else if (getLocalUserId()) {
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
    } else {
        http_response_code(401);
        echo "Unauthorized";
        return;
    }
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

    $user = new User();
    $userData = $user->getUserById($userId);

    if (!$userData || !password_verify($currentPassword, $userData['password'])) {
        echo (["error_password" => "Incorrect password."]);
        return;
    } else {
        echo json_encode(["success" => "Password verified."]);
    }
}

function changePassword()
{

    startSession();

    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
        echo json_encode(["errors" => "Unauthorized"]);
        return;
    }

    $newPass = $_POST['new_password'] ?? '';
    $confirmPass = $_POST['confirm_password'] ?? '';

    $validationErrors = validateNewPassword($newPass, $confirmPass);
    if ($validationErrors) {
        echo json_encode(["error" => $validationErrors]);
        return;
    }

    $user = new User();
    $success = $user->updatePassword($userId, $newPass);

    if ($success) {
        echo json_encode(["success" => "Password changed successfully."]);
    } else {
        echo json_encode(["error" => "Failed to update password."]);
    }
}
