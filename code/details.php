<?php
session_start();

require_once(dirname(__DIR__) . '/global_config.php');
require_once(APP_ROOT_PATH . '/db_config.php');
require_once(APP_ROOT_PATH . '/code/checkUser.php');
checkUser();

$id = $_GET["id"];

$dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$query = $dbh->prepare("SELECT * FROM  students WHERE id=$id");
$query->execute();
$data = $query->fetchAll();

$_SESSION['spename'] = htmlspecialchars($data[0]['name']);
$_SESSION['spenotes'] = $data[0]['notes'];
$_SESSION['spetimes'] = $data[0]['times'];

$count = $_SESSION['count'];
$notes = $_SESSION['spenotes'];
$spenowstatus=((int)substr("$notes","$count",1));
$_SESSION['spenowstatus'] = $spenowstatus;

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
                        $count = $_SESSION['count'];
                        $notes = $_SESSION['spenotes'];

                        if($_SESSION['classify'] > 5 && $_SESSION['classify'] < 12){
                            if($_SESSION['spenowstatus'] == 2){
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
                        $notes = $_SESSION['spenotes'];

                        if($_SESSION['classify'] > 11 && $_SESSION['classify'] < 17){
                            if($_SESSION['spenowstatus'] == 2){
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
                            if($_SESSION['spenowstatus'] == 2){
                                echo '<i class="fa fa-check yes">' . "</i>";
                            } else if($_SESSION['classify'] > 21 && $_SESSION['spenowstatus'] == 1){
                                echo '<i class="fa fa-close no">' . "</i>";
                            }
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $_SESSION['spetimes'];
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
                        if(($_SESSION['today'] > 0) || ($_SESSION['today'] == 0 && $_SESSION['classify'] > 5)){
                            $count = 0;//得出下标
                            $notes = $_SESSION['spenotes'];
                            $status=((int)substr("$notes","$count",1));

                            if(($_SESSION['today'] > 0 && $status == 1) || $_SESSION['classify'] > 11 && $status == 1){
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
                            $notes = $_SESSION['spenotes'];
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
                            $notes = $_SESSION['spenotes'];
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
                            $notes = $_SESSION['spenotes'];
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
                            $notes = $_SESSION['spenotes'];
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
                            $notes = $_SESSION['spenotes'];
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
                            $notes = $_SESSION['spenotes'];
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
                            $notes = $_SESSION['spenotes'];
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
                            $notes = $_SESSION['spenotes'];
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
                            $notes = $_SESSION['spenotes'];
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
                            $notes = $_SESSION['spenotes'];
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
                            $notes = $_SESSION['spenotes'];
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
                            $notes = $_SESSION['spenotes'];
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
                            $notes = $_SESSION['spenotes'];
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
                            $notes = $_SESSION['spenotes'];
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
                            $notes = $_SESSION['spenotes'];
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
                            $notes = $_SESSION['spenotes'];
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
                            $notes = $_SESSION['spenotes'];
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
                            $notes = $_SESSION['spenotes'];
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
                            $notes = $_SESSION['spenotes'];
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
                            $notes = $_SESSION['spenotes'];
                            $status=((int)substr("$notes","$count",1));

                            if(($_SESSION['today'] > 6 && $status == 1) || $_SESSION['classify'] > 21 && $status == 1){
                                echo '<i class="fa fa-close no">' . "</i>";
                            }else if($status == 2){
                                echo '<i class="fa fa-check yes">' . "</i>";
                            }
                        }
                        ?>
                    </th>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>