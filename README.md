

# 環境構築



## Dockerビルド

・git clone git@github.com:coachtech-material/laravel-docker-template.git

・docker-compose up -d --build


## Laravel環境構築

・docker-compose exec php bash

・composer install

・cp .env.example .env　ーー＞　環境変数の変更

・php artisan key:generate

・php artisan migrate

・php artisan db:seed


## 開発環境

トップ画面　http://localhost/

ユーザー登録　http://localhost/register

phpMyAdmin　http://localhost:8080/index.php


## 使用技術

php　7.4.9-fpm

Laravel 　8.75

MySQL　8.0.26

nginx:1.21.1

## ER図

<img width="920" alt="スクリーンショット 2025-06-06 13 12 48" src="https://github.com/user-attachments/assets/2f78f403-c807-4c21-b0fe-3977dcf7f2f7" />


