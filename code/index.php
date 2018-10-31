<?php
session_start();
require_once(dirname(__DIR__) . '/global_config.php');
require_once(APP_ROOT_PATH . '/db_config.php');
require_once(APP_ROOT_PATH . '/code/checkUser.php');
require_once(APP_ROOT_PATH . '/code/signinfor.php');
require_once(APP_ROOT_PATH . '/code/sort.php');
checkUser();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>环创签到系统</title>
    <script src="tool/jquery.min.js"></script>
    <script src="tool/bootstrap.min.js"></script>
    <script src="tool/vue.min.js"></script>
    <script src="js/index.js"></script>
    <link rel="stylesheet" href="tool/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="tool/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css">
</head>
<body style="margin-bottom: 50px">
<div class="nav navbar-default">
    <div class="container">
        <div class="row">
            <a href="logoout.php" class="backIndex">
                <i class="fa fa-mail-reply"></i>
                <span>注销</span>
            </a>
            <div class="name" style="float: right">
                <?php
                echo '<div >' . htmlspecialchars($_SESSION['name']) . '</div>' . '</a>';
                ?>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row" style="text-align: center;">
        <div id="title">
            环创签到系统
        </div>
    </div>
    <div class="row" id="signPart" style="text-align: center;margin-bottom: 40px">
        <button id="sign" v-bind:class="{ signed : signed }" type="submit" name="sign">
            <span v-show="!(loading||signed)">签到</span>
            <i v-show="loading&&!signed" class="fa fa-spinner fa-spin" style="display: none;font-size:50px;"></i>
            <i v-show="signed" class="fa fa-check" style="display: none;font-size: 70px;"></i>
        </button>
    </div>
    <div class="row">
        <div class="title">
            说明
        </div>
        <div class="col-md-12">
            <div class="content">
                <ul class="time">
                    <li>早上时间：8:00-12:00</li>
                    <li>下午时间：13:00-17:00</li>
                    <li>晚上时间：18:00-22:00</li>
                    <li>最少次数：八次</li>
                </ul>
            </div>
        </div>
        <div class="col-md-12">
            <div class="content">
                <ul class="time">
                    <li>成员姓名：
                        <?php
                        if (isset($_SESSION['name'])) {
                            echo htmlspecialchars($_SESSION['name']) . '</a>';
                        }
                        ?>
                    </li>
                    <li>本周签到：
                        <?php
                        try {
                            $dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
                            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            date_default_timezone_set("Asia/Shanghai");
                            $monday = strtotime('this week Monday',time());
                            $nextmonday = strtotime('next monday',time());
                            $id = htmlspecialchars($_SESSION['id']);
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
                        ?>次
                    </li>
                    <li>本时段是：
                        <?php
                        if ($_SESSION['classify'] > 7 && $_SESSION['classify'] < 12) {
                            echo "早上";
                        } else if ($_SESSION['classify'] > 12 && $_SESSION['classify'] < 17) {
                            echo "下午";
                        } else if ($_SESSION['classify'] > 17 && $_SESSION['classify'] < 22) {
                            echo "晚上";
                        } else {
                            echo "非签到时段";
                        }
                        ?>
                    </li>
                    <li>签到状态：
                        <?php
                        $note = 0;
                        date_default_timezone_set("Asia/Shanghai");
                        if ($_SESSION['classify'] > 7 && $_SESSION['classify'] < 12) {
                            $lowlimittime = "08:00:00";
                            $uplimittime = "12:00:00";
                            $note = 1;
                        } else if ($_SESSION['classify'] > 12 && $_SESSION['classify'] < 17) {
                            $lowlimittime = "13:00:00";
                            $uplimittime = "17:00:00";
                            $note = 1;
                        } else if ($_SESSION['classify'] > 17 && $_SESSION['classify'] < 22) {
                            $lowlimittime = "18:00:00";
                            $uplimittime = "22:00:00";
                            $note = 1;
                        } else {
                            echo "未签到";
                        }
                        if ($note) {
                            $result = signInfor($_SESSION['id'], $_SESSION['ymd'], $lowlimittime, $uplimittime);
                            if ($result) {
                                echo "已签到";
                            } else {
                                echo "未签到";
                            }
                        }
                        ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="title">
            今日签到
        </div>
        <table class="table table-bordered table-hover" style="text-align: center">
            <thead>
            <tr>
                <th>早上</th>
                <th>下午</th>
                <th>晚上</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <?php
                    $lowlimittime = "08:00:00";
                    $uplimittime = "12:00:00";
                    $result = signInfor($_SESSION['id'], $_SESSION['ymd'], $lowlimittime, $uplimittime);
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
                    $result = signInfor($_SESSION['id'], $_SESSION['ymd'], $lowlimittime, $uplimittime);
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
                    $result = signInfor($_SESSION['id'], $_SESSION['ymd'], $lowlimittime, $uplimittime);
                    if($result){
                        echo '<i class="fa fa-check yes">' . "</i>";
                    }else if($_SESSION['classify'] > 21){
                        echo '<i class="fa fa-close no">' . "</i>";
                    }
                    ?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="title">本周签到</div>
        <table class="table table-bordered table-striped table-hover" style="text-align: center">
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
                    $result = signInfor($_SESSION['id'], $_SESSION['mondayymd'], $lowlimittime, $uplimittime);
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
                    $result = signInfor($_SESSION['id'], $_SESSION['mondayymd'], $lowlimittime, $uplimittime);
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
                    $result = signInfor($_SESSION['id'], $_SESSION['mondayymd'], $lowlimittime, $uplimittime);
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
                    $result = signInfor($_SESSION['id'], $_SESSION['tuesdayymd'], $lowlimittime, $uplimittime);
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
                    $result = signInfor($_SESSION['id'], $_SESSION['tuesdayymd'], $lowlimittime, $uplimittime);
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
                    $result = signInfor($_SESSION['id'], $_SESSION['tuesdayymd'], $lowlimittime, $uplimittime);
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
                    $result = signInfor($_SESSION['id'], $_SESSION['wednesdayymd'], $lowlimittime, $uplimittime);
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
                    $result = signInfor($_SESSION['id'], $_SESSION['wednesdayymd'], $lowlimittime, $uplimittime);
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
                    $result = signInfor($_SESSION['id'], $_SESSION['wednesdayymd'], $lowlimittime, $uplimittime);
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
                    $result = signInfor($_SESSION['id'], $_SESSION['thursdayymd'], $lowlimittime, $uplimittime);
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
                    $result = signInfor($_SESSION['id'], $_SESSION['thursdayymd'], $lowlimittime, $uplimittime);
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
                    $result = signInfor($_SESSION['id'], $_SESSION['thursdayymd'], $lowlimittime, $uplimittime);
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
                    $result = signInfor($_SESSION['id'], $_SESSION['fridayymd'], $lowlimittime, $uplimittime);
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
                    $result = signInfor($_SESSION['id'], $_SESSION['fridayymd'], $lowlimittime, $uplimittime);
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
                    $result = signInfor($_SESSION['id'], $_SESSION['fridayymd'], $lowlimittime, $uplimittime);
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
                    $result = signInfor($_SESSION['id'], $_SESSION['saturdayymd'], $lowlimittime, $uplimittime);
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
                    $result = signInfor($_SESSION['id'], $_SESSION['saturdayymd'], $lowlimittime, $uplimittime);
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
                    $result = signInfor($_SESSION['id'], $_SESSION['saturdayymd'], $lowlimittime, $uplimittime);
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
                    $result = signInfor($_SESSION['id'], $_SESSION['sundayymd'], $lowlimittime, $uplimittime);
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
                    $result = signInfor($_SESSION['id'], $_SESSION['sundayymd'], $lowlimittime, $uplimittime);
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
                    $result = signInfor($_SESSION['id'], $_SESSION['sundayymd'], $lowlimittime, $uplimittime);
                    if($result){
                        echo '<i class="fa fa-check yes">' . "</i>";
                    }else if($_SESSION['today'] > 7 || ($_SESSION['today'] == 7 && $_SESSION['classify'] > 21)){
                        echo '<i class="fa fa-close no">' . "</i>";
                    }
                    ?>
                </th>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="title">
            签到排行
        </div>
        <table class="table table-bordered table-striped table-hover" style="text-align: center">
            <thead>
            <tr>
                <th>姓名</th>
                <th>方向</th>
                <th>早上</th>
                <th>下午</th>
                <th>晚上</th>
                <th>总签到数</th>
                <th>详情</th>
            </tr>
            </thead>
            <tbody>
            <?php
            require_once(dirname(__DIR__) . '/global_config.php');
            require_once(APP_ROOT_PATH . '/db_config.php');
            $dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $time = $_SESSION['lastmonday'];
            $sql = "SELECT * FROM  users WHERE time > $time";
            $stmt = $dbh->prepare($sql);
            $id = htmlspecialchars($_SESSION['id']);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $data = $stmt->fetchAll();

            $i = 0;
            while ($i < count($data)) {
                try {
                    date_default_timezone_set("Asia/Shanghai");
                    $monday = strtotime('this week Monday', time());
                    $nextmonday = strtotime('next monday', time());
                    $dataid = htmlspecialchars($data[$i]['id']);
                    $sqltwo = "SELECT * FROM signlist WHERE  userid = :id AND time > $monday AND time < $nextmonday";
                    $stmtwo = $dbh->prepare($sqltwo);
                    $stmtwo->bindParam(':id', $dataid);
                    $stmtwo->execute();
                    $datatwo = $stmtwo->fetchAll();
                    $count = count($datatwo);
                    $data[$i]['count'] = $count;
                    $i++;
                } catch (PDOException $e) {
                    print "Error!: " . $e->getMessage() . "<br/>";
                }
            }
            $data = quickSort($data);
            $i = 0;
            while ($i < count($data)) {
                echo "<tr>" . "<td>" . htmlspecialchars($data[$i]['name']) . "</td>";
                echo "<td>" . htmlspecialchars($data[$i]['direction']) . "</td>";
                $lowlimittime = "08:00:00";
                $uplimittime = "12:00:00";
                $result = signInfor(htmlspecialchars($data[$i]['id']), $_SESSION['ymd'], $lowlimittime, $uplimittime);
                if ($result) {
                    echo "<td>" . '<i class="fa fa-check yes">' . "</i>" . "</td>";
                } else if ($_SESSION['classify'] > 11) {
                    echo "<td>" . '<i class="fa fa-close no">' . "</i>" . "</td>";
                }else {
                    echo "<td>" . "</td>";
                }
                $lowlimittime = "13:00:00";
                $uplimittime = "17:00:00";
                $result = signInfor(htmlspecialchars($data[$i]['id']), $_SESSION['ymd'], $lowlimittime, $uplimittime);
                if ($result) {
                    echo "<td>" . '<i class="fa fa-check yes">' . "</i>" . "</td>";
                } else if ($_SESSION['classify'] > 16) {
                    echo "<td>" . '<i class="fa fa-close no">' . "</i>" . "</td>";
                }else {
                    echo "<td>" . "</td>";
                }
                $lowlimittime = "18:00:00";
                $uplimittime = "22:00:00";
                $result = signInfor(htmlspecialchars($data[$i]['id']), $_SESSION['ymd'], $lowlimittime, $uplimittime);
                if ($result) {
                    echo "<td>" . '<i class="fa fa-check yes">' . "</i>" . "</td>";
                } else if ($_SESSION['classify'] > 21) {
                    echo "<td>" . '<i class="fa fa-close no">' . "</i>" . "</td>";
                }else {
                    echo "<td>" . "</td>";
                }
                echo "<td>" . $data[$i]['count'] . "</td>";
                echo "<td>" . "<a href='details.php?id=" . $data[$i]['id'] . "'>" . "进入" . "</a>" . "</td>" . "</tr>";
                $i++;
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>