<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!----引入Bootstrap样式表、JavaScript和Jquery文件----->
    <link type="text/css" rel="stylesheet" href="public/Bootstrap/css/bootstrap.min.css">
    <script src="public/Bootstrap/js/jquery-3.2.1.min.js"></script>
    <script src="public/Bootstrap/js/bootstrap.min.js"></script>
    <title>MySql-安装器</title>
    <style>
        .show{border:1px red solid;background-color:#00a0dc;height:30px;}
        h1{color:rgb(55,173,217)}
    </style>
    <script>
        function inputValidation() {
            var host = document.getElementById('host').value;
            var dbuser = document.getElementById('dbuser').value;
            var dbpaw = document.getElementById('dbpaw').value;
            var dbname = document.getElementById('dbname').value;

            if(host == ""){
                document.getElementById('host_p').innerHTML = "数据库地址不能为空！"
                return false;
            }else{
                document.getElementById('host_p').innerHTML = ""
            }

            if(dbuser == ""){
                document.getElementById('dbuser_p').innerHTML = "数据库用户名不能为空！"
                return false;
            }else{
                document.getElementById('dbuser_p').innerHTML = ""

            }

            if(dbpaw == ""){
                document.getElementById('dbpaw_p').innerHTML = "数据库密码不能为空！"
                return false;
            }else{
                document.getElementById('dbpaw_p').innerHTML = ""
            }

            if(dbname == ""){
                document.getElementById('dbname_p').innerHTML = "数据库名不能为空！"
                return false;
            }else{
                document.getElementById('dbname_p').innerHTML = ""
            }
            document.getElementById("log1").innerHTML ="<div class='alert alert-info'>正在连接MySql...</div>";
        }
    </script>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="well col-md-6 col-md-offset-3">
            <div class="page-header">
                <h1 class="text-center">MySql-安装器</h1>
                <hr>
                <form role="form" enctype="multipart/form-data" method="post" action="install.php" onsubmit="return inputValidation()">
                    <fieldset>
                        <div class="form-group">
                            <label for="host">数据库地址:</label>
                            <input type="text" class="form-control" id="host" name="host" placeholder="默认请输入: localhost">
                            <p class="help-block" style="color:red" id="host_p"></p>
                        </div>
                        <div class="form-group">
                            <label for="dbuser">数据库用户:</label>
                            <input type="text" class="form-control" id="dbuser" name="dbuser" placeholder="默认请输入:  root">
                            <p class="help-block" style="color:red;" id="dbuser_p"></p>
                        </div>
                        <div class="form-group">
                            <label for="dbpaw">数据库密码:</label>
                            <input type="text" class="form-control" id="dbpaw" name="dbpaw" placeholder="创建之初设置的连接密码">
                            <p class="help-block" style="color:red;" id="dbpaw_p"></p>
                        </div>
                        <div class="form-group">
                            <label for="dbname">默认数据库创建名称:</label>
                            <input type="text" class="form-control" id="dbname" name="dbname" placeholder="指定一个数据库名称">
                            <p class="help-block" style="color:red" id="dbname_p"></p>
                        </div>
                    </fieldset>
                    <button type="submit" name="button" class="btn btn-info btn-block">提交</button>
                </form>
            </div>
            <div id="log1"></div>
            <div class="text-center">
                <div id="log2"></div>
                <div id="log3"></div>
                <div id="log4"></div>
                <div id="log5"></div>
                <div id="log6"></div>
            </div>
            <?php
            if(isset($_POST['button'])){
                $host = $_POST['host'];
                $dbuser = $_POST['dbuser'];
                $dbpaw = $_POST['dbpaw'];
                $dbname = $_POST['dbname'];
                //echo $host."<br>".$dbuser."<br>".$dbpaw."<br>".$dbname."<br>";
                echo "<script>document.getElementById('log1').innerHTML = ' '</script>";
                echo "<script>document.getElementById('log2').innerHTML = ' '</script>";
                echo "<script>document.getElementById('log3').innerHTML = ' '</script>";
                echo "<script>document.getElementById('log4').innerHTML = ' '</script>";
                echo "<script>document.getElementById('log5').innerHTML = ' '</script>";
                //---------开始输出日志----------
                if($conn = @mysqli_connect($host,$dbuser,$dbpaw)){
                    echo '<script>document.getElementById("log1").innerHTML ="<div class=\'alert alert-success\'>MySql连接成功</div>"</script>';
                    //--------下面判断数据库是否存在，如果存在就覆盖安装
                    if(@mysqli_query($conn,"drop database if exists $dbname"));

                    echo '<script>document.getElementById("log2").innerHTML ="<div class=\'alert alert-info\'>尝试创建数据库......</div>"</script>';
                    $sqlCreateDatabase = "create database $dbname";
                    if($createDatabaseQuery = mysqli_query($conn,$sqlCreateDatabase)){
                        echo '<script>document.getElementById("log2").innerHTML ="<div class=\'alert alert-success\'>创建数据库成功</div>"</script>';



                        /*----------里面开始写Sql语句创建相关的数据表---------*/
                        //V-----------------创建一个用户表-------------------V
                        $user = "
                            CREATE TABLE users (
                                  id int(10) unsigned NOT NULL AUTO_INCREMENT,
                                  username varchar(20) NOT NULL,
                                  password varchar(50) NOT NULL,
                                  gender varchar(4) NOT NULL,
                                  birthday date NOT NULL,
                                  phone varchar(20) NOT NULL,
                                  mail varchar(50) NOT NULL,
                                  portrait varchar(200) NOT NULL,
                                  instruction varchar(20) DEFAULT NULL,
                                  admin varchar(5) NOT NULL,
                                  ip varchar(40) DEFAULT NULL,
                                  position varchar(100) DEFAULT NULL,
                                  statistics int(10) DEFAULT NULL,
                                  lastTime varchar(20) DEFAULT NULL,
                                  PRIMARY KEY (`id`)
                                );
                        ";

                        //V-----------------创建一个文章表-------------------V
                        $articles = "
                            CREATE TABLE articles (
                                  id int(10) unsigned NOT NULL AUTO_INCREMENT,
                                  title varchar(100) NOT NULL,
                                  content text NOT NULL,
                                  time varchar(20) NOT NULL,
                                  state varchar(11) NOT NULL,
                                  user_id int(11) NOT NULL,
                                  class varchar(10) NOT NULL,
                                  commentaries int(10) DEFAULT NULL,
                                  PRIMARY KEY (`id`)
                                );
                        ";

                        //V-----------------创建一个评论表-------------------V
                        $commentaries = "
                        CREATE TABLE commentaries (
                          id int(10) unsigned NOT NULL AUTO_INCREMENT,
                          articles_id int(11) NOT NULL,
                          content varchar(200) NOT NULL,
                          time date NOT NULL,
                          user_id int(11) NOT NULL,
                          PRIMARY KEY (`id`)
                        );
                        ";

                        //V-----------------创建一个网站留言表-------------------V
                        $message = "
                        CREATE TABLE message (
                          id int(10) unsigned NOT NULL AUTO_INCREMENT,
                          content varchar(200) NOT NULL,
                          time varchar(30) NOT NULL,
                          user_id int(11) NOT NULL,
                          ip varchar(30) DEFAULT NULL,
                          PRIMARY KEY (`id`)
                        );
                        ";


                        //V-----------------创建一个配置表-------------------V
                        $config = "
                         CREATE TABLE config (
                          id int(10) unsigned NOT NULL AUTO_INCREMENT,
                          title varchar(20) NOT NULL,
                          icon varchar(50) NOT NULL,
                          logo varchar(50) NOT NULL,
                          domain varchar(20) NOT NULL,
                          mail varchar(20) NOT NULL,
                          record varchar(20) NOT NULL,
                          inform varchar(50) DEFAULT NULL,
                          curtain varchar(200) DEFAULT NULL,
                          music varchar(200) NOT NULL,
                          frontRecord varchar(50) DEFAULT NULL,
                          PRIMARY KEY (`id`)
                        );
                        ";

                        //好友关系表
                        $friends = "
                          CREATE TABLE friends (
                          id int(10) unsigned NOT NULL AUTO_INCREMENT,
                          user_id int(11) DEFAULT NULL,
                          friends_id int(11) DEFAULT NULL,
                          PRIMARY KEY (`id`)
                        );
                         ";

                        //好友留言表
                        $frMessage = "
                         CREATE TABLE frmessage (
                          id int(10) unsigned NOT NULL AUTO_INCREMENT,
                          user_id int(11) DEFAULT NULL,
                          friends_id int(11) DEFAULT NULL,
                          content varchar(200) DEFAULT NULL,
                          time varchar(20) DEFAULT NULL,
                          PRIMARY KEY (`id`)
                          );
                           ";

                        //V-----------------创建一个工具表-------------------V
                        $toolsSql = "
                        CREATE TABLE tools (
                                  id int(10) unsigned NOT NULL AUTO_INCREMENT,
                                  title varchar(10) NOT NULL,
                                  info varchar(20) NOT NULL,
                                  images varchar(200) NOT NULL,
                                  url varchar(200) NOT NULL,
                                  PRIMARY KEY (`id`)
                                );
                            ";

                        /*------------------------MySql语句完成-------------------------*/
                        echo '<script>document.getElementById("log3").innerHTML ="<div class=\'alert alert-info\'>准备创建数据表......</div>"</script>';
                        @mysqli_select_db($conn, $dbname);
                        @$userQuery = mysqli_query($conn,$user);                    //用户表
                        @$articlesQuery = mysqli_query($conn,$articles);            //文章表
                        @$commentariesQuery = mysqli_query($conn,$commentaries);    //评论表
                        @$messageQuery = mysqli_query($conn,$message);              //网站留言表
                        @$configQuery = mysqli_query($conn,$config);                //配置表
                        @$friendsQuery = mysqli_query($conn,$friends);              //好友关系表
                        @$frMessageQuery = mysqli_query($conn,$frMessage);          //好友留言表
                        @$toolsQuery = mysqli_query($conn,$toolsSql);          //好友留言表

                        mysqli_query($conn,"
                                        insert into users(username,password,gender,birthday,phone,mail,portrait,instruction,admin)
                                        values('admin','".md5('admin')."','男','2017-12-20','13404086094','admin@admin.com','1.png','说明测试','1');
                                        ");//默认的用户
                        mysqli_query($conn,"insert into config values ('1', '静影探风', '1.png', '1.ico', 'geekln.cn', 'test@test.com', '测试', '蜀备XXXXX', 'jm.png', '1.mp3', '博客系统目前处于测试阶段，您现在看到的是数据库读取公告测试！');");  //默认配置表数据
                        mysqli_query($conn,"
                        INSERT INTO tools VALUES 
                            ('0', 'H5游戏', '搭建至Geekln域名下的小游戏', 'games.png', 'http://games.geekln.cn'),
                            ('6', 'Jetbrains', '可激活Jetbrains全系列', 'Jetbrains.jpg', 'http://idea.liyang.io/'),
                            ('2', '编码在线转换器', '在线转换各种编码', 'code.jpg', 'http://tool.oschina.net/encode'),
                            ('3', '文字加密解密', 'url,base64,md5加密解密', 'jmjm.png', 'https://tool.lu/encdec/'),
                            ('4', '进制转换', '常用进制转换工具', 'jzzh.png', 'https://tool.lu/hexconvert/'),
                            ('5', 'IP地址查询', '查询指定IP地址信息', 'ip.jpg', 'https://tool.lu/ip/');
                        ");//默认的工具表
                        if($toolsQuery&&$friendsQuery&&$frMessageQuery&&$userQuery&&$articlesQuery&&$commentariesQuery&&$messageQuery&&$configQuery){
                            echo '<script>document.getElementById("log3").innerHTML ="<div class=\'alert alert-success\'>数据表创建成功</div>"</script>';
                            $source = "backup.php";
                            $home = "index.php";
                            //chmod("public",0777);  设置文件权限
                            //知识点  chmod权限设置
                            /*

                            --------------------->
                            第一个数字永远是 0
                            第二个数字规定所有者的权限
                            第二个数字规定所有者所属的用户组的权限
                            第四个数字规定其他所有人的权限
                            可能的值（如需设置多个权限，请对下面的数字进行总计）：
                            1 - 执行权限
                            2 - 写权限
                            4 - 读权限
                            ----------------------->

                            chmod("test.txt",0600); // 所有者可读写，其他人没有任何权限

                            chmod("test.txt",0644); // 所有者可读写，其他人可读

                            chmod("test.txt",0755); // 所有者有所有权限，其他所有人可读和执行

                            chmod("test.txt",0740); // 所有者有所有权限，所有者所在的组可读
                            */
                            if(@copy($source,$home)){
                                echo '<script>document.getElementById("log4").innerHTML ="<div class=\'alert alert-success\'>主页文件已生成</div>"</script>';
                                echo '<script>document.getElementById("log5").innerHTML ="<div class=\'alert alert-info\'>开始生成配置文件......</div>"</script>';

                                $connFile = fopen("public/function/ClassConn.php", "w");
                                $code = '
                                      <?php  
                                    header("Content-type: text/html; charset=utf-8"); 
                                    class ClassConn{
                                        public function conn(){'.'
                                             $dbms="mysql";
                                             $host="'.$host.'";
                                             $dbuser="'.$dbuser.'";
                                             $dbpaw="'.$dbpaw.'";
                                             $dbname="'.$dbname.'"; 
                                             $dsn="$dbms:host=$host;dbname=$dbname";
                                             
                                             try{
                                                    $pdo = new PDO($dsn,$dbuser,$dbpaw);
                                                    //echo "数据库连接成功";
                                                    return $pdo;
                                                }catch (PDOException $e){
                                                    die(\'发生了不可预料的错误:<br>\'.$e->getMessage().\'<br>\');
                                             }
                                        }  
                                    }   
                                    //$conn  = new ClassConn();
                                    //$conn->conn();
                                    ?>';
                                @unlink($connFile);
                                if(fwrite($connFile, $code)){
                                    echo '<script>document.getElementById("log5").innerHTML ="<div class=\'alert alert-success\'>数据库配置文件生成成功</div>"</script>';
                                    fclose($connFile);
                                    //echo '<script>document.getElementById("log6").innerHTML ="<div class=\'alert alert-success text-center\'>初始化完成,点击跳转到首页</div>"</script>';
                                    echo "<script>document.getElementById('log1').innerHTML = ' '</script>";
                                    echo "<script>document.getElementById('log2').innerHTML = ' '</script>";
                                    echo "<script>document.getElementById('log3').innerHTML = ' '</script>";
                                    echo "<script>document.getElementById('log4').innerHTML = ' '</script>";
                                    echo "<script>document.getElementById('log5').innerHTML = ' '</script>";
                                    echo '<script>document.getElementById("log1").innerHTML ="<div class=\'alert alert-success\'>初始化完成,务必记住以下信息:<br>初始用户名:admin<br>初始密码:admin<br>登陆后台请删除初始用户创建管理员账户!</div>"</script>';
                                    echo '<script>document.getElementById("log2").innerHTML =" <a role=\'button\' class=\'btn btn-success\' href=\'admin/login/index.php\'>点击跳转到管理页面</a>"</script>';
                                }else{
                                    echo '<script>document.getElementById("log5").innerHTML ="<div class=\'alert alert-danger\'>数据库配置文件生成失败</div>"</script>';
                                }

                            }else{
                                echo '<script>document.getElementById("log4").innerHTML ="<div class=\'alert alert-danger\'>主页生成失败 <br> 手动生成请将根目录下backup.php文件重命名为index.php</div>"</script>';
                            }
                        }else{
                            echo '<script>document.getElementById("log3").innerHTML ="<div class=\'alert alert-danger\'>数据表创建失败</div>"</script>';
                            echo mysqli_error($conn);
                        }

                    }else{
                        echo '<script>document.getElementById("log2").innerHTML ="<div class=\'alert alert-danger\'>创建数据库失败</div>"</script>';
                    }
                }else{
                    echo '<script>document.getElementById("log1").innerHTML ="<div class=\'alert alert-danger\'>MySql数据连接失败</div>"</script>';
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