<?php
session_start();
require_once(dirname(__DIR__) . '/global_config.php');
require_once(APP_ROOT_PATH . '/db_config.php');
require_once(APP_ROOT_PATH . '/code/checkUser.php');
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
<body style="margin-bottom: 50px" >
<div class="nav navbar-default">
    <div class="container">
        <div class="row">
            <a href="logoout.php" class="backIndex">
                <i class="fa fa-mail-reply"></i>
                <span>注销</span>
            </a>
            <div class="name" style="float: right">

                     <?php
                     if (isset($_SESSION['name'])) {
                         echo '<div >' . htmlspecialchars($_SESSION['name']) . '</div>' . '</a>';
                     } else {
                         echo '<a href="login.php" >' . '<div >' . "登录" . '</div>' . "</a>";
                     }
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
        <button id="sign" v-bind:class="{ signed : signed }" type="submit" name="sign" >
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
                    <li>早上时间：6:00-12:00</li>
                    <li>下午时间：12:00-17:00</li>
                    <li>晚上时间：17:00-22:00</li>
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
                            echo htmlspecialchars($_SESSION['name']). '</a>';
                        }
                        ?>
                    </li>
                    <li>本周签到：
                        <?php
                        if (isset($_SESSION['times'])) {
                            echo htmlspecialchars($_SESSION['times']). '</a>';
                        }
                        ?>次
                    </li>
                    <li>本时段是：
                        <?php
                        if($_SESSION['classify'] > 5 && $_SESSION['classify'] < 12){
                            echo "早上";
                        }else if($_SESSION['classify'] > 11 && $_SESSION['classify'] < 17){
                            echo "下午";
                        }else if($_SESSION['classify'] > 16 && $_SESSION['classify'] < 22){
                            echo "晚上";
                        }
                        else{
                            echo "非签到时段";
                        }
                        ?>
                    </li>
                    <li>签到状态：
                        <?php
                        if($_SESSION['nowstatus'] == 2 && $_SESSION['classify'] < 22 && $_SESSION['classify'] > 5){
                        echo "已签到";
                        }else{
                            echo "未签到";
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
                    $count = $_SESSION['count'];
                    $notes = $_SESSION['notes'];

                    if($_SESSION['classify'] > 5 && $_SESSION['classify'] < 12){
                        if($_SESSION['nowstatus'] == 2){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }
                    } else if($_SESSION['classify'] > 11 && $_SESSION['classify'] < 17){
                        $count = $count - 1;
                        $laststatus=((int)substr("$notes","$count",1));

                        if($laststatus == 1){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }else if($laststatus == 2){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }
                    } else if($_SESSION['classify'] > 16){
                        $count = $count - 2;
                        $lasteststatus=((int)substr("$notes","$count",1));

                        if($lasteststatus == 1){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }else if($lasteststatus == 2){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }
                    }
                    ?>
                </td>
                <td>
                    <?php
                    $count = $_SESSION['count'];
                    $notes = $_SESSION['notes'];

                    if($_SESSION['classify'] > 11 && $_SESSION['classify'] < 17){
                        if($_SESSION['nowstatus'] == 2){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }
                    } else if($_SESSION['classify'] > 16){
                        $count = $count - 1;
                        $laststatus=((int)substr("$notes","$count",1));

                        if($laststatus == 1){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }else if($laststatus == 2){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }
                    }

                    ?>
                </td>
                <td>
                    <?php
                    if($_SESSION['classify'] > 16){
                        if($_SESSION['nowstatus'] == 2){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        } else if($_SESSION['classify'] > 21 && $_SESSION['nowstatus'] == 1){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }
                    }
                    ?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="title">本周签到</div>
        <table class="table table-bordered table-striped table-hover"style="text-align: center">
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
                    if(($_SESSION['today'] > 0) || ($_SESSION['today'] == 0 && $_SESSION['classify'] > 5)){
                        $count = 0;//得出下标
                        $notes = $_SESSION['notes'];
                        $status=((int)substr("$notes","$count",1));

                        if(($_SESSION['today'] > 0 && $status == 1) ||  $_SESSION['classify'] > 11 && $status == 1){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }else if($status == 2){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }
                    }
                    ?>
                </th>
                <th>
                    <?php
                    if(($_SESSION['today'] > 0) || ($_SESSION['today'] == 0 && $_SESSION['classify'] > 11)){
                        $count = 1;//得出下标
                        $notes = $_SESSION['notes'];
                        $status=((int)substr("$notes","$count",1));

                        if(($_SESSION['today'] > 0 && $status == 1) || $_SESSION['classify'] > 16 && $status == 1){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }else if($status == 2){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }
                    }
                    ?>
                </th>
                <th>
                    <?php
                    if(($_SESSION['today'] > 0) || ($_SESSION['today'] == 0 && $_SESSION['classify'] > 16)){
                        $count = 2;//得出下标
                        $notes = $_SESSION['notes'];
                        $status=((int)substr("$notes","$count",1));

                        if(($_SESSION['today'] > 0 && $status == 1) || $_SESSION['classify'] > 21 && $status == 1){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }else if($status == 2){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }
                    }
                    ?>
                </th>
            </tr>
            <tr>
                <th>周二</th>
                <th>
                    <?php
                    if(($_SESSION['today'] > 1) || ($_SESSION['today'] == 1 && $_SESSION['classify'] > 5)){
                        $count = 3;//得出下标
                        $notes = $_SESSION['notes'];
                        $status=((int)substr("$notes","$count",1));

                        if(($_SESSION['today'] > 1 && $status == 1) || $_SESSION['classify'] > 11 && $status == 1){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }else if($status == 2){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }
                    }
                    ?>
                </th>
                <th>
                    <?php
                    if(($_SESSION['today'] > 1) || ($_SESSION['today'] == 1 && $_SESSION['classify'] > 11)){
                        $count = 4;//得出下标
                        $notes = $_SESSION['notes'];
                        $status=((int)substr("$notes","$count",1));

                        if(($_SESSION['today'] > 1 && $status == 1) || $_SESSION['classify'] > 16 && $status == 1){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }else if($status == 2){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }
                    }
                    ?>
                </th>
                <th>
                    <?php
                    if(($_SESSION['today'] > 1) || ($_SESSION['today'] == 1 && $_SESSION['classify'] > 16)){
                        $count = 5;//得出下标
                        $notes = $_SESSION['notes'];
                        $status=((int)substr("$notes","$count",1));

                        if(($_SESSION['today'] > 1 && $status == 1) || $_SESSION['classify'] > 21 && $status == 1){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }else if($status == 2){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }
                    }
                    ?>
                </th>
            </tr>
            <tr>
                <th>周三</th>
                <th>
                    <?php
                    if(($_SESSION['today'] > 2) || ($_SESSION['today'] == 2 && $_SESSION['classify'] > 5)){
                        $count = 6;//得出下标
                        $notes = $_SESSION['notes'];
                        $status=((int)substr("$notes","$count",1));

                        if(($_SESSION['today'] > 2 && $status == 1) || $_SESSION['classify'] > 11 && $status == 1){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }else if($status == 2){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }
                    }
                    ?>
                </th>
                <th>
                    <?php
                    if(($_SESSION['today'] > 2) || ($_SESSION['today'] == 2 && $_SESSION['classify'] > 11)){
                        $count = 7;//得出下标
                        $notes = $_SESSION['notes'];
                        $status=((int)substr("$notes","$count",1));

                        if(($_SESSION['today'] > 2 && $status == 1) || $_SESSION['classify'] > 16 && $status == 1){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }else if($status == 2){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }
                    }
                    ?>
                </th>
                <th>
                    <?php
                    if(($_SESSION['today'] > 2) || ($_SESSION['today'] == 2 && $_SESSION['classify'] > 16)){
                        $count = 8;//得出下标
                        $notes = $_SESSION['notes'];
                        $status=((int)substr("$notes","$count",1));

                        if(($_SESSION['today'] > 2 && $status == 1) || $_SESSION['classify'] > 21 && $status == 1){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }else if($status == 2){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }
                    }
                    ?>
                </th>
            </tr>
            <tr>
                <th>周四</th>
                <th>
                    <?php
                    if(($_SESSION['today'] > 3) || ($_SESSION['today'] == 3 && $_SESSION['classify'] > 5)){
                        $count = 9;//得出下标
                        $notes = $_SESSION['notes'];
                        $status=((int)substr("$notes","$count",1));

                        if(($_SESSION['today'] > 3 && $status == 1) || $_SESSION['classify'] > 11 && $status == 1){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }else if($status == 2){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }
                    }
                    ?>
                </th>
                <th>
                    <?php
                    if(($_SESSION['today'] > 3) || ($_SESSION['today'] == 3 && $_SESSION['classify'] > 11)){
                        $count = 10;//得出下标
                        $notes = $_SESSION['notes'];
                        $status=((int)substr("$notes","$count",1));

                        if(($_SESSION['today'] > 3 && $status == 1) || $_SESSION['classify'] > 16 && $status == 1){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }else if($status == 2){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }
                    }
                    ?>
                </th>
                <th>
                    <?php
                    if(($_SESSION['today'] > 3) || ($_SESSION['today'] == 3 && $_SESSION['classify'] > 16)){
                        $count = 11;//得出下标
                        $notes = $_SESSION['notes'];
                        $status=((int)substr("$notes","$count",1));

                        if(($_SESSION['today'] > 3 && $status == 1) || $_SESSION['classify'] > 21 && $status == 1){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }else if($status == 2){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }
                    }
                    ?>
                </th>
            </tr>
            <tr>
                <th>周五</th>
                <th>
                    <?php
                    if(($_SESSION['today'] > 4) || ($_SESSION['today'] == 4 && $_SESSION['classify'] > 5)){
                        $count = 12;//得出下标
                        $notes = $_SESSION['notes'];
                        $status=((int)substr("$notes","$count",1));

                        if(($_SESSION['today'] > 4 && $status == 1) || $_SESSION['classify'] > 11 && $status == 1){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }else if($status == 2){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }
                    }
                    ?>
                </th>
                <th>
                    <?php
                    if(($_SESSION['today'] > 4) || ($_SESSION['today'] == 4 && $_SESSION['classify'] > 11)){
                        $count = 13;//得出下标
                        $notes = $_SESSION['notes'];
                        $status=((int)substr("$notes","$count",1));

                        if(($_SESSION['today'] > 4 && $status == 1) || $_SESSION['classify'] > 16 && $status == 1){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }else if($status == 2){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }
                    }
                    ?>
                </th>
                <th>
                    <?php
                    if(($_SESSION['today'] > 4) || ($_SESSION['today'] == 4 && $_SESSION['classify'] > 16)){
                        $count = 14;//得出下标
                        $notes = $_SESSION['notes'];
                        $status=((int)substr("$notes","$count",1));

                        if(($_SESSION['today'] > 4 && $status == 1) || $_SESSION['classify'] > 21 && $status == 1){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }else if($status == 2){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }
                    }
                    ?>
                </th>
            </tr>
            <tr>
                <th>周六</th>
                <th>
                    <?php
                    if(($_SESSION['today'] > 5) || ($_SESSION['today'] == 5 && $_SESSION['classify'] > 5)){
                        $count = 15;//得出下标
                        $notes = $_SESSION['notes'];
                        $status=((int)substr("$notes","$count",1));

                        if(($_SESSION['today'] > 5 && $status == 1) || $_SESSION['classify'] > 11 && $status == 1){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }else if($status == 2){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }
                    }
                    ?>
                </th>
                <th>
                    <?php
                    if(($_SESSION['today'] > 5) || ($_SESSION['today'] == 5 && $_SESSION['classify'] > 11)){
                        $count = 16;//得出下标
                        $notes = $_SESSION['notes'];
                        $status=((int)substr("$notes","$count",1));

                        if(($_SESSION['today'] > 5 && $status == 1) || $_SESSION['classify'] > 16 && $status == 1){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }else if($status == 2){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }
                    }
                    ?>
                </th>
                <th>
                    <?php
                    if(($_SESSION['today'] > 5) || ($_SESSION['today'] == 5 && $_SESSION['classify'] > 16)){
                        $count = 17 ;//得出下标
                        $notes = $_SESSION['notes'];
                        $status=((int)substr("$notes","$count",1));

                        if(($_SESSION['today'] > 5 && $status == 1) || $_SESSION['classify'] > 21 && $status == 1){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }else if($status == 2){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }
                    }
                    ?>
                </th>
            </tr>
            <tr>
                <th>周日</th>
                <th>
                    <?php
                    if(($_SESSION['today'] > 6) || ($_SESSION['today'] == 6 && $_SESSION['classify'] > 5)){
                        $count = 18;//得出下标
                        $notes = $_SESSION['notes'];
                        $status=((int)substr("$notes","$count",1));

                        if(($_SESSION['today'] > 6 && $status == 1) || $_SESSION['classify'] > 11 && $status == 1){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }else if($status == 2){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }
                    }
                    ?>
                </th>
                <th>
                    <?php
                    if(($_SESSION['today'] > 6) || ($_SESSION['today'] == 6 && $_SESSION['classify'] > 11)){
                        $count = 19;//得出下标
                        $notes = $_SESSION['notes'];
                        $status=((int)substr("$notes","$count",1));

                        if(($_SESSION['today'] > 6 && $status == 1) || $_SESSION['classify'] > 16 && $status == 1){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }else if($status == 2){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }
                    }
                    ?>
                </th>
                <th>
                    <?php
                    if(($_SESSION['today'] > 6) || ($_SESSION['today'] == 6 && $_SESSION['classify'] > 16)){
                        $count = 20;//得出下标
                        $notes = $_SESSION['notes'];
                        $status=((int)substr("$notes","$count",1));

                        if(($_SESSION['today'] > 6 && $status == 1) || $_SESSION['classify'] > 21 && $status == 1){
                            echo '<i class="fa fa-close no">' . "</i>";
                        }else if($status == 2){
                            echo '<i class="fa fa-check yes">' . "</i>";
                        }
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
            $query = $dbh->prepare("SELECT * FROM  students ORDER BY times DESC");
            $query->execute();
            $data = $query->fetchAll();

            //显示记录
            $i = 0;

            while ($i < count($data)) {
                echo "<tr>". "<td>".htmlspecialchars($data[$i]['name'])."</td>";
                echo "<td>".htmlspecialchars($data[$i]['direction'])."</td>";

                $count = $_SESSION['count'];
                $notes = $data[$i]['notes'];
                $nowstatus=((int)substr("$notes","$count",1));

                if($nowstatus < 1 || $nowstatus > 2){
                    echo "<td>" . "数据错误". "</td>";
                    echo "<td>" . "数据错误". "</td>";
                    echo "<td>" . "数据错误". "</td>";
                }else if($_SESSION['classify'] > 5 && $_SESSION['classify'] < 12){
                    if($nowstatus == 1){
                        echo "<td>" . "</td>";
                        echo "<td>" . "</td>";
                        echo "<td>" . "</td>";
                    }else if($nowstatus == 2){
                        echo "<td>" . "<i class='fa fa-check yes'>" . "</i>" . "</td>";
                        echo "<td>" . "</td>";
                        echo "<td>" . "</td>";
                    }
                } else if($_SESSION['classify'] > 11 && $_SESSION['classify'] < 17){
                    $count = $count - 1;
                    $laststatus=((int)substr("$notes","$count",1));

                    if($laststatus == 1){
                        echo "<td>" ."<i class='fa fa-close no'>" . "</i>" . "</td>";
                    }else if($laststatus == 2){
                        echo "<td>" ."<i class='fa fa-check yes'>" . "</i>" . "</td>";
                    }
                    if($nowstatus == 1){
                        echo "<td>" ."</td>";
                        echo "<td>" ."</td>";
                    }else if($nowstatus == 2){
                        echo "<td>" ."<i class='fa fa-check yes'>" . "</i>" . "</td>";
                        echo "<td>" ."</td>";
                    }
                } else if($_SESSION['classify'] > 16 && $_SESSION['classify'] < 22){
                    $count = $count - 2;
                    $lasteststatus=((int)substr("$notes","$count",1));

                    $count = $count + 1;
                    $laststatus=((int)substr("$notes","$count",1));

                    if($lasteststatus == 1){
                        echo "<td>" ."<i class='fa fa-close no'>" . "</i>" . "</td>";
                    }else if($lasteststatus == 2){
                        echo "<td>" ."<i class='fa fa-check yes'>" . "</i>" . "</td>";
                    }

                    if($laststatus == 1){
                        echo "<td>" ."<i class='fa fa-close no'>" . "</i>" . "</td>";
                    }else if($laststatus == 2){
                        echo "<td>" ."<i class='fa fa-check yes'>" . "</i>" . "</td>";
                    }

                    if($nowstatus == 1){
                        echo "<td>" . "</td>";
                    }else if($nowstatus == 2){
                        echo "<td>" ."<i class='fa fa-check yes'>" . "</i>" . "</td>";
                    }
                } else if($_SESSION['classify'] > 21){
                    $count = $count - 2;
                    $lasteststatus=((int)substr("$notes","$count",1));

                    $count = $count + 1;
                    $laststatus=((int)substr("$notes","$count",1));

                    if($lasteststatus == 1){
                        echo "<td>" ."<i class='fa fa-close no'>" . "</i>" . "</td>";
                    }else if($lasteststatus == 2){
                        echo "<td>" ."<i class='fa fa-check yes'>" . "</i>" . "</td>";
                    }

                    if($laststatus == 1){
                        echo "<td>" ."<i class='fa fa-close no'>" . "</i>" . "</td>";
                    }else if($laststatus == 2){
                        echo "<td>" ."<i class='fa fa-check yes'>" . "</i>" . "</td>";
                    }

                    if($nowstatus == 1){
                        echo "<td>" ."<i class='fa fa-close no'>" . "</i>" . "</td>";
                    }else if($nowstatus == 2){
                        echo "<td>" ."<i class='fa fa-check yes'>" . "</i>" . "</td>";
                    }
                }

                echo "<td>".htmlspecialchars($data[$i]['times'])."</td>";
                echo "<td>"."<a href='details.php?id=" . $data[$i]['id'] . "'>"."进入"."</a>"."</td>"."</tr>";
                $i++;
            }

            ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>