<?php
session_start();
header("Content-type:text/html;charset=utf-8");
require_once(dirname(__DIR__) . '/global_config.php');
require_once(APP_ROOT_PATH . '/db_config.php');
if (isset($_POST['submit'])) {
    if (empty($_POST['id']) || empty($_POST['password'])) {
        echo "<script>alert('账号或密码不能为空!')</script>";
    } else {
        try {
            $dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = 'SELECT * FROM users WHERE id = :id AND password = SHA(:password)';
            $stmt = $dbh->prepare($sql);
            $id = htmlspecialchars($_POST['id']);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':password', $_POST['password']);
            if ($stmt->execute()) {
                if ($row = $stmt->fetch()) {
                    try {
                        date_default_timezone_set("Asia/Shanghai");
                        $timestamp = strtotime(date('Y-m-d H:i:s'));
                        $time = date('Y-m-d H:i:s');
                        $sql2 = 'UPDATE users SET time = :time WHERE id = :id';
                        $stmt2 = $dbh->prepare($sql2);
                        $stmt2->bindParam(':id', $id);
                        $stmt2->bindParam(':time', $timestamp);
                        if ($stmt2->execute()) {
                            $today = date("N");
                            $year = ((int)substr("$time", 0, 4));
                            $month = ((int)substr("$time", 5, 2));
                            $day = ((int)substr("$time", 8, 2));
                            $classify = ((int)substr("$time", 11, 2));
                            $ymd = date('Y-m-d');
                            $_SESSION['id'] = $row['id'];
                            $_SESSION['name'] = $row['name'];
                            $_SESSION['classify'] = $classify;
                            $_SESSION['ymd'] = $ymd;
                            $_SESSION['today'] = $today;
                            $_SESSION['time'] = $timestamp;

                            $monday = strtotime('this week Monday',time());
                            $tuesday = strtotime('this week Tuesday',time());
                            $wednesday = strtotime('this week Wednesday',time());
                            $thursday = strtotime('this week Thursday',time());
                            $friday = strtotime('this week Friday',time());
                            $saturday = strtotime('this week Saturday',time());
                            $sunday = strtotime('this week Sunday',time());
                            $lastmonday = strtotime('last week Monday',time());
                            $mondayymd=date('Y-m-d',"$monday");
                            $_SESSION['mondayymd'] = $mondayymd;
                            $tuesdayymd=date('Y-m-d',"$tuesday");
                            $_SESSION['tuesdayymd'] = $tuesdayymd;
                            $wednesdayymd=date('Y-m-d',"$wednesday");
                            $_SESSION['wednesdayymd'] = $wednesdayymd;
                            $thursdayymd=date('Y-m-d',"$thursday");
                            $_SESSION['thursdayymd'] = $thursdayymd;
                            $fridayymd=date('Y-m-d',"$friday");
                            $_SESSION['fridayymd'] = $fridayymd;
                            $saturdayymd=date('Y-m-d',"$saturday");
                            $_SESSION['saturdayymd'] = $saturdayymd;
                            $sundayymd=date('Y-m-d',"$sunday");
                            $_SESSION['sundayymd'] = $sundayymd;
                            $_SESSION['lastmonday'] = $lastmonday;

                            $url = 'index.php';
                            header('Location: ' . $url);
                            $dbh = null;
                        } else {
                            echo '服务器异常' . $stmt->errorCode();
                        }
                    } catch (PDOException $e) {
                        print "Error!: " . $e->getMessage() . "<br/>";
                    }
                } else {
                    echo "<script>alert('账号或密码错误!')</script>";
                }
            } else {
                echo '服务器异常' . $stmt->errorCode();
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
        }
    }
}
?>

<!doctype html>
<html lang="en" style="height: 99%">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>环创签到系统登录页面</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="tool/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="tool/bootstrap.min.css">
    <link rel="stylesheet" href="tool/UI-Transition-master/transition.min.css">
    <link rel="stylesheet" href="tool/UI-Dropdown-master/dropdown.min.css">
    <script src="tool/jquery.min.js"></script>
    <script src="tool/vue.min.js"></script>
    <script src="tool/bootstrap.min.js"></script>
    <script src="tool/Particleground.js"></script>
    <script src="tool/UI-Transition-master/transition.min.js"></script>
    <script src="tool/UI-Dropdown-master/dropdown.min.js"></script>
    <script src="js/login.js"></script>
</head>
<body style="height: 99%">
<div class="container" style="z-index: 100">
    <div class="row">
        <div class="col-md-4 col-md-offset-4" id="loginPart">
            <div class="title">环创签到系统</div>
            <div class="content">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                    <div class="input-group">
                            <span class="input-group-addon" style="width: 40px;">
                                <i class="fa fa-user-o"></i>
                            </span>
                        <input type="text" name="id" class="form-control" placeholder="学号"
                               aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group">
                            <span class="input-group-addon" style="width: 40px;">
                                <i class="fa fa-lock"></i>
                            </span>
                        <input type="password" name="password" class="form-control" placeholder="密码"
                               aria-describedby="basic-addon1">
                    </div>
                    <button type="submit" id="loginButton" class="btn btn-primary" name="submit">登录</button>
                </form>
            </div>
            <div class="reto reRegister">
                <div class="dai">注册</div>
                <i class="fa fa-angle-double-down"></i>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4" id="registerPart">
            <div class="reto reLogin">
                <i class="fa fa-angle-double-up"></i>
                <div class="dai">登录</div>
            </div>
            <div class="title">环创签到系统注册</div>
            <div class="content">
                <div class="input-group">
                            <span class="input-group-addon" style="width: 40px;">
                                <i class="fa fa-user-o"></i>
                            </span>
                    <input type="text" name="id" class="form-control" v-model="stdNum" placeholder="学号"
                           aria-describedby="basic-addon1">
                </div>
                <div class="input-group">
                            <span class="input-group-addon" style="width: 40px;">
                                <i class="fa fa-universal-access"></i>
                            </span>
                    <input type="text" name="name" class="form-control" v-model="stdName" placeholder="姓名"
                           aria-describedby="basic-addon1">
                </div>
                <div class="input-group" >
                            <span class="input-group-addon" style="width: 40px;">
                                <i class="fa fa-location-arrow"></i>
                            </span>
                    <div class="ui fluid selection dropdown" style="height: 50px;border-radius: 0px 5px 5px 0px;">
                        <input type="hidden" name="user">
                        <i class="dropdown icon" style="line-height: 25px;"></i>
                        <div class="default text" style="line-height: 25px;color: rgb(173,173,173);">考核方向</div>
                        <div class="menu">
                            <div class="item" data-value="桌面端">
                                桌面端
                            </div>
                            <div class="item" data-value="WEB前端">
                                WEB前端
                            </div>
                            <div class="item" data-value="WEB后端">
                                WEB后端
                            </div>
                            <div class="item" data-value="移动端">
                                移动端
                            </div>
                            <div class="item" data-value="设计端">
                                设计端
                            </div>
                            <div class="item" data-value="运营部">
                                运营部
                            </div>
                        </div>
                    </div>
                </div>
                <div class="input-group">
                            <span class="input-group-addon" style="width: 40px;">
                                <i class="fa fa-lock"></i>
                            </span>
                    <input type="password" name="password" class="form-control" v-model="password" placeholder="密码"
                           aria-describedby="basic-addon1">
                </div>
                <button type="submit" id="regiterButton" class="btn btn-danger" name="sub">注册</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
