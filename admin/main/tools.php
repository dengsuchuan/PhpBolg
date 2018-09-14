<?php
/**
 * 工具管理
 */
header("Content-type: text/html; charset=utf-8");
include_once '../../public/function/ClassDb.php';

session_start();
if(@$_SESSION['username'] == null){
    echo "<script>location.href='../login/index.php'; </script>";
}else{
    $adminname = @$_SESSION['username'];
    $password = @$_SESSION['password'];

    $pdo = new ClassDb();                   //实例化数据库操作对象
    $config = $pdo->readConfiguration();    //读取配置表    日志

    $toolsSql = "select * from tools order by id";
    $toolsArray = $pdo->select($toolsSql);

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
    <title>仪表盘-工具管理</title>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="well col-md-12">
            <ul class="nav nav-tabs">
                <li><a href="basic.php">基础信息</a></li>
                <li><a href="articles.php">文章管理</a></li>
                <li><a href="users.php">用户管理</a></li>
                <li class="active"><a href="tools.php">工具管理</a></li>
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
                    $pdo->exitLogin('../login/index.php');
                }
                ?>
                <h2 class="text-center">工具管理</h2>
                <!------------------------------------------------华丽丽的分割线------------------------------>
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">1号工具框</h3>
                    </div>
                    <div class="panel-body">
                        <form action="tools.php" method="post" role="form" class="form-horizontal" enctype="multipart/form-data">
                            <fieldset>
                                <div class="form-group">
                                    <label for="title" class="col-md-2 control-label">标题:</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="title_01" id="title" value="<?php echo $toolsArray[0]['title']; ?>" placeholder="工具标题">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="info" class="col-md-2 control-label">说明:</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="info_01" id="info" value="<?php echo $toolsArray[0]['info']; ?>" placeholder="工具简介">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="images" class="col-md-2 control-label">封面图:</label>
                                    <div class="col-md-10">
                                        <input type="file" class="form-control" name="images_01" id="images" placeholder="展示的工具图标">
                                        <input type="hidden" class="form-control" name="images_sql_01"  value="<?php echo $toolsArray[0]['images']; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="url" class="col-md-2 control-label">地址:</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="url_01" id="url" value="<?php echo $toolsArray[0]['url']; ?>" placeholder="工具的URL地址:http://......">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-offset-2 col-md-10">
                                        <button type="submit" name="tools_but01" class="btn btn-info">提交</button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                        <?php
                            if(isset($_POST['tools_but01'])){
                                $title_01 = $_POST['title_01'];
                                $info_01 = $_POST['info_01'];
                                $url_01 = $_POST['url_01'];
                                $images_sql_01 = $_POST['images_sql_01'];

                                //封面图片
                                $imagesArray = $_FILES['images_01'];
                                $images_01 = $pdo->fines($imagesArray,'../../public/images/',$images_sql_01,'tools.php','工具框01图片上传失败！');
                                //图片上传完毕

                                $sqlUpdate = "update tools set title='$title_01',info = '$info_01',images = '$images_01',url = '$url_01' where id = 0";
                                $pdo->exec($sqlUpdate);
                                echo "<script>location.href='tools.php'; </script>";
                            }
                        ?>
                    </div>
                </div>
                <!-----------------------------------1号工具箱完成---------------------------------------------------->
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">2号工具框</h3>
                    </div>
                    <div class="panel-body">
                        <form action="tools.php" method="post" role="form" class="form-horizontal" enctype="multipart/form-data">
                            <fieldset>
                                <div class="form-group">
                                    <label for="title" class="col-md-2 control-label">标题:</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="title_02" id="title" value="<?php echo $toolsArray[1]['title']; ?>" placeholder="工具标题">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="info" class="col-md-2 control-label">说明:</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="info_02" id="info" value="<?php echo $toolsArray[1]['info']; ?>" placeholder="工具简介">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="images" class="col-md-2 control-label">封面图:</label>
                                    <div class="col-md-10">
                                        <input type="file" class="form-control" name="images_02" id="images" placeholder="展示的工具图标">
                                        <input type="hidden" class="form-control" name="images_sql_02"  value="<?php echo $toolsArray[1]['images']; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="url" class="col-md-2 control-label">地址:</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="url_02" id="url" value="<?php echo $toolsArray[1]['url']; ?>" placeholder="工具的URL地址:http://......">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-offset-2 col-md-10">
                                        <button type="submit" name="tools_but02" class="btn btn-info">提交</button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                        <?php
                        if(isset($_POST['tools_but02'])){
                            $title_02 = $_POST['title_02'];
                            $info_02 = $_POST['info_02'];
                            $url_02 = $_POST['url_02'];
                            $images_sql_02 = $_POST['images_sql_02'];

                            //封面图片
                            $imagesArray = $_FILES['images_02'];
                            $images_02 = $pdo->fines($imagesArray,'../../public/images/',$images_sql_02,'tools.php','工具框02图片上传失败！');
                            //图片上传完毕
                            $sqlUpdate = "update tools set title='$title_02',info = '$info_02',images = '$images_02',url = '$url_02' where id = 1";
                            $pdo->exec($sqlUpdate);
                            echo "<script>location.href='tools.php'; </script>";



                        }
                        ?>
                    </div>
                </div>
                <!-----------------------------------2号工具箱完成---------------------------------------------------->
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">3号工具框</h3>
                    </div>
                    <div class="panel-body">
                        <form action="tools.php" method="post" role="form" class="form-horizontal" enctype="multipart/form-data">
                            <fieldset>
                                <div class="form-group">
                                    <label for="title" class="col-md-2 control-label">标题:</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="title_03" id="title" value="<?php echo $toolsArray[2]['title']; ?>" placeholder="工具标题">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="info" class="col-md-2 control-label">说明:</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="info_03" id="info" value="<?php echo $toolsArray[2]['info']; ?>" placeholder="工具简介">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="images" class="col-md-2 control-label">封面图:</label>
                                    <div class="col-md-10">
                                        <input type="file" class="form-control" name="images_03" id="images" placeholder="展示的工具图标">
                                        <input type="hidden" class="form-control" name="images_sql_03"  value="<?php echo $toolsArray[2]['images']; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="url" class="col-md-2 control-label">地址:</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="url_03" id="url" value="<?php echo $toolsArray[2]['url']; ?>" placeholder="工具的URL地址:http://......">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-offset-2 col-md-10">
                                        <button type="submit" name="tools_but03" class="btn btn-info">提交</button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                        <?php
                        if(isset($_POST['tools_but03'])){
                            $title_03 = $_POST['title_03'];
                            $info_03 = $_POST['info_03'];
                            $url_03 = $_POST['url_03'];
                            $images_sql_03 = $_POST['images_sql_03'];

                            //封面图片
                            $imagesArray = $_FILES['images_03'];
                            $images_03 = $pdo->fines($imagesArray,'../../public/images/',$images_sql_03,'tools.php','工具框03图片上传失败！');
                            //图片上传完毕
                            $sqlUpdate = "update tools set title='$title_03',info = '$info_03',images = '$images_03',url = '$url_03' where id = 2";
                            $pdo->exec($sqlUpdate);
                            echo "<script>location.href='tools.php'; </script>";
                        }
                        ?>
                    </div>
                </div>
                <!-----------------------------------3号工具箱完成---------------------------------------------------->
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">4号工具框</h3>
                    </div>
                    <div class="panel-body">
                        <form action="tools.php" method="post" role="form" class="form-horizontal" enctype="multipart/form-data">
                            <fieldset>
                                <div class="form-group">
                                    <label for="title" class="col-md-2 control-label">标题:</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="title_04" id="title" value="<?php echo $toolsArray[3]['title']; ?>" placeholder="工具标题">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="info" class="col-md-2 control-label">说明:</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="info_04" id="info" value="<?php echo $toolsArray[3]['info']; ?>" placeholder="工具简介">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="images" class="col-md-2 control-label">封面图:</label>
                                    <div class="col-md-10">
                                        <input type="file" class="form-control" name="images_04" id="images" placeholder="展示的工具图标">
                                        <input type="hidden" class="form-control" name="images_sql_04"  value="<?php echo $toolsArray[3]['images']; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="url" class="col-md-2 control-label">地址:</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="url_04" id="url" value="<?php echo $toolsArray[3]['url']; ?>" placeholder="工具的URL地址:http://......">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-offset-2 col-md-10">
                                        <button type="submit" name="tools_but04" class="btn btn-info">提交</button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                        <?php
                        if(isset($_POST['tools_but04'])){
                            $title_04 = $_POST['title_04'];
                            $info_04 = $_POST['info_04'];
                            $url_04 = $_POST['url_04'];
                            $images_sql_04 = $_POST['images_sql_04'];

                            //封面图片
                            $imagesArray = $_FILES['images_04'];
                            $images_04 = $pdo->fines($imagesArray,'../../public/images/',$images_sql_04,'tools.php','工具框04图片上传失败！');
                            //图片上传完毕
                            $sqlUpdate = "update tools set title='$title_04',info = '$info_04',images = '$images_04',url = '$url_04' where id = 3";
                            $pdo->update($sqlUpdate);
                            echo "<script>location.href='tools.php'; </script>";



                        }
                        ?>
                    </div>
                </div>
                <!-----------------------------------4号工具箱完成---------------------------------------------------->
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">5号工具框</h3>
                    </div>
                    <div class="panel-body">
                        <form action="tools.php" method="post" role="form" class="form-horizontal" enctype="multipart/form-data">
                            <fieldset>
                                <div class="form-group">
                                    <label for="title" class="col-md-2 control-label">标题:</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="title_05" id="title" value="<?php echo $toolsArray[4]['title']; ?>" placeholder="工具标题">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="info" class="col-md-2 control-label">说明:</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="info_05" id="info" value="<?php echo $toolsArray[4]['info']; ?>" placeholder="工具简介">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="images" class="col-md-2 control-label">封面图:</label>
                                    <div class="col-md-10">
                                        <input type="file" class="form-control" name="images_05" id="images" placeholder="展示的工具图标">
                                        <input type="hidden" class="form-control" name="images_sql_05"  value="<?php echo $toolsArray[4]['images']; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="url" class="col-md-2 control-label">地址:</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="url_05" id="url" value="<?php echo $toolsArray[4]['url']; ?>" placeholder="工具的URL地址:http://......">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-offset-2 col-md-10">
                                        <button type="submit" name="tools_but05" class="btn btn-info">提交</button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                        <?php
                        if(isset($_POST['tools_but05'])){
                            $title_05 = $_POST['title_05'];
                            $info_05 = $_POST['info_05'];
                            $url_05 = $_POST['url_05'];
                            $images_sql_05 = $_POST['images_sql_05'];

                            //封面图片
                            $imagesArray = $_FILES['images_05'];
                            $images_05 = $pdo->fines($imagesArray,'../../public/images/',$images_sql_05,'tools.php','工具框05图片上传失败！');
                            //图片上传完毕
                            $sqlUpdate = "update tools set title='$title_05',info = '$info_05',images = '$images_05',url = '$url_05' where id = 4";
                            $pdo->exec($sqlUpdate);
                            echo "<script>location.href='tools.php'; </script>";



                        }
                        ?>
                    </div>
                </div>
                <!-----------------------------------5号工具箱完成---------------------------------------------------->
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">6号工具框</h3>
                    </div>
                    <div class="panel-body">
                        <form action="tools.php" method="post" role="form" class="form-horizontal" enctype="multipart/form-data">
                            <fieldset>
                                <div class="form-group">
                                    <label for="title" class="col-md-2 control-label">标题:</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="title_06" id="title" value="<?php echo $toolsArray[5]['title']; ?>" placeholder="工具标题">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="info" class="col-md-2 control-label">说明:</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="info_06" id="info" value="<?php echo $toolsArray[5]['info']; ?>" placeholder="工具简介">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="images" class="col-md-2 control-label">封面图:</label>
                                    <div class="col-md-10">
                                        <input type="file" class="form-control" name="images_06" id="images" placeholder="展示的工具图标">
                                        <input type="hidden" class="form-control" name="images_sql_06"  value="<?php echo $toolsArray[5]['images']; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="url" class="col-md-2 control-label">地址:</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="url_06" id="url" value="<?php echo $toolsArray[5]['url']; ?>" placeholder="工具的URL地址:http://......">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-offset-2 col-md-10">
                                        <button type="submit" name="tools_but06" class="btn btn-info">提交</button>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                        <?php
                        if(isset($_POST['tools_but06'])){
                            $title_06 = $_POST['title_06'];
                            $info_06 = $_POST['info_06'];
                            $url_06 = $_POST['url_06'];
                            $images_sql_06 = $_POST['images_sql_06'];

                            //封面图片
                            $imagesArray = $_FILES['images_06'];
                            $images_06 = $pdo->fines($imagesArray,'../../public/images/',$images_sql_06,'tools.php','工具框06图片上传失败！');
                            //图片上传完毕
                            $sqlUpdate = "update tools set title='$title_06',info = '$info_06',images = '$images_06',url = '$url_06' where id = 5";
                            $pdo->exec($sqlUpdate);
                            echo "<script>location.href='tools.php'; </script>";
                        }
                        ?>
                    </div>
                </div>
                <!-----------------------------------6号工具箱完成---------------------------------------------------->
            </div>
        </div>
    </div>
</div>

</body>
</html>
