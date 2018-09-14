<?php
include 'ClassConn.php';

class ClassDb extends ClassConn
{
    //验证登陆
    public function login($username,$password,$ip,$admin){
        $sql = "select * from users where username='$username' and password='$password'";
        $results = $this->conn()->query($sql);
        if($results->fetchColumn()){
            //echo "<div class='alert alert-success'>登陆成功</div>";
            //重定向浏览器
            session_start();
            @$_SESSION['username'] = $username;
            @$_SESSION['password'] = $password;

            $sqlUser = "select * from users where username='$username' and password='$password' ";
            $userArray = $this->select($sqlUser);
            @$_SESSION['id'] = $userArray[0]['id'];
            $statistics = $userArray[0]['statistics'] + 1;
            $showtime=date("Y-m-d H:i", time()+7*60*60);
            $sqlUpdate = "update users set ip='$ip',statistics='$statistics',lastTime='$showtime' where username = '$username' and password = '$password'";
            $this->conn()->exec($sqlUpdate);
            if($userArray[0]['admin'] == 1){
                header("Location: ../../admin/main/basic.php");
            }elseif($userArray[0]['admin'] == 0){
                header("Location: ../../home/home.php");
            }else{
                header("Location: ../../home/home.php");
            }
            //确保重定向后，后续代码不会被执行
            exit;
        }else{
            echo "<div class='alert alert-danger'>登陆失败，请检查你的用户名或者密码是否正确...</div>";
        }
    }

    //字段匹配相同度
    public function match($table,$field,$text){  //表  字段名  内容
        $sql = "select * from $table where $field = '$text'";
        $sqlArray = $this->select($sql);
        return $sqlArray;
    }

    //注销登录状态
    public function exitLogin($url){
        session_unset();
        session_destroy();
        echo "<script>alert('已退出当前账户');location.href='$url'; </script>";
    }

    //读配置表内容
    public function readConfiguration(){
        $sql="select * from config order by id";
        $configArray = $this->select($sql);
        return $configArray;
    }

    //读文章表内容
    public function readArticles(){
        $sql="select a.id,a.title,a.content,a.time,a.state,u.username,a.class,a.commentaries from articles a inner join users u on a.user_id = u.id";
        $articlesArray = $this->conn()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        return $articlesArray;
    }

    //查询文章信息
    function articlesSelectTable($id=0){
        if($id == 0){
            $sql = "
            select a.id,a.title,a.time,a.time,a.state,b.username,a.class
            from articles a inner join users b on a.user_id = b.id;
            ";
        }else{
            $sql = "select a.id,a.title,a.time,a.time,a.state,b.username,a.class
            from articles a inner join users b on a.user_id = b.id
            where a.id = '$id';
            ";
            //单条查询
        }
        //echo $sql;
        $articleseQuery = $this->conn()->query($sql);
        $articleseArrar = $articleseQuery->fetchAll(PDO::FETCH_ASSOC);
        return $articleseArrar;

    }

    //select普通查询
    function select($sql){
        $query = $this->conn()->query($sql);
        $arrar = $query->fetchAll(PDO::FETCH_ASSOC);
        return $arrar;
    }

    //exec数据库修改
    public function exec($sql){
        if($this->conn()->exec($sql)){
            return true;
        }else{
            return false;
        }
    }

    //删除表内信息
    public function articlesDelTable($table,$field,$text){   //表名  字段名  内容
        $sql = "delete from $table where $field = '$text'";
        if($this->conn()->exec($sql)){
            return true;
        }else{
            return false;
        }

    }

    //指定字段分页
    public function friendPag($table,$field,$text,$length){ //表 指定的字段 查找的文字  每页显示的条数
        if(@$text == null){
            $sqlCountTable="select count(*) from $table order by id";       //查询数据表内容条数
            $all = $this->conn()->query($sqlCountTable)->fetchAll();
        }else{
            //$articlesSelectArray = $pdo->articlesSelectTable(0);
            $sqlCountTable="select count(*) from $table where $field like '%$text%'";       //查询数据表内容条数
            $all = $this->conn()->query($sqlCountTable)->fetchAll();
        }

        $total = $all[0][0];                    //$rowCnt数组中的第0个下标 存了表的数据条数
        //$length=20;                             //设置每页显示条数
        $page=@$_GET['p']?$_GET['p']:1;

        $offset=($page-1)*$length;      //定义个取值初始位置
        $totpage=ceil($total/$length); //总条数

        $prevpage=$page-1;
        if($page>=$totpage){
            $nextpage=$totpage;
        }else{
            $nextpage=$page+1;
        }

        $paginationArray = array(
            'offset' => $offset,
            'length' => $length,
            'total' =>$total,
            'totpage' => $totpage,
            'prevpage' =>$prevpage,
            'nextpage' => $nextpage,
            'page' => $page
        );
        return $paginationArray;

    }

    //文件上传
    public function fines($input_files,$url_files,$sql_files,$to_url,$text){ //新文件  文件路径 旧文件 跳转位置 上传失败弹出提示
        if($input_files['size']){
            $filesName = $input_files['name'];
            $filesNameInfo = pathinfo($filesName);
            $filesType = $filesNameInfo['extension'];
            $filesTemp = $input_files['tmp_name'];
            $filesHandlingName = time().'_'.mt_rand().'.'.$filesType;
            $filesUrl = $url_files.$filesHandlingName;
            if(move_uploaded_file($filesTemp,$filesUrl)){
                unlink($url_files.$sql_files);
            }else{
                echo "<script>alert('$text');location.href='$to_url'; </script>";
            }
            $files = $filesHandlingName;
        }else{
            $files = $sql_files;
        }
        return $files;
    }

}

