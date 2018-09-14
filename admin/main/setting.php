<?php
/**
 * 系统设置
 */
header("Content-type: text/html; charset=utf-8");
include_once '../../public/function/ClassDb.php';

session_start();
if(@$_SESSION['username'] == null){
    echo "<script>location.href='../login/index.php'; </script>";
}else{
    $adminname = @$_SESSION['username'];

    $pdo = new ClassDb();                   //实例化数据库操作对象
    $config = $pdo->readConfiguration();    //读取配置表    日志

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
    <title>仪表盘-系统设置</title>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="well col-md-12">
            <ul class="nav nav-tabs">
                <li><a href="basic.php">基础信息</a></li>
                <li><a href="articles.php">文章管理</a></li>
                <li><a href="users.php">用户管理</a></li>
                <li><a href="tools.php">工具管理</a></li>
                <li><a href="commentaries.php">评论管理</a></li>
                <li><a href="message.php">留言管理</a></li>
                <li class="active"><a href="setting.php">系统设置</a></li>
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
                        $pdo->exitLogin();
                    }
                ?>
                <h2 class="text-center">系统设置</h2>
                <form action="setting.php" method="post" role="form" class="form-horizontal"  enctype="multipart/form-data">
                    <fieldset>
                        <div class="form-group">
                            <label for="title" class="col-md-2 control-label">网站标题:</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control text-center" id="title" name="title" value="<?php echo $config[0]['title']; ?>" placeholder="网站的标题">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="icon" class="col-md-2 control-label">网站图标:</label>
                            <div class="col-md-10">
                                <div class="thumbnail">
                                    <img src="../../public/images/<?php echo $config[0]['icon']; ?>">
                                    <div class="caption text-center">
                                        <input type="text" class="form-control text-center" id="icon" value="<?php echo $config[0]['icon']; ?>" placeholder="网站的域名,不填http://   例如:geekln.cn">
                                        <input  type="hidden" name="icon_sql" value="<?php echo $config[0]['icon']; ?>">
                                        <br>
                                        <p>
                                            <input type="file"  name="icon" class="btn btn-primary" role="button">
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="logo" class="col-md-2 control-label">网站LOGO:</label>
                            <div class="col-md-10">
                                <div class="thumbnail">
                                    <img src="../../public/images/<?php echo $config[0]['logo']; ?>">
                                    <input  type="hidden" name="logo_sql" value="<?php echo $config[0]['logo']; ?>">
                                    <div class="caption text-center">
                                        <input type="text" class="form-control text-center" id="logo" value="<?php echo $config[0]['logo']; ?>" placeholder="网站的域名,不填http://   例如:geekln.cn">
                                        <br>
                                        <p>
                                            <input name="logo" type="file" class="btn btn-primary" role="button">
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="domain" class="col-md-2 control-label">域名:</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="domain" name="domain" value="<?php echo $config[0]['domain']; ?>" placeholder="网站的域名,不填http://   例如:geekln.cn">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="mail" class="col-md-2 control-label">站长邮箱:</label>
                            <div class="col-md-10">
                                <input type="email" class="form-control" id="mail" name="mail" value="<?php echo $config[0]['mail']; ?>" placeholder="网站站长的邮箱，用于接收相关的事物邮件">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="record" class="col-md-2 control-label">后台公告:</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="record" name="record" value="<?php echo $config[0]['record']; ?>" placeholder="给管理员们看的公告">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="frontRecord" class="col-md-2 control-label">前台公告:</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="frontRecord" name="frontRecord" value="<?php echo $config[0]['frontRecord']; ?>" placeholder="给用户看的公告">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inform" class="col-md-2 control-label">备案号:</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="inform" name="inform" value="<?php echo $config[0]['inform']; ?>" placeholder="域名备案号">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="curtain" class="col-md-2 control-label">网站巨幕:</label>
                            <div class="col-md-10">
                                <div class="thumbnail">
                                    <img src="../../public/images/<?php echo $config[0]['curtain']; ?>">
                                    <input  type="hidden" name="curtain_sql" value="<?php echo $config[0]['curtain']; ?>">
                                    <div class="caption text-center">
                                        <input type="text" class="form-control text-center" id="curtain"  value="<?php echo $config[0]['curtain']; ?>" placeholder="网站的域名,不填http://   例如:geekln.cn">
                                        <br>
                                        <p>
                                            <input type="file" name="curtain" class="btn btn-primary" role="button">
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="curtain" class="col-md-2 control-label">背景音乐:</label>
                            <div class="col-md-10">
                                <div class="thumbnail">
                                    <audio src="../../public/music/<?php echo $config[0]['music']; ?>" controls="smallconsole" class="col-md-12"></audio>
                                    <input  type="hidden" name="music_sql" value="<?php echo $config[0]['music']; ?>">
                                    <br><br>
                                    <div class="caption text-center">
                                        <input type="file" class="btn btn-primary" role="button" name="music">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!---------------------------------------------->
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-10">
                                <button type="submit" name="update" class="btn btn-info btn-lg btn-block">数据更新</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
                <?php
                if(isset($_POST['update'])){
                    $title = $_POST['title'];         //主题
                    $domain = $_POST['domain'];        //域名
                    $mail = $_POST['mail'];            //站长邮箱
                    $record = $_POST['record'];        //后台公告
                    $frontRecord = $_POST['frontRecord'];  //前端公告
                    $inform = $_POST['inform'];        //备案号

                    $icon_sql = $_POST['icon_sql'];
                    $logo_sql = $_POST['logo_sql'];
                    $curtain_sql = $_POST['curtain_sql'];
                    $music_sql = $_POST['music_sql'];


                    //网站LOGO
                    $logoArray = $_FILES['logo'];
                    $logo = $pdo->fines($logoArray,'../../public/images/',$logo_sql,'setting.php','logo上传失败');

                    //网站图标icon
                    $iconArray = $_FILES['icon'];
                    $icon = $pdo->fines($iconArray,'../../public/images/',$icon_sql,'setting.php','网站图标上传失败');

                    //巨幕
                    $curtainArray = $_FILES['curtain'];
                    $curtain = $pdo->fines($curtainArray,'../../public/images/',$curtain_sql,'setting.php','巨幕上传失败');

                    //音乐
                    $musicArrar = $_FILES['music'];
                    $music = $pdo->fines($musicArrar,'../../public/music/',$music_sql,'setting.php','音乐上传失败');

                    //-------------开始组建SQL语句-----------------
                    $sqlUpdate = "update config set title='$title',icon='$icon',logo='$logo',domain='$domain',mail='$mail',record='$record',inform='$inform',curtain='$curtain',music='$music',frontRecord='$frontRecord'";
                    $pdo->exec($sqlUpdate);
                    echo "<script>location.href='setting.php'; </script>";

                }
                ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>
