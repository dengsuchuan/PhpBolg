<?php
/**
 * 媒体管理
 */
session_start();
if(@$_SESSION['username'] == null || @$_SESSION['password'] == null){
    echo "<script>location.href='../login/index.php'; </script>";
}else{
    include_once '../../public/function/ClassDb.php';
    $pdo = new ClassDb();                   //实例化数据库操作对象
    $config = $pdo->readConfiguration();    //读取配置表    日志
    $adminname = @$_SESSION['username'];

    $id = $_GET['id'];
    $userArray = $pdo->select("select * from users where id = '$id'");

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
    <title>仪表盘-用户管理</title>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="well col-md-12">
            <ul class="nav nav-tabs">
                <li><a href="../main/basic.php">基础信息</a></li>
                <li><a href="../main/articles.php">文章管理</a></li>
                <li class="active"><a href="../main/users.php">用户管理</a></li>
                <li><a href="../main/tools.php">工具管理</a></li>
                <li><a href="../main/commentaries.php">评论管理</a></li>
                <li><a href="../main/message.php">留言管理</a></li>
                <li><a href="../main/setting.php">系统设置</a></li>
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
                </form>
                <?php
                if(isset($_POST['exitLogin'])){
                    $pdo->exitLogin();
                }
                ?>
                <h2 class="text-center">修改<?php echo $userArray[0]['username']; ?>用户信息</h2>
                <div class="well col-md-12">
                    <form method="post" about="">
                        <fieldset>
                            <div class="form-group">
                                <label for="name">用户名称:</label>
                                <input type="text" class="form-control" id="name" name="username" value="<?php echo $userArray[0]['username']; ?>" placeholder="UserName">
                                <p class="help-block">可以用手机号或者邮箱登陆.</p>
                            </div>
                            <div class="form-group">
                                <label for="password">密码:</label>
                                <input type="text" class="form-control" id="password" name="password" value="<?php echo $userArray[0]['password']; ?>" placeholder="PassWord">
                                <p class="help-block">密码必须大于6为.</p>
                            </div>
                            <div class="form-group">
                                <label for="password">性别:</label>
                                <select class="form-control" name="gender">
                                    <?php
                                    if($userArray[0]['gender'] == '男'){
                                        echo "
                                        <option value='男' selected='selected'>男</option>
                                        <option value='女'>女</option>
                                        ";
                                    }else{
                                        echo "
                                        <option value='男'>男</option>
                                        <option value='女' selected='selected'>女</option>
                                        ";
                                    }
                                    ?>

                                </select>
                                <p class="help-block">随便选一项吧</p>
                            </div>
                            <div class="form-group">
                                <label for="phone">手机:</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $userArray[0]['phone']; ?>" placeholder="Phone">
                                <p class="help-block">11位手机号码</p>
                            </div>
                            <div class="form-group">
                                <label for="email">邮箱:</label>
                                <input type="email" class="form-control" id="email" value="<?php echo $userArray[0]['mail']; ?>" name="email" placeholder="Email">
                                <p class="help-block">例如:geekln@163.com</p>
                            </div>
                            <div class="form-group">
                                <label for="birthday">生日:</label>
                                <input type="date" class="form-control" id="birthday" name="birthday" value="<?php echo $userArray[0]['birthday']; ?>" placeholder="出生日期">
                                <p class="help-block">例如:1998-12-20</p>
                            </div>
                            <div class="form-group">
                                <label for="password">用户权限:</label>
                                <select class="form-control" name="admin">
                                    <?php
                                    if($userArray[0]['admin'] == 0){
                                        echo "
                                        <option value='0' selected='selected'>普通用户</option>
                                        <option value='1'>管理员</option>
                                        ";
                                    }else{
                                        echo "
                                        <option value='0'>普通用户</option>
                                        <option value='1' selected='selected'>管理员</option>
                                        ";
                                    }
                                    ?>

                                </select>
                                <p class="help-block">随便选一项吧，别选错了</p>
                            </div>
                        </fieldset>
                        <button type="submit" name="button" class="btn btn-info btn-block">提交更新</button>
                    </form>
                    <br>
                    <?php
                    if(isset($_POST['button'])){
                        $username = $_POST['username'];
                        $password = $_POST['password'];
                        $gender = $_POST['gender'];
                        $phone = $_POST['phone'];
                        $email = $_POST['email'];
                        $admin = $_POST['admin'];
                        $birthday = $_POST['birthday'];

                        $sqlUpdateUser = "update users set username='$username',password='$password',gender='$gender',phone='$phone',mail='$email',birthday='$birthday',admin='$admin' where id='$id'";
                        $pdo->exec($sqlUpdateUser);

                        echo "<script>alert('修改完成，返回到用户管理首页！');location='../main/users.php'</script>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
