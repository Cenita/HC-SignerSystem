<?php
header("Content-type:text/html;charset=utf-8");
require_once(dirname(__DIR__) . '/global_config.php');
require_once(APP_ROOT_PATH . '/db_config.php');

function signInfor($id, $ymd, $lowlimit, $uplimit){
    try {
        date_default_timezone_set("Asia/Shanghai");
        $lowlimittime = $ymd." ".$lowlimit;
        $lowlimit = strtotime("$lowlimittime");
        $uplimittime = $ymd." ".$uplimit;
        $uplimit = strtotime("$uplimittime");
        $dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM signlist WHERE  userid = :id AND time > $lowlimit AND time < $uplimit";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $data = $stmt->fetchAll();
        if (count($data)) {
            return 1;
        } else {
            return 0;
        }
        $dbh = null;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
    }
}

?>