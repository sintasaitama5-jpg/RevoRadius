# REVO RADIUS - Blueprint & Architecture Document

Dokumen ini adalah cetak biru teknis untuk **REVO RADIUS**, platform manajemen jaringan Mikrotik berbasis web yang revolusioner, modular, scalable, dan production-ready.

---

## 1. Ringkasan Visi Sistem
**REVO RADIUS** dibangun dengan visi sebagai "pusat komando" bagi ISP, RT/RW Net, dan operator Hotspot. Sistem ini tidak hanya mencatat tagihan, tetapi mengatur router, voucher, member PPPoE, dan infrastruktur tunnel (WireGuard/L2TP) secara terpusat. Visi utama sistem ini adalah **Modularitas Ekstrem dan Keandalan**: jika satu modul (misalnya FreeRADIUS) mati atau satu router offline, modul lain (seperti billing atau manajemen voucher) harus tetap berjalan tanpa hambatan.

## 2. Arsitektur Modular Tingkat Tinggi
Sistem menggunakan pendekatan *Domain-Driven Design (DDD)* di dalam kerangka kerja **Laravel (PHP)** untuk Backend dan **Vue 3 (JavaScript)** untuk Frontend SPA.
- **Frontend Layer**: SPA Vue 3 yang ringan. Berkomunikasi hanya melalui REST API.
- **API/Controller Layer**: Menerima request, memvalidasi input, dan memverifikasi izin (*Authorization*).
- **Service Layer**: Berisi logika bisnis (misal: `VoucherService`, `BillingService`). Service dari satu modul tidak boleh mengakses database modul lain secara langsung, melainkan harus memanggil Service modul tersebut.
- **Repository/Data Layer**: Menangani *query* ke **MariaDB**.
- **Integration Layer**: Menangani komunikasi ke pihak ketiga (Mikrotik API, FreeRADIUS database, Midtrans Payment Gateway).
- **Queue/Job Layer**: Menangani proses berat di latar belakang agar UI tetap responsif (misal: sinkronisasi massal ke router, pengiriman invoice email).

## 3. Penjelasan Fase Pengembangan Step by Step
Sistem ini akan dibangun dalam 7 Fase agar stabil dan terukur:
- **Phase 1 (Core Platform)**: Setup framework, UI dasar, login, role, permission, manajemen staf, dan audit log. Ini fondasi wajib.
- **Phase 2 (Router Management)**: Membuat manajer koneksi ke Mikrotik (v6 dan v7), menyimpan kredensial aman, sinkronisasi data dasar (interface, resource).
- **Phase 3 (Hotspot Voucher)**: Membuat sistem voucher (generasi massal, *state machine* status voucher).
- **Phase 4 (PPPoE & Billing)**: Manajemen member bulanan, penagihan otomatis (*invoice*), integrasi manual & payment gateway (Midtrans).
- **Phase 5 (FreeRADIUS)**: Mengintegrasikan MariaDB Revo Radius dengan schema database FreeRADIUS untuk otentikasi terpusat.
- **Phase 6 (Tunnel Management)**: Manajemen VPN (WireGuard/L2TP) untuk interkoneksi antar router.
- **Phase 7 (Reporting)**: Laporan finansial, analitik, dan *export* data.

## 4. Daftar Modul dan Tanggung Jawabnya
1. **Core Modul**: Menangani *authentication*, RBAC (Role-Based Access Control), profil sistem, dan logging.
2. **Router Modul**: Bertanggung jawab penuh atas koneksi ke Mikrotik. Semua modul lain yang butuh bicara ke Mikrotik *wajib* lewat modul ini.
3. **Hotspot Modul**: Khusus mengurus logika voucher dan member hotspot.
4. **Billing Modul**: Mencetak invoice, melacak pembayaran, memicu *suspend/unsuspend* via Router Modul atau Radius Modul.
5. **Radius Modul**: Menerjemahkan paket Revo Radius menjadi atribut FreeRADIUS (radcheck/radreply).
6. **Tunnel Modul**: Menyimpan konfigurasi IPSEC/WireGuard/L2TP dan menerapkannya ke Router Modul.

