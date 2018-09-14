<?php
/**
 * 查看用户
 */
include_once '../../public/function/ClassDb.php';
session_start();
if(@$_SESSION['username'] == null){
    echo "<script>location.href='../login/index.php'; </script>";
}else{
    $adminname = @$_SESSION['username'];

    $pdo = new ClassDb();                   //实例化数据库操作对象
    $config = $pdo->readConfiguration();    //读取配置表    日志

    $id = $_GET['id'];
    $username = $_GET['username'];

    $usersArray = $pdo->select("select * from users where id = '$id'");
    $countArticles = $pdo->select("select count(*) as count from articles where user_id = '$id'");
    $commentariesCount = $pdo->select("select count(*) as count from commentaries where user_id = '$id'");
    $messageCount = $pdo->select("select count(*) as count from message where user_id = '$id'");
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
    <title>仪表盘-<?php echo $username ?>信息</title>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="well col-md-12">
            <!------------------------------------------------------>
            <div class="table-responsive">
                <div class="col-md-4 col-md-offset-4">
                    <div class="thumbnail">
                        <div class="caption text-center">
                            <h3>用户头像</h3>
                        </div>
                        <img src="../../public/images/users/<?php echo $usersArray[0]['portrait']; ?>" class="img-responsive img-circle center-block" style="width:150px;height:150px;">
                        <div class="caption text-center">
                            <h3></h3>
                        </div>
                    </div>
                    <br>
                </div>
                <table class="table table-hover table-bordered">
                    <caption class="h3 text-center"><?php echo $username ?>用户信息</caption>
                    <thead>
                    <tr class="active">
                        <th class="text-center">编号</th>
                        <th class="text-center">姓名</th>
                        <th class="text-center">密码</th>
                        <th class="text-center">性别</th>
                        <th class="text-center">生日</th>
                        <th class="text-center">手机</th>
                        <th class="text-center">邮箱</th>
                        <th class="text-center">用户权限</th>
                        <th class="text-center">个人说明</th>
                    </tr>
                    </thead>

                    <tbody>
                        <tr class="success text-center">
                            <td><?php echo $usersArray[0]['id']; ?></td>
                            <td><?php echo $usersArray[0]['username']; ?></td>
                            <td><?php echo $usersArray[0]['password']; ?></td>
                            <td><?php echo $usersArray[0]['gender']; ?></td>
                            <td><?php echo $usersArray[0]['birthday']; ?></td>
                            <td><?php echo $usersArray[0]['phone']; ?></td>
                            <td><?php echo $usersArray[0]['mail']; ?></td>
                            <td><?php if($usersArray[0]['admin'] == 1){echo '管理员';} else {echo '普通用户';}; ?></td>
                            <td><?php echo $usersArray[0]['instruction']; ?></td>
                        </tr>
                    </tbody>
                </table>
                <!-------------------------------------------------------------------------->
                <table class="table table-hover table-bordered">
                    <caption class="h3 text-center">统计信息</caption>
                    <thead>
                    <tr class="active">
                        <th class="text-center">发表文章总数</th>
                        <th class="text-center">发表评论总数</th>
                        <th class="text-center">发表留言总数</th>
                        <th class="text-center">用户登录总次数</th>
                        <th class="text-center">用户最后一次登录IP地址</th>
                        <th class="text-center">用户最后一次登陆时间</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr class="success text-center">
                        <td><?php echo $countArticles[0]['count']; ?></td>
                        <td><?php echo $commentariesCount[0]['count']; ?></td>
                        <td><?php echo $messageCount[0]['count']; ?></td>
                        <td><?php echo $usersArray[0]['statistics']; ?></td>
                        <td><?php echo $usersArray[0]['ip']; ?></td>
                        <td><?php echo $usersArray[0]['lastTime']; ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <hr>
            <p class="text-center">
                <a type="button" class="btn btn-info" href="userDel.php?id=<?php echo $usersArray[0]['id']; ?>">销毁此用户</a>
                <a type="button" class="btn btn-info" href="userEdit.php?id=<?php echo $usersArray[0]['id']; ?>">修改用户信息</a>
            </p>
        </div>
    </div>
</div>

</body>
</html>
