<?php
//GET送信された記事番号を変数に入れます
$id = htmlspecialchars($_GET["id"],ENT_QUOTES);

//データベースにつないで返信元の記事情報取ってくる
require("db.php");
$out_str = "SELECT p.*, m.name, m.picture FROM posts p JOIN members m ON p.member_id = m.id WHERE p.id = ?";
$kekka = $db -> prepare($out_str);
$kekka -> execute([$id]);
$kekka2 = $kekka -> fetch(PDO::FETCH_ASSOC);

if(!$kekka2){
    header("Location:http://shimada.atwebpages.com/post/index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>返信画面 - スタイリッシュ掲示板</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>💬 返信する</h1>
            <p class="welcome-text">以下の投稿に返信します</p>
        </header>

        <!-- 元の投稿 -->
        <div class="post-card" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
            <div class="post-message">
                <?php echo nl2br(htmlspecialchars($kekka2["message"])); ?>
            </div>
            
            <div class="post-author">
                <?php if(!empty($kekka2["picture"])): ?>
                    <img src="image/<?php echo htmlspecialchars($kekka2["picture"]); ?>" alt="プロフィール画像">
                <?php else: ?>
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='50' height='50'%3E%3Crect fill='%23667eea' width='50' height='50'/%3E%3Ctext x='50%25' y='50%25' font-size='24' text-anchor='middle' alignment-baseline='middle' fill='white'%3E👤%3C/text%3E%3C/svg%3E" alt="デフォルト画像">
                <?php endif; ?>
                <span class="author-name"><?php echo htmlspecialchars($kekka2["name"]); ?></span>
            </div>
            
            <div class="post-date">
                📅 <?php echo htmlspecialchars($kekka2["created"]); ?>
            </div>
        </div>

        <!-- 返信フォーム -->
        <div class="post-form" style="margin-top: 30px;">
            <h2>✏️ 返信内容を入力</h2>
            <form action="write2.php" method="post">
                <textarea name="message" placeholder="返信内容を入力してください..." required></textarea>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                
                <p style="margin-top: 15px; display: flex; gap: 10px; justify-content: flex-end;">
                    <a href="index.php" class="btn" style="background: #95a5a6;">キャンセル</a>
                    <button type="submit">返信を投稿</button>
                </p>
            </form>
        </div>
    </div>
</body>
</html>