## 5. Alur Frontend ke Backend (Data Flow & 10 Use Case Wajib)

Setiap request mengikuti arsitektur berlapis:
`Frontend` -> `API Controller` (Validasi & Authorization) -> `Service Layer` (Logika Bisnis) -> `Repository` (Database MariaDB) -> `Integration/Queue` (Mikrotik/Radius/Midtrans).

Berikut adalah detail Data Flow untuk 10 Use Case krusial:

**1. Tambah Router**
- **Frontend**: Admin mengisi Form (Nama Router, IP, Port, Username, Password, Owner).
- **Backend**: Controller memvalidasi IP dan kredensial.
- **Service Layer**: `RouterService` mengenkripsi password dengan `Crypt::encryptString`.
- **Repository**: Menyimpan data router ke tabel `routers`.
- **Queue/Job**: `TestRouterConnectionJob` dipicu ke dalam queue untuk mencoba ping dan API login tanpa membuat UI frontend terblokir.
- **Response**: Mengembalikan status "Router berhasil ditambahkan, sedang menguji koneksi."

**2. Generate Voucher**
- **Frontend**: Operator mengisi form (Jumlah: 100, Paket: 1 Hari, Status: Unpaid) dan klik "Simpan".
- **Backend**: Controller memvalidasi input. Cek permission `create_voucher`.
- **Service Layer**: `VoucherService` dipanggil. Membuat *Batch ID*, me-looping pembuatan 100 kode/password acak.
- **Repository**: Menyimpan 100 baris ke tabel `vouchers` di MariaDB.
- **Queue**: Jika router spesifik dipilih, job sinkronisasi (misal: tambah user userman) masuk antrean. Jika otentikasi sentral, data disimpan di tabel `vouchers` (belum masuk FreeRADIUS).
- **Audit Log**: "Operator A generate 100 voucher".
- **Response**: Frontend menerima JSON success.

**3. Aktivasi Voucher Unpaid menjadi Paid/Active**
- **Frontend**: Operator/Reseller memasukkan kode voucher yang ingin dijual, klik "Aktifkan & Bayar".
- **Backend**: Validasi kode eksis dan status masih `UNPAID`.
- **Service Layer**: `VoucherActivationService` mengubah state voucher dari `UNPAID` menjadi `PAID`. Menambahkan nominal harga ke `finance_ledger` (Mengakui Pendapatan).
- **Integration Layer**: `RadiusService` dipanggil untuk menyuntikkan user ke tabel `radcheck` dan profil ke `radreply` (karena baru boleh login setelah dibayar).
- **Response**: Status voucher di UI berubah menjadi Hijau (PAID/Siap Pakai).

**4. Tambah Member PPPoE**
- **Frontend**: Admin mengisi profil pelanggan, nomor HP, alamat, paket internet, dan router NAS tujuan.
- **Backend**: Validasi kelengkapan data.
- **Service Layer**: `CustomerService` membuat data pelanggan. `SubscriptionService` membuat kontrak berlangganan.
- **Repository**: Simpan ke `customers`, `customer_services`.
- **Integration**: Menambahkan secret PPPoE langsung ke Router Mikrotik via API (disisipkan ke antrean Queue agar aman jika router offline) ATAU memasukkan data ke MariaDB (Radius) jika menggunakan FreeRADIUS sebagai backend PPPoE.

**5. Buat Invoice Bulanan**
- **Scheduler**: Berjalan otomatis pukul 00:01 (Cron/Job `GenerateMonthlyInvoicesJob`).
- **Service Layer**: Mengambil seluruh pelanggan yang kontraknya aktif (`status: ACTIVE`).
- **BillingService**: Men-generate nomor invoice (INV-2023-XXX), menghitung PPN, total harga.
- **Repository**: Menyimpan baris baru ke tabel `invoices` dengan state `UNPAID`.
- **Integration Layer**: (Opsional) Mengirim pesan WhatsApp/Email tagihan ke pelanggan.

**6. Bayar Tagihan (Manual/Payment Gateway)**
- **Manual (Frontend)**: Kasir menerima uang tunai, mencari invoice, klik "Tandai Lunas".
- **Midtrans (Webhook)**: Midtrans memanggil endpoint `/api/webhook/midtrans` dengan payload sukses.
- **Service Layer**: `PaymentService` dipanggil. Mencatat log pembayaran ke tabel `payments`.
- **Repository**: Mengubah state invoice dari `UNPAID` menjadi `PAID`.
- **Event**: Memancarkan event `InvoicePaid`. Listener akan mengecek apakah user sedang `SUSPENDED`. Jika ya, trigger unsuspend job.

**7. Suspend User Telat Bayar**
- **Scheduler**: Berjalan setiap hari mengecek invoice `UNPAID` yang `due_date`-nya telah lewat.
- **Service Layer**: Menandai invoice menjadi `OVERDUE`. Mengubah status di tabel `customer_services` menjadi `SUSPENDED`.
- **Queue/Integration**: Memasukkan `SuspendCustomerJob` ke dalam queue. Worker akan memanggil API Mikrotik untuk menghapus Active Session PPPoE/Hotspot user tersebut, atau mengganti IP user ke IP Pool Isolir (`radreply`).

**8. Sync User ke FreeRADIUS**
- **Backend**: Dipicu otomatis saat pembuatan/perubahan paket member (Event Driven).
- **Service Layer**: `RadiusSyncService` dipanggil.
- **Repository Layer**: Menghapus entry lama di `radcheck` dan `radreply` untuk username tersebut, lalu memasukkan data baru (Cleartext-Password, Mikrotik-Rate-Limit, Framed-IP-Address).
- **Response**: User bisa langsung login di Mikrotik klien yang menjadikan Revo Radius sebagai FreeRADIUS Server-nya.

**9. Membuat WireGuard Tunnel**
- **Frontend**: Admin memilih "Router Pusat" dan "Router Cabang", klik "Buat Tunnel WireGuard".
- **Backend**: Validasi ketersediaan IP address subnet VPN.
- **Service Layer**: `TunnelService` menghasilkan Private Key dan Public Key untuk cabang. Menetapkan IP Address (contoh: 10.9.9.1 dan 10.9.9.2).
- **Queue/Integration**: Memanggil Mikrotik API untuk Router Pusat (menambahkan interface wg, listen port, menambahkan peer cabang). Memanggil Mikrotik API untuk Router Cabang (menambahkan interface wg, memasukkan public key pusat, allowed address, dan routing/endpoint).
- **Repository**: Simpan status di `wireguard_links`.

**10. Membuat L2TP Tunnel**
- **Frontend**: Admin membuat L2TP Secret.
- **Service Layer**: Membuat kredensial Username dan Password.
- **Integration Layer**: Menambahkan `/ppp secret` di Mikrotik L2TP Server secara API.
- **Response**: Mengembalikan kredensial L2TP Server yang bisa di-copy paste oleh teknisi ke router lain yang mungkin tidak dikendalikan sistem (klien eksternal).

## 6. Desain Database Modular Lengkap dengan Alasan Tabel
Database menggunakan **MariaDB**.

**CORE DOMAIN**
- `users`: Data login admin/staf.
- `roles`, `permissions`, `user_roles`, `role_permissions`: Sistem RBAC untuk membatasi akses (Superadmin, Finance, dll).
- `staff_profiles`: Data detail staf.
- `owners`: Entitas pemilik bisnis/franchise.
- `router_owners`: Pivot tabel yang menentukan router mana dimiliki owner/staf mana (Multi-tenant).
- `audit_logs`: Wajib untuk keamanan. Mencatat siapa melakukan apa kapan.
- `app_settings`: Pengaturan global.

**ROUTER DOMAIN**
- `routers`: Menyimpan IP, port, OS version (v6/v7). (Password dienkripsi di tabel terpisah atau kolom khusus `router_credentials`).
- `router_groups`: Pengelompokan router (misal: "Cabang Utara").
- `router_sync_logs`: Riwayat kapan terakhir router disinkronisasi dan statusnya (sukses/gagal).

**HOTSPOT DOMAIN**
- `hotspot_profiles`: Paket hotspot (kecepatan, harga, masa aktif).
- `voucher_batches`: Memudahkan manajemen cetak massal.
- `vouchers`: Data individual voucher (kode, password).
- `voucher_activations`: Kapan voucher dipakai, di router mana, MAC address pengakses.

**PPPOE & BILLING DOMAIN**
- `customers`: Data profil pelanggan.
- `customer_services`: Layanan aktif pelanggan (Paket 20Mbps).
- `pppoe_profiles`: Paket spesifik PPPoE.
- `invoices`: Tagihan bulanan.
- `payments`: Catatan pembayaran (bisa tunai atau via Midtrans).
- `service_suspensions`: Log kapan layanan pelanggan diisolir karena telat bayar.

**RADIUS DOMAIN (Terintegrasi dengan Schema Bawaan FreeRADIUS)**
- *Revo Radius menggunakan tabel standar FreeRADIUS (radcheck, radreply, radacct, nas) namun menambahkan tabel mapping:*
- `radius_users_map`: Menyambungkan `customers.id` dengan `username` di `radcheck`.
- `radius_sync_logs`: Menandai sinkronisasi antara billing Revo dan database Radius.

**TUNNEL DOMAIN**
- `wireguard_peers`: Konfigurasi *public key*, *allowed IPs*.
- `wireguard_links`: Relasi antara 2 router yang dihubungkan WireGuard.
- `l2tp_accounts`: Kredensial L2TP/IPSec.

**Alasan Pemisahan**: Modul Hotspot hanya menyentuh tabel hotspot. Saat butuh tagihan, Hotspot memanggil API internal modul Billing yang akan menulis ke `invoices`. Ini mencegah "Tabel Monster" dan _spaghetti code_.

## 7. Entity Relationship Overview
- `users` (1:N) `audit_logs`
- `users` (M:N) `roles`
- `owners` (M:N) `routers` (via `router_owners`)
- `routers` (1:N) `vouchers` (Jika voucher di-assign ke router spesifik)
- `customers` (1:N) `customer_services`
- `customer_services` (1:N) `invoices`
- `invoices` (1:N) `payments`

## 8. Role dan Permission Matrix
| Role | Permissions |
| :--- | :--- |
| **Superadmin** | Semua akses (mengelola admin lain, hapus router, dll). |
| **Owner** | Hanya bisa lihat dashboard, router, dan pendapatan *miliknya sendiri*. |
| **Operator** | `create_voucher`, `read_voucher`, `print_voucher`, `read_router_status`. Tidak bisa hapus. |
| **Finance** | `read_invoice`, `create_payment`, `verify_midtrans`, `view_finance_report`. |
| **Technician**| `create_router`, `reboot_router`, `manage_tunnel`. Tidak bisa lihat uang/pendapatan. |

## 9. Desain State Machine

**Voucher State Machine**
`GENERATED` -> (dicetak) -> `UNPAID` -> (terjual/dibayar) -> `PAID` -> (login pertama) -> `ACTIVE` -> (waktu habis) -> `EXPIRED`.
*(Pendapatan perusahaan hanya diakui saat transisi ke `PAID` atau `ACTIVE` jika prabayar).*

**Subscription (PPPoE/Hotspot Member) State Machine**
`PENDING` -> (didaftarkan) -> `ACTIVE` -> (tagihan tidak dibayar/lewat jatuh tempo) -> `SUSPENDED` -> (tagihan dilunasi) -> `ACTIVE` -> (berhenti berlangganan) -> `TERMINATED`.

**Invoice State Machine**
`DRAFT` -> `UNPAID` -> (dibayar sebagian) -> `PARTIALLY_PAID` -> (lunas) -> `PAID`.
Jika lewat jatuh tempo dari `UNPAID` -> `OVERDUE` (memicu event `CustomerSuspended`).

**Router Sync State Machine**
`PENDING` -> `IN_PROGRESS` -> `SUCCESS` atau `FAILED` -> `RETRYING`.

## 10. Desain Integrasi Mikrotik API
- **Protokol**: TCP port 8728 (API standard) untuk kompatibilitas v6 dan v7.
- **Mekanisme Queue**: Setiap instruksi ke Mikrotik (misal: tambah user PPPoE) dimasukkan ke Laravel Queue (RabbitMQ / Redis / Database Queue).
- **Graceful Failure**: Jika Queue Worker mencoba memanggil API dan gagal (Router Offline), *job* tidak dibuang, melainkan di-*delay* (misal 5 menit) dan masuk fase *Retrying* maksimal 5x. Jika tetap gagal, dikirim notifikasi ke Telegram/Email Admin.

## 11. Desain Integrasi FreeRADIUS
- **Opsi A (Direct Database)**: Karena MariaDB digunakan, Revo Radius akan langsung menulis ke tabel `radcheck` (untuk username/password), `radreply` (untuk limitasi/IP), dan membaca `radacct` (untuk laporan pemakaian data bulanan).
- Keuntungan: Sangat cepat dan tidak membutuhkan modifikasi *daemon* FreeRADIUS. Cukup arahkan config `sql` FreeRADIUS ke database MariaDB Revo Radius.

## 12. Desain Integrasi WireGuard dan L2TP
- **WireGuard**: Revo Radius menghasilkan sepasang kunci (Private/Public) di backend. Private key dikirim secara aman via API ke Router A (sebagai endpoint). Public key dikirim ke Router B (sebagai peer).
- **Sistem Keamanan**: Konfigurasi dikirimkan melalui koneksi API terenkripsi bila memungkinkan, atau melalui Tunnel manajemen.
- Modul ini memastikan port tidak bentrok antar cabang.

## 13. Struktur Folder Proyek (Laravel DDD Pattern)
```
/opt/revo-radius/app/
├── app/
│   ├── Domains/
│   │   ├── Core/ (User, Role, Audit)
│   │   ├── Router/ (Mikrotik API Client, Sync Jobs)
│   │   ├── Hotspot/ (Voucher, Profiles)
│   │   ├── Billing/ (Invoice, Payment, Midtrans Integration)
│   │   └── Radius/ (Radcheck mapping)
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Middleware/
├── config/
├── routes/
│   ├── api.php
├── storage/
│   └── logs/ (Rotated logs)
```

## 14. Struktur Service Linux / Systemd
Aplikasi tidak boleh menimpa service bawaan sistem. Revo Radius akan memiliki 3 *systemd service*:

1. **revo-radius-web.service**: Menjalankan Laravel Octane (Swoole) atau PHP-FPM di port khusus (misal 8080) agar tidak bentrok dengan Apache/Nginx bawaan.
2. **revo-radius-worker.service**: Menjalankan Laravel Queue (`php artisan queue:work`) untuk background jobs (Mikrotik sync).
3. **revo-radius-cron.service**: Timer (pengganti cron) untuk menjalankan scheduler harian (buat tagihan, cek jatuh tempo).

## 15. Contoh Environment Variables (.env)
```env
APP_NAME="Revo Radius"
APP_ENV=production
APP_KEY=base64:...
APP_URL=http://10.10.10.1:8080

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=revo_radius_db
DB_USERNAME=revo_user
DB_PASSWORD=SuperSecretDatabasePassword

# MIKROTIK
MIKROTIK_CONNECT_TIMEOUT=5
MIKROTIK_MAX_RETRY=3

# MIDTRANS PAYMENT
MIDTRANS_SERVER_KEY=SB-Mid-server-...
MIDTRANS_IS_PRODUCTION=false
```

## 16. Daftar Endpoint API (Sebagian)
- `POST /api/v1/auth/login`
- `GET /api/v1/routers`
- `POST /api/v1/routers/{id}/sync`
- `POST /api/v1/vouchers/generate`
- `GET /api/v1/vouchers?status=unpaid`
- `POST /api/v1/billing/invoices/{id}/pay-manual`
- `POST /api/v1/webhook/midtrans`
- `GET /api/v1/tunnels/wireguard`

## 17. Daftar Halaman Frontend (Vue SPA)

**1. Halaman Dashboard**
- **Tujuan**: Menampilkan ringkasan status sistem dan pendapatan.
- **Komponen UI**: 4 Kartu metrik (Total Router, Voucher Aktif, Member PPPoE, Tagihan Jatuh Tempo), Chart Bar Pendapatan Bulanan, Pie Chart Status Router (Online/Offline).
- **Aksi Tombol**: Filter rentang waktu.
- **Alur Backend**: Saat diakses, UI memanggil `GET /api/v1/dashboard/stats`. Backend melakukan query `COUNT` dan `SUM` secara *cache* agar cepat.

**2. Halaman Router Manager**
- **Tujuan**: Mengelola daftar router Mikrotik.
- **Komponen UI**: Tabel (Nama, IP, Status, Versi OS, Owner). Form Input (Nama, IP, Port, Username, Password tersembunyi, Pilihan Owner).
- **Validasi Form**: IP harus format IPv4, Port berupa angka.
- **Aksi Tombol**: `Tambah Router`, `Test Koneksi`, `Hapus`.
- **Alur Backend**: Tombol "Test Koneksi" memanggil `POST /api/v1/routers/{id}/test`. Backend mengirimkan *Ping* dan mencoba login TCP API ke router tersebut lalu mengembalikan `success` atau `error`.

**3. Halaman Voucher Manager**
- **Tujuan**: Memproduksi dan melacak status hotspot voucher.
- **Komponen UI**: Tabel Daftar Batch, Tabel Daftar Voucher. Form Generate (Input: Pilihan Paket, Jumlah Voucher, Pilih Router Spesifik/Global, Status Awal Paid/Unpaid).
- **Validasi Form**: Jumlah voucher tidak boleh > 1000 per request.
- **Aksi Tombol**: `Generate`, `Print PDF`, `Aktifkan (Ubah ke Paid)`.
- **Alur Backend**: Klik "Aktifkan" mengirim state baru ke Backend, Backend mengakui pendapatan, dan Radius menambahkan kredensial user tersebut agar valid login.

**4. Halaman PPPoE / Hotspot Member**
- **Tujuan**: Mengelola pelanggan tetap bulanan.
- **Komponen UI**: Tabel Pelanggan (Nama, Username, Paket, IP Address, Status Berlangganan). Form Input Pelanggan Baru (Data Diri, Pilihan Paket PPPoE, Tenggat Pembayaran).
- **Validasi Form**: Username harus unik, password min 6 karakter.
- **Aksi Tombol**: `Tambah Member`, `Suspend Manual`, `Edit`.
- **Alur Backend**: Menambah member memicu `CustomerService` dan antrean Job ke Router Mikrotik untuk *inject* PPPoE secret atau *inject* ke tabel Radius MariaDB.

**5. Halaman Billing & Invoices**
- **Tujuan**: Menampilkan dan mengeksekusi tagihan.
- **Komponen UI**: Tabel Invoices (Nomor, Nama Pelanggan, Nominal, Tanggal Jatuh Tempo, Status: PAID/UNPAID/OVERDUE). Form Konfirmasi Bayar (Input: Tanggal Bayar, Catatan).
- **Validasi Form**: Tanggal bayar tidak boleh di masa depan.
- **Aksi Tombol**: `Kirim Pengingat WA`, `Tandai Lunas (Manual)`.
- **Alur Backend**: Klik "Tandai Lunas" -> Controller -> PaymentService merubah state `invoices` ke `PAID` -> Memicu Event `InvoicePaid` -> Listener `UnsuspendCustomerJob` dijalankan di belakang layar.

**6. Halaman Radius & Active Sessions**
- **Tujuan**: Memantau siapa yang sedang online via otentikasi Radius.
- **Komponen UI**: Tabel Radius Session (Username, NAS/Router Name, Start Time, Traffic Up/Down).
- **Aksi Tombol**: `Disconnect/Kick User`.
- **Alur Backend**: "Kick User" memanggil API Backend. Backend mengirimkan *Packet PoD (Packet of Disconnect)* atau *CoA (Change of Authorization)* ke NAS Mikrotik untuk memutus sesi secara real-time.

**7. Halaman Tunnel Manager (WireGuard/L2TP)**
- **Tujuan**: Membuat koneksi VPN antar router.
- **Komponen UI**: List Endpoint/Peers. Form Buat WireGuard (Pilih Router A, Pilih Router B).
- **Aksi Tombol**: `Generate Tunnel`.
- **Alur Backend**: Backend *generate* Public/Private key pair, menyimpan ke MariaDB, lalu mengirim instruksi pembuatan antarmuka (interface) WireGuard secara asinkron (Job) ke kedua router secara bersamaan.

**8. Halaman Settings & Audit Log**
- **Tujuan**: Konfigurasi global, manajemen Role/Staff, dan pengawasan.
- **Komponen UI**: Form Konfigurasi (Limitasi API, Midtrans Key). Tabel Audit Log (Waktu, Nama Staf, Aksi, Modul).
- **Aksi Tombol**: `Simpan Pengaturan`.
- **Alur Backend**: Read-only query untuk Audit Log untuk mencegah perubahan data histori. Update ke tabel `app_settings` dibatasi khusus Role `Superadmin`.

## 18. Scheduler / Cron / Jobs yang Dibutuhkan
- `GenerateMonthlyInvoicesJob`: Berjalan setiap tanggal 1 (atau sesuai siklus penagihan pelanggan) untuk menerbitkan invoice.
- `CheckOverdueInvoicesJob`: Berjalan setiap hari jam 00:01 untuk memindahkan status invoice dari `UNPAID` menjadi `OVERDUE` dan men-suspend layanan.
- `RouterHealthCheckJob`: Berjalan setiap 5 menit untuk nge-ping dan test koneksi port API router.
- `RadiusAccountingCleanupJob`: Membersihkan data *stale session* di Radius.

## 19. Strategi Logging, Monitoring, dan Backup
- **Logging**: Menggunakan Monolog, dipisah per modul: `billing.log`, `router-sync.log`. Merotasi log harian (maks 30 hari).
- **Monitoring**: Endpoint `GET /api/health` untuk mengecek koneksi DB, antrean Queue, dan disk space.
- **Backup**: Scheduler otomatis menjalankan `mysqldump` setiap jam 2 pagi, dienkripsi, dan disimpan ke `/var/lib/revo-radius/backups/`.

## 20. Strategi Keamanan
- Hash password menggunakan **Bcrypt** atau **Argon2**.
- API diamankan dengan **Sanctum/JWT** (Bearer Token) dengan *expiration time*.
- **CSRF Protection** otomatis di-handle framework.
- Input validation ketat (Mencegah SQL Injection).
- Kredensial router disimpan di DB dalam format **terenkripsi** menggunakan `APP_KEY` Laravel, sehingga admin database tidak bisa melihat password root Mikrotik dalam bentuk *plaintext*.

## 21. Strategi Migrasi Database
Laravel Migrations (`php artisan migrate`) menangani semua DDL (Data Definition Language).
Setiap modul memiliki folder migrasinya sendiri. Skema tidak akan pernah dibuat manual via phpMyAdmin. Jika ada perubahan struktur, developer membuat *migration file* baru.

## 22. Strategi Testing
- **Unit Testing**: Mengetes logika `VoucherService` atau `BillingService` tanpa menyentuh database nyata.
- **Integration Testing**: Mengetes HTTP endpoint (`/api/v1/routers`) dan memalsukan (Mock) respon Mikrotik API agar tidak perlu router fisik saat *testing pipeline*.

## 23. Roadmap Implementasi
1. **Bulan 1**: Core Setup, RBAC, Database Schema, UI Dashboard dasar.
2. **Bulan 2**: Router Module (API Client), PPPoE Member CRUD.
3. **Bulan 3**: Billing Engine, Invoice Generation, Integrasi Pembayaran (Manual & Midtrans).
4. **Bulan 4**: Hotspot Voucher Module, PDF Generator.
5. **Bulan 5**: Integrasi Database FreeRADIUS, Sinkronisasi Otomatis.
6. **Bulan 6**: Tunnel Management (WireGuard/L2TP), Reporting Lanjutan, Testing Produksi.

