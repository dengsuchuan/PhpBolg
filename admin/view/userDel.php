<?php
include_once '../../public/function/ClassDb.php';
session_start();
if(@$_SESSION['username'] == null || @$_SESSION['password'] == null){
    echo "<script>location.href='../login/index.php'; </script>";
}else{
    $id = $_GET['id'];
    $pdo = new ClassDb();                   //实例化数据库操作对象
    $conn = $pdo->conn();

    $pdo->articlesDelTable('users','id',$id); //执行删除操作
    $pdo->articlesDelTable('articles','user_id',$id); //执行删除操作
    $pdo->articlesDelTable('message','user_id',$id); //执行删除操作

    header("Location: ../main/users.php");
}
