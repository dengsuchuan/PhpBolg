<?php
/**
 * 站点留言
 */
require '../public/function/ClassDb.php';
require '../public/function/position.php';
$pdo = new ClassDb();                   //实例化数据库操作对象
$config = $pdo->readConfiguration();    //读取配置表

$position = new position();  //实例化地理位置对象
$ip = $position->getip();
session_start();

@$text = $_POST['text'];
$showtime=date("Y-m-d H:i", time()+7*60*60);

if(isset($_GET['articles_id'])){
    $articles_id = $_GET['articles_id'];
    $sql = "select * from articles where id = '$articles_id';";
    $articlesArray = $pdo->select($sql);
}
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
    <!---------引入富文本编辑器----------->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.js"></script>

    <style>
        .note-editable{
            height:350px;
        }
        .panel-body{
            height:350px;
        }
    </style>
    <title><?php echo $config[0]['title']; ?>·编辑文章</title>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="well col-md-12">
            <div class='jumbotron' id='curtain' style='background-image: url("../public/images/<?php echo $config[0]['curtain'] ?>")'>
                <div class="col-md-2 col-md-offset-10">
                    <div class="btn-group">
                        <?php
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
                            $password = $_SESSION['password'];
                            echo "
                                <form method='post' action='articles.php'>
                                    <button type='submit' name='exitLogin' title='退出当前管理员账户的登陆状态' class='btn btn-default'>用户:$username  点击退出</button>
                                 </form>
                            ";
                            $state = 1;
                        }
                        if(isset($_POST['exitLogin'])){
                            $url = "home.php";
                            $pdo->exitLogin($url);
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
                        <li><a href="detail.php"> <span class="glyphicon glyphicon-th"></span>关于</a></li>
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
            <div class="well col-md-12">
                <form action="upar.php?articles_id=<?php echo $articles_id; ?>" method="post">
                    <input type="text" name="title" class="form-control" placeholder="主题:" value="<?php echo @$articlesArray[0]['title']; ?>">
                    <span style="font-size:20px;">
                        分类:
                        <?php
                        if(@$articlesArray[0]['class'] == '文学'){
                            echo "
                                <input type='radio' value='文学' name='class' checked='checked'>文学&nbsp;&nbsp;/&nbsp;&nbsp;
                                <input type='radio' value='编程' name='class'>编程
                                ";
                        }else{
                            echo "
                                <input type='radio' value='文学' name='class'>文学&nbsp;&nbsp;/&nbsp;&nbsp;
                                <input type='radio' value='编程' name='class' checked='checked'>编程
                                ";
                        }
                        ?>
                    </span>
                    <textarea id="content" name="content"><?php echo @$articlesArray[0]['content']; ?></textarea>
                    <script>
                        $(document).ready(function() {
                            $('#content').summernote();
                        });
                    </script>
                    <input type="submit" name="publish" value="更新">
                </form>
                <?php
                if(isset($_POST['publish'])){
                    $title = $_POST['title'];
                    $content = $_POST['content'];
                    $class = $_POST['class'];

                    $user_id = $_SESSION['id'];
                    $sql = "update articles set title='$title',content='$content',user_id='$user_id',class='$class'
                            where id = '$articles_id'";
                    if($pdo->exec($sql)){
                        echo "<script>alert('更新成功!');location.href='upar.php?articles_id=$articles_id'; </script>";
                    }else{
                        echo "<script>alert('更新失败!');</script>";
                    }
                }
                ?>
            </div>
            <div class="well col-md-12 text-center">
                <span>Copyright©2017-2018 <a href="#">公开博客·静影探风</a> All Rights Reserved.</span>
            </div>
        </div>
    </div>
</body>
</html>
