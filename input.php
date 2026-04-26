<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規会員登録 - スタイリッシュ掲示板</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>✨ 新規会員登録</h1>
            <p class="welcome-text">アカウントを作成してコミュニティに参加しましょう</p>
        </header>

        <div class="post-form">
            <h2>会員情報を入力</h2>
            <p style="color: #666; margin-bottom: 20px;">次のフォームに必須事項を入力して確認ボタンを押してください。</p>
            
            <form action="comfirm.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">👤 ニックネーム【必須】</label>
                    <input type="text" name="name" id="name" placeholder="表示名を入力" required>
                </div>
                
                <div class="form-group">
                    <label for="mail">📧 メールアドレス【必須】</label>
                    <input type="email" name="mail" id="mail" placeholder="example@email.com" required>
                </div>
                
                <div class="form-group">
                    <label for="pass">🔑 パスワード【必須】</label>
                    <input type="password" name="pass" id="pass" placeholder="安全なパスワードを設定" required>
                </div>
                
                <div class="form-group">
                    <label for="img">📷 プロフィール画像（任意）</label>
                    <input type="file" name="img" id="img" accept="image/*">
                </div>
                
                <p style="text-align: center; margin-top: 25px;">
                    <button type="submit">確認する</button>
                </p>
            </form>
            
            <p style="text-align: center; margin-top: 20px; color: #666;">
                すでにアカウントをお持ちの方は <a href="login.php" style="color: #667eea; font-weight: bold;">ログイン</a>
            </p>
        </div>
    </div>
</body>
</html>
