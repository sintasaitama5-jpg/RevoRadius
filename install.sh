#!/bin/bash

# ======================================================================
# REVO RADIUS AUTOMATED INSTALLATION SCRIPT
# OS Target: Ubuntu 24.04 LTS (Bare Metal / VPS)
# ======================================================================

# --- Konfigurasi Variabel ---
# Ubah nilai di bawah ini jika diperlukan sebelum script dijalankan
DB_NAME="revo_radius_db"
DB_USER="revo_user"
DB_PASS="Rahasia123!"

APP_DIR="/opt/revo-radius/app"
LOG_DIR="/var/log/revo-radius"
BACKUP_DIR="/var/lib/revo-radius/backups"

WEB_USER="www-data"
WEB_GROUP="www-data"

# Fungsi log untuk membedakan output script
function print_msg() {
    echo -e "\n\e[1;32m[REVO-RADIUS]\e[0m \e[1;37m$1\e[0m"
}

function print_err() {
    echo -e "\n\e[1;31m[ERROR]\e[0m \e[1;37m$1\e[0m"
    exit 1
}

# 1. Cek hak akses root
if [ "$EUID" -ne 0 ]; then
  print_err "Script ini harus dijalankan sebagai root (gunakan: sudo ./install.sh)"
fi

print_msg "Memulai instalasi dependensi REVO RADIUS untuk Ubuntu 24.04..."

# 2. Update repository & Install Dependensi Utama (PHP 8.3, MariaDB, Nginx, Redis)
print_msg "Menginstal MariaDB, Nginx, Redis, dan PHP 8.3 beserta ekstensinya..."
apt update && apt upgrade -y || print_err "Gagal update repository"

apt install -y software-properties-common curl wget unzip git
apt install -y mariadb-server php8.3 php8.3-fpm php8.3-cli php8.3-mysql \
               php8.3-xml php8.3-mbstring php8.3-curl php8.3-zip php8.3-gd \
               nginx redis-server supervisor || print_err "Gagal instalasi dependensi apt"

# 3. Instalasi Composer
print_msg "Menginstal Composer..."
if ! command -v composer &> /dev/null; then
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer
fi

# 4. Konfigurasi Database MariaDB
print_msg "Mengkonfigurasi Database Revo Radius..."

# Mengamankan MariaDB secara dasar (jika belum) dan membuat user/db
mysql -e "CREATE DATABASE IF NOT EXISTS ${DB_NAME};"
mysql -e "CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';"
# Jika user sudah ada, update passwordnya
mysql -e "ALTER USER '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';"
mysql -e "GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"

# 5. Membuat Struktur Direktori Terisolasi
print_msg "Membuat struktur direktori aplikasi (${APP_DIR})..."
mkdir -p "$APP_DIR"
mkdir -p "$LOG_DIR"
mkdir -p "$BACKUP_DIR"

# Mengatur Ownership agar bisa dibaca/tulis oleh Nginx/PHP-FPM
chown -R $WEB_USER:$WEB_GROUP /opt/revo-radius
chown -R $WEB_USER:$WEB_GROUP $LOG_DIR
chown -R $WEB_USER:$WEB_GROUP /var/lib/revo-radius

chmod -R 775 /opt/revo-radius
chmod -R 775 $LOG_DIR

# 6. Membuat Konfigurasi Nginx (Isolasi di Port 8080)
print_msg "Mengonfigurasi Nginx Reverse Proxy (Port 8080)..."
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

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

# Mengaktifkan Nginx conf
ln -sf /etc/nginx/sites-available/revo-radius /etc/nginx/sites-enabled/
systemctl restart nginx

# 7. Membuat Systemd Service untuk Queue Worker (Sinkronisasi Mikrotik)
print_msg "Membuat Systemd Service untuk Background Worker (revo-radius-worker)..."
WORKER_SERVICE="/etc/systemd/system/revo-radius-worker.service"

cat <<EOF > "$WORKER_SERVICE"
[Unit]
Description=Revo Radius Queue Worker
After=network.target

[Service]
User=${WEB_USER}
Group=${WEB_GROUP}
Restart=always
ExecStart=/usr/bin/php ${APP_DIR}/artisan queue:work --sleep=3 --tries=3 --timeout=90
StandardOutput=append:${LOG_DIR}/worker.log
StandardError=append:${LOG_DIR}/worker.log

[Install]
WantedBy=multi-user.target
EOF

systemctl daemon-reload
systemctl enable revo-radius-worker

# 8. Instruksi Akhir untuk User
print_msg "================================================================="
echo "Instalasi Server & Dependensi Revo Radius BERHASIL!"
echo "Server siap menerima source code Laravel."
echo ""
echo "LANGKAH SELANJUTNYA (Yang harus Anda lakukan secara manual):"
echo "1. Clone/Upload source code Laravel ke direktori: ${APP_DIR}"
echo "2. Masuk ke direktori aplikasi: cd ${APP_DIR}"
echo "3. Instal PHP vendor: composer install --optimize-autoloader --no-dev"
echo "4. Copy .env: cp .env.example .env"
echo "5. Generate key: php artisan key:generate"
echo ""
echo "Di file .env Anda, isi konfigurasi database dengan:"
echo "DB_DATABASE=${DB_NAME}"
echo "DB_USERNAME=${DB_USER}"
echo "DB_PASSWORD=${DB_PASS}"
echo ""
echo "6. Jalankan Migrasi DB: php artisan migrate --seed"
echo "7. Jalankan Queue Worker: sudo systemctl start revo-radius-worker"
echo "8. Akses Revo Radius di browser: http://<IP_SERVER>:8080"
print_msg "================================================================="
