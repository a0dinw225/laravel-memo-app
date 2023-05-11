## name(アプリケーション名)

「 LaravelSimpleMemo 」

## Overview（アプリケーションの概要）
 
Laravelでメモアプリを作成。
 
## Functions & Technology List(機能と技術一覧)

Webサービス機能一覧
* 認証機能
* メモ作成機能
* メモ編集機能
* メモ削除機能
* タグ機能
* タグ追加機能  

## How to Use(利用方法)

会員登録してログインすると、メモ一覧に遷移してメモを作成することができる。  
そして、タグを追加することでメモと紐づけられる。タグ一覧からタグを選択すると紐づいているメモ一覧が表示される。

## image(イメージ図)

<img width="1438" alt="スクリーンショット 2023-04-29 14 12 17" src="https://user-images.githubusercontent.com/93024617/235284949-59c16612-8901-4dbb-9eb8-0512aba101d7.png">
 
## Features(Webサービスの特徴)

* 認証機能はLaravel/uiを利用している。
* メモアプリとして最低限の機能とタグ機能を実装している。

## ER図

![laravel-memo-app](https://github.com/a0dinw225/laravel-memo-app/assets/93024617/168b2366-b2fc-41e3-a4f2-d9fb2162d4e5)

## Requirement(要件)

* HTML5
* CSS3
* Bootstrap v5.0
* PHPバージョン 7.4.21
* Laravelバージョン 8.79.0
* Web server : Apache
* MySQL Version 8.0
 
## Installation(インストール手順)

### Dockerによる環境構築

`.env`を作成しDB接続のパスワードを設定  
```
DB_PASSWORD=
```

イメージ作成
```
docker-compose build
```

コンテナ起動
```
docker-compose up
```

APPコンテナに入る
```
docker exec -it myapp /bin/bash
```

マイグレーションを実行
```
php artisan migrate
```

暗号化キー設定
```
php artisan key:generate
```
※`.envのAPP_KEY`が設定されるため、コンテナを再起動して`.env`を再読み込みする必要がある

## URL  
http://localhost:8000

## DB接続手順

DBコンテナに入る
```
docker exec -it mydb /bin/bash
```

MySQL接続
```
mysql -u root -p
```
※パスワードは`.env`の`DB_PASSWORD`

## PHPUnit　テスト準備
DBコンテナに入り、MySQL接続しテスト用DBを作成
```
create database laravel_memo_app_test;
```

`.env`をコピーして、`.env.testing`を作成  
`.env.testing`の変更箇所
```
APP_ENV=testing
APP_KEY= 　#APP_KEYを空にする
DB_CONNECTION=mysql_test
DB_DATABASE=laravel_memo_app_test
```

`.env.testing`のAPP_KEY生成のコマンド
```
php artisan key:generate --show --env=testing
```
表示されたキーをコピーし、`.env.testing`のAPP_KEYに設定する

Dockerを再起動
```
docker-compose restart
```

DBテスト用コンテナに入る
```
docker exec -it myapp_testing /bin/bash
```

テスト用DBのマイグレーションを実行
```
php artisan migrate --env=testing
```

## PHPUnit テスト実行手順

テスト用のコンテナに入る
```
docker exec -it myapp_testing /bin/bash
```
※注意  
APPコンテナに入り、テストを行うと開発環境用DBのデータがリセットされてしまう  
必ずテスト用コンテナ内でテストする

テスト実行
```
./vendor/bin/phpunit
```

指定ファイルのみ実行
```
./vendor/bin/phpunit tests/Unit/MemoRepositoryTest.php
```

指定の関数のみ実行
```
./vendor/bin/phpunit --filter it_can_get_user_memo_with_tags_by_id
```

指定ファイルの指定の関数のみ実行
```
./vendor/bin/phpunit --filter it_can_get_user_memo_with_tags_by_id tests/Unit/MemoRepositoryTest.php
```
