<?php
require_once 'control/helper.control.php';

$filter = $_GET['filters'] ?? '';
if (!empty($filter)) $filter = urldecode($filter);

if (!isset($_GET['query0'])) {
    showHome(['bestSelling' => 5, 'newProducts' => 5,]);
} else if ($_GET['query0'] === 'profile' && !isset($_GET['query1'])) {
    showProfile();
} else if ($_GET['query0'] === 'prebuilts' && !isset($_GET['query1'])) {
    showProducts($filter, 'prebuilt');
} else if ($_GET['query0'] === 'components' && !isset($_GET['query1'])) {
    showProducts($filter, 'component');
} else if ($_GET['query0'] === 'peripherals' && !isset($_GET['query1'])) {
    showProducts($filter, 'peripheral');
} else if ($_GET['query0'] === 'cart' && !isset($_GET['query1'])) {
    showCart();
} else if ($_GET['query0'] === 'about' && !isset($_GET['query1'])) {
    showAboutUs();
} else if ($_GET['query0'] === 'forgot-password' && !isset($_GET['query1'])) {
    showForgotPassword();
} else if ($_GET['query0'] === 'change-password' && !isset($_GET['query1'])) {
    showChangePassword();
} else if ($_GET['query0'] === 'prebuilts' || $_GET['query0'] === 'components' || $_GET['query0'] === 'peripherals' || $_GET['query0'] === 'deals' && isset($_GET['query1'])) { //to be compared with a product Id
    showSpecificProduct($_GET['query1']);
} else {
    echo 'url is invalid';
}
