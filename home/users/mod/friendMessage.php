<?php
require '../../../public/function/ClassDb.php';
require '../../../public/function/position.php';
session_start();
if(@$_SESSION['username'] == null || @$_SESSION['password'] == null){
    echo "<script>location.href='../../../admin/login/index.php'; </script>";
}else{
    $pdo = new ClassDb();                   //实例化数据库操作对象
    $config = $pdo->readConfiguration();    //读取配置表

    $position = new position();  //实例化地理位置对象
    $ip = $position->getip();

    @$userid = $_GET['user_id'];
    @$fuid = $_GET['fuid'];
    @$fusername = $_GET['username'];

//@$text = $_POST['text'];
    $showtime=date("Y-m-d H:i", time()+7*60*60);
}

?>
<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="../../../public/images/<?php echo $config[0]['icon']; ?>">
    <link type="text/css" rel="stylesheet" href="../../public/css/main.css">
    <!----引入Bootstrap样式表、JavaScript和Jquery文件----->
    <link type="text/css" rel="stylesheet" href="../../../public/Bootstrap/css/bootstrap.min.css">
    <script src="../../../public/Bootstrap/js/jquery-3.2.1.min.js"></script>
    <script src="../../../public/Bootstrap/js/bootstrap.min.js"></script>
    <style>
        h1{color:rgb(55,173,217)}
    </style>
    <title><?php echo $config[0]['title']; ?>·我的好友</title>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="well col-md-12">
            <div class='jumbotron' id='curtain' style='background-image: url("../../../public/images/<?php echo $config[0]['curtain'] ?>")'>
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
                                    <li><a href='../../../admin/login/index.php'>登陆</a></li>
                                </ul>
                            ";
                            $state = 0;
                        }else{
                            $username = $_SESSION['username'];
                            $password = $_SESSION['password'];
                            echo "
                                <form method='post' action='../../articles.php'>
                                    <button type='submit' name='exitLogin' title='退出当前管理员账户的登陆状态' class='btn btn-default'>用户:$username  点击退出</button>
                                 </form>
                            ";
                            $state = 1;
                        }
                        if(isset($_POST['exitLogin'])){
                            $pdo->exitLogin("../../home.php");
                        }
                        ?>
                    </div>
                </div>
                <h1 id="title"><?php echo $config[0]['title']; ?></h1>
                <p><cite title="孤独的魅影，探寻风的足迹！"><?php echo $config[0]['domain']; ?></cite></p>
                <p>每日寄语:生命就像大海，如果没有狂风暴雨的袭击，生命就显得枯燥乏味。</p>
                <div class="col-md-8">
                    <ul class="nav nav-tabs">
                        <li><a href="../../home.php"> <span class="glyphicon glyphicon-home"></span>首页</a></li>
                        <li><a href="../../articles.php"> <span class="glyphicon glyphicon-file"></span>文章</a></li>
                        <li><a href="../../tools.php"> <span class="glyphicon glyphicon-briefcase"></span>工具</a></li>
                        <li><a href="../../detail.php"> <span class="glyphicon glyphicon-th"></span>关于</a></li>
                        <li><a href="../../message.php"> <span class="glyphicon glyphicon-equalizer "></span>站点留言</a></li>
                        <?php
                        if($state){
                            echo "
                            <li class='active'><a href='../users.php'> <span class='glyphicon glyphicon-user'></span>个人中心</a></li>
                            <li><a href='../../publish.php'> <span class='glyphicon glyphicon-pencil'></span>发表文章</a></li>
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
            <!-------------------------------左右布局开始----------------------->

                <ul class="nav nav-pills">
                    <!--<ul class="nav nav-pills nav-stacked">-->    <!--垂直胶囊式标签-->
                    <li><a href="../users.php">用户状态</a></li>
                    <li><a href="../userFriend.php">好友列表</a></li>
                    <li><a href="../userArticles.php">我的文章</a></li>
                    <li><a href="../myMeassage.php">好友留言</a></li>
                    <li><a href="../userEdit.php">修改信息</a></li>
                </ul>
                <hr style="background-color: #00a0d2;height:2px;">
                <div class="page-header">
                    <h1>给<?php echo $fusername; ?>留言:</h1>
                    <form action="friendMessage.php?user_id=<?php echo $userid; ?>&fuid=<?php echo $fuid; ?>&username=<?php echo $fusername; ?>" method="post">
                        <textarea type='text' name='text' style='height:250px;width:100%;font-size:25px;' class='col-md-12'></textarea>
                        <h3><button type='submit' name='btnMessage' class='btn btn-info btn-lg'>发送</button></h3>
                    </form>
                    <?php
                    if(isset($_POST['btnMessage']) && $_POST['text'] != null){
                        $text = $_POST['text'];
                        $messageSql = "insert into frmessage(user_id,friends_id,content,time) 
                                      values('$userid','$fuid','$text','$showtime')
                                      ";
                        if(!$pdo->exec($messageSql)){
                            echo "<script>alert('留言失败'); </script>";
                        }
                    }
                    ?>
                </div>
                <!----------------------------------------------->
                <?php
                $sqlUser = "select * from users where username = '$username' and password = '$password'";
                $userArray = $pdo->select($sqlUser);
                $userId = $userArray[0]['id'];

                $paginationArray = $pdo->friendPag('frmessage','friends_id',@$fuid,10);
                //print_r($paginationArray);

                $offset = $paginationArray['offset'];
                $length = $paginationArray['length'];
                $total = $paginationArray['total'];
                $totpage = $paginationArray['totpage'];
                $prevpage = $paginationArray['prevpage'];
                $nextpage = $paginationArray['nextpage'];
                $page = $paginationArray['page'];

                $messageSql = "
                                select u.username,s.username as frusername,f.id,f.content,f.time,f.user_id
                                from users u inner join frmessage f on f.user_id = u.id
                                             inner join users s on f.friends_id = s.id
                                where f.friends_id = $fuid
                                order by time desc
                                limit $offset,$length";
                $messageArray = $pdo->select($messageSql);
                ?>
                <nav>
                    <ul class="pager">
                        <a>您给此用户了<?php echo $total ?>条留言</a><br><br>
                        <a>共<?php echo $totpage ?>页</a>&nbsp;|
                        <li><a href='friendMessage.php?p=1'>首页</a></li>
                        <li><a href='friendMessage.php?p=<?php echo $prevpage ?>'>上一页</a></li>
                        <li><a href='friendMessage.php?p=<?php echo $nextpage ?>'>下一页</a></li>
                        <li><a href='friendMessage.php?p=<?php echo $totpage ?>'>尾部</a></li>
                        &nbsp;|&nbsp;<a>第<?php echo $page ?>页</a>
                    </ul>
                </nav>
                <!----------------------------------------------->
                <?php foreach ($messageArray as $value){?>
                    <div class="well">
                        <a href="#" style="font-size:13px;padding-top:2px;"> <span class="glyphicon glyphicon-time"></span> 时间:<?php echo $value['time']; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
                        <p>
                            <?php echo $value['content']; ?>
                        </p>
                    </div>
                <?php } ?>
            </div>
                <?php
                foreach ($messageArray as $value){
                } ?>
                <!-- ------------------------------------------------->
                <div class="well col-md-12 text-center">
                    <span>Copyright©2017-2018 <a href="#">公开博客·静影探风</a> All Rights Reserved.</span>
                </div>
            </div>
        </div>
</body>
</html>
