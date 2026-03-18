#!/bin/bash

# ======================================================================
# REVO RADIUS AUTOMATED FULL INSTALLATION SCRIPT (UNATTENDED)
# OS Target: Ubuntu 24.04 LTS (Bare Metal / VPS)
# Fungsi: Install Dependensi, Setup DB, Clone Repo, Migrasi, & Auto Start
# ======================================================================

# --- Konfigurasi Variabel Otomatis ---
# Ganti URL ini dengan repositori asli Anda setelah project dibuat!
# Untuk demonstrasi agar sistem langsung "aktif", script akan membuat
# boilerplate Laravel kosongan jika variabel ini tidak diubah.
REPO_URL="https://github.com/laravel/laravel.git"

DB_NAME="revo_radius_db"
DB_USER="revo_user"
# Generate random password kuat untuk DB agar aman
DB_PASS=$(tr -dc A-Za-z0-9 </dev/urandom | head -c 16)

APP_DIR="/opt/revo-radius/app"
LOG_DIR="/var/log/revo-radius"
BACKUP_DIR="/var/lib/revo-radius/backups"

WEB_USER="www-data"
WEB_GROUP="www-data"

# Fungsi log warna
function print_msg() { echo -e "\n\e[1;32m[REVO-RADIUS]\e[0m \e[1;37m$1\e[0m"; }
function print_warn() { echo -e "\n\e[1;33m[WARNING]\e[0m \e[1;37m$1\e[0m"; }
function print_err() { echo -e "\n\e[1;31m[ERROR]\e[0m \e[1;37m$1\e[0m"; exit 1; }

# 1. Pengecekan Akses Root
if [ "$EUID" -ne 0 ]; then
  print_err "Script ini harus dijalankan sebagai root. (Gunakan: sudo ./install.sh)"
fi

print_msg "Memulai Instalasi Otomatis (Unattended) REVO RADIUS..."

# 2. Update System & Install Dependensi
print_msg "Tahap 1: Instalasi Nginx, MariaDB, Redis, dan PHP 8.3..."
export DEBIAN_FRONTEND=noninteractive
apt update -y
apt upgrade -y
apt install -y software-properties-common curl wget unzip git net-tools
apt install -y mariadb-server php8.3 php8.3-fpm php8.3-cli php8.3-mysql \
               php8.3-xml php8.3-mbstring php8.3-curl php8.3-zip php8.3-gd \
               nginx redis-server supervisor || print_err "Gagal menginstal dependensi."

# 3. Instalasi Composer Global
if ! command -v composer &> /dev/null; then
    print_msg "Menginstal Composer..."
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer
fi

# 4. Setup Database MariaDB Otomatis
print_msg "Tahap 2: Setup Database MariaDB..."
systemctl start mariadb
systemctl enable mariadb

mysql -e "CREATE DATABASE IF NOT EXISTS ${DB_NAME};"
mysql -e "CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';"
mysql -e "ALTER USER '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';"
mysql -e "GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"

# 5. Membuat Struktur Direktori Isolasi
print_msg "Tahap 3: Menyiapkan Struktur Direktori (/opt/revo-radius)..."
mkdir -p "$APP_DIR"
mkdir -p "$LOG_DIR"
mkdir -p "$BACKUP_DIR"

# 6. Mengambil Source Code (GitHub Clone)
print_msg "Tahap 4: Mengunduh Source Code Aplikasi dari GitHub..."
if [ -d "${APP_DIR}/.git" ]; then
    print_warn "Direktori repositori sudah ada. Melakukan git pull..."
    cd $APP_DIR && git pull origin main
else
    print_msg "Cloning dari ${REPO_URL} ke ${APP_DIR}..."
    # Karena kita menggunakan repo dummy laravel/laravel, clone branch master/main
    rm -rf $APP_DIR/* $APP_DIR/.* 2>/dev/null
    git clone $REPO_URL $APP_DIR || print_err "Gagal melakukan clone repositori."
fi

# 7. Setup Aplikasi (Composer, .env, Key, Migrate)
print_msg "Tahap 5: Setup Framework Backend (Composer & Environment)..."
cd $APP_DIR

# Pastikan kepemilikan diubah ke user root dulu saat menjalankan composer
# sebagai workaround agar root bisa menjalankan instalasi
composer install --optimize-autoloader --no-dev --no-interaction

# Membuat .env jika belum ada
if [ ! -f "${APP_DIR}/.env" ]; then
    cp .env.example .env
fi

# Mengubah konfigurasi database di dalam file .env
sed -i "s/DB_CONNECTION=.*/DB_CONNECTION=mysql/g" .env
sed -i "s/DB_HOST=.*/DB_HOST=127.0.0.1/g" .env
sed -i "s/DB_PORT=.*/DB_PORT=3306/g" .env
sed -i "s/DB_DATABASE=.*/DB_DATABASE=${DB_NAME}/g" .env
sed -i "s/DB_USERNAME=.*/DB_USERNAME=${DB_USER}/g" .env
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=${DB_PASS}/g" .env
sed -i "s/APP_ENV=.*/APP_ENV=production/g" .env
sed -i "s/APP_DEBUG=.*/APP_DEBUG=false/g" .env
sed -i "s/APP_URL=.*/APP_URL=http:\/\/localhost:8080/g" .env

# Generate Application Key
php artisan key:generate --force

# Menjalankan Migrasi Database
print_msg "Tahap 6: Melakukan Migrasi Database..."
php artisan migrate --force

# Optimasi Cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 8. Konfigurasi Permission File
print_msg "Menyesuaikan Permission Web Server..."
chown -R $WEB_USER:$WEB_GROUP /opt/revo-radius
chown -R $WEB_USER:$WEB_GROUP $LOG_DIR
chown -R $WEB_USER:$WEB_GROUP /var/lib/revo-radius

find /opt/revo-radius -type f -exec chmod 664 {} \;
find /opt/revo-radius -type d -exec chmod 775 {} \;
chmod -R ug+rwx $APP_DIR/storage $APP_DIR/bootstrap/cache

# 9. Konfigurasi Nginx (Port 8080)
print_msg "Tahap 7: Konfigurasi Nginx (Port 8080)..."
NGINX_CONF="/etc/nginx/sites-available/revo-radius"
cat <<EOF > "$NGINX_CONF"
server {
    listen 8080;
    server_name _;
    root ${APP_DIR}/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }
    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* { deny all; }
}
EOF
ln -sf /etc/nginx/sites-available/revo-radius /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default
systemctl restart nginx

# 10. Konfigurasi Systemd Queue Worker
print_msg "Tahap 8: Mengonfigurasi Service Latar Belakang (Systemd Worker)..."
WORKER_SERVICE="/etc/systemd/system/revo-radius-worker.service"
cat <<EOF > "$WORKER_SERVICE"
[Unit]
Description=Revo Radius Queue Worker
After=network.target

[Service]
User=${WEB_USER}
Group=${WEB_GROUP}
Restart=always
ExecStart=/usr/bin/php ${APP_DIR}/artisan queue:work --sleep=3 --tries=3
StandardOutput=append:${LOG_DIR}/worker.log
StandardError=append:${LOG_DIR}/worker.log

[Install]
WantedBy=multi-user.target
EOF
systemctl daemon-reload
systemctl enable revo-radius-worker
systemctl start revo-radius-worker
systemctl restart php8.3-fpm

# 11. Pengecekan Sistem Otomatis
print_msg "Tahap 9: Pengecekan Sistem Otomatis (System Check)..."
echo "--------------------------------------------------------"

# Cek DB
if systemctl is-active --quiet mariadb; then echo -e "MariaDB     : \e[32m[ONLINE]\e[0m"; else echo -e "MariaDB     : \e[31m[OFFLINE]\e[0m"; fi
# Cek Nginx
if systemctl is-active --quiet nginx; then echo -e "Nginx       : \e[32m[ONLINE]\e[0m"; else echo -e "Nginx       : \e[31m[OFFLINE]\e[0m"; fi
# Cek PHP
if systemctl is-active --quiet php8.3-fpm; then echo -e "PHP 8.3-FPM : \e[32m[ONLINE]\e[0m"; else echo -e "PHP 8.3-FPM : \e[31m[OFFLINE]\e[0m"; fi
# Cek Worker
if systemctl is-active --quiet revo-radius-worker; then echo -e "Worker Job  : \e[32m[ONLINE]\e[0m"; else echo -e "Worker Job  : \e[31m[OFFLINE]\e[0m"; fi

# Cek Web Akses (Curl localhost:8080)
HTTP_STATUS=$(curl -o /dev/null -s -w "%{http_code}\n" http://127.0.0.1:8080)
if [ "$HTTP_STATUS" == "200" ] || [ "$HTTP_STATUS" == "302" ]; then
    echo -e "Web App     : \e[32m[AKTIF] (Response: $HTTP_STATUS)\e[0m"
else
    echo -e "Web App     : \e[31m[GAGAL] (Response: $HTTP_STATUS)\e[0m"
    print_warn "Aplikasi belum berjalan dengan sempurna, periksa error log di Nginx/Laravel."
fi
echo "--------------------------------------------------------"

IP_ADDR=$(hostname -I | awk '{print $1}')

print_msg "================================================================="
echo -e "\e[1;32mINSTALASI REVO RADIUS SELESAI & SISTEM AKTIF!\e[0m"
echo ""
echo "Informasi Penting Database Anda:"
echo "DB_DATABASE : ${DB_NAME}"
echo "DB_USERNAME : ${DB_USER}"
echo "DB_PASSWORD : ${DB_PASS}"
echo "(Harap simpan password database di atas dengan aman!)"
echo ""
echo "Akses Sistem Revo Radius Anda di browser pada URL:"
echo -e "\e[1;36mhttp://${IP_ADDR}:8080\e[0m"
print_msg "================================================================="
