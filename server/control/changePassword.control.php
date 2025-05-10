<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') die();

require_once '../../config/session.php';
startSession();
if (!isset($_SESSION['recoveryEmail'])) {
    echo json_encode(['password_again' => 'You have not started a password recovery session, unable to continue.']);
    die();
}

include 'helper.control.php';
require_once '../model/User.php';
require_once './helper.control.php';

$raw_data = file_get_contents("php://input");
$data = json_decode($raw_data, true);

resetPassword($data['newPass'], $data['confirmPass']);

function resetPassword($newPass, $confirmPass) {
    $email = $_SESSION['recoveryEmail'];

    $user = new User();
    $foundUser = $user->getUserByEmail($email);
    if (empty($foundUser)) {
        echo json_encode(["password_again" => "Failed to retrieve user data for this email."]);
        die();
    } else if ($foundUser['newPasswordExpiry'] == null) {
        echo json_encode(["password_again" => "This email in not approved yet to make a password change."]);
        die();
    }

    if (hasNewPasswordExpired($foundUser['newPasswordExpiry'])) {
        echo json_encode(['password_again' => 'Your reset password time has expired.']);
        die();
    }

    validateNewPassword($newPass, $confirmPass);

    $passChanged = $user->updatePassword($email, $newPass);
    if ($passChanged) {
        //Remove session after finishing changing password
        session_unset();
        session_destroy();
        echo json_encode(["success" => "Password changed successfully."]);
    } else {
        echo json_encode(["password_again" => "Failed to change password in the database."]);
    }
}

function hasNewPasswordExpired($expiry) {
    return strtotime($expiry) <= time();
}

