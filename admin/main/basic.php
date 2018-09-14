<?php
/**
 * 仪表盘，网站基础信息
 */
header("Content-type: text/html; charset=utf-8");
require '../../public/function/ClassDb.php';
require '../../public/function/position.php';
session_start();
if(@$_SESSION['username'] == null || @$_SESSION['password'] == null){
    echo "<script>location.href='../login/index.php'; </script>";
}else{
    $adminname = @$_SESSION['username'];
    $password = @$_SESSION['password'];

    $pdo = new ClassDb();                   //实例化数据库操作对象
    $config = $pdo->readConfiguration();    //读取配置表

    $position = new position();  //实例化地理位置对象
    $ip = $position->getip();
    //echo $ip;

    $userArray = $pdo->select("select * from users where username = '$adminname' and password = '$password'");
}
?>
<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="../../public/images/<?php echo $config[0]['icon']; ?>">
    <!----引入Bootstrap样式表、JavaScript和Jquery文件----->
    <link type="text/css" rel="stylesheet" href="../../public/Bootstrap/css/bootstrap.min.css">
    <script src="../../public/Bootstrap/js/jquery-3.2.1.min.js"></script>
    <script src="../../public/Bootstrap/js/bootstrap.min.js"></script>
    <title>仪表盘-基础信息</title>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="well col-md-12">
            <ul class="nav nav-tabs">
                <li class="active"><a href="basic.php">基础信息</a></li>
                <li><a href="articles.php">文章管理</a></li>
                <li><a href="users.php">用户管理</a></li>
                <li><a href="tools.php">工具管理</a></li>
                <li><a href="commentaries.php">评论管理</a></li>
                <li><a href="message.php">留言管理</a></li>
                <li><a href="setting.php">系统设置</a></li>
            </ul>

            <!------------------------------------------------------>
            <br>
            <div class="alert alert-warning alert-dismissible">
                <button class="close" type="button" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                <strong> <span class="glyphicon glyphicon-bullhorn"> 站内公告:</span></strong>

                <?php echo $config[0]['record']; ?><!--这里读到了表内的公告-->
            </div>
            <div class="col-md-12">
                <form method="post" action="setting.php">
                    <button type="submit" name="exitLogin" title="退出当前管理员账户的登陆状态" class="btn btn-default">管理员:<?php echo $adminname; ?>  点击退出</button>
                    <a target='_blank' href="../../index.php" class="btn btn-info">到首页</a>
                </form>
                <?php
                if(isset($_POST['exitLogin'])){
                    $url = "../../home/home.php";
                    $pdo->exitLogin($url);
                }
                ?>
                <h2 class="text-center">基础信息</h2>
            </div>
            <div class="well col-md-12">
                当前用户登录次数: <var><?php echo $userArray[0]['statistics']; ?></var> 次
            </div>
            <div class="well col-md-12">
                登陆者IP:<var><?php echo $ip; echo $position->getLocation(); ?></var>
            </div>
            <div class="well col-md-12">
                程序编码:<var>UTF-8</var>
            </div>
            <div class="well col-md-12">
                服务器软件:<var><?php echo apache_get_version();?></var>
            </div>
            <div class="well col-md-12">
                PHP版本：<var><?php echo phpversion();  ?></var>
            </div>
            <div class="well col-md-12">
                MYSQL版本:<var><?php  echo phpversion();  ?></var>
            </div>
            <div class="well col-md-12">
                当前域名:<var><?php  echo isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ''); ?></var>
            </div>
    </div>
</div>
</div>
</body>
</html>
