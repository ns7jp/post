# データベースセットアップガイド

## 📋 概要

スタイリッシュ掲示板アプリのデータベースをセットアップするためのガイドです。

## 📁 提供されるSQLファイル

### 1. `create_database.sql` - 基本版
- シンプルな構造
- テストデータ付き
- 初心者向け

### 2. `create_database_secure.sql` - セキュア版（推奨）
- セキュリティ強化
- パスワードハッシュ化対応
- 便利なビュー付き
- 統計情報取得用クエリ付き

## 🚀 セットアップ手順

### 方法1: コマンドラインから実行

```bash
# MySQLにログイン
mysql -u root -p

# SQLファイルを実行
source /path/to/create_database.sql
# または
source /path/to/create_database_secure.sql

# 確認
USE mini_bbs;
SHOW TABLES;
```

### 方法2: phpMyAdminから実行

1. phpMyAdminにログイン
2. 「インポート」タブをクリック
3. SQLファイルを選択してアップロード
4. 「実行」をクリック

### 方法3: ターミナルから直接実行

```bash
# 基本版
mysql -u root -p < create_database.sql

# セキュア版
mysql -u root -p < create_database_secure.sql
```

## 📊 データベース構造

### membersテーブル（会員情報）

| カラム名 | データ型 | 説明 |
|---------|---------|------|
| id | INT(11) | 会員ID（主キー、自動増分） |
| name | VARCHAR(100) | ニックネーム |
| mail | VARCHAR(255) | メールアドレス（ユニーク） |
| pass | VARCHAR(255) | パスワード |
| picture | VARCHAR(255) | プロフィール画像ファイル名 |
| created | TIMESTAMP | 登録日時 |
| updated | TIMESTAMP | 更新日時 |

**セキュア版の追加カラム:**
- status: アカウント状態（1=有効, 0=無効）
- last_login: 最終ログイン日時

### postsテーブル（投稿情報）

| カラム名 | データ型 | 説明 |
|---------|---------|------|
| id | INT(11) | 投稿ID（主キー、自動増分） |
| member_id | INT(11) | 投稿者の会員ID（外部キー） |
| message | TEXT | 投稿内容 |
| reply_post_id | INT(11) | 返信元の投稿ID（0=新規投稿） |
| created | TIMESTAMP | 投稿日時 |
| updated | TIMESTAMP | 更新日時 |

**セキュア版の追加カラム:**
- is_deleted: 削除フラグ（0=有効, 1=削除済み）

## 🔐 セキュリティ推奨事項

### パスワードのハッシュ化

現在のコードではパスワードが平文で保存されています。以下のようにPHPコードを修正することを強く推奨します:

**regist.php の修正例:**
```php
// 修正前
$pass = htmlspecialchars($_POST["pass"], ENT_QUOTES);

// 修正後
$pass = password_hash($_POST["pass"], PASSWORD_DEFAULT);
```

**check.php の修正例:**
```php
// 修正前
if($kekka2["pass"] == $pass){
    // ログイン成功
}

// 修正後
if(password_verify($pass, $kekka2["pass"])){
    // ログイン成功
}
```

## 🧪 テストデータ

両方のSQLファイルにはテストデータが含まれています:

### テストユーザー

| メールアドレス | パスワード | ニックネーム |
|--------------|----------|------------|
| tanaka@example.com | password123 | 田中太郎 |
| sato@example.com | password456 | 佐藤花子 |
| suzuki@example.com | password789 | 鈴木一郎 |

**セキュア版には追加で:**
- yamada@example.com / password111 / 山田美咲
- takahashi@example.com / password222 / 高橋健太

### サンプル投稿

- 5〜10件のサンプル投稿
- 返信のサンプルも含む

## 🗑️ データベースのリセット

データベースを完全にリセットしたい場合:

```sql
DROP DATABASE IF EXISTS mini_bbs;
```

その後、再度SQLファイルを実行してください。

## ✅ 動作確認

### 1. テーブルの確認

```sql
USE mini_bbs;
SHOW TABLES;
```

期待される結果:
- members
- posts

### 2. データの確認

```sql
-- 会員数の確認
SELECT COUNT(*) FROM members;

-- 投稿数の確認
SELECT COUNT(*) FROM posts;

-- 会員情報付き投稿一覧
SELECT m.name, p.message, p.created
FROM members m
INNER JOIN posts p ON m.id = p.member_id
ORDER BY p.created DESC
LIMIT 5;
```

## 🔧 トラブルシューティング

### エラー: "Access denied for user"
→ MySQLのユーザー名とパスワードを確認してください

### エラー: "Database already exists"
→ 既存のデータベースを削除するか、別の名前を使用してください

### エラー: "Cannot add foreign key constraint"
→ テーブルの削除順序を確認してください（postsを先に削除）

## 📝 db.phpの設定

SQLファイル実行後、`db.php`の接続情報を確認してください:

```php
<?php
try{
    $db = new PDO(
        'mysql:dbname=mini_bbs;host=127.0.0.1;charset=utf8mb4',
        'root',  // ユーザー名
        ''       // パスワード
    );
}catch(PDOException $e){
    print("接続エラー");
    exit();
}
?>
```

## 🌟 セキュア版の追加機能

### ビュー（View）

セキュア版には便利なビューが含まれています:

1. **v_posts_with_member** - 会員情報付き投稿一覧
2. **v_posts_with_reply_count** - 返信数付き投稿一覧

使用例:
```sql
SELECT * FROM v_posts_with_member LIMIT 10;
SELECT * FROM v_posts_with_reply_count;
```

### 統計情報

セキュア版のSQLファイルにはコメントアウトされた統計クエリが含まれています:

- 会員数
- 投稿数
- 返信数
- 最も投稿が多いユーザーTOP5

## 📞 サポート

問題が発生した場合は、以下を確認してください:

1. MySQL/MariaDBが正しくインストールされているか
2. MySQLサーバーが起動しているか
3. 接続情報（ホスト、ユーザー名、パスワード）が正しいか
4. 文字エンコーディングがUTF-8/UTF8MB4になっているか

---

セットアップが完了したら、`login.php`にアクセスしてテストユーザーでログインしてみてください！
