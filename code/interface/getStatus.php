<?php
session_start();
require_once(dirname(__DIR__) . '/../global_config.php');
require_once(APP_ROOT_PATH . '/db_config.php');
$content="";
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
    $_SESSION['status'] = 0;
}
if ($note) {
    try {
        $dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $id = htmlspecialchars($_SESSION['id']);
        $sql = "SELECT * FROM signlist WHERE  userid = :id AND time > $lowlimit AND time < $uplimit";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $data = $stmt->fetchAll();
        $count = count($data);
        if ($count) {
            $content = "已经签到";
            $_SESSION['status'] = 1;
        } else {
            $content = "没有签到";
            $_SESSION['status'] = 0;
        }
        $dbh = null;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
    }
}
$datetwo = Array(
    "content" => $content
);
echo json_encode($datetwo);
?>