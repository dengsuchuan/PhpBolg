<?php
/**
 * 首页-home
 */
require '../public/function/ClassDb.php';
require '../public/function/position.php';
$pdo = new ClassDb();                   //实例化数据库操作对象
$config = $pdo->readConfiguration();    //读取配置表

$position = new position();  //实例化地理位置对象
$ip = $position->getip();
$showtime=date("Y-m-d H:i", time()+7*60*60);
@$text = $_POST['text'];
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
    <title><?php echo $config[0]['title']; ?>·文章</title>
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
                            $password = $_SESSION['password'];
                            $uid = $_SESSION['id'];
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
                <?php
                $id = $_GET['id'];
                $sql = "select a.id,a.title,a.content,a.time,a.state,b.username,a.class,commentaries
                        from articles a inner join users b on a.user_id = b.id
                        where a.id = '$id'";
                $articlesArray = $pdo->select($sql);

                //开始分页
                $paginationArray = $pdo->friendPag('commentaries','articles_id',@$id,10);
                $offset = $paginationArray['offset'];
                $length = $paginationArray['length'];
                $total = $paginationArray['total'];
                $totpage = $paginationArray['totpage'];
                $prevpage = $paginationArray['prevpage'];
                $nextpage = $paginationArray['nextpage'];
                $page = $paginationArray['page'];
                ?>
                <legend><h1><?php echo $articlesArray[0]['title']; ?></h1></legend>
                <a href="#" style="font-size:13px;padding-top:2px;"> <span class="glyphicon glyphicon-user"></span>作者:<?php echo $articlesArray[0]['username']; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
                <a href="#" style="font-size:13px;padding-top:2px;"> <span class="glyphicon glyphicon-list-alt"></span>分类:<?php echo $articlesArray[0]['class']; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
                <a href="#" style="font-size:13px;padding-top:2px;"> <span class="glyphicon glyphicon-time"></span>发表时间:<?php echo $articlesArray[0]['time']; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
                <a href="#" style="font-size:13px;padding-top:2px;"> <span class="glyphicon glyphicon-tags"></span>评论数量:<?php echo $total; ?></a>
                <br><br>
                <div class="col-md-12">
                    <?php echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$articlesArray[0]['content']; ?>
                    <hr style="background-color:skyblue;height:2px;">
                    <div class="page-header">
                        <h1>
                            <small>
                                <span class="glyphicon glyphicon-thumbs-up"></span>
                                对此篇文章有什么见解，评论~~
                            </small>
                        </h1>
                        <form action="arinfo.php?id=<?php echo $id; ?>" method="post">
                            <?php
                            if($state){
                                echo "<textarea type='text' name='text' style='height:250px;width:100%;font-size:20px;' class='col-md-12' placeholder='输入您要评论的内容，不可以超过200个字符'></textarea>";
                                echo "<h3><button type='submit' name='btn' class='btn btn-info btn-lg'>发表</button></h3>";
                            }else{
                                echo "<h3>登陆后才可有留言哦 <a href='../admin/login/index.php'>点击登录</a></h3>";
                            }
                            ?>
                        </form>
                        <?php
                            if(isset($_POST['btn']) && $_POST['text'] != ""){
                                $sql = "insert into commentaries(articles_id,content,time,user_id) values('$id','$text','$showtime','$uid');";
                                if(!$pdo->exec($sql)){
                                    echo "<script>alert('发表失败')</script>";
                                }
                            }
                        ?>
                        <hr style="background-color:skyblue;height:2px;">

                        <?php
                        $commentariesSql = "
                                        select c.id,c.content,c.time,u.username,u.portrait 
                                        from  commentaries c inner join users u on c.user_id = u.id 
                                        where c.articles_id = '$id'
                                        order by time desc 
                                        limit $offset,$length";
                        $commentariesArray = $pdo->select($commentariesSql);
                        //print_r($commentariesArray);
                        ?>
                        <nav>
                            <ul class="pager">
                                <a>此篇文章有<?php echo $total ?>条用户评论</a><br><br>
                                <a>共<?php echo $totpage ?>页</a>&nbsp;|
                                <li><a href='arinfo.php?p=1&id=<?php echo $id; ?>'>首页</a></li>
                                <li><a href='arinfo.php?p=<?php echo $prevpage ?>&id=<?php echo $id; ?>'>上一页</a></li>
                                <li><a href='arinfo.php?p=<?php echo $nextpage ?>&id=<?php echo $id; ?>'>下一页</a></li>
                                <li><a href='arinfo.php?p=<?php echo $totpage ?>&id=<?php echo $id; ?>'>尾部</a></li>
                                &nbsp;|&nbsp;<a>第<?php echo $page ?>页</a>
                            </ul>
                        </nav>
                        <?php foreach ($commentariesArray as $value){?>
                            <div class="well col-md-12">
                                <a href="#" style="font-size:13px;padding-top:2px;"> <span class="glyphicon glyphicon-user"></span> 用户:<?php echo $value['username']; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
                                <a href="#" style="font-size:13px;padding-top:2px;"> <span class="glyphicon glyphicon-time"></span> 时间:<?php echo $value['time']; ?></a>
                                <div class="col-md-2" >
                                    <img src="../public/images/users/<?php echo $value['portrait']; ?>" class="img-responsive img-circle center-block" style="width:100px;height:100px;">
                                </div>
                                <div class="col-md-10">
                                    <p style="font-size:20px;"><?php echo $value['content']; ?></p>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
    </div>
    <div class="well col-md-12 text-center">
        <span>Copyright©2017-2018 <a href="#">公开博客·静影探风</a> All Rights Reserved.</span>
    </div>
</div>
</body>
</html>
