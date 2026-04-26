<?php
// 1.$_POSTで送られた内容を変数に代入
$user = htmlspecialchars($_POST["name"],ENT_QUOTES);
$mail = htmlspecialchars($_POST["mail"],ENT_QUOTES);
$pass = htmlspecialchars($_POST["pass"],ENT_QUOTES);
// 暗号化しておく
$pass2 = password_hash($pass,PASSWORD_DEFAULT);
$img = htmlspecialchars($_POST["img"],ENT_QUOTES);
// var_dump($user,$mail,$pass2,$img);

// 将来的にはここでパスワード暗号化します
// データベースにつなぐ（ためにファイル作ります）
require("db.php");

//SQL文作成→prepare→execute
$out_str = "INSERT INTO members SET name=?,email=?,password=?,picture=?,created=NOW()";
$kekka = $db -> prepare($out_str);
$s = $kekka -> execute([$user,$mail,$pass2,$img]);

//ログインページに自動ジャンプ(上にあるvar_dump消す)
header("location:http://shimada.atwebpages.com/post/login.php");
exit(); //念のため
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登録完了画面</title>
</head>
<body>
    <h1>登録完了(将来的にはするはず)</h1>
    <p>将来的にはこのページは自動遷移で見えなくなるよ。</p>
</body>
</html>