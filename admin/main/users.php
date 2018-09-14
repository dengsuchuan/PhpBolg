<?php
/**
 * 媒体管理
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
    <title>仪表盘-用户管理</title>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="well col-md-12">
            <ul class="nav nav-tabs">
                <li><a href="basic.php">基础信息</a></li>
                <li><a href="articles.php">文章管理</a></li>
                <li class="active"><a href="users.php">用户管理</a></li>
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
                    $pdo->exitLogin('../login/index.php');
                }
                ?>
                <h2 class="text-center">用户管理</h2>
                <div class="well col-md-6">
                    <form method="post" about="">
                        <fieldset>
                            <legend><span class="glyphicon glyphicon-user"></span>添加用户</legend>
                            <div class="form-group">
                                <label for="name">用户名称:</label>
                                <input type="text" class="form-control" id="name" name="username" placeholder="UserName">
                                <p class="help-block">可以用手机号或者邮箱登陆.</p>
                            </div>
                            <div class="form-group">
                                <label for="password">密码:</label>
                                <input type="text" class="form-control" id="password" name="password" placeholder="PassWord">
                                <p class="help-block">密码必须大于6为.</p>
                            </div>
                            <div class="form-group">
                                <label for="password">性别:</label>
                                <select class="form-control" name="gender">
                                    <option value="男">男</option>
                                    <option value="女">女</option>
                                </select>
                                <p class="help-block">随便选一项吧</p>
                            </div>
                            <div class="form-group">
                                <label for="phone">手机:</label>
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone">
                                <p class="help-block">11位手机号码</p>
                            </div>
                            <div class="form-group">
                                <label for="email">邮箱:</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                                <p class="help-block">例如:geekln@163.com</p>
                            </div>
                            <div class="form-group">
                                <label for="birthday">生日:</label>
                                <input type="date" class="form-control" id="birthday" name="birthday" placeholder="Email">
                                <p class="help-block">例如:geekln@163.com</p>
                            </div>
                            <div class="form-group">
                                <label for="password">用户权限:</label>
                                <select class="form-control" name="admin">
                                    <option value="0">普通用户</option>
                                    <option value="1">管理员</option>
                                </select>
                                <p class="help-block">随便选一项吧，别选错了</p>
                            </div>
                        </fieldset>
                        <button type="submit" name="button" class="btn btn-info btn-block">提交</button>
                    </form>
                    <br>
                    <?php
                    if(isset($_POST['button'])){
                        $username = $_POST['username'];
                        $password = md5($_POST['password']);
                        $gender = $_POST['gender'];
                        $phone = $_POST['phone'];
                        $email = $_POST['email'];
                        $admin = $_POST['admin'];
                        $birthday = $_POST['birthday'];

                        $userArray = $pdo->match('users','username',$username);
                        $userArray2 = $pdo->match('users','mail',$email);

                        if($userArray){
                            echo "<div class='alert alert-danger'>警告:该用户名已被注册！</div>";
                        }else{
                            if($userArray2){
                                echo "<div class='alert alert-danger'>警告:该邮箱已被注册！</div>";
                            }else{
                                $sql = "insert into users(username,password,gender,birthday,phone,mail,portrait,instruction,admin) 
                                                    values('$username','$password','$gender','0000-00-00','$phone','$email','default.png','这个用户很懒，什么都没留下','0')";
                                if(!$pdo->exec($sql)){
                                    echo "<div class='alert alert-danger'>警告:注册失败！</div>";
                                }else{
                                    echo "<div class='alert alert-success'>用户添加成功</div>";
                                }
                            }
                        }
                    }
                    ?>
                </div>

                <div class="well col-md-6">
                    <legend><span class="glyphicon glyphicon-list-alt"></span>管理用户</legend>
                    <form method="post" about="users.php">
                        <div class="input-group">
                            <input type="text" name="username" class="form-control" placeholder="查找的用户名...">
                            <span class="input-group-btn">
                                <button type="submit" name="go" class="btn btn-default">查找</button>
                            </span>
                        </div>
                    </form>
                    <br>

                    <table class="table table-hover table-bordered text-center">
                        <thead>
                        <tr class="active">
                            <th class="text-center">用户名</th>
                            <th class="text-center">邮箱</th>
                            <th class="text-center">操作</th>
                        </tr>
                        </thead>
                    <?php
                    if(isset($_POST['go'])){
                        $username = $_POST['username'];
                        $sqlCountLimit = "select * from users where username = '$username'";
                        $allLimitArray = $pdo->select($sqlCountLimit);

                        foreach($allLimitArray as $value){
                        echo "<tbody>
                    <tr class='info'>";
                        echo "<td>".$value['username']."</td>";
                        echo "<td><a href='mailto:".$value['mail']."'>".$value['mail']."</a></td>";
                        echo "
                        <form method='post' about='articles.php'>
                            <td class='text-center'>
                                <div class='btn-group'>
                                    <button class='btn btn-info btn-xs'><a target='_blank' href='../view/userLook.php?id=".$value['id']."&username=".$value['username']."'>查看</a></button>
                                    <button class='btn btn-info dropdown-toggle btn-xs' data-toggle='dropdown' id='dropdownMenu2'
                                            data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
                                    <span class='caret'></span>
                                    </button>
                                    <ul class='dropdown-menu'>
                                        <li><a href='../view/userDel.php?id=".$value['id']."'>删除</a></li>
                                        <li><a target='_blank' href='../view/userEdit.php?id=".$value['id']."'>编辑</a></li>
                                    </ul>
                                </div>
                            </td>
                        </form>
                    </tr>";
                        }
                    }?>
                        </tbody>
                    </table>
                </div>
                <div class="well col-md-6">
                    <?php
                    $paginationArray = $pdo->friendPag('users','username',@$text,12);
                    $offset = $paginationArray['offset'];
                    $length = $paginationArray['length'];
                    $total = $paginationArray['total'];
                    $totpage = $paginationArray['totpage'];
                    $prevpage = $paginationArray['prevpage'];
                    $nextpage = $paginationArray['nextpage'];
                    $page = $paginationArray['page'];
                    ?>
                    <legend><span class="glyphicon glyphicon-heart"></span>所有用户&nbsp;|&nbsp;<a>共有<?php echo $total ?>位</a></legend>
                    <table class="table table-hover table-bordered text-center">
                        <thead>
                        <tr class="active">
                            <th class="text-center">编号</th>
                            <th class="text-center">用户名</th>
                            <th class="text-center">邮箱</th>
                            <th class="text-center">操作</th>
                        </tr>
                        </thead>
                        <nav>
                            <ul class="pager">
                                <a>共<?php echo $totpage ?>页</a>&nbsp;|
                                <li><a href='users.php?p=1'>首页</a></li>
                                <li><a href='users.php?p=<?php echo $prevpage ?>'>上一页</a></li>
                                <li><a href='users.php?p=<?php echo $nextpage ?>'>下一页</a></li>
                                <li><a href='users.php?p=<?php echo $totpage ?>'>尾部</a></li>
                                &nbsp;|&nbsp;<a>第<?php echo $page ?>页</a>
                            </ul>
                        </nav>
                        <?php
                        $sqlCountLimit = "select * from users limit $offset,$length;";
                        $allLimitArray = $pdo->select($sqlCountLimit);

                        foreach($allLimitArray as $value){
                            echo "<tbody>
                            <tr class='info'>";
                            echo "<td>".$value['id']."</td>";
                            echo "<td>".$value['username']."</td>";      //多表查询 查用户名
                            echo "<td><a href='mailto:".$value['mail']."'>".$value['mail']."</td>";
                            echo "
                    <form method='post' about='articles.php'>
                        <td class='text-center'>
                            <div class='btn-group'>
                                <button class='btn btn-info btn-xs'><a target='_blank' href='../view/userLook.php?id=".$value['id']."&username=".$value['username']."'>查看</a></button>
                                <button class='btn btn-info dropdown-toggle btn-xs' data-toggle='dropdown' id='dropdownMenu2'
                                        data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
                                    <span class='caret'></span>
                                </button>
                                <ul class='dropdown-menu'>
                                    <li><a href='../view/userDel.php?id=".$value['id']."'>删除</a></li>
                                    <li><a target='_blank' href='../view/userEdit.php?id=".$value['id']."'>编辑</a></li>
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
</div>

</body>
</html>
