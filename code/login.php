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
            $sql = 'SELECT * FROM students WHERE id = :id AND password = SHA(:password)';
            $stmt = $dbh->prepare($sql);
            $k = htmlspecialchars($_POST['id']);
            $stmt->bindParam(':id', $k);
            $stmt->bindParam(':password', $_POST['password']);
            if ($stmt->execute()) {
                if ($row = $stmt->fetch()) {
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['name'] = $row['name'];
                    $_SESSION['times'] = $row['times'];
                    $_SESSION['notes'] = $row['notes'];

                    date_default_timezone_set("Asia/Shanghai");
                    $today=date("w");//判断星期几,周日为0，再减去1
                    if($today == 0)
                    {
                        $today = 6;
                    } else{
                        $today = $today - 1;
                    }

                    $date=date('H:i:s');
                    $classify=((int)substr("$date",0,2));

                    if($classify >= 0){
                        $now = 0;
                    }

                    if($classify > 11 && $classify < 17){
                        $now = 1;
                    }

                    if($classify > 16){
                        $now = 2;
                    }

                    $count = $today * 3 + $now;//计算出下标
                    $notes = $_SESSION['notes'];
                    $nowstatus=((int)substr("$notes","$count",1));
                    $_SESSION['nowstatus'] = $nowstatus;
                    $_SESSION['classify'] = $classify;
                    $_SESSION['today'] = $today;
                    $_SESSION['count'] = $count;

                    $a = date('Y-m-d');
                    $b=((int)substr("$a",2,2));
                    $c=((int)substr("$a",5,2));
                    $d=((int)substr("$a",8,2));
                    $numtime = $b * 10000 + $c * 100 + $d;

                    if($numtime > $row['sunday']){//自动更新数据库
                        $sunday = $numtime + (6 - $today);
                        $notes = "111111111111111111111";
                        $times = 0;

                        $sql2 = 'UPDATE students SET notes = :notes , times = :times , sunday = :sunday WHERE id = :id';
                        $stmt2 = $dbh->prepare($sql2);
                        $stmt2->bindParam(':id', $k);
                        $stmt2->bindParam(':notes', $notes);
                        $stmt2->bindParam(':times', $times);
                        $stmt2->bindParam(':sunday', $sunday);
                        $stmt2->execute();

                        $_SESSION['notes'] = "111111111111111111111";
                        $_SESSION['times'] = 0;
                    }

                    $url = 'index.php';
                    header('Location: ' . $url);
                } else {
                    echo "<script>alert('账号或密码错误!')</script>";
                }
            } else {
                echo '服务器异常' . $stmt->errorCode();
            }

            $dbh = null;

        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
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
            <div class="title">欢迎登陆环创签到系统</div>
            <div class="content">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                    <div class="input-group">
                            <span class="input-group-addon" style="width: 40px;">
                                <i class="fa fa-user-o"></i>
                            </span>
                        <input type="text" name="id" class="form-control" placeholder="学号" aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group">
                            <span class="input-group-addon" style="width: 40px;">
                                <i class="fa fa-lock"></i>
                            </span>
                        <input type="password" name="password" class="form-control" placeholder="密码" aria-describedby="basic-addon1">
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
                            <span class="input-group-addon"  style="width: 40px;">
                                <i class="fa fa-user-o"></i>
                            </span>
                        <input type="text" name="id" class="form-control" v-model="stdNum" placeholder="学号" aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group" >
                            <span class="input-group-addon" style="width: 40px;">
                                <i class="fa fa-child"></i>
                            </span>
                        <input type="text" name="name" class="form-control " v-model="stdName" placeholder="姓名" aria-describedby="basic-addon1">
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
                    <div class="input-group" >
                            <span class="input-group-addon" style="width: 40px;">
                                <i class="fa fa-lock"></i>
                            </span>
                        <input type="password" name="password" class="form-control" v-model="password" placeholder="密码" aria-describedby="basic-addon1">
                    </div>
                    <button type="submit" id="regiterButton" class="btn btn-danger" name="sub">注册</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
