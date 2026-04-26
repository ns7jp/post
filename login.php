<?php
// セッション使うのでスタート
session_start();
$toke_byte = random_bytes(16);
$csrf_token = bin2hex($toke_byte);
// ↑これをセッションとhiddenタグに渡す
$_SESSION["token"] = $csrf_token;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン - スタイリッシュ掲示板</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🔐 ログイン</h1>
            <p class="welcome-text">スタイリッシュ掲示板へようこそ</p>
        </header>

        <div class="post-form">
            <h2>ログイン情報を入力</h2>
            <form action="check.php" method="post">
                <div class="form-group">
                    <label for="id">📧 ログインID</label>
                    <input type="text" name="id" id="id" placeholder="メールアドレスを入力" required>
                </div>
                
                <div class="form-group">
                    <label for="pass">🔑 パスワード</label>
                    <input type="password" name="pass" id="pass" placeholder="パスワードを入力" required>
                </div>
                
                <input type="hidden" name="token" value="<?php print($csrf_token); ?>">
                
                <p style="text-align: center; margin-top: 20px;">
                    <button type="submit">ログイン</button>
                </p>
            </form>
            
            <p style="text-align: center; margin-top: 20px; color: #666;">
                アカウントをお持ちでない方は <a href="input.php" style="color: #667eea; font-weight: bold;">新規登録</a>
            </p>
        </div>
    </div>
</body>
</html>
