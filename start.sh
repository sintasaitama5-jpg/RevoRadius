#!/bin/bash

echo "================================================"
echo "    Memulai REVO RADIUS Network Console"
echo "================================================"

# Pastikan vendor dan node_modules ada
if [ ! -d "vendor" ]; then
    echo "Installing PHP dependencies..."
    composer install
fi

if [ ! -d "node_modules" ]; then
    echo "Installing Node dependencies..."
    npm install
fi

# Reset database & jalankan seeder untuk development lokal (Hati-hati jika di produksi!)
echo "Menyiapkan Database Lokal (SQLite)..."
touch database/database.sqlite
php artisan migrate:fresh --seed

echo ""
echo "================================================"
echo " Aplikasi siap digunakan!"
echo " Silakan buka browser di: http://127.0.0.1:8000"
echo " Login dengan:"
echo " Email   : admin@revo.com"
echo " Password: password"
echo "================================================"
echo ""
echo "Menjalankan Laravel Server & Vite Frontend..."

# Jalankan Laravel & Vite secara bersamaan (concurrently)
npx concurrently -c "green,blue" -n "LARAVEL,VITE" "php artisan serve --port=8000" "npm run dev"
