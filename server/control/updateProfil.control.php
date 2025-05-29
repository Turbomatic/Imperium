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
    http_response_code(400);
    exit;
}

include 'helper.control.php';
require '../model/User.php';
require '../../config/db.php';

$user = new User($conn);
$userId = $_SESSION['user_id'] ?? null;


switch ($requestType) {
    case 'update_profile':
        updateProfile($user, $userId, $postData);
        break;
    case 'remove_item':
        removeItem($user, $userId, $postData);
        break;
    case 'logout':
        destroySession();
        echo json_encode(['success' => true]);
        exit();
        break;
    case 'delete_account':
        deleteAccount($user, $userId);
        destroySession();
        break;
    case 'add_saved_item':
        addSavedItem($user, $userId, $postData);
        break;
    default:
        http_response_code(400);
        echo json_encode(['success' => 'Unknown request type']);
        break;
}

if (!$userId) {
    http_response_code(401);
    echo json_encode(['login' => false, 'message' => 'Unauthorized']);
    exit;
}

function updateProfile($user, $userId, array $postData)
{
    $errors = [];

    $name = trim($postData['username'] ?? '');
    $email = trim($postData['email'] ?? '');


    if (empty($name) && empty($email)) {
        http_response_code(400);
        $error['error'] = 'At least one of name or email is required';
        return;
    }


    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        $errors['email'] = 'Invalid email format';
        return;
    }


    $userData = $user->getUserById($userId);
    if (!$userData) {
        http_response_code(404);
        $errors['error'] = 'User not found';
        return;
    }


    $newData = [];
    if (!empty($name)) {
        $newData['name'] = $name;
    }
    if (!empty($email)) {
        $newData['email'] = $email;
    }

    $userExists = $user->checkEmail($email);

    if ($userExists && $userExists['id'] != $_SESSION['user_id'] && !isset($errors['email'])) {
        $errors['email'] = "User with this email already exists!";
    }

    if (!empty($errors)) {
        echo json_encode($errors);
        exit;
    }

    $success = $user->updateUser($userId, $newData);

    if ($success) {
        echo json_encode(['success' => 'Profile updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => 'Failed to update profile or no changes detected']);
    }
}

function removeItem($user, $userId, $postData)
{
    $error = '';

    $itemId = $postData['productId'] ?? null;
    if (!$itemId) {
        http_response_code(400);

        $error = 'Item ID is required';
        return;
    }

    if (!empty($error)) {
        echo json_encode($error);
        exit;
    }

    $success = $user->removeSavedItem($userId, $itemId);

    if (!$success) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to remove item']);
    } else {
        http_response_code(200);
        echo json_encode(['success' => 'Item removed successfully']);
    }
}
function deleteAccount($user, $userId)
{
    error_log("Deleting user with ID: $userId");
    $success = $user->deleteUser($userId);
    error_log("Delete result: " . ($success ? "Success" : "Failure"));
    if (!$success) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete account']);
    } else {
        destroySession();
        echo json_encode(['success' => 'Account deleted successfully']);
    }
}
function addSavedItem($user, $userId, $postData)
{
    if(!$userId){
        echo json_encode(['login'=>'Must log in!']);
        exit();
    }
    $error = '';

    $productId = $postData['product_id'] ?? null;
    if (!$productId) {
        http_response_code(400);
        $error = 'Product ID is required';
        return;
    }

    if (!empty($error)) {
        echo json_encode($error);
        exit;
    }

    $success = $user->addProductToSavedItems($userId, $productId);

    if (!$success) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to add item']);
    } else {
        http_response_code(200);
        echo json_encode(['success' => 'Item added successfully']);
    }
}

$conn = null;
