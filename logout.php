<?php
//セッション開始
session_start();
//セッションにある$_SESSION["user_id"]削除(P117)
unset($_SESSION["user_id"]); //方法1
//session_unset(); //方法2
//session_destroy(); //方法3
header("location:http://shimada.atwebpages.com/post/login.php"); //自動遷移
// ログアウトしましたと表示してもいいし、
// 自動でログインページに遷移してもいい
