<?php
require '../../public/function/ClassDb.php';
$pdo = new ClassDb();
?>

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
    <title>用户注册</title>
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
    <script>
        function tijiao() {
            var username = document.getElementById('username').value;
            var paw01 = document.getElementById('paw01').value;
            var paw02 = document.getElementById('paw02').value;
            var date = document.getElementById('date').value;
            var email = document.getElementById('email').value;
            var phone = document.getElementById('phone').value;

            if(username == " " || username.length < 3){
               document.getElementById('username_log').innerHTML = "用户名错误，请检查！";
               return false;
            }else{
                document.getElementById('username_log').innerHTML = " ";

            }

            if(paw01 == "" || paw01.length < 6){
                document.getElementById('paw01_log').innerHTML = "密码框至少6位且不能为空";
                return false;
            }else{
                document.getElementById('paw01_log').innerHTML = " ";
            }

            if(paw02 == "" || paw02.length < 6){
                document.getElementById('paw02_log').innerHTML = "密码框至少6位且不能为空";
                return false;
            }else{
                document.getElementById('paw02_log').innerHTML = " ";
            }

            if(paw01 != paw02){
                document.getElementById('paw02_log').innerHTML = "两次密码不一致！";
                return false;
            }else{
                document.getElementById('paw02_log').innerHTML = " ";
            }

            if(email == ""){
                document.getElementById('email_log').innerHTML = "未输入邮箱地址";
                return false;
            }else{
                document.getElementById('email_log').innerHTML = " ";
            }

            if(date == ""){
                document.getElementById('date_log').innerHTML = "请选择出生日期";
                return false;
            }else{
                document.getElementById('date_log').innerHTML = "";
            }

            if(phone == ""){
                document.getElementById('phone_log').innerHTML = "未输入手机号";
                return false;
            }else{
                document.getElementById('phone_log').innerHTML = " ";
            }

        }
    </script>
</head>
<body>
<div class="container">
    <div class="row row-centered">
        <div class="well col-md-9 col-centered">
            <h2 class="text-center">用户注册</h2>
            <form action="reg.php" method="post" role="form" onsubmit="return tijiao()">
                <div class="input-group input-group-md">
                    <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-user" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" id="username" name="username" placeholder="请输入用户名,至少5位字符"/>
                </div>
                <a id="username_log"> </a>
                <br>
                <div class="input-group input-group-md">
                    <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-lock"></i></span>
                    <input type="password" class="form-control" id="paw01" name="paw01" placeholder="请输入密码"/>
                </div>
                <a id="paw01_log"> </a>
                <br/>
                <div class="input-group input-group-md">
                    <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-lock"></i></span>
                    <input type="password" class="form-control" id="paw02" name="paw02" placeholder="再次输入密码"/>
                </div>
                <a id="paw02_log"> </a>
                <br/>
                <div class="input-group input-group-md">
                    <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-heart-empty"></i></span>
                    <select class="form-control" name="gender">
                        <option value="男" selected="selected">男</option>
                        <option value="女">女</option>
                    </select>
                </div>
                <br>
                <div class="input-group input-group-md">
                    <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-envelope"></i></span>
                    <input type="email" class="form-control" id="email" name="email" placeholder="邮箱"/>
                </div>
                <a id="email_log"> </a>
                <br/>
                <div class="input-group input-group-md">
                    <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-time"></i></span>
                    <input type="date" class="form-control" id="date" name="date"/>
                </div>
                <a id="date_log"> </a>
                <br/>
                <div class="input-group input-group-md">
                    <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-earphone"></i></span>
                    <input type="number" class="form-control" id="phone" name="phone"/>
                </div>
                <a id="phone_log"> </a>
                <br/>

                <button type="submit" class="btn btn-success btn-block" name="reg" onclick="tijiao()">提交注册</button>
            </form>
            <br>
            <?php
            if(isset($_POST['reg'])){
                $username = $_POST['username'];
                $password = md5($_POST['paw02']);
                $gender = $_POST['gender'];
                $date = $_POST['date'];
                $email = $_POST['email'];
                $phone = $_POST['phone'];

                $userArray = $pdo->match('users','username',$username);
                $userArray2 = $pdo->match('users','mail',$email);

                if($userArray){
                    echo "<div class='alert alert-danger'>警告:该用户名已被注册！</div>";
                }else{
                    if($userArray2){
                        echo "<div class='alert alert-danger'>警告:该邮箱已被注册！</div>";
                    }else{
                        $sql = "insert into users(username,password,gender,birthday,phone,mail,portrait,instruction,admin) 
                        values('$username','$password','$gender','$date','$phone','$email','default.png','这个用户很懒，什么都没留下。','0')";
                        if(!$pdo->exec($sql)){
                            echo "<div class='alert alert-danger'>警告:注册失败！</div>";
                        }else{
                            echo "<script>alert('注册成功！');location.href='../home.php'; </script>";
                        }
                    }
                }
            }
            ?>
            <hr>
            <p class="text-center"><a href="https://www.geekln.cn" title="站长博客，欢迎访问...">静影探风</a>   |   <var>孤独的魅影，探寻风的足迹！</var></p>
        </div>
    </div>
</div>


</body>
</html>