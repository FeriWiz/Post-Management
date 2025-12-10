# Post Management System

یک سیستم مدیریت پست کامل با لاراول، احراز هویت JWT و مستندات Swagger

## ویژگی‌های پروژه
- احراز هویت با JWT
- CRUD کامل پست‌ها
- آپلود تصویر برای پست‌ها
- مستندات کامل و تعاملی API با Swagger
- ساختار تمیز و استاندارد لاراول

## پیش‌نیازها
- PHP ≥ 8.1
- Composer
- Node.js & NPM
- MySQL یا هر دیتابیس سازگار با لاراول
- Git

## مراحل راه‌اندازی پروژه

```bash
# ۱. کلون کردن پروژه
git clone https://github.com/FeriWiz/Post-Management.git
cd Post-Management

# ۲. نصب پکیج‌های PHP
composer install

# ۳. ساخت فایل .env
cp .env.example .env

# ۴. تولید کلید برنامه
php artisan key:generate

# ۵. تولید کلید JWT
php artisan jwt:secret

# ۶. اجرای مهاجرت‌های دیتابیس
php artisan migrate

# ۷. ایجاد لینک symbolic برای آپلود فایل‌ها
php artisan storage:link

# ۸. نصب و کامپایل assetهای فرانت‌اند (در صورت نیاز)
npm install
npm run dev

# ۹. اجرای سرور لوکال
php artisan serve

مستندات API (Swagger)
Swagger UI برای مشاهده و تست تمام endpointها در آدرس زیر آماده است:
http://127.0.0.1:8000/api/documentation
