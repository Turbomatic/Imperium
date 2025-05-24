<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') die();

include 'helper.control.php';
require '../model/User.php';

$raw_data = file_get_contents("php://input");
$data = json_decode($raw_data, true);
$headers = getallheaders();

loginUser($data['email'], $data['password']);

function loginUser($email, $password)
{
    $errors = [];
    // Email validation
    $email = test_input($email);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }
    // Authenticate
     require '../../config/db.php';
    $user = new User($conn);
    $foundUser = $user->getUserByEmail($email);
    if (empty($foundUser)) {
        if (!isset($errors['email'])) {
            $errors['email'] = 'User not found';
        }
    }
    //testing
    else if (!password_verify($password, $foundUser['password'])) {
        $errors['password'] = 'Wrong password';
    }


    // Stop if errors
    if (!empty($errors)) {
        echo json_encode($errors);
        exit;
        die();
    }

    require_once '../../config/session.php';
    // Start session
    startSession();
    $_SESSION['loggedin'] = true;
    $_SESSION['user_id'] = $foundUser['id'];
    $_SESSION['user_name'] = $foundUser['name'];

    echo json_encode(["success" => "Logged in successfully."]);
    $conn = null;
}
