<?php
session_start();
require_once(dirname(__DIR__) . '/../global_config.php');
require_once(APP_ROOT_PATH . '/db_config.php');
require_once(APP_ROOT_PATH . '/code/checkUser.php');
checkUser();
$ip = $_SERVER['REMOTE_ADDR'];
$ip2 = $_SERVER['HTTP_X_FORWARDED_FOR'];
if ($_SESSION['status'] == 0 && ($ip==IPONE || $ip == IPTWO || $ip2 == IPONE || $ip2 == IPTWO)){
    $content = "";
    $note = 0;
    date_default_timezone_set("Asia/Shanghai");
    if ($_SESSION['classify'] > 7 && $_SESSION['classify'] < 12) {
        $lowlimittime = $_SESSION['ymd'] . " " . "08:00:00";
        $lowlimit = strtotime("$lowlimittime");
        $uplimittime = $_SESSION['ymd'] . " " . "12:00:00";
        $uplimit = strtotime("$uplimittime");
        $note = 1;
    } else if ($_SESSION['classify'] > 12 && $_SESSION['classify'] < 17) {
        $lowlimittime = $_SESSION['ymd'] . " " . "13:00:00";
        $lowlimit = strtotime("$lowlimittime");
        $uplimittime = $_SESSION['ymd'] . " " . "17:00:00";
        $uplimit = strtotime("$uplimittime");
        $note = 1;
    } else if ($_SESSION['classify'] > 17 && $_SESSION['classify'] < 22) {
        $lowlimittime = $_SESSION['ymd'] . " " . "18:00:00";
        $lowlimit = strtotime("$lowlimittime");
        $uplimittime = $_SESSION['ymd'] . " " . "22:00:00";
        $uplimit = strtotime("$uplimittime");
        $note = 1;
    } else {
        $content = "非签到时段";
    }
    if($note){
        $timestamp = strtotime(date('Y-m-d H:i:s'));
        $arr = array();
        while (count($arr) < 4) {
            $arr[] = rand(1, 9);
            $arr = array_unique($arr);
        }
        $keyid = implode("", $arr) . $timestamp;
        $id = htmlspecialchars($_SESSION['id']);
        try {
            $dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = 'INSERT INTO signlist(id, time, userid) value (:id,:time,:userid)';
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':userid', $id);
            $stmt->bindParam(':time', $timestamp);
            $stmt->bindParam(':id', $keyid);
            $stmt->execute();
            $content = "签到成功";
            $_SESSION['status'] = 1;
            $dbh = null;
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            return;
        }
    }
}else if($_SESSION['status'] == 0){
    $content = "请在官方指定地点签到";
}else if($_SESSION['status'] == 1){
    $content = "已签到";
}
$datetwo = Array(
    "content" => $content
);
echo json_encode($datetwo);
?>