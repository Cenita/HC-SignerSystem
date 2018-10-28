<?php
require_once(dirname(__DIR__) . '/global_config.php');

function checkUser()
{
    if (!isset($_SESSION['name'])) {
        $loginUr2 = ADMIN_SERVER . '/login.php';
        header('Location: ' . $loginUr2);
        die();
    }
    $ip = $_SERVER['REMOTE_ADDR'];
    $ip2 = $_SERVER['HTTP_X_FORWARDED_FOR'];
    $temp = 0;
    if($ip == "219.129.250.214"){
        $temp++;
    }
    if($ip == "219.129.250.220"){
        $temp++;
    }
    if($ip2 == "219.129.250.214"){
        $temp++;
    }
    if($ip2 == "219.129.250.220"){
        $temp++;
    }
    if($temp == 0){
        $loginUr2 = ADMIN_SERVER . '/login.php';
        header('Location: ' . $loginUr2);
        die();
    }
}
?>