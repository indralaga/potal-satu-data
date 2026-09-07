# Setup Guide - Satu Data TAPUT

## Prerequisites

- PHP 8.1 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Node.js 16+ dan npm
- Composer
- Git

## Step-by-Step Installation

### 1. Clone Repository

```bash
git clone https://github.com/indralaga/satu-data-taput.git
cd satu-data-taput
```

### 2. Backend Setup (Laravel)

#### 2.1 Install Dependencies
```bash
composer install
```

#### 2.2 Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

#### 2.3 Update .env File
Edit file `.env` dengan konfigurasi database Anda:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=satu_data_taput
DB_USERNAME=root
DB_PASSWORD=your_password
```

#### 2.4 Create Database
```bash
mysql -u root -p
CREATE DATABASE satu_data_taput;
exit;
```

#### 2.5 Run Migrations & Seeding
```bash
php artisan migrate --seed
```

Ini akan membuat:
- 5 OPD (Organisasi Perangkat Daerah)
- 6 User default (1 admin portal, 5 admin OPD, 1 viewer)

#### 2.6 Create Storage Link
```bash
php artisan storage:link
```

### 3. Frontend Setup (Vue.js + Vite)

#### 3.1 Install Dependencies
```bash
npm install
```

#### 3.2 Build Assets (Development)
```bash
npm run dev
```

### 4. Start Application

**Terminal 1 - Laravel Server:**
```bash
php artisan serve
```

**Terminal 2 - Vite Dev Server:**
```bash
npm run dev
```

Akses aplikasi: http://localhost:8000

## Default Credentials

### Admin Portal
- **Email:** admin@taput.gov.id
- **Password:** password123
- **Role:** Admin Portal (full access)

### Admin OPD - Dinas Kesehatan
- **Email:** admin.dinas.kesehatan@taput.gov.id
- **Password:** password123
- **Role:** Admin OPD

### Admin OPD - Dinas Pendidikan
- **Email:** admin.dinas.pendidikan@taput.gov.id
- **Password:** password123
- **Role:** Admin OPD

### Viewer
- **Email:** viewer@example.com
- **Password:** password123
- **Role:** Viewer (read-only)

## Project Structure

```
satu-data-taput/
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/
│   │   │   ├── AuthController.php
│   │   │   ├── DatasetController.php
│   │   │   ├── UserController.php
│   │   │   └── DashboardController.php
│   │   └── Middleware/
│   │       └── AdminMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── Dataset.php
│       ├── DatasetFile.php
│       ├── DatasetMetadata.php
│       └── OPD.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── js/
│   │   ├── pages/
│   │   │   ├── Login.vue
│   │   │   ├── Dashboard.vue
│   │   │   ├── DatasetList.vue
│   │   │   ├── DatasetUpload.vue
│   │   │   └── UserManagement.vue
│   │   ├── router/
│   │   ├── app.js
│   │   └── App.vue
│   ├── views/
│   │   └── app.blade.php
│   └── css/
│       └── app.css
├── routes/
│   ├── api.php
│   └── web.php
├── storage/
│   └── app/
│       └── public/
│           └── datasets/
├── .env.example
├── composer.json
├── package.json
├── vite.config.js
├── tailwind.config.js
└── README.md
```

## Common Tasks

### Fresh Migration
Jika perlu reset database:
```bash
php artisan migrate:fresh --seed
```

### Create New User
```php
php artisan tinker
$user = App\Models\User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => Hash::make('password123'),
    'role' => 'admin_opd',
    'opd_id' => 1,
    'is_active' => true,
]);
```

### View Logs
```bash
tail -f storage/logs/laravel.log
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## Troubleshooting

### 1. Database Connection Error
**Problem:** SQLSTATE[HY000]

**Solution:**
- Pastikan MySQL running
- Check `.env` database credentials
- Verify database sudah dibuat

### 2. File Upload Not Working
**Problem:** File tidak tersimpan

**Solution:**
```bash
php artisan storage:link
chmod -R 775 storage/app/public
```

### 3. Vite Not Compiling
**Problem:** Assets tidak load

**Solution:**
```bash
rm -rf node_modules package-lock.json
npm install
npm run dev
```

### 4. Sanctum Token Issues
**Problem:** 401 Unauthorized

**Solution:**
- Pastikan token ada di localStorage
- Check Authorization header di browser DevTools
- Verify `SANCTUM_STATEFUL_DOMAINS` di .env

## Deployment Guide

### Production Build

#### 1. Backend Setup
```bash
composer install --no-dev
php artisan config:cache
php artisan route:cache
```

#### 2. Frontend Build
```bash
npm run build
```

#### 3. Database Migration
```bash
php artisan migrate --force
```

#### 4. Set Permissions
```bash
chown -R www-data:www-data storage/
chmod -R 775 storage/
```

#### 5. Configure Web Server (Apache/Nginx)

**Apache (.htaccess in public/):**
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ index.php [QSA,L]
</IfModule>
```

**Nginx:**
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

## API Documentation

Lihat [API.md](./API.md) untuk dokumentasi lengkap endpoint API.

## Support

Jika ada masalah, buat issue di GitHub repository.
