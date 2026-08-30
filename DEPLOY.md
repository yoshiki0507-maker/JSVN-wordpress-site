# CoreServerへのデプロイ手順

## 1. MySQLデータベースを作成
CoreServerの管理画面（コントロールパネル）で、MySQLデータベースを1つ作成し、
以下をメモしておく。
- データベース名
- データベースユーザー名
- パスワード
- ホスト名（通常 `mysql○○.coreserver.jp` の形式。管理画面に表示される）

## 2. テーブルを作成
作成したデータベースに対して、phpMyAdmin等で `schema.sql` の内容を実行する。

## 3. 管理者パスワードを決める
手元のPCやCoreServerのSSHでPHPを実行し、パスワードのハッシュ値を作る。
```
php -r "echo password_hash('好きなパスワード', PASSWORD_DEFAULT), PHP_EOL;"
```
出力された `$2y$10$...` の文字列をコピーしておく。

## 4. config.php を作成
`config.sample.php` を `config.php` としてコピーし、1〜3で用意した値を書き込む。
`config.php` はリポジトリには含めない（`.gitignore`済み）ので、サーバー側で直接作成する。

## 5. ファイルをアップロード
このリポジトリ一式（`index.php` `login.php` `logout.php` `lib/` `api/` `config.php`）を、
CoreServerの `public_html` 以下（例: `public_html/houmon-kango/` のようなサブディレクトリ推奨）に
FTP/SFTPでアップロードする。

## 6. SSL（https）を有効化
CoreServerの管理画面で無料独自SSLを有効にし、必ず `https://` でアクセスする。
利用者の疾患名などの個人情報を扱うため、平文のHTTPは使わないこと。

## 7. 動作確認
`https://（ドメイン）/（配置したパス）/login.php` にアクセスし、3で決めたパスワードでログインする。
