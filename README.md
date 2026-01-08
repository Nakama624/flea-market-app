# flea-market-app

## Dockerビルド
- `git clone git@github.com:Nakama624/flea-market-app.git`
- `cd flea-market-app`
- `docker-compose up -d --build`

## Laravel環境構築
- `docker-compose exec php bash`
- `composer install`
- `cp .env.example .env`

> `.env` ファイルを以下のように修正。
> ```diff
> - DB_HOST=127.0.0.1
> + DB_HOST=mysql
>
> - DB_DATABASE=laravel
> - DB_USERNAME=root
> - DB_PASSWORD=
> + DB_DATABASE=laravel_db
> + DB_USERNAME=laravel_user
> + DB_PASSWORD=laravel_pass
> ```

- `php artisan key:generate`
- `php artisan migrate`
- `php artisan db:seed`


## mailhog設定手順
> `.env` ファイルを以下のように修正。
> ```diff
> -　MAIL_FROM_ADDRESS=null
> +　MAIL_FROM_ADDRESS=no-reply@example.com
>```

## stripe決済手順
> `.env` ファイルを以下のように追加。
> ```diff
> +　STRIPE_SECRET=
→それぞれのアカウントSTRIPE_SECRETを取得してもらう
> +　APP_URL=http://localhost
> ```

### クレジットカードテスト
- メールアドレス：任意のアドレス
- カード番号(VISA)：4242424242424242
- MM/YY：（任意の将来の日付）
- セキュリティコード：（任意の 3 桁の数字）
- 名前：任意の名前

### コンビニ支払いテスト
※振込完了は未対応
- メールアドレス：任意のアドレス
- 名前：任意の名前

## テスト実行
- `docker-compose exec php bash`
> `.env.testing` ファイルを以下のように修正。
> ```diff
★★
> - DB_HOST=127.0.0.1
> + DB_HOST=mysql
>
> - DB_DATABASE=laravel
> - DB_USERNAME=root
> - DB_PASSWORD=
> + DB_DATABASE=laravel_db
> + DB_USERNAME=laravel_user
> + DB_PASSWORD=laravel_pass
> ```

- `vendor/bin/phpunit tests/Feature/RegisterTest.php`
- `vendor/bin/phpunit tests/Feature/LoginTest.php`
- `vendor/bin/phpunit tests/Feature/LogoutTest.php`
- `vendor/bin/phpunit tests/Feature/IndexTest.php`
- `vendor/bin/phpunit tests/Feature/MylistTest.php`


## 使用技術（実行環境）
- PHP 8.1.34
- Laravel Framework 8.83.29
- mysql  Ver 8.0.26
- nginx/1.21.1
- Mailhog
- stripe決済

## ER図
![alt text](flea_market_app.drawio.png)

## URL
- ログイン：http://localhost/login
- 新規登録：http://localhost/register
- マイページ：http://localhost/mypage
- 商品一覧：http://localhost/
- phpMyAdmin：http://localhost:8080/
