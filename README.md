# Satu Data 

Portal data terpadu untuk manajemen dan visualisasi data sektoral 

## 🎯 Fitur Utama

- ✅ Autentikasi & Autorisasi berbasis role (Admin OPD, Admin Portal, Public)
- ✅ Upload Dataset (CSV, Excel, JSON)
- ✅ Dashboard Admin dengan statistik real-time
- ✅ Visualisasi Data dengan Chart.js
- ✅ Filter & Search Dataset
- ✅ Manajemen User berbasis role
- ✅ Metadata Management (nama, deskripsi, tanggal update)

## 🛠️ Tech Stack

- **Backend**: Laravel 10.x
- **Frontend**: Vue 3.x
- **Database**: MySQL
- **Build Tool**: Vite
- **API**: RESTful API with Sanctum
- **Styling**: Tailwind CSS

## 📋 Prasyarat

- PHP 8.1+
- Composer
- Node.js 16+
- MySQL 5.7+
- Git

## 🚀 Installation

### 1. Clone Repository
```bash
git clone https://github.com/indralaga/satu-data.git
cd satu-data
```

### 2. Install Dependencies

#### Backend
```bash
composer install
cp .env.example .env
php artisan key:generate
```

#### Frontend
```bash
npm install
```

### 3. Database Setup
```bash
# Buat database MySQL terlebih dahulu
mysql -u root -p
CREATE DATABASE satu_data_;
exit;

# Jalankan migrations dan seeder
php artisan migrate --seed
```

### 4. Run Application

#### Terminal 1 - Laravel Server
```bash
php artisan serve
```

#### Terminal 2 - Vite Dev Server
```bash
npm run dev
```

Akses aplikasi di: **http://localhost:8000**

## 👥 Default Users

Setelah menjalankan seeder, berikut user default yang tersedia:

### Admin Portal
- Email: `admin@taput.gov.id`
- Password: `password123`

### Admin OPD (Contoh: Dinas Kesehatan)
- Email: `admin.dinas.kesehatan@taput.gov.id`
- Password: `password123`

### Viewer
- Email: `viewer@example.com`
- Password: `password123`

## 📁 Struktur Project

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
│   ├── Models/
│   │   ├── User.php
│   │   ├── Dataset.php
│   │   ├── DatasetFile.php
│   │   ├── DatasetMetadata.php
│   │   └── OPD.php
│   └── Services/
├── database/
│   ├── migrations/
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/
│   ├── js/
│   │   ├── components/
│   │   ├── pages/
│   │   │   ├── Login.vue
│   │   │   ├── Dashboard.vue
│   │   │   ├── DatasetList.vue
│   │   │   ├── DatasetUpload.vue
│   │   │   └── UserManagement.vue
│   │   ├── router/
│   │   │   └── index.js
│   │   ├── app.js
│   │   └── bootstrap.js
│   ├── views/
│   │   └── app.blade.php
│   └── css/
│       └── app.css
├── routes/
│   ├── api.php
│   └── web.php
├── storage/
│   └── app/
│       └── datasets/
├── .env.example
├── composer.json
├── package.json
├── vite.config.js
└── tailwind.config.js
```

## 🔐 Roles & Permissions

### 🛡️ Admin Portal
- Lihat semua dataset
- Manajemen user (approve, edit, hapus)
- Lihat statistik global
- Edit metadata dataset
- Akses dashboard lengkap

### 🏢 Admin OPD
- Upload dataset ke portal
- Kelola dataset sendiri (edit, hapus)
- Lihat statistik OPD sendiri
- Tidak bisa edit dataset OPD lain

### 👤 Public/Viewer
- Lihat dataset publik saja
- Download dataset publik
- Tidak bisa upload atau edit
- Akses terbatas ke halaman publik

## 📊 API Endpoints

### Authentication
```
POST   /api/login              - Login
POST   /api/register           - Register
POST   /api/logout             - Logout (require auth)
GET    /api/user               - Get current user (require auth)
```

### Datasets
```
GET    /api/datasets           - List datasets (require auth)
POST   /api/datasets           - Create dataset (require auth, admin)
GET    /api/datasets/{id}      - Get dataset detail (require auth)
PUT    /api/datasets/{id}      - Update dataset (require auth, admin)
DELETE /api/datasets/{id}      - Delete dataset (require auth, admin)
POST   /api/datasets/{id}/download - Download dataset file (require auth)
```

### Users (Admin Portal only)
```
GET    /api/users              - List users
GET    /api/users/{id}         - Get user detail
PUT    /api/users/{id}         - Update user
DELETE /api/users/{id}         - Delete user
POST   /api/users/{id}/approve - Approve pending user
```

### Dashboard
```
GET    /api/dashboard          - Get dashboard statistics
```

## 📝 Catatan Pengembangan

- File upload disimpan di `storage/app/public/datasets/`
- CSV parsing menggunakan library `league/csv`
- Authentication menggunakan Laravel Sanctum
- Middleware custom untuk role-based access control

## 🐛 Troubleshooting

### Jika migration error:
```bash
php artisan migrate:fresh --seed
```

### Jika vite tidak running:
```bash
rm -rf node_modules package-lock.json
npm install
npm run dev
```

### Jika file upload tidak bekerja:
```bash
php artisan storage:link
chmod -R 775 storage/app/public
```

## 🤝 Contributing

Silakan fork repository ini dan buat pull request untuk kontribusi.

## 📄 License

MIT License

## 👨‍💻 Author

Indra Laga
