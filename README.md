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

![コメント 2022-01-30 135731](https://user-images.githubusercontent.com/93024617/151687277-3c48333a-a260-4e28-b752-72f0625468a3.png)

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
