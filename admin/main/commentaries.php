<?php
/**
 * 评论管理
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

    @$text = $_POST['text'];
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
    <title>仪表盘-评论管理</title>
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
                <li class="active"><a href="commentaries.php">评论管理</a></li>
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
                <h2 class="text-center">评论管理</h2>
                <form method="post" about="commentaries.php">
                    <div class="input-group">
                        <input type="text" name="text" class="form-control" placeholder="查找的评论内容关键字">
                        <span class="input-group-btn">
                                <button type="submit" class="btn btn-default">查找</button>
                        </span>
                    </div>
                </form>
                <table class="table table-hover table-bordered text-center">
                    <thead>
                    <tr class="active">
                        <th class="text-center">文章标题</th>
                        <th class="text-center">评论内容</th>
                        <th class="text-center">评论时间</th>
                        <th class="text-center">评论者</th>
                        <th class="text-center">操作</th>
                    </tr>
                    </thead>
                    <?php
                    $paginationArray = $pdo->friendPag('commentaries','content',@$text,15);
                    $offset = $paginationArray['offset'];
                    $length = $paginationArray['length'];
                    $total = $paginationArray['total'];
                    $totpage = $paginationArray['totpage'];
                    $prevpage = $paginationArray['prevpage'];
                    $nextpage = $paginationArray['nextpage'];
                    $page = $paginationArray['page'];

                    $sqlCountLimit = "select c.articles_id,a.title,c.content,c.time,c.id,u.username
                                    from articles a inner join commentaries c on c.articles_id = a.id inner join users u on c.user_id = u.id and c.content like '%$text%'
                                    order by time desc
                                    limit $offset,$length;
                                    ";
                    $allLimitArray = $pdo->select($sqlCountLimit);

                    ?>
                    <nav>
                        <ul class="pager">
                            <a>共有<?php echo $total ?>条评论</a><br><br>
                            <a>共<?php echo $totpage ?>页</a>&nbsp;|
                            <li><a href='commentaries.php?p=1'>首页</a></li>
                            <li><a href='commentaries.php?p=<?php echo $prevpage ?>'>上一页</a></li>
                            <li><a href='commentaries.php?p=<?php echo $nextpage ?>'>下一页</a></li>
                            <li><a href='commentaries.php?p=<?php echo $totpage ?>'>尾部</a></li>
                            &nbsp;|&nbsp;<a>第<?php echo $page ?>页</a>
                        </ul>
                    </nav>
                    <?php
                    foreach($allLimitArray as $value){
                        echo "<tbody>
                    <tr class='info'>";
                        echo "<td><a target='_blank' href='../../home/arinfo.php?id=".$value['articles_id']."'>".$value['title']."</a></td>";
                        echo "<td>".$value['content']."</td>";
                        echo "<td>".$value['time']."</td>";
                        echo "<td>".$value['username']."</td>";
                        echo "
                    <form method='post' about='articles.php'>
                        <td class='text-center'>
                            <div class='btn-group'>
                                <button class='btn btn-info btn-xs'><a target='_blank' href='../../home/arinfo.php?id=".$value['articles_id']."'>查看</a></button>
                                <button class='btn btn-info dropdown-toggle btn-xs' data-toggle='dropdown' id='dropdownMenu2'
                                        data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
                                    <span class='caret'></span>
                                </button>
                                <ul class='dropdown-menu'>
                                    <li><a href='../view/commentariesDel.php?id=".$value['id']."'>删除</a></li>
                                </ul>
                            </div>
                        </td>
                    </form>
                    </tr>
                    ";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>
