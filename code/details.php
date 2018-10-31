<?php
session_start();
require_once(dirname(__DIR__) . '/global_config.php');
require_once(APP_ROOT_PATH . '/db_config.php');
require_once(APP_ROOT_PATH . '/code/checkUser.php');
require_once(APP_ROOT_PATH . '/code/signinfor.php');
checkUser();
$id = $_GET["id"];
$dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$query = $dbh->prepare("SELECT * FROM  users WHERE id=$id");
$query->execute();
$data = $query->fetchAll();
$_SESSION['spename'] = htmlspecialchars($data[0]['name']);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>签到系统详情页</title>
    <script src="tool/jquery.min.js"></script>
    <script src="tool/bootstrap.min.js"></script>
    <script src="tool/vue.min.js"></script>
    <script src="js/index.js"></script>
    <link rel="stylesheet" href="tool/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="tool/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <div class="nav navbar-default">
        <div class="container">
            <div class="row">
                <a href="index.php" class="backIndex">
                    <i class="fa fa-mail-reply"></i>
                    <span>返回首页</span>
                </a>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row" style="text-align: center;">
            <div id="title" style="margin-top: 20px;height: auto">
                <?php
                echo $_SESSION['spename'] . "的详情页面";
                ?>
            </div>
        </div>
        <div class="row">
            <div class="title">
                今日签到
            </div>
            <table class="table table-bordered table-hover" style="text-align: center">
                <thead>
                <tr>
                    <th>姓名</th>
                    <th>早上</th>
                    <th>下午</th>
                    <th>晚上</th>
                    <th>总签到数</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <?php
                        echo $_SESSION['spename'];
                        ?>
                    </td>
                    <td>
                        <?php
                        $lowlimittime = "08:00:00";
                        $uplimittime = "12:00:00";
                        $result = signInfor($_GET['id'], $_SESSION['ymd'], $lowlimittime, $uplimittime);
                        if($result){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }else if($_SESSION['classify'] > 11){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        $lowlimittime = "13:00:00";
                        $uplimittime = "17:00:00";
                        $result = signInfor($_GET['id'], $_SESSION['ymd'], $lowlimittime, $uplimittime);
                        if($result){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }else if($_SESSION['classify'] > 16){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        $lowlimittime = "18:00:00";
                        $uplimittime = "22:00:00";
                        $result = signInfor($_GET['id'], $_SESSION['ymd'], $lowlimittime, $uplimittime);
                        if($result){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }else if($_SESSION['classify'] > 21){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        try {
                            $dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
                            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            date_default_timezone_set("Asia/Shanghai");
                            $monday = strtotime('this week Monday',time());
                            $nextmonday = strtotime('next monday',time());
                            $id = htmlspecialchars($_GET['id']);
                            $sql = "SELECT * FROM signlist WHERE  userid = :id AND time > $monday AND time < $nextmonday";
                            $stmt = $dbh->prepare($sql);
                            $stmt->bindParam(':id', $id);
                            $stmt->execute();
                            $data = $stmt->fetchAll();
                            $count = count($data);
                            echo $count;
                            $dbh = null;
                        } catch (PDOException $e) {
                            print "Error!: " . $e->getMessage() . "<br/>";
                        }
                        ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="title">
                签到详情
            </div>
            <table class="table table-bordered table-hover" style="text-align: center">
                <thead>
                <tr>
                    <th>日期</th>
                    <th>早上</th>
                    <th>下午</th>
                    <th>晚上</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th>周一</th>
                    <th>
                        <?php
                        $lowlimittime = "08:00:00";
                        $uplimittime = "12:00:00";
                        $result = signInfor($_GET["id"], $_SESSION['mondayymd'], $lowlimittime, $uplimittime);
                        if($result){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }else if($_SESSION['today'] > 1 || ($_SESSION['today'] == 1 && $_SESSION['classify'] > 11)){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }
                        ?>
                    </th>
                    <th>
                        <?php
                        $lowlimittime = "13:00:00";
                        $uplimittime = "17:00:00";
                        $result = signInfor($_GET['id'], $_SESSION['mondayymd'], $lowlimittime, $uplimittime);
                        if($result){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }else if($_SESSION['today'] > 1 || ($_SESSION['today'] == 1 && $_SESSION['classify'] > 16)){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }
                        ?>
                    </th>
                    <th>
                        <?php
                        $lowlimittime = "18:00:00";
                        $uplimittime = "22:00:00";
                        $result = signInfor($_GET['id'], $_SESSION['mondayymd'], $lowlimittime, $uplimittime);
                        if($result){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }else if($_SESSION['today'] > 1 || ($_SESSION['today'] == 1 && $_SESSION['classify'] > 21)){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }
                        ?>
                    </th>
                </tr>
                <tr>
                    <th>周二</th>
                    <th>
                        <?php
                        $lowlimittime = "08:00:00";
                        $uplimittime = "12:00:00";
                        $result = signInfor($_GET['id'], $_SESSION['tuesdayymd'], $lowlimittime, $uplimittime);
                        if($result){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }else if($_SESSION['today'] > 2 || ($_SESSION['today'] == 2 && $_SESSION['classify'] > 11)){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }
                        ?>
                    </th>
                    <th>
                        <?php
                        $lowlimittime = "13:00:00";
                        $uplimittime = "17:00:00";
                        $result = signInfor($_GET['id'], $_SESSION['tuesdayymd'], $lowlimittime, $uplimittime);
                        if($result){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }else if($_SESSION['today'] > 2 || ($_SESSION['today'] == 2 && $_SESSION['classify'] > 16)){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }
                        ?>
                    </th>
                    <th>
                        <?php
                        $lowlimittime = "18:00:00";
                        $uplimittime = "22:00:00";
                        $result = signInfor($_GET['id'], $_SESSION['tuesdayymd'], $lowlimittime, $uplimittime);
                        if($result){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }else if($_SESSION['today'] > 2 || ($_SESSION['today'] == 2 && $_SESSION['classify'] > 21)){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }
                        ?>
                    </th>
                </tr>
                <tr>
                    <th>周三</th>
                    <th>
                        <?php
                        $lowlimittime = "08:00:00";
                        $uplimittime = "12:00:00";
                        $result = signInfor($_GET['id'], $_SESSION['wednesdayymd'], $lowlimittime, $uplimittime);
                        if($result){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }else if($_SESSION['today'] > 3 || ($_SESSION['today'] == 3 && $_SESSION['classify'] > 11)){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }
                        ?>
                    </th>
                    <th>
                        <?php
                        $lowlimittime = "13:00:00";
                        $uplimittime = "17:00:00";
                        $result = signInfor($_GET['id'], $_SESSION['wednesdayymd'], $lowlimittime, $uplimittime);
                        if($result){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }else if($_SESSION['today'] > 3 || ($_SESSION['today'] == 3 && $_SESSION['classify'] > 16)){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }
                        ?>
                    </th>
                    <th>
                        <?php
                        $lowlimittime = "18:00:00";
                        $uplimittime = "22:00:00";
                        $result = signInfor($_GET['id'], $_SESSION['wednesdayymd'], $lowlimittime, $uplimittime);
                        if($result){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }else if($_SESSION['today'] > 3 || ($_SESSION['today'] == 3 && $_SESSION['classify'] > 21)){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }
                        ?>
                    </th>
                </tr>
                <tr>
                    <th>周四</th>
                    <th>
                        <?php
                        $lowlimittime = "08:00:00";
                        $uplimittime = "12:00:00";
                        $result = signInfor($_GET['id'], $_SESSION['thursdayymd'], $lowlimittime, $uplimittime);
                        if($result){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }else if($_SESSION['today'] > 4 || ($_SESSION['today'] == 4 && $_SESSION['classify'] > 11)){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }
                        ?>
                    </th>
                    <th>
                        <?php
                        $lowlimittime = "13:00:00";
                        $uplimittime = "17:00:00";
                        $result = signInfor($_GET['id'], $_SESSION['thursdayymd'], $lowlimittime, $uplimittime);
                        if($result){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }else if($_SESSION['today'] > 4 || ($_SESSION['today'] == 4 && $_SESSION['classify'] > 16)){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }
                        ?>
                    </th>
                    <th>
                        <?php
                        $lowlimittime = "18:00:00";
                        $uplimittime = "22:00:00";
                        $result = signInfor($_GET['id'], $_SESSION['thursdayymd'], $lowlimittime, $uplimittime);
                        if($result){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }else if($_SESSION['today'] > 4 || ($_SESSION['today'] == 4 && $_SESSION['classify'] > 21)){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }
                        ?>
                    </th>
                </tr>
                <tr>
                    <th>周五</th>
                    <th>
                        <?php
                        $lowlimittime = "08:00:00";
                        $uplimittime = "12:00:00";
                        $result = signInfor($_GET['id'], $_SESSION['fridayymd'], $lowlimittime, $uplimittime);
                        if($result){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }else if($_SESSION['today'] > 5 || ($_SESSION['today'] == 5 && $_SESSION['classify'] > 11)){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }
                        ?>
                    </th>
                    <th>
                        <?php
                        $lowlimittime = "13:00:00";
                        $uplimittime = "17:00:00";
                        $result = signInfor($_GET['id'], $_SESSION['fridayymd'], $lowlimittime, $uplimittime);
                        if($result){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }else if($_SESSION['today'] > 5 || ($_SESSION['today'] == 5 && $_SESSION['classify'] > 16)){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }
                        ?>
                    </th>
                    <th>
                        <?php
                        $lowlimittime = "18:00:00";
                        $uplimittime = "22:00:00";
                        $result = signInfor($_GET['id'], $_SESSION['fridayymd'], $lowlimittime, $uplimittime);
                        if($result){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }else if($_SESSION['today'] > 5 || ($_SESSION['today'] == 5 && $_SESSION['classify'] > 21)){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }
                        ?>
                    </th>
                </tr>
                <tr>
                    <th>周六</th>
                    <th>
                        <?php
                        $lowlimittime = "08:00:00";
                        $uplimittime = "12:00:00";
                        $result = signInfor($_GET['id'], $_SESSION['saturdayymd'], $lowlimittime, $uplimittime);
                        if($result){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }else if($_SESSION['today'] > 6 || ($_SESSION['today'] == 6 && $_SESSION['classify'] > 11)){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }
                        ?>
                    </th>
                    <th>
                        <?php
                        $lowlimittime = "13:00:00";
                        $uplimittime = "17:00:00";
                        $result = signInfor($_GET['id'], $_SESSION['saturdayymd'], $lowlimittime, $uplimittime);
                        if($result){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }else if($_SESSION['today'] > 6 || ($_SESSION['today'] == 6 && $_SESSION['classify'] > 16)){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }
                        ?>
                    </th>
                    <th>
                        <?php
                        $lowlimittime = "18:00:00";
                        $uplimittime = "22:00:00";
                        $result = signInfor($_GET['id'], $_SESSION['saturdayymd'], $lowlimittime, $uplimittime);
                        if($result){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }else if($_SESSION['today'] > 6 || ($_SESSION['today'] == 6 && $_SESSION['classify'] > 21)){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }
                        ?>
                    </th>
                </tr>
                <tr>
                    <th>周日</th>
                    <th>
                        <?php
                        $lowlimittime = "08:00:00";
                        $uplimittime = "12:00:00";
                        $result = signInfor($_GET['id'], $_SESSION['sundayymd'], $lowlimittime, $uplimittime);
                        if($result){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }else if($_SESSION['today'] > 7 || ($_SESSION['today'] == 7 && $_SESSION['classify'] > 11)){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }
                        ?>
                    </th>
                    <th>
                        <?php
                        $lowlimittime = "13:00:00";
                        $uplimittime = "17:00:00";
                        $result = signInfor($_GET['id'], $_SESSION['sundayymd'], $lowlimittime, $uplimittime);
                        if($result){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }else if($_SESSION['today'] > 7 || ($_SESSION['today'] == 7 && $_SESSION['classify'] > 16)){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }
                        ?>
                    </th>
                    <th>
                        <?php
                        $lowlimittime = "18:00:00";
                        $uplimittime = "22:00:00";
                        $result = signInfor($_GET['id'], $_SESSION['sundayymd'], $lowlimittime, $uplimittime);
                        if($result){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }else if($_SESSION['today'] > 7 || ($_SESSION['today'] == 7 && $_SESSION['classify'] > 21)){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }
                        ?>
                    </th>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>