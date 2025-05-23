<?php

require_once 'control/helper.control.php';
require_once '../config/session.php';

# GET METHOD DATA
$query0 = $_GET['query0'] ?? '';
$query1 = $_GET['query1'] ?? '';
//add more vars if needed

if ($query0 == 'login' && !$query1) {
    //TO DO : do the checks inside contorl
    startSession();
    if(isset($_SESSION['user_id'])){
        echo '<h1>Welcome <span style ="color: blue">'. $_SESSION['user_name'].'</h1>';
    }
    else{
        showLoginForm();
    }
}
else if (!$query0 && !$query1) {
    showHome();
}
else if($query0 == 'profile' && !$query1){
    //TO DO : do the checks inside contorl
    startSession();
    if (isset($_SESSION['user_id'])){
        showProfile();
    }
    else{
        showLoginForm();
    }
}
else if ($query0 == 'prebuilts' && !$query1) {
    showProducts();
}
else if ($query0 == 'components' && !$query1) {
    //placeholder
    echo 'no data yet on components';
}
else if ($query0 == 'peripherals' && !$query1) {
    //placeholder
    echo 'no data yet on peripherals';
}
else if ($query0 == 'prebuilts' && $query1) { //$query1 to be checked for its value
    showSpecificProduct();
}
else if ($query0 == 'cart' && !$query1) {
    showCart();
}
else if ($query0 == 'forgot-password'){
    showForgotPassword();
}
else if ($query0 == 'change-password'){
    showChangePassword();
}
else {
    echo 'url is invalid';
}