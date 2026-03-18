# 🚀 REVO RADIUS

**Platform Manajemen Jaringan Mikrotik Modular & Terpusat**

![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)
![Build: Passing](https://img.shields.io/badge/build-passing-brightgreen)
![Version: 1.0.0-MVP](https://img.shields.io/badge/version-1.0.0--MVP-orange)

## 📌 Apa itu Revo Radius?

**REVO RADIUS** adalah sistem billing dan manajemen ISP/RT-RW Net yang dirancang dengan **arsitektur modular ekstrem**. Sistem ini menangani ribuan router Mikrotik (v6 dan v7), manajemen staf multi-tenant, billing bulanan, hotspot voucher massal, serta integrasi langsung dengan **FreeRADIUS** dan **WireGuard/L2TP Tunnel** tanpa mengganggu ekosistem Linux Anda yang sudah ada.

Dibangun dengan prinsip bahwa *"kegagalan satu modul tidak boleh meruntuhkan seluruh sistem."*

---

## 🏗️ Fitur Utama

- **🛡️ Manajemen Multi-Router & Multi-Owner**: Setiap staf/owner hanya bisa melihat router dan pendapatan dari areanya sendiri (Role-Based Access Control).
- **💸 Billing & Invoice Cerdas**: Penagihan bulanan otomatis, payment gateway (Midtrans & Manual), auto-suspend saat menunggak, dan auto-unsuspend saat lunas.
- **🎫 Hotspot Voucher Engine**: Generator voucher massal dengan pelacakan pendapatan yang akurat (voucher *unpaid* tidak dihitung sebagai pemasukan sampai diaktifkan/terjual).
- **📡 FreeRADIUS Native Integration**: Terhubung langsung dengan skema database FreeRADIUS (`radcheck`, `radreply`, `radacct`) menggunakan MariaDB.
- **🔐 Manajemen Tunnel (WireGuard & L2TP)**: Hubungkan ribuan router cabang ke server pusat secara otomatis via API dari dashboard Revo Radius.
- **⚙️ Toleransi Kegagalan (Graceful Sync)**: Jika router klien *offline*, sinkronisasi perubahan profil/user akan dimasukkan ke dalam antrean (*queue*) latar belakang dan dicoba ulang (retry) otomatis saat router *online* kembali.

---

## 🛠️ Tech Stack & Persyaratan Sistem

**Teknologi Utama:**
- **Backend:** PHP 8.3 (Laravel 11.x)
- **Frontend:** Vue 3 (SPA / Inertia.js)
- **Database:** MariaDB 10.6+
- **Antrean (Queue):** Redis / Database Queue (Worker Systemd)
- **Web Server:** Nginx (via Reverse Proxy untuk isolasi port)

**Persyaratan Bare Metal:**
- Sistem Operasi: **Ubuntu 24.04 LTS**
- CPU Minimal: 2 Core
- RAM Minimal: 4 GB
- Penyimpanan: 40 GB NVMe/SSD

---

## 🚀 Instalasi Bare Metal (Ubuntu 24.04)

Revo Radius dirancang untuk beroperasi di foldernya sendiri tanpa menimpa konfigurasi layanan utama sistem. Panduan lengkap arsitektur sistem dapat dibaca di [`ARCHITECTURE.md`](ARCHITECTURE.md).

### 1. Menyiapkan Dependensi

Jalankan perintah berikut untuk menginstal PHP 8.3 dan MariaDB:

```bash
sudo apt update
sudo apt install -y mariadb-server php8.3 php8.3-fpm php8.3-cli php8.3-mysql php8.3-xml php8.3-mbstring php8.3-curl unzip curl nginx redis-server
```

### 2. Konfigurasi Database

Buat database khusus untuk Revo Radius:

```bash
sudo mysql -e "CREATE DATABASE revo_radius_db;"
sudo mysql -e "CREATE USER 'revo_user'@'localhost' IDENTIFIED BY 'PasswordKuatAnda123!';"
sudo mysql -e "GRANT ALL PRIVILEGES ON revo_radius_db.* TO 'revo_user'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"
```

### 3. Mengatur Folder Aplikasi

```bash
# Buat direktori aplikasi, log, dan backup
sudo mkdir -p /opt/revo-radius/app
sudo mkdir -p /var/log/revo-radius
sudo mkdir -p /var/lib/revo-radius/backups

# Atur kepemilikan folder agar bisa diakses oleh web-server
sudo chown -R www-data:www-data /opt/revo-radius /var/log/revo-radius /var/lib/revo-radius
```

### 4. Deploy Aplikasi

Masuk ke direktori `/opt/revo-radius/app/` lalu jalankan perintah berikut (asumsi *source code* sudah di-clone ke folder ini):

```bash
cd /opt/revo-radius/app
composer install --optimize-autoloader --no-dev
cp .env.example .env
php artisan key:generate
```

Ubah kredensial database di file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=revo_radius_db
DB_USERNAME=revo_user
DB_PASSWORD=PasswordKuatAnda123!
```

Migrasikan skema database dan masukkan data default (Superadmin & Role):
```bash
php artisan migrate --seed
```

### 5. Mengaktifkan Background Worker

Background worker wajib dinyalakan karena sinkronisasi Mikrotik dan pengiriman Invoice diproses di latar belakang agar UI tetap cepat.

Buat file systemd di `/etc/systemd/system/revo-radius-worker.service`:

```ini
[Unit]
Description=Revo Radius Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /opt/revo-radius/app/artisan queue:work --sleep=3 --tries=3
StandardOutput=append:/var/log/revo-radius/worker.log
StandardError=append:/var/log/revo-radius/worker.log

[Install]
WantedBy=multi-user.target
```

Nyalakan *service* worker tersebut:
```bash
sudo systemctl daemon-reload
sudo systemctl enable revo-radius-worker
sudo systemctl start revo-radius-worker
```

---

## 📖 Struktur Direktori Khusus Linux
Aplikasi ini mematuhi standar direktori Linux (FHS) agar tidak bertabrakan dengan *software* lain:
- **Source Code**: `/opt/revo-radius/app/`
- **Konfigurasi Spesifik**: `/etc/revo-radius/` (Opsional, untuk env lanjutan)
- **Log Aplikasi & Worker**: `/var/log/revo-radius/`
- **Penyimpanan Backup DB & File (PDF/Invoice)**: `/var/lib/revo-radius/`

---

## 🤝 Dokumentasi Lanjutan

Untuk memahami alur kerja frontend-ke-backend, struktur database modular, State Machine voucher, cara integrasi Mikrotik API (v6 & v7) serta arsitektur Tunnel (WireGuard/L2TP), silakan baca dokumen pendamping:
👉 **[ARCHITECTURE.md](ARCHITECTURE.md)**

---

## 🛡️ Keamanan & Lisensi

Revo Radius tidak pernah menyimpan password router Anda dalam bentuk teks biasa (plaintext). Semua kredensial router Mikrotik dienkripsi di dalam *database* menggunakan algoritma AES-256 (via `APP_KEY` environment variable).

Hak cipta dilindungi undang-undang. Diperuntukkan bagi Enterprise dan komunitas ISP/Hotspot.
