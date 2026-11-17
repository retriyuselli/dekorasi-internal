# ERP Dekorasi

Aplikasi internal satu perusahaan untuk fondasi ERP dekorasi. Tahap 1 mencakup autentikasi, peran, master data, dashboard, dan log aktivitas. Modul penjualan, proyek, inventaris, dan keuangan belum dikerjakan.

## Yang dibutuhkan

- PHP 8.2 atau lebih baru, plus ekstensi `pdo_mysql`, `mbstring`, `tokenizer`, `xml`, `curl`, `zip`, dan `fileinfo`
- Composer 2
- Node.js dan npm
- MySQL 8, atau MariaDB yang kompatibel, di `127.0.0.1:3306`

XAMPP di mesin ini memakai MariaDB 10.4. Nyalakan MySQL dari XAMPP Control Panel. Perintah `xampp startmysql` meminta hak root, jadi tidak dijalankan otomatis.

Sesi dan cache memakai file supaya halaman depan tetap terbuka saat database sedang dimatikan. Data aplikasi tetap di MySQL.

## Menyiapkan aplikasi

```bash
cd /Applications/XAMPP/xamppfiles/htdocs/application/dekorasi
composer install
cp .env.example .env
php artisan key:generate
```

Isi database di `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dekorasi_erp
DB_USERNAME=root
DB_PASSWORD=
```

Buat database `dekorasi_erp`, lalu migrasi, seed, dan tautan file:

```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
npm install
composer run dev
```

`composer run dev` menjalankan server, antrean, log, dan Vite sekaligus. Admin ada di `http://127.0.0.1:8000/admin`.

Akun awal dari seeder:

- Email: `admin@dekorasi.local`
- Kata sandi: nilai `ADMIN_PASSWORD` di `.env` (bawaan lokal: `password`)

Ganti kata sandi itu sebelum aplikasi dipakai di luar mesin lokal. Seeder tidak menimpa kata sandi jika akun sudah ada, dan tidak menggandakan peran atau data referensi saat dijalankan ulang.

## Peran

Super Admin, Director, Sales, Project Manager, Warehouse, Finance, dan Staff. Izin diperiksa di server lewat Filament Shield dan Policy. Pengguna tanpa izin mendapat HTTP 403. Akun dengan `is_active` mati tidak bisa masuk.

## Pengujian

```bash
php artisan test
```

Pengujian memakai SQLite di memori dan tidak membutuhkan MySQL yang sedang berjalan.
