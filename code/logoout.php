<?php
session_start();
header('Content-type:text/html;charset=utf-8');

if (isset($_SESSION['name'])) {
    session_unset();//释放变量
    session_destroy();//清空数据
    setcookie(session_name(), '', time() - 3600);
    $url = 'login.php';
    header('Location: ' . $url);
} else {
    $url = 'login.php';
    header('Location: ' . $url);
}

?>