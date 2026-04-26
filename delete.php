<?php
$id = htmlspecialchars($_GET["id"],ENT_QUOTES);
//データベースにつなぐ
require("db.php");
//SQL文→prepre→execute
//(GET送信使っているのでプレースホルダ使いましょう)
$out_str = "DELETE FROM posts WHERE id = ?";
$kekka = $db -> prepare($out_str);
$s = $kekka -> execute([$id]);
// var_dump($s);
// ↑$sがtrueなら削除成功(データベースで確認しよう)
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>投稿の削除</title>
</head>
<body>
    <h1>投稿の削除</h1>
    <p>削除しました。</p>
    <p><a href="index.php">トップページに戻る</a></p>
</body>
</html>