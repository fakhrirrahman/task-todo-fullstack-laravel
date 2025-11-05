<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

---

## 🚀 Project Setup

Ikuti langkah-langkah di bawah untuk menjalankan proyek ini secara lokal.

### 🛠️ Requirements

- PHP >= 8.3
- Composer
- Laravel CLI
- Mysql
- Node.js & NPM
- Git

### ⚙️ Installation Steps

```bash
# 1. Clone
git clone https://github.com/fakhrirrahman/task-todo-fullstack-laravel.git
cd task-todo-fullstack-laravel

# 2. Install dependencies
composer install
npm install

# 3. Salin file konfigurasi environment
cp .env.example .env

# 4. Update konfigurasi database di file .env
# Kemudian generate application key
php artisan key:generate

# 5. Jalankan migrasi dan seeder (jika ada)
php artisan migrate --seed

# 6. Jalankan server lokal
php artisan serve
npm run dev
```
### 🌐 Akses Aplikasi
Akses aplikasi di browser dengan URL berikut:

```
http://localhost:8000
```
### 🔑 Login ke Aplikasi
Gunakan kredensial berikut untuk login ke aplikasi:
```
Username: manager
Password: password
```
