# MyKiji

## ■ ローカル環境構築方法
- ソースを取得する。
 `$ git pull git@github.com:fchkn/MyKiji.git`
- dockerイメージを取得する。※docker-compose.ymlがあるフォルダ内で実行すること。
 `$ docker-compose build`
- dockerコンテナを作成する。
 `$ docker-compose up -d`
- appコンテナに入る
 `$ docker-compose exec app sh`
- Cakephpのパッケージをインストールする。
 `/var/www # composer install`
- src>congig>app_local.phpのDatasources(usernameとdatabase)を以下のように修正する。
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
        'password' => 'secret',

        'database' => 'sample',
```