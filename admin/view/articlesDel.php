<?php
include_once '../../public/function/ClassDb.php';
session_start();
if(@$_SESSION['username'] == null || @$_SESSION['password'] == null){
    echo "<script>location.href='../login/index.php'; </script>";
}else{
    if(isset($_GET['qid'])){
$id = $_GET['qid'];
$url = "../../home/users/userArticles.php";
	}else{
$id = $_GET['id'];     //文章id
$url = "../main/articles.php";
}
    
    $pdo = new ClassDb();                   //实例化数据库操作对象
    $conn = $pdo->conn();
    $pdo->articlesDelTable('articles','id',$id); //执行删除操作
    $pdo->articlesDelTable('commentaries','articles_id',$id); //执行删除操作
    echo "<script>location.href='$url'; </script>";
}
