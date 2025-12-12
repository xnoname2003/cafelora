---
# Teknologi yang Digunakan

Aplikasi ini dibangun menggunakan beberapa teknologi utama yang mendukung backend, forntend, dan database, sehingga aplikasi dapat berjalan dengan baik dan terstruktur. Berikut teknologi yang digunakan:

| Teknologi          | Versi  | Keterangan                                                                      | 
|--------------------|--------|---------------------------------------------------------------------------------|
| PHP                | ^8.2   | Bahasa pemrograman server-side yang menjalankan logika aplikasi.                |
| Laravel Framework  | ^12.0  | Framework PHP berbasis MVC untuk mengatur routing, controller, model, dan view. | 
| Composer           | Latest | Dependency manager untuk mengelola library dan package PHP.                     | 
| NPM                | Latest | Package manager untuk mengelola library dan modul JavaScript.                   |
| Vite               | Latest | Build tool untuk mengompilasi dan mengelola file frontend.                      | 
| MySQL/PostgreSQL   | Latest | Database relasional untuk menyimpan semua data aplikasi.                        | 
| Filament           | ^3.3   | Admin panel Laravel untuk membuat CRUD cepat, form, tabel, dan manajemen data.  |

---
# Persyaratan Sistem

Sebelum menjalankan aplikasi, pastikan perangkat sudah memenuhi persyaratan berikut:

1. *PHP >= 8.2*            : Versi PHP yang kompatibel dengan Laravel 12.
2. *Composer*              : Untuk mengelola dependency PHP.
3. *Node.js & NPM*         : Untuk menjalankan script frontend dan build assets.
4. *MySQL atau PostgreSQL* : Database untuk menyimpan data aplikasi.
5. *Web Server*            : Seperti Apache atau Nginx, atau bisa menggunakan Laravel built-in server.

Untuk pengguna Windows, disarankan menggunakan salah satu dari:
1. *Laragon* : Sudah termasuk PHP, MySQL, dan Apache, memudahkan setup lingkungan development.
2. *XAMPP*   : Paket server populer untuk PHP dan MySQL.
3. *Herd*    : Alternatif ringan untuk development lokal.

---
# Langkah Instalasi

## 1. Clone atau Download Repository

```bash
# Clone repository (jika menggunakan git)
https://github.com/xnoname2003/cafelora.git
cd cafelora
```

Langkah ini digunakan untuk mengambil kode sumber aplikasi dari repository Git. Jika menggunakan Git, perintah git clone akan menyalin seluruh project ke komputer kita, kemudian cd cafelora masuk ke folder proyek agar siap untuk langkah selanjutnya.

## 2. Install Dependencies PHP

```bash
composer install
```

Perintah ini digunakan untuk menginstal *library dan package PHP* yang dibutuhkan oleh Laravel. Semua dependensi sudah didefinisikan di file composer.json, sehingga aplikasi bisa berjalan dengan baik.

## 3. Install Dependencies JavaScript

```bash
npm install
```

Laravel menggunakan JavaScript untuk frontend (misal interaksi UI atau build assets). Perintah ini akan menginstal semua package JavaScript yang diperlukan sesuai definisi di package.json.

## 4. Konfigurasi Environment

```bash
# Copy file .env.example menjadi .env
copy .env.example .env

# Generate application key
php artisan key:generate
```

1. copy .env.example .env   : Membuat salinan file konfigurasi environment agar bisa diubah sesuai pengaturan lokal.
2. php artisan key:generate : Membuat *aplication key* yang digunakan Laravel untuk keamanan, misalnya enkripsi session dan data sensitif.

## 5. Konfigurasi Database

Edit file .env untuk menyesuaikan pengaturan database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cafelora
DB_USERNAME=root
DB_PASSWORD=
```

Di sini kita menentukan tipe database (MySQL), alamat server database, nama database, username, dan password. Ini agar Laravel bisa terhubung ke database lokal.

## 6. Jalankan Migration & Seeder

```bash
# Membuat tabel-tabel di database
php artisan migrate

# (Optional) Generate data dummy untuk testing
php artisan db:seed
```

1. php artisan migrate : Membuat semua *tabel di database* sesuai file migration.
2. php artisan db:seed : Opsional, untuk mengisi database dengan *data dummy* agar bisa langsung dicoba tanpa menambahkan data manual.

## 7. Build Assets Frontend

```bash
# Untuk development (dengan hot reload)
npm run dev

# Untuk production
npm run build
```

1. npm run dev   : Membangun aset frontend untuk *development*, termasuk hot reload agar perubahan langsung terlihat di browser.
2. npm run build : Membangun aset frontend untuk *production*, biasanya lebih optimal dan sudah terminify.

## 8. Jalankan Aplikasi

```bash
# Menggunakan Laravel built-in server
php artisan serve
```

Perintah ini menjalankan *Laravel built-in server* sehingga aplikasi bisa diakses melalui browser. Default URL: http://127.0.0.1:8000.

---
