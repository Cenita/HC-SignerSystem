<?php
session_start();
header("Content-type:text/html;charset=utf-8");
require_once(dirname(__DIR__) . '/..//global_config.php');
require_once(APP_ROOT_PATH . '/db_config.php');
$date=Array(
    "status"=>200,
    "content"=>"",
);
$ip = $_SERVER['REMOTE_ADDR'];
$ip2 = $_SERVER['HTTP_X_FORWARDED_FOR'];
if ($ip==IPONE || $ip == IPTWO || $ip2 == IPONE || $ip2 == IPTWO) {
    if (empty($_POST['id']) || empty($_POST['name']) || empty($_POST['direction']) || empty($_POST['password'])) {
        $date["content"]="信息均不能为空";
        echo json_encode($date);
        return;
    } else if (strlen($_POST['id']) != 11) {
        $date["content"]="学号为十一位";
        echo json_encode($date);
        return;
    } else if (strlen($_POST['name']) > 10) {
        $date["content"]="不合法名字";
        echo json_encode($date);
        return;
    } else if (strlen($_POST['direction']) > 10) {
        $date["content"]="不合法方向";
        echo json_encode($date);
        return;
    }else if (strlen($_POST['password']) < 6 || strlen($_POST['password']) > 16) {
        $date["content"]="密码需要大于6位小于16位";
        echo json_encode($date);
        return;
    } else {
        date_default_timezone_set("Asia/Shanghai");
        $time = strtotime(date('Y-m-d H:i:s'));
        try {
            $dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $id = htmlspecialchars($_POST['id']);
            $password = $_POST['password'];
            $direction = htmlspecialchars($_POST['direction']);
            $name = htmlspecialchars($_POST['name']);
            $confirm = 1;

            $sql = 'SELECT count(*) FROM users WHERE  id = :id';
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $data = $stmt->fetch();
            $count = $data[0];
            if($count)
            {
                $date["content"]="学号已被注册";
                echo json_encode($date);
                return;
            }

            $sqltwo = 'INSERT INTO users(id, password, direction, name, confirm, time) value (:id, SHA(:password), :direction, :name, :confirm, :time)';
            $stmttwo = $dbh->prepare($sqltwo);
            $stmttwo->bindParam(':id', $id);
            $stmttwo->bindParam(':password', $password);
            $stmttwo->bindParam(':direction', $direction);
            $stmttwo->bindParam(':name', $name);
            $stmttwo->bindParam(':confirm', $confirm);
            $stmttwo->bindParam(':time', $time);
            if ($stmttwo->execute()) {
                $date["content"]="注册成功";
                echo json_encode($date);
                return;
            } else {
                $date["content"]="注册失败";
                echo json_encode($date);
                return;
            }
            $dbh = null;
        } catch (PDOException $e) {
            $date["status"]=400;
            echo json_encode($date);
            return;
            die();
        }
    }
}else {
    $date["content"]="请在官方指定地点完成注册";
    echo json_encode($date);
    return;
}
?>