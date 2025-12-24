# flea-market-app

## Laravel環境構築

git clone git@github.com:Nakama624/flea-market-app.git
cd flea-market-app
docker-compose up -d --build
docker-compose exec php bash
composer install
cp .env.example .env
　～環境変数の変更～
DB_HOST=mysql
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass

php artisan key:generate
php artisan migrate
php artisan seed

## 使用技術（実行環境）
PHP：Laravel Framework 8.83.29

## ER図
![alt text](flea_market_app.drawio.png)


## URL
・ログイン
  http://localhost/login
・新規登録
  http://localhost/register
・マイページ
　http://localhost/mypage
・商品一覧
　http://localhost/
・phpMyAdmin
　http://localhost:8080/
