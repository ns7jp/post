<?php
// 前提:新規登録で選ぶ画像はpngまたはjpgにしてください。
// 1.POST送信で送られた名前・メアド・パスワードを変数に代入
$user = htmlspecialchars($_POST["name"],ENT_QUOTES);
$mail = htmlspecialchars($_POST["mail"],ENT_QUOTES);
$pass = htmlspecialchars($_POST["pass"],ENT_QUOTES);

// 画像アップロード処理
$image_name = "";
if(isset($_FILES["img"]) && $_FILES["img"]["error"] == 0){
    // 拡張子取ってくる
    $ext = substr($_FILES["img"]["name"],-4);
    // 重複防止にファイル名はtime関数使う
    $name = time();
    // 画像をimageフォルダに名前を付けて保存する
    move_uploaded_file($_FILES["img"]["tmp_name"],"image/{$name}{$ext}");
    $image_name = "{$name}{$ext}";
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>確認画面 - スタイリッシュ掲示板</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>✅ 確認画面</h1>
            <p class="welcome-text">登録する内容を確認してください</p>
        </header>

        <div class="post-form">
            <h2>登録情報の確認</h2>
            
            <div style="background: white; padding: 25px; border-radius: 10px; margin-bottom: 20px;">
                <div style="margin-bottom: 20px;">
                    <p style="color: #667eea; font-weight: bold; margin-bottom: 8px;">👤 ニックネーム</p>
                    <p style="font-size: 1.1em;"><?php echo htmlspecialchars($user); ?></p>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <p style="color: #667eea; font-weight: bold; margin-bottom: 8px;">📧 メールアドレス</p>
                    <p style="font-size: 1.1em;"><?php echo htmlspecialchars($mail); ?></p>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <p style="color: #667eea; font-weight: bold; margin-bottom: 8px;">🔑 パスワード</p>
                    <p style="font-size: 1.1em;"><?php echo str_repeat("●", strlen($pass)); ?></p>
                </div>
                
                <?php if($image_name): ?>
                <div style="margin-bottom: 20px;">
                    <p style="color: #667eea; font-weight: bold; margin-bottom: 8px;">📷 プロフィール画像</p>
                    <img src="image/<?php echo htmlspecialchars($image_name); ?>" 
                         alt="プロフィール画像" 
                         style="max-width: 200px; border-radius: 10px; border: 3px solid #667eea;">
                </div>
                <?php endif; ?>
            </div>

            <!-- formのhidden機能を使って必要な情報を送る -->
            <form action="regist.php" method="post">
                <input type="hidden" name="name" value="<?php echo htmlspecialchars($user); ?>">
                <input type="hidden" name="mail" value="<?php echo htmlspecialchars($mail); ?>">
                <input type="hidden" name="pass" value="<?php echo htmlspecialchars($pass); ?>">
                <input type="hidden" name="img" value="<?php echo htmlspecialchars($image_name); ?>">
                
                <p style="text-align: center; display: flex; gap: 10px; justify-content: center;">
                    <a href="input.php" class="btn" style="background: #95a5a6;">戻る</a>
                    <button type="submit">登録する</button>
                </p>
            </form>
        </div>
    </div>
</body>
</html>
