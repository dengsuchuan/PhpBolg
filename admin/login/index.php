<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!----引入Bootstrap样式表、JavaScript和Jquery文件----->
    <link type="text/css" rel="stylesheet" href="../../public/Bootstrap/css/bootstrap.min.css">
    <script src="../../public/Bootstrap/js/jquery-3.2.1.min.js"></script>
    <script src="../../public/Bootstrap/js/bootstrap.min.js"></script>
    <title>登陆</title>
    <style>
        body{background-color: #456;}
        .container{
            display:table;
            height:100%;
            padding-top:150px;
        }

        .row{
            display: table-cell;
            vertical-align: middle;
        }
        .row-centered {
            text-align:center;
        }
        .col-centered {
            display:inline-block;
            float:none;
            text-align:left;
            margin-right:-4px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row row-centered">
        <div class="well col-md-6 col-centered">
            <h2 class="text-center">登录</h2>
            <form action="" method="post" role="form">
                <div class="input-group input-group-md">
                    <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-user" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" id="userid" name="username" placeholder="请输入用户名"/>
                </div>
                <br>
                <div class="input-group input-group-md">
                    <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-lock"></i></span>
                    <input type="password" class="form-control" id="password" name="password" placeholder="请输入密码"/>
                </div>
                <br/>
                <button type="submit" class="btn btn-success btn-block" name="login">登录</button>
            </form>
            <br>
            <?php
            if(isset($_POST['login'])){
                require '../../public/function/ClassDb.php';
                require '../../public/function/position.php';
                $pdo = new position();
                $ip = $pdo->getip();
                $username = $_POST['username'];
                $password = md5($_POST['password']);
                $conn = new ClassDb();
                $conn->login($username,$password,$ip,$admin);
            }
            ?>
            <hr>
            <p class="text-center"><a href="https://www.geekln.cn" title="站长博客，欢迎访问...">静影探风</a>   |   <var>孤独的魅影，探寻风的足迹！</var></p>
        </div>
    </div>
</div>


</body>
</html>