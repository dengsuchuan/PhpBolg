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
    <title><?php echo $config[0]['title']; ?>·文章展示</title>
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
                        <li class="active"><a href="articles.php"> <span class="glyphicon glyphicon-file"></span>文章</a></li>
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
            <div class="well col-md-8">
                <form method="post" about="home.php">
                    <div class="input-group">
                        <input type="text" name="text" class="form-control" placeholder="查找的文章内容关键字......">
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-default">查找</button>
                        </span>
                    </div>
                </form>
                <br>
                <?php
                $paginationArray = $pdo->friendPag('articles','content',@$text,5);
                //print_r($paginationArray);

                $offset = $paginationArray['offset'];
                $length = $paginationArray['length'];
                $total = $paginationArray['total'];
                $totpage = $paginationArray['totpage'];
                $prevpage = $paginationArray['prevpage'];
                $nextpage = $paginationArray['nextpage'];
                $page = $paginationArray['page'];

                $sqlArticles = "select a.id,a.title,a.content,a.time,a.state,b.username,a.class,commentaries
                                from articles a inner join users b on a.user_id = b.id
                                where a.content like '%$text%' order by time desc limit $offset,$length;
                                ";

                $articlesArray = $pdo->select($sqlArticles);
                ?>
                <nav>
                    <ul class="pager">
                        <a>本站有<?php echo $total ?>篇文章</a><br><br>
                        <a>共<?php echo $totpage ?>页</a>&nbsp;|
                        <li><a href='articles.php?p=1'>首页</a></li>
                        <li><a href='articles.php?p=<?php echo $prevpage ?>'>上一页</a></li>
                        <li><a href='articles.php?p=<?php echo $nextpage ?>'>下一页</a></li>
                        <li><a href='articles.php?p=<?php echo $totpage ?>'>尾部</a></li>
                        &nbsp;|&nbsp;<a>第<?php echo $page ?>页</a>
                    </ul>
                </nav>
                <!----------------------------------------------->
                <?php foreach ($articlesArray as $value){?>
                <div class="well">
                    <legend> <span class="glyphicon glyphicon-file"></span><a href="arinfo.php?id=<?php echo $value['id']; ?>"><?php echo $value['title']; ?></a></legend>
                    <a href="#" style="font-size:13px;padding-top:2px;"> <span class="glyphicon glyphicon-user"></span>作者:<?php echo $value['username']; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
                    <a href="#" style="font-size:13px;padding-top:2px;"> <span class="glyphicon glyphicon-list-alt"></span>分类:<?php echo $value['class']; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
                    <a href="#" style="font-size:13px;padding-top:2px;"> <span class="glyphicon glyphicon-time"></span>发表时间:<?php echo $value['time']; ?></a><br>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <?php
                        $str = strip_tags($value['content']);
                        $str = mb_substr($str,0,150,'utf-8');
echo $str."......";
			
                        ?><br>
                        <a class="btn btn-info btn-xs" href="arinfo.php?id=<?php echo $value['id']; ?>">---[查看更多]---</a>
                    </p>
                </div>
                <?php } ?>
                <!-- ------------------------------------------------->
            </div>
            <div class="well col-md-4">
                <legend> <span class="glyphicon glyphicon-th-list"></span> 最新文章</legend>
                <?php
                    $sqlNewArticles = "select id,title,time,content from articles order by time desc limit 0,5;";
                    $articlesNewArray = $pdo->select($sqlNewArticles);
                    foreach ($articlesNewArray as $value){
                        echo "<div class='col-md-12'><a href='arinfo.php?id=".$value['id']."' style='font-size:18px;'>"."[".$value['time']."]".mb_substr($value['title'],0,12, 'utf-8')."</span></div>";

                    }
                ?>
            </div>
            <div class="well col-md-4">
                <legend> <span class="glyphicon glyphicon-th-list"></span> 最新评论</legend>
                <?php
                $sqlNewCommentaries = "select time,content,articles_id from commentaries order by time desc limit 0,5;";
                $commentariesNewArray = $pdo->select($sqlNewCommentaries);
                foreach ($commentariesNewArray as $value){
                    echo "<div class='col-md-12'><a href='arinfo.php?id=".$value['articles_id']."' style='font-size:18px;'>"."[".$value['time']."]".mb_substr($value['content'],0,12, 'utf-8')."</span></div>";
                }
                ?>
            </div>
            <div class="well col-md-4">
                <legend> <span class="glyphicon glyphicon-th-list"></span> 最新留言</legend>
                <?php
                $sqlNewMessage = "select content,time from message order by time desc limit 0,5;";
                $messageNewArray = $pdo->select($sqlNewMessage);
                foreach ($messageNewArray as $value){
                    echo "<div class='col-md-12'><a href='message.php' style='font-size:18px;'>"."[".mb_substr($value['time'],0,10, 'utf-8')."]".mb_substr($value['content'],0,12, 'utf-8')."</span></div>";
                }
                ?>
            </div>
            <div class="well col-md-4">
                <legend> <span class="glyphicon glyphicon-headphones"></span>背景音乐:</legend>
                <audio src="../public/music/<?php echo $config[0]['music']; ?>" controls="smallconsole" autoplay="autoplay" class="col-md-12"></audio>
            </div>
        </div>
        <div class="well col-md-12 text-center">
            <span>Copyright©2017-2018 <a href="#">公开博客·静影探风</a> All Rights Reserved.</span>
        </div>
    </div>
</div>
</body>
</html>
