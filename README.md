# MyKiji

## ■ コンテナ構成
ローカル環境はDockerコンテナで構成しています。
各コンテナと構成内容は以下の通りです。
- mykiji-web-1 ⇒ nginx (http://localhost:34251/)
- mykiji-app-1 ⇒ php
- mykiji-db-1  ⇒ mysql
- mykiji-phpmyadmin-1 ⇒ phpmyadmin (http://localhost:3000/)
- mykiji-mailhog-1 ⇒ mailhog (http://localhost:8025)

## ■ ローカル環境構築方法
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
