<?php
session_start(); //あとでログイン情報保存するのでセッション開始
// $_POSTで送られた内容を変数に代入する(トークンも受け取る)
$id = htmlspecialchars($_POST["id"],ENT_QUOTES);
$pass = htmlspecialchars($_POST["pass"],ENT_QUOTES);
$token = htmlspecialchars($_POST["token"],ENT_QUOTES);
// $_POSTで受け取ったトークン値と
// セッションに保存されているトークン値が同じか判定
// 違った場合は「不正アクセス」と表示する
// （ログインやエラーページにジャンプでもOK）
if($token != $_SESSION["token"]){
    
    print("不正アクセス");
    exit();
}


// var_dump($id,$pass);

//データベースにつないでデータベースのメアドと$idが一致する人を探す
require("db.php");

$out_str = "SELECT COUNT(id) as user_count FROM members WHERE email = ?";
$kekka = $db -> prepare($out_str);
$kekka -> execute([$id]);
// ↑ここで$kekka型が代わりfetch()で受け取れるようになります
$count = $kekka -> fetch(PDO::FETCH_ASSOC);
// var_dump($count);
// ↑この配列の0番目が0ならユーザーがいない、1以上なら該当ユーザーがいます
// 見つかればパスワードチェック、見つからなければログイン失敗画面へ
if($count["user_count"] < 1){
    header("Location:http://shimada.atwebpages.com/post/error.php");
    exit();
    // print("該当ユーザーなし");
    // あとでエラーページに移動するよ
}else{
    // print("該当ユーザーあり");
    // SQL文作り直し
    // ユーザーの入力したメアドと一致するレコードの
    // パスワードを取得
    //prepareとexecute使ってデータベース上のパスワード
    // を変数に代入してvar_dump
    $out_str = "SELECT id,password FROM members WHERE email = ?";
    $kekka = $db -> prepare($out_str);
    $kekka -> execute([$id]);
    $kekka2 = $kekka -> fetch(PDO::FETCH_ASSOC);
    $db_pass = $kekka2["password"];
    $db_id = $kekka2["id"];
    // var_dump($db_pass);
    // パスワードの合致をチェック
    // $passと$db_passが同じかどうかチェックする
    $ans = password_verify($pass,$db_pass);
    // var_dump($ans);
    if($ans == true){
        //セッションにユーザーのidを保存しておく
        $_SESSION["user_id"] = $db_id;
        //パスワード合致したのでトップページに
        header("Location:http://shimada.atwebpages.com/post/index.php");
        exit();
    }else{
        // パスワード違うのでエラーページに
        header("Location:http://shimada.atwebpages.com/post/error.php");
        exit();
    }

}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログインチェック画面</title>
</head>
<body>
    <p>このページは自動遷移するので将来的には表示されません</p>
</body>
</html>