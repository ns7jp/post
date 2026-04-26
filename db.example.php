<?php
/**
 * DB 接続設定のサンプルファイル
 *
 * このファイルを `db.php` としてコピーし、
 * 自身の環境に合わせて接続情報を書き換えてください。
 */

try {
    $db = new PDO(
        'mysql:dbname=YOUR_DATABASE_NAME;host=YOUR_HOST;charset=utf8',
        'YOUR_USERNAME',
        'YOUR_PASSWORD'
    );
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print("接続エラー");
    exit();
}
