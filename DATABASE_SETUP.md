# データベースセットアップガイド

## 📋 概要

スタイリッシュ掲示板アプリのデータベースをセットアップするためのガイドです。

## 🔰 このファイルの読み方

初学者の方は、まず `members` テーブルが「会員情報」、`posts` テーブルが「投稿と返信」を保存する場所だと押さえてください。PHP 側では `db.php` で MySQL に接続し、`check.php` や `regist.php`、`write.php` などから SQL を実行します。

現在のコードでは、登録時に `regist.php` の `password_hash()` でパスワードをハッシュ化し、ログイン時に `check.php` の `password_verify()` で照合します。そのため、会員テーブルのパスワード列は `password` という名前で、ハッシュ化済み文字列を保存する前提です。

## 📁 SQLファイルを使う場合の例

`create_database.sql` や `create_database_secure.sql` を用意している場合は、次のような役割になります。このリポジトリ内に SQL ファイルがない環境では、後述のテーブル構造を参考に phpMyAdmin などで手動作成してください。

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
| password | VARCHAR(255) | ハッシュ化済みパスワード |
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

現在のコードは、登録時に `password_hash()`、ログイン時に `password_verify()` を使う構成です。学習のため、対応する処理は次のファイルで確認できます:

- `regist.php`: `password_hash($pass, PASSWORD_DEFAULT)` で保存用の文字列を作る
- `check.php`: `password_verify($pass, $db_pass)` で入力値と保存済みハッシュを照合する

## 🧪 テストデータ

SQLファイルや自作の初期データには、次のようなテストユーザーを用意すると動作確認しやすくなります:

### テストユーザー

| メールアドレス | パスワード | ニックネーム |
|--------------|----------|------------|
| tanaka@example.com | password123 | 田中太郎 |
| sato@example.com | password456 | 佐藤花子 |
| suzuki@example.com | password789 | 鈴木一郎 |

テストデータを自分で作る場合も、`password` 列には平文ではなく `password_hash()` 相当のハッシュ化済み文字列を入れる必要があります。

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

テーブル作成後、`db.php`の接続情報を確認してください:

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
