# MyKiji

## ■ サービス内容
記事を投稿することができるサイトです。<br>
画像の添付・動画リンクの埋め込み等もできます。<br>
サイトURL：https://mykiji.com

## ■ 使用技術
- PHP 8.1
- CakePHP 4.4
- Bootstrap 4.5
- MySQL 8.0
- nginx
- Docker

## ■ 機能一覧
- 記事投稿
- 記事編集
- 記事削除
- 記事一覧表示
- 記事詳細表示
- 記事検索
- ユーザーログイン
- ユーザー登録
- ユーザー編集
- ユーザー退会
- ユーザーパスワード再発行
- ユーザーフォロー機能
- お気に入り記事登録

## ■ ローカル環境
ローカル環境はDockerコンテナで構成しています。<br>
各コンテナ名とその内容は以下の通りです。
- mykiji-web-1 ⇒ nginx (http://localhost:34251)
- mykiji-app-1 ⇒ PHP
- mykiji-db-1 ⇒ MySQL
- mykiji-phpmyadmin-1 ⇒ phpmyadmin (http://localhost:3000)
- mykiji-mailhog-1 ⇒ mailhog (http://localhost:8025)

環境構築手順は以下の通りです。
- ソースを取得する。<br>
`$ git pull git@github.com:fchkn/MyKiji.git`
- dockerイメージを取得する。※docker-compose.ymlがあるフォルダ内で実行すること。<br>
`$ docker-compose build --no-cache`
- dockerコンテナを作成する。<br>
`$ docker-compose up -d`
- appコンテナに入る<br>
`$ docker-compose exec app sh`
- Cakephp等のvendorライブラリをインストールする。<br>
`/var/www # composer install`
- appコンテナから出る<br>
`/var/www # exit`
- src>config>app_local.phpを以下のように修正する。
```
'Datasources' => [
    'default' => [
        'host' => 'db',
        /*
            * CakePHP will use the default DB port based on the driver selected
            * MySQL on MAMP uses port 8889, MAMP users will want to uncomment
            * the following line and set the port accordingly
            */
        //'port' => 'non_standard_port_number',

        'username' => 'user',
        'password' => 'password',

        'database' => 'mykiji',
```
```
'EmailTransport' => [
    'default' => [
        'host' => 'mailhog',
        'port' => 1025,
        'username' => null,
        'password' => null,
        'client' => null,
        'url' => env('EMAIL_TRANSPORT_DEFAULT_URL', null),
    ],
],
```
- docker->mysql->base.sqlをphpmyadminで実行して初期テーブルを作成する<br>
