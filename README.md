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

## mailhog
### 環境設定
> `.env` ファイルを以下のように修正。
> ```diff
> -　MAIL_FROM_ADDRESS=null
> +　MAIL_FROM_ADDRESS=no-reply@example.com
>```

## stripe決済
### 環境設定
> stripe決済のアカウントを作成し、`.env` ファイルに以下のように追加。
> https://dashboard.stripe.com/login?locale=ja-JP
> ```diff
> +　STRIPE_SECRET=（stripe決済各ユーザーのシークレットキー）
> +　APP_URL=http://localhost
> ```

### 実行テスト１/クレジットカード（VISA/成功）
- メールアドレス：任意のアドレス
- カード番号(VISA)：4242424242424242
- MM/YY：（任意の将来の日付）
- セキュリティコード：（任意の 3 桁の数字）
- 名前：任意の名前

### 実行テスト２/コンビニ支払い（振込結果は非同期）
- メールアドレス：任意のアドレス
- 名前：任意の名前

### テスト詳細
- https://docs.stripe.com/testing


## 単体テスト
### DBを作成
- `docker-compose exec mysql bash`
- `mysql -u root -p`
- `CREATE DATABASE demo_test;`
- `exit`

### .env.testingを作成
- `docker-compose exec php bash`
- `cp .env .env.testing`

> `.env.testing` ファイルを以下のように修正。
> ```diff
> - APP_ENV=local
> - APP_KEY=(.envのKEY)
> + APP_ENV=test
> + APP_KEY=
>
> - DB_DATABASE=laravel_db
> - DB_USERNAME=laravel_user
> - DB_PASSWORD=laravel_pass
> + DB_DATABASE=demo_test
> + DB_USERNAME=root
> + DB_PASSWORD=root
> ```

- `php artisan key:generate --env=testing`
- `php artisan migrate --env=testing`

### テスト実行
- 1.会員登録機能：
  `vendor/bin/phpunit tests/Feature/RegisterTest.php`
- 2.ログイン機能：
  `vendor/bin/phpunit tests/Feature/LoginTest.php`
- 3.ログアウト機能：
  `vendor/bin/phpunit tests/Feature/LogoutTest.php`
- 4.商品一覧取得：
 `vendor/bin/phpunit tests/Feature/IndexTest.php`
- 5.マイリスト一覧取得：
 `vendor/bin/phpunit tests/Feature/MylistTest.php`
- 6.商品検索機能：
 `vendor/bin/phpunit tests/Feature/ItemSearchTest.php`
- 7.商品詳細情報取得：
 `vendor/bin/phpunit tests/Feature/ItemDetailTest.php`
- 8.いいね機能：
 `vendor/bin/phpunit tests/Feature/LikesTest.php`
- 9.コメント送信機能：
 `vendor/bin/phpunit tests/Feature/CommentsTest.php`
- 10.商品購入機能：
 `vendor/bin/phpunit tests/Feature/PurchaseTest.php`
- 11.支払方法選択機能：
 `vendor/bin/phpunit tests/Feature/SelectPaymentMethodTest.php`
- 12.配送先変更機能：
 `vendor/bin/phpunit tests/Feature/ChangeDeliveryAddressTest.php`
- 13.ユーザー情報取得：
 `vendor/bin/phpunit tests/Feature/GetUserInfoTest.php`
- 14.ユーザー情報変更：
 `vendor/bin/phpunit tests/Feature/ModifiedUserInfoTest.php`
- 15.出品商品情報登録：
 `vendor/bin/phpunit tests/Feature/SellItemTest.php`
- 16.メール認証機能：
 `vendor/bin/phpunit tests/Feature/MailAuthTest.php`

## 使用技術（実行環境）
- PHP 8.1.34
- Laravel Framework 8.83.29
- mysql  Ver 8.0.26
- nginx/1.21.1
- stripe決済 versions : * v19.1.0
- Mailhog(取得なし)

## ER図
![alt text](flea_market_app.drawio.png)

## URL
- ログイン：http://localhost/login
- 新規登録：http://localhost/register
- マイページ：http://localhost/mypage
- 商品一覧：http://localhost/
- 出品（ログイン必須）http://localhost/sell
- 商品詳細画面やユーザープロフィール画面は商品一覧やマイページから遷移
- phpMyAdmin：http://localhost:8080/

