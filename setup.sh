#!/bin/bash

# Satu Data TAPUT Setup Script

echo "========================================"
echo "Satu Data TAPUT - Setup Script"
echo "========================================"
echo ""

# Check prerequisites
echo "Checking prerequisites..."

if ! command -v php &> /dev/null; then
    echo "❌ PHP not found. Please install PHP 8.1 or higher."
    exit 1
fi

if ! command -v mysql &> /dev/null; then
    echo "❌ MySQL not found. Please install MySQL 5.7 or higher."
    exit 1
fi

if ! command -v node &> /dev/null; then
    echo "❌ Node.js not found. Please install Node.js 16 or higher."
    exit 1
fi

echo "✅ All prerequisites met"
echo ""

# Backend setup
echo "Setting up Backend (Laravel)..."
echo ""

echo "1. Installing Composer dependencies..."
composer install

echo "2. Creating .env file..."
if [ ! -f .env ]; then
    cp .env.example .env
    echo "✅ .env created. Please edit with your database credentials."
else
    echo "⚠️  .env already exists, skipping..."
fi

echo "3. Generating application key..."
php artisan key:generate

echo "4. Creating database..."
read -p "Enter MySQL username (default: root): " MYSQL_USER
MYSQL_USER=${MYSQL_USER:-root}
read -sp "Enter MySQL password: " MYSQL_PASSWORD
echo ""

mysql -u $MYSQL_USER -p$MYSQL_PASSWORD -e "CREATE DATABASE IF NOT EXISTS satu_data_taput;"

echo "5. Running migrations..."
php artisan migrate --seed

echo "6. Creating storage link..."
php artisan storage:link

echo "✅ Backend setup completed"
echo ""

# Frontend setup
echo "Setting up Frontend (Vue.js)..."
echo ""

echo "1. Installing npm dependencies..."
npm install

echo "2. Building assets..."
npm run build

echo "✅ Frontend setup completed"
echo ""

echo "========================================"
echo "Setup completed successfully! 🎉"
echo "========================================"
echo ""
echo "Next steps:"
echo "1. Open Terminal 1 and run: php artisan serve"
echo "2. Open Terminal 2 and run: npm run dev"
echo "3. Open http://localhost:8000 in your browser"
echo ""
echo "Default credentials:"
echo "  Email: admin@taput.gov.id"
echo "  Password: password123"
echo ""
