<?php
session_start();
require_once(dirname(__DIR__) . '/../global_config.php');
require_once(APP_ROOT_PATH . '/db_config.php');
require_once(APP_ROOT_PATH . '/code/checkUser.php');

$content="";
if($_SESSION['nowstatus'] == 1 && $_SESSION['classify'] > 5 && $_SESSION['classify'] < 22) {
    $notes = $_SESSION['notes'];
    $count = $_SESSION['count'];
    $notes[$count] = 2;
    $times = $_SESSION['times'] + 1;

    try {
        $dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'UPDATE students SET notes = :notes , times = :times WHERE id = :id';
        $stmt = $dbh->prepare($sql);
        $k = $_SESSION['id'];
        $stmt->bindParam(':id', $k);
        $stmt->bindParam(':notes', $notes);
        $stmt->bindParam(':times', $times);
        $stmt->execute();

        $_SESSION['times'] = $times;
        $_SESSION['notes'] = $notes;
        $_SESSION['nowstatus'] = 2;

        $dbh = null;

    }catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
    $content="签到成功";
}else if($_SESSION['nowstatus'] == 2 && $_SESSION['classify'] > 5 && $_SESSION['classify'] < 22){
    $content="已经签到";
}else if($_SESSION['classify'] < 6 || $_SESSION['classify'] > 21){
    $content="非签到时段";
}

$date=Array(
    "content"=>$content
);
echo json_encode($date);
?>