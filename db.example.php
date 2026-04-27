<?php
/**
 * DB 接続設定のサンプルファイル
 *
 * このファイルを `db.php` としてコピーし、
 * 自身の環境に合わせて接続情報を書き換えてください。
 *
 * Windows: エクスプローラーで右クリック → コピー → 貼り付け → 名前を db.php に変更
 * Mac/Linux: cp db.example.php db.php
 *
 * XAMPP デフォルト設定の場合：
 *   - ホスト: 127.0.0.1
 *   - ユーザー: root
 *   - パスワード: （空欄）
 *   - データベース名: 自身で作成したもの（例：mini_bbs）
 */

try {
    $db = new PDO(
        'mysql:dbname=YOUR_DATABASE_NAME;host=YOUR_HOST;charset=utf8mb4',
        'YOUR_USERNAME',
        'YOUR_PASSWORD'
    );
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // 本番環境ではエラー詳細を表示しない
    print("接続エラー");
    exit();
}
