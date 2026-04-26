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

## セットアップ

### 1. リポジトリをクローン
\`\`\`bash
git clone https://github.com/ns7jp/post.git
cd post
\`\`\`

### 2. DB 設定ファイルを作成
\`\`\`bash
cp db.example.php db.php
\`\`\`
`db.php` を開き、自身の MySQL 接続情報を記入してください。

### 3. データベーステーブルを作成
\`\`\`sql
CREATE TABLE members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    picture VARCHAR(255),
    created DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    message TEXT NOT NULL,
    reply_post_id INT DEFAULT 0,
    created DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
);
\`\`\`

### 4. 起動
XAMPP 等で `htdocs/post/` に配置するか、ビルトインサーバー：
\`\`\`bash
php -S localhost:8000
\`\`\`

---

## 制作背景

公共職業訓練「情報処理（Pythonエンジニア）コース」（ISPアカデミー川越校 / 2025年10月〜2026年1月）の学習成果として制作しました。

---

## 著者

**島田則幸（Noriyuki Shimada）**

- 🌐 [ポートフォリオサイト](http://shimada.atwebpages.com/pf/)
- 📂 [ほかの作品](https://github.com/ns7jp/works)
- 📧 net7jp@gmail.com
