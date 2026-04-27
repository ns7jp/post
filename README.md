# スタイリッシュ掲示板

PHP + MySQL で実装したシンプルな掲示板アプリ。ユーザー登録・ログイン・投稿・返信機能を備えています。

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?logo=mysql&logoColor=white)
![Security](https://img.shields.io/badge/Security-CSRF%2FXSS%2Fbcrypt-success)

🔗 **ライブデモ**: http://shimada.atwebpages.com/post/

> ⚠️ **デモサイトは無料ホスティング（HTTP）で運用しています。** 学習目的のため、本番運用はしていません。実際のログイン情報は使用しないでください。

---

## 主な機能

- **ユーザー認証**：新規登録 / ログイン / ログアウト
- **投稿・返信**：スレッド作成、ツリー型返信表示
- **削除機能**：自分の投稿を削除可能
- **画像アップロード**：プロフィール画像対応
- **セッション管理**：PHP セッションでログイン状態を保持

---

## 技術構成

| 項目             | 技術                                     |
|-----------------|------------------------------------------|
| バックエンド     | PHP 8.x                                 |
| データベース     | MySQL（PDO 経由で接続）                  |
| フロントエンド   | HTML / CSS / JavaScript                  |
| 認証             | セッション認証 / CSRF 保護 / bcrypt ハッシュ |

---

## セキュリティ実装

| 対策 | 実装内容 |
|------|--------|
| **SQLインジェクション** | ユーザー入力を扱うクエリで PDO + プリペアドステートメント |
| **XSS対策** | `htmlspecialchars()` による出力エスケープ |
| **CSRF対策** | `random_bytes()` でトークン生成、フォームに hidden で埋め込み、サーバー側で検証 |
| **パスワード保護** | `password_hash()`（bcrypt）でハッシュ化保存、`password_verify()` で検証 |
| **セッション管理** | PHP セッションで認証状態を保持 |

---

## 🚀 セットアップ（XAMPP環境での手順）

ローカルで動かしてみたい方向けの手順です。**Windows + XAMPP** を前提にしています。

### 必要なソフトウェア

| ソフト | バージョン | 入手先 |
|-------|----------|------|
| **XAMPP** | 8.x | https://www.apachefriends.org |

XAMPP には PHP・MySQL・Apache が全部入っているので、これ一つで動きます。

---

### Step 1: ソースコードをダウンロード

このリポジトリの右上にある緑色の **「Code」** ボタンをクリック → **「Download ZIP」** を選択。  
ダウンロードした ZIP を任意の場所に解凍します（フォルダ名は `post` になります）。

> Git に慣れている方は `git clone https://github.com/ns7jp/post.git` でもOK。

---

### Step 2: XAMPP の htdocs に配置

ZIP から解凍した `post` フォルダを、XAMPP の **htdocs** フォルダに移動します。

```
C:\xampp\htdocs\post\
```

中に `login.php` `index.php` などが並んでいる状態にしてください。

---

### Step 3: XAMPP を起動

1. **XAMPP Control Panel** を起動
2. **Apache** の「Start」ボタンをクリック
3. **MySQL** の「Start」ボタンをクリック
4. 両方とも緑色（Running）になればOK

---

### Step 4: データベースを作成

1. ブラウザで **http://localhost/phpmyadmin/** を開く
2. 左サイドバーの **「新規作成」** をクリック
3. データベース名に **`mini_bbs`** と入力
4. 文字コードは **`utf8mb4_general_ci`** を選択
5. **「作成」** ボタンをクリック

---

### Step 5: テーブルを作成

1. 左サイドバーで作成した `mini_bbs` をクリック
2. 上部メニューの **「SQL」** タブをクリック
3. 以下のSQL文を**全部コピーして貼り付け**：

```sql
CREATE TABLE members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    picture VARCHAR(255),
    created DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    message TEXT NOT NULL,
    reply_post_id INT DEFAULT 0,
    created DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

4. 右下の **「実行」** ボタンをクリック
5. 「2 件のクエリが正常に実行されました」と表示されたら成功

---

### Step 6: DB 接続情報を設定

1. `C:\xampp\htdocs\post\` 内の **`db.example.php`** を**コピーして** `db.php` という名前にする  
   （Windowsエクスプローラーで右クリック → コピー → 貼り付け → 名前を `db.php` に変更）

2. **`db.php` をメモ帳で開く**

3. 以下のように書き換え（XAMPPデフォルト値）：

```php
<?php
try {
    $db = new PDO(
        'mysql:dbname=mini_bbs;host=127.0.0.1;charset=utf8mb4',
        'root',  // XAMPPのデフォルトユーザー
        ''       // XAMPPのデフォルトパスワード（空欄）
    );
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print("接続エラー");
    exit();
}
```

4. 上書き保存

---

### Step 7: 画像保存用フォルダを作成

`C:\xampp\htdocs\post\` 内に **`image`** という名前のフォルダを新規作成してください。  
（プロフィール画像のアップロード先になります）

---

### Step 8: 動作確認

1. ブラウザで **http://localhost/post/login.php** を開く
2. 「新規登録」をクリック → アカウント作成
3. ログイン → 投稿してみる

これで動作確認完了です 🎉

---

## ❓ つまずきポイント

| 症状 | 対処法 |
|------|------|
| Apacheが起動しない | ポート80が他のソフト（Skype等）に使われている可能性。XAMPPのconfigでポート変更 |
| MySQLが起動しない | XAMPPの「Config」→「my.ini」でポート3306競合を確認 |
| 「接続エラー」と表示 | `db.php` のユーザー名・パスワード・DB名を確認 |
| 「Table doesn't exist」 | Step 5 のSQLが正常実行できているか phpMyAdmin で確認 |
| 画像アップロードでエラー | Step 7 の `image` フォルダが作成されているか確認 |

---

## ディレクトリ構成

```
post/
├── db.example.php       ... DB接続設定のサンプル（リポジトリに含む）
├── db.php               ... 自分の環境用（.gitignoreで除外、Step 6で作成）
├── index.php             ... 投稿一覧（全表示）
├── index2.php            ... 投稿一覧（返信ツリー版）
├── login.php             ... ログイン画面
├── check.php             ... ログイン認証処理
├── logout.php            ... ログアウト
├── input.php             ... 新規登録フォーム
├── comfirm.php           ... 登録確認画面
├── regist.php            ... 登録処理
├── write.php             ... 新規投稿処理
├── write2.php            ... 返信投稿処理
├── reply.php             ... 返信ページ
├── delete.php            ... 削除処理
├── error.php             ... エラー画面
├── style.css             ... スタイルシート
└── image/                ... プロフィール画像保存先（Step 7で作成）
```

---

## 制作背景

公共職業訓練「情報処理（Pythonエンジニア）コース」（ISPアカデミー川越校 / 2025年10月〜2026年1月）の学習成果として制作しました。

---

## 著者

**島田則幸（Noriyuki Shimada）**

- 🌐 [ポートフォリオサイト](https://ns7jp.github.io/)
- 📂 [ほかの作品](https://github.com/ns7jp/works)
- 📧 net7jp@gmail.com

---

## ライセンス

このリポジトリのコードは学習目的で公開しています。参考としてご活用いただけます。
