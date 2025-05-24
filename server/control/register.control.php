<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') die();

include 'helper.control.php';
require '../model/User.php';

$raw_data = file_get_contents("php://input");
$data = json_decode($raw_data, true);
$headers = getallheaders();

registerUser($data['name'], $data['surname'], $data['email'], $data['password'], $data['password_again']);

function registerUser($name, $surname, $email, $password, $password_again)
{
    $errors = [];
    // Name
    if (empty($name)) {
        $errors['name'] = "Name is required";
    } else {
        $name = test_input($name);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name) && !isset($errors['name'])) {
            $errors['name'] = 'Name should contain only letters and spaces.';
        }
    }

    // Surname
    if (empty($surname)) {
        $errors['surname'] = "Surname is required";
    } else {
        $surname = test_input($surname);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $surname) && !isset($errors['surname'])) {
            $errors['surname'] = 'Surname should contain only letters and spaces.';
        }
    }

    // Email
    if (empty($email)) {
        $errors['email'] = "Email is required";
    } else {
        $email = test_input($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !isset($errors['email'])) {
            $errors['email'] = "Invalid email format.";
        }
    }

    // Password
    if (empty($password)) {
        $errors['password'] = "Password is required";
    } else {
        if (
            strlen($password) < 8 ||
            !preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[^A-Za-z0-9]).+$/', $password)
        ) {
            $errors['password'] = "Password must be at least 8 characters long and contain at least one letter, one number, and one special character.";
        }
    }

    // Repeat Password
    if (empty($password_again)) {
        $errors['repassword'] = "Repeated password is required";
    } else {
        if ($password !== $password_again) {
            $errors['password_again'] = "Passwords do not match.";
        }
    }


    // Check if user exists
    require '../../config/db.php';
    $user = new User($conn);
    $userExists = $user->checkEmail($email);
    if ($userExists && !isset($errors['email'])) {
        $errors['email'] = "User with this email already exists!";
    }

    // If errors exist, stop here
    if (!empty($errors)) {
        echo json_encode($errors);
        exit;
        die();
    }

    // Register user
    $isCreated = $user->register($name, $surname, $email, $password);

    if (!$isCreated) {
        echo json_encode(["error" => "Registration failed. Please try again."]);
    } else {
        echo json_encode(["success" => "Successfully registered!"]);
    }

    $conn = null;
    exit;
}
