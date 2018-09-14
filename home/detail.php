<?php
/**
 * 关于
 */
require '../public/function/ClassDb.php';
require '../public/function/position.php';
$pdo = new ClassDb();                   //实例化数据库操作对象
$config = $pdo->readConfiguration();    //读取配置表

$position = new position();  //实例化地理位置对象
$ip = $position->getip();

@$text = $_POST['text'];
$showtime=date("Y-m-d H:i", time()+7*60*60);
?>
<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="../public/images/<?php echo $config[0]['icon']; ?>">
    <link type="text/css" rel="stylesheet" href="public/css/main.css">
    <!----引入Bootstrap样式表、JavaScript和Jquery文件----->
    <link type="text/css" rel="stylesheet" href="../public/Bootstrap/css/bootstrap.min.css">
    <script src="../public/Bootstrap/js/jquery-3.2.1.min.js"></script>
    <script src="../public/Bootstrap/js/bootstrap.min.js"></script>
    <style>
        h1{color:rgb(55,173,217)}
    </style>
    <title><?php echo $config[0]['title']; ?>·关于</title>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="well col-md-12">
            <div class='jumbotron' id='curtain' style='background-image: url("../public/images/<?php echo $config[0]['curtain'] ?>")'>
                <div class="col-md-2 col-md-offset-10">
                    <div class="btn-group">
                        <?php
                        session_start();
                        $state = 0;
                        if(@$_SESSION['username'] == null || @$_SESSION['password'] == null){
                            echo "
                                <button class='btn btn-info dropdown-toggle' data-toggle='dropdown' id='dropdownMenu1'
                                data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>游客,您好！ <span class='caret'></span>
                                </button>
                                <ul class='dropdown-menu'>
                                    <li><a href='../admin/login/index.php'>登陆</a></li>
                                    <li><a href='login/reg.php'>注册</a></li>
                                </ul>
                            ";
                            $state = 0;
                        }else{
                            $username = $_SESSION['username'];
                            echo "
                                <form method='post' action='articles.php'>
                                    <button type='submit' name='exitLogin' title='退出当前管理员账户的登陆状态' class='btn btn-default'>用户:$username  点击退出</button>
                                 </form>
                            ";
                            $state = 1;
                        }
                        if(isset($_POST['exitLogin'])){
                            $pdo->exitLogin("home.php");
                        }
                        ?>
                    </div>
                </div>
                <h1 id="title"><?php echo $config[0]['title']; ?></h1>
                <?php include ('../api/weather/weather.html'); ?>
                <p><cite title="孤独的魅影，探寻风的足迹！"><?php echo $config[0]['domain']; ?></cite></p>
                <p>每日寄语:生命就像大海，如果没有狂风暴雨的袭击，生命就显得枯燥乏味。</p>
                <div class="col-md-8">
                    <ul class="nav nav-tabs">
                        <li><a href="home.php"> <span class="glyphicon glyphicon-home"></span>首页</a></li>
                        <li><a href="articles.php"> <span class="glyphicon glyphicon-file"></span>文章</a></li>
                        <li><a href="tools.php"> <span class="glyphicon glyphicon-briefcase"></span>工具</a></li>
                        <li class="active"><a href="detail.php"> <span class="glyphicon glyphicon-th"></span>关于</a></li>
                        <li><a href="message.php"> <span class="glyphicon glyphicon-equalizer "></span>站点留言</a></li>
                        <?php
                        if($state){
                            echo "
                            <li><a href='users/users.php'> <span class='glyphicon glyphicon-user'></span>个人中心</a></li>
                            <li><a href='publish.php'> <span class='glyphicon glyphicon-pencil'></span>发表文章</a></li>
                            ";
                        }?>
                    </ul>
                </div>
                <div class="col-md-4">
                    <ul class="nav nav-tabs">
                        <li><a href="#" title="当前日期"><?php echo $showtime; ?></a></li>
                        <li><a href="#" title="当前IP地址">IP:<?php echo $ip; ?></a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-12">
                <div class="alert alert-warning alert-dismissible">
                    <button class="close" type="button" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                    <span class="glyphicon glyphicon-cloud"></span> <?php echo $config[0]['frontRecord']; ?>
                </div>
            </div>
            <hr>
            <div class="page-header">
                <h1>多用户公开博客·静影探风
                    <small>
                        <span class="glyphicon glyphicon-tags"></span>
                        平台简介~~
                    </small>
                </h1>
            </div>
            <div class="panel panel-default">
                <div class="panel-body">
                    应用需求
                </div>
                <div class="panel-footer">
                    <h4>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;此博客平台，告别常规的单用户模式，用户可以自行注册发表文章，其他用户在未登录的状态下可以浏览文章内容，但无法评论文章。当用户登录后可发表文章、评论文章、平台留言、添加好友、好友留言。此平台有
                        六个工具框，根据用户需求管理员不定时更新工具内容。
                    </h4>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-body">
                    开发
                </div>
                <div class="panel-footer">
                    <h4>
                        开发语言:<code>PHP + HTML + Bootstrap + JavaScript</code><br>
                        WEB服务器:<code>Apache/2.4.23 (Win32)</code><br>
                        开发周期:<code>432小时(18天)</code> <br>
                        维护难度:<code>简单</code><br><br>
                        开发者首页:<code>https://www.geekn.cn</code><br>
                        开发邮箱:<code>1373518279@qq.com</code><br>
                        开发者:<code>不羁de流年</code><br>

                    </h4>
                </div>
            </div>
        </div>
        <div class="well col-md-12 text-center">
            <span>Copyright©2017-2018 <a href="#">公开博客·静影探风</a> All Rights Reserved.</span>
        </div>
    </div>
</body>
</html>
