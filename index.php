<?php
session_start();
// $_SESSION["user_id"]があるか確認してログインしているかチェック
if(!isset($_SESSION["user_id"])){
    //ログインしてないんでlogin.phpへ飛ばす
    header("Location:http://shimada.atwebpages.com/post/login.php");
    exit(); //以下データベースの操作あるので忘れない
}
//データベースにつなぐ
require("db.php");

// セッション情報を使ってユーザーの名前を探してくるよ
$kekka = $db -> query("SELECT name FROM members WHERE id={$_SESSION["user_id"]}");
$kekka2 = $kekka -> fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>トップページ - スタイリッシュ掲示板</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>📝 スタイリッシュ掲示板</h1>
            <p class="welcome-text">ようこそ、<?php print(htmlspecialchars($kekka2["name"])); ?>さん</p>
        </header>

        <!-- 投稿フォーム -->
        <div class="post-form">
            <h2>✨ 新しい投稿</h2>
            <form action="write.php" method="post">
                <textarea name="message" placeholder="今何を考えていますか？" required></textarea>
                <p style="margin-top: 15px; text-align: right;">
                    <button type="submit">投稿する</button>
                </p>
            </form>
        </div>

        <!-- 投稿一覧 -->
        <div class="posts-section">
            <h2>💬 投稿一覧</h2>
            <?php
            $kekka = $db -> query("SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id = p.member_id ORDER BY p.id DESC");
            while($line = $kekka -> fetch(PDO::FETCH_ASSOC)){
            ?>
                <div class="post-card">
                    <?php
                    // だれかへの返信なのか判断する
                    if($line["reply_post_id"] != 0){
                        echo '<div class="reply-indicator">この投稿は <a href="#post-' . htmlspecialchars($line["reply_post_id"]) . '">投稿#' . htmlspecialchars($line["reply_post_id"]) . '</a> への返信です</div>';
                    }
                    ?>
                    
                    <div id="post-<?php echo htmlspecialchars($line["id"]); ?>" class="post-message">
                        <?php echo nl2br(htmlspecialchars($line["message"])); ?>
                    </div>
                    
                    <div class="post-author">
                        <?php if(!empty($line["picture"])): ?>
                            <img src="image/<?php echo htmlspecialchars($line["picture"]); ?>" alt="プロフィール画像">
                        <?php else: ?>
                            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='50' height='50'%3E%3Crect fill='%23667eea' width='50' height='50'/%3E%3Ctext x='50%25' y='50%25' font-size='24' text-anchor='middle' alignment-baseline='middle' fill='white'%3E👤%3C/text%3E%3C/svg%3E" alt="デフォルト画像">
                        <?php endif; ?>
                        <span class="author-name"><?php echo htmlspecialchars($line["name"]); ?></span>
                    </div>
                    
                    <div class="post-date">
                        📅 <?php echo htmlspecialchars($line["created"]); ?>
                    </div>
                    
                    <div class="post-actions">
                        <?php if($_SESSION["user_id"] == $line["member_id"]): ?>
                            <a href="delete.php?id=<?php echo htmlspecialchars($line["id"]); ?>" class="delete-link" onclick="return confirm('この投稿を削除しますか?');">🗑️ 削除する</a>
                        <?php endif; ?>
                        <a href="reply.php?id=<?php echo htmlspecialchars($line["id"]); ?>">💬 返信する</a>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>

        <!-- フッター -->
        <div class="footer">
            <a href="logout.php" class="logout-link">ログアウト</a>
        </div>
    </div>
</body>
</html>
