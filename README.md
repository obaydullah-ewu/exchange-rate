## About Currency Exchange Rate Install

**Instruction of Install**

- git clone
- composer install
- copy env.example .env
- php artisan key:generate
- **open your mysql and create database as you want, then same database name save your .env database** 
- php artisan migrate

**Used Technology**

- **Version Control - Git & GitHub**
- **DB: MySQL**
- **PHP Version 8.1**
- **Laravel Version 10.0**
- **Frontend – HTML, CSS, JS & Bootstrap**

**DB Model**

- Currency
- CurrencyHistory

**New Command Add for Cron Jobs**

- php artisan exchange:update

**Add Traits for Api Response Handling**

- Location: app/Traits

**Controller (app/Http/Controllers)**

- **Frontend:** HomeController
- **Api:** Api/AuthController && Api/CurrencyController


**Thank You**