---

## 25. Langkah Instalasi Ubuntu 24.04 Bare Metal
Aplikasi didesain diinstall dalam *folder terisolasi*.

```bash
# 1. Update system & install dependencies
sudo apt update && sudo apt install -y mariadb-server php8.3 php8.3-fpm php8.3-cli php8.3-mysql php8.3-xml php8.3-mbstring php8.3-curl unzip curl nginx redis-server

# 2. Setup Database MariaDB (tanpa mengganggu db lain)
sudo mysql -e "CREATE DATABASE revo_radius_db;"
sudo mysql -e "CREATE USER 'revo_user'@'localhost' IDENTIFIED BY 'Rahasia123!';"
sudo mysql -e "GRANT ALL PRIVILEGES ON revo_radius_db.* TO 'revo_user'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"

# 3. Buat direktori aplikasi
sudo mkdir -p /opt/revo-radius/app
sudo mkdir -p /var/log/revo-radius
sudo mkdir -p /var/lib/revo-radius/backups
sudo chown -R www-data:www-data /opt/revo-radius /var/log/revo-radius /var/lib/revo-radius

# 4. Clone repo dan install vendor (Diasumsikan repo sudah ada)
# cd /opt/revo-radius/app
# composer install --optimize-autoloader --no-dev
# cp .env.example .env
# php artisan key:generate
# php artisan migrate --seed
```

## 26. Langkah Menjalankan Service Tanpa Bentrok
Untuk menghindari konflik dengan Nginx/Apache pengguna lain di server yang sama, Revo Radius akan menggunakan *port spesifik* dan *reverse proxy lokal*, serta menggunakan systemd.

**Membuat Systemd untuk Queue Worker**
Buat file `/etc/systemd/system/revo-radius-worker.service`:
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

**Konfigurasi Nginx Spesifik (Opsional jika ingin Web Server)**
Buat file `/etc/nginx/sites-available/revo-radius`:
```nginx
server {
    listen 8080; # Gunakan port 8080 agar tidak bentrok dengan web standar di 80
    server_name _;
    root /opt/revo-radius/app/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```
Aktifkan service:
```bash
sudo ln -s /etc/nginx/sites-available/revo-radius /etc/nginx/sites-enabled/
sudo systemctl restart nginx
sudo systemctl enable revo-radius-worker
sudo systemctl start revo-radius-worker
```

## 27. Daftar Risiko Teknis dan Mitigasinya

1. **Risiko**: Router klien mati mendadak saat sistem sedang melakukan sinkronisasi data tagihan (suspend user).
   **Mitigasi**: Implementasi arsitektur berbasis antrean (Job Queue). Jika target gagal dihubungi (timeout), job tidak ditandai selesai, melainkan di-*delay* dan dicoba lagi nanti saat router online.

2. **Risiko**: Database FreeRADIUS terlalu membebani MariaDB saat *Concurrent Users* sangat tinggi (10,000+ session otentikasi per detik).
   **Mitigasi**: Pisahkan tabel FreeRADIUS (`radacct`) ke server/disk database terpisah jika skala membesar, atau batasi *interval accounting update* dari Mikrotik menjadi 10-15 menit alih-alih 1 menit.

3. **Risiko**: Admin database bisa melihat password root semua Mikrotik cabang.
   **Mitigasi**: Password Mikrotik yang disimpan di database `routers` dienkripsi secara dua arah (AES-256) menggunakan *Laravel Encryption*. Tanpa `APP_KEY` yang hanya ada di server aplikasi, data di DB hanyalah teks acak.

4. **Risiko**: Serangan eksternal ke port API Revo Radius.
   **Mitigasi**: Penerapan rate-limiting (maksimal 60 request per menit per IP) di layer API, dan menyarankan instalasi aplikasi di belakang VPN (seperti WireGuard) sehingga UI/API tidak terbuka ke public internet.

---
*Dokumen Arsitektur ini dirancang secara khusus untuk REVO RADIUS demi menjamin modularitas dan stabilitas sistem enterprise.*
