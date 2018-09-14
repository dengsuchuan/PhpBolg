<?php
include_once '../../public/function/ClassDb.php';
$id = $_GET['id'];
$pdo = new ClassDb();                   //实例化数据库操作对象
$conn = $pdo->conn();

$pdo->articlesDelTable('message','id',$id); //执行删除操作

header("Location: ../main/message.php");