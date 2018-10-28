<?php
session_start();

$content="";
if($_SESSION['classify'] > 21 || $_SESSION['classify'] < 6 || $_SESSION['nowstatus'] == 1) {
    $content="没有签到";
} else {
    $content="已经签到";
}
$date=Array(
    "content"=>$content
);
echo json_encode($date);
?>