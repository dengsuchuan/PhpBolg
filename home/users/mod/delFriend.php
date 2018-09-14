<?php
require '../../../public/function/ClassDb.php';
session_start();
if(@$_SESSION['username'] == null || @$_SESSION['password'] == null){
    echo "<script>location.href='../../../admin/login/index.php'; </script>";
}else{
    @$fid = $_GET['fid'];
    $pdo = new ClassDb();                   //实例化数据库操作对象
    if($pdo->articlesDelTable('friends',$fid)){
        echo "<script>alert('成功解除好友关系');location.href='../userFriend.php'; </script>";
    }else{
        echo "<script>alert('解除好友关系失败');location.href='../userFriend.php'; </script>";
    }
}


