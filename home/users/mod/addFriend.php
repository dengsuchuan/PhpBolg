<?php
require '../../../public/function/ClassDb.php';
session_start();
if(@$_SESSION['username'] == null || @$_SESSION['password'] == null){
    echo "<script>location.href='../../../admin/login/index.php'; </script>";
}else{
    @$uid = $_GET['uid'];
    @$fruid = $_GET['fruid'];
    $pdo = new ClassDb();                   //实例化数据库操作对象
    $sql = "select * from friends where user_id = '$uid' and friends_id = '$fruid'";
    $friendArray = $pdo->select($sql);
    if($friendArray){
        echo "<script>alert('已经是好友，无需添加');location.href='../userFriend.php'; </script>";
    }else{
        $sqlAddFriend = "insert into friends(user_id,friends_id) values('$uid','$fruid')";
        if($pdo->exec($sqlAddFriend)){
            echo "<script>alert('添加成功');location.href='../userFriend.php'; </script>";
        }else{
            echo "<script>alert('添加失败');location.href='../userFriend.php'; </script>";

        }
    }
}
