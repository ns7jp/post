<?php
session_start();
//セッションからログインユーザーのIDを取得する
$user_id = $_SESSION["user_id"];
//POST送信の内容を変数に入れる
$message = htmlspecialchars($_POST["message"],ENT_QUOTES);
//データベースにつなぐ
require("db.php");
//SQL文作成
$out_str = "INSERT INTO posts SET message=?,member_id=?,created=NOW()";
$kekka = $db -> prepare($out_str);
$s =  $kekka -> execute([$message,$user_id]);
// var_dump($s);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>書き込み完了</title>
</head>
<body>
    <p>書き込みました</p>
    <p><a href="index.php">戻る</a></p>
</body>
</html>