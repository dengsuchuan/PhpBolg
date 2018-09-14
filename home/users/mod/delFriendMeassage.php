<?php
require '../../../public/function/ClassDb.php';

session_start();
if(@$_SESSION['username'] == null || @$_SESSION['password'] == null){
    echo "<script>location.href='../../../admin/login/index.php'; </script>";
}else{
    @$id = $_GET['id'];
    $pdo = new ClassDb();                   //实例化数据库操作对象
    if($pdo->articlesDelTable('frmessage','id',$id)){
        echo "<script>alert('成功删除留言');location.href='../myMeassage.php'; </script>";
    }else{
        echo "<script>alert('留言删除失败');location.href='../myMeassage.php'; </script>";
    }
}
