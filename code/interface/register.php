<?php
session_start();
header("Content-type:text/html;charset=utf-8");
require_once(dirname(__DIR__) . '/..//global_config.php');
require_once(APP_ROOT_PATH . '/db_config.php');

if (true) {
    $date=Array(
        "status"=>200,
        "content"=>""
    );
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
        date_default_timezone_set("Asia/Shanghai");//计算一周的起始和结束日期
        $a = date('Y-m-d');
        $b=((int)substr("$a",2,2));
        $c=((int)substr("$a",5,2));
        $d=((int)substr("$a",8,2));
        $numtime = $b * 10000 + $c * 100 + $d;

        $today=date("w");//判断星期几,周日为0，再减去1
        if($today == 0)
        {
            $today = 6;
        } else{
            $today = $today - 1;
        }
        $sunday = $numtime + (6 - $today);

        try {
            $dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $id = htmlspecialchars($_POST['id']);
            $password = $_POST['password'];
            $direction = htmlspecialchars($_POST['direction']);
            $name = htmlspecialchars($_POST['name']);
            $times = 0;
            $notes = "111111111111111111111";
            $sqltwo = 'SELECT count(*) FROM students WHERE  id = :id';
            $test = $dbh->prepare($sqltwo);
            $test->bindParam(':id', $id);
            $test->execute();
            $data = $test->fetch();
            $count = $data[0];
            if($count)
            {
                $date["content"]="学号已被注册";
                echo json_encode($date);
                return;
            }
            $sql = 'INSERT INTO students(id, password, direction, name, times, notes, sunday) value (:id, SHA(:password), :direction, :name, :times, :notes, :sunday)';
            $stmt = $dbh->prepare($sql);

            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':direction', $direction);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':times', $times);
            $stmt->bindParam(':notes', $notes);
            $stmt->bindParam(':sunday', $sunday);

            if ($stmt->execute()) {
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
}
?>