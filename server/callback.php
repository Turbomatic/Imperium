<?php
require_once  '../vendor/autoload.php';
require_once '../config/google.config.php';
require_once './model/User.php';

session_start();

$client = new Google_Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri('http://localhost/imperium/server/callback.php');

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    // Get profile info
    $oauth =  new Google_Service_Oauth2($client);
    $profile = $oauth->userinfo->get();


    $googleUserData = [
        'id' => $profile->id,
        'name' => $profile->name,
        'email' => $profile->email,
        'picture' => $profile->picture,
    ];

    // Example: Save user info in session
    require '../config/db.php';
    $user = new User($conn);
    $userId = $user->registerGoogleUser($googleUserData);

    $_SESSION['email'] = $googleUserData['email'];
    $_SESSION['user_name'] = $googleUserData['name'];
    $_SESSION['user_id'] = $userId;

    // Redirect to a protected page
    $conn = null; // Close the database connection
    header('Location: ../public/profile');
    exit;
};
