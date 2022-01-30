## name(アプリケーション名)

「 LaravelSimpleMemo 」

## Overview（アプリケーションの概要）
 
Laravelでメモを作成。
 
## Functions & Technology List(機能と技術一覧)

Webサービス機能一覧
* 認証機能
* メモ作成機能
* メモ編集機能
* メモ削除機能
* タグ機能
* タグ追加機能  

使用した技術一覧
* Laravel/ui
* 一対多, 多対多の関係 
* データベース(MySQL)

## How to Use(利用方法)

会員登録してログインすると、メモ一覧に遷移してメモを作成することができる。  
そして、タグを追加することでメモと紐づけられる。タグ一覧からタグを選択すると紐づいているメモ一覧が表示される。
 
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
* Laravelバージョン 8.5.19
* Web server : Apache
* MySQL Version 5.7.34
* Node.js v16.13.2
* npm バージョン　8.1.2
 
## Installation(インストール手順)

MAMP　ダウンロード

https://www.mamp.info/en/downloads/

composerのインストール

```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
composer -v
```

うまく動かなかった場合
```
sudo mv composer.phar /usr/local/bin/composer
composer -v
```

node.jsとnpmの導入

node.js　推奨版LTSをダウンロード

https://nodejs.org/ja/

```
node -v
npm -v
```

laravelプロジェクト作成

```
composer create-project 'laravel/laravel=8.5.19' --prefer-dist laravel-simple-memo
```

composerインストール
```
composer require laravel/ui
```

bootstrap導入
```
php artisan ui bootstrap --auth
```

npmインストール
```
npm install && npm run dev
```

うまくいかない場合は

```
npm audit fix
npm audit fix --force
npm install
npm run dev
```