---
# INSTALASI

---
## Teknologi yang Digunakan

Sistem informasi Cafelora dibangun menggunakan kombinasi teknologi *backend*, *frontend*, dan *database* yang saling terintegrasi untuk mendukung proses operasional kafe secara optimal. Pemilihan teknologi ini didasarkan pada kebutuhan pengembangan aplikasi yang terstruktur, mudah dikembangkan, serta sesuai dengan praktik pembuatan sistem informasi web modern.

| Teknologi         | Versi  | Keterangan                                                                                                                              | 
|-------------------|--------|-----------------------------------------------------------------------------------------------------------------------------------------|
| PHP               | ^8.2   | Bahasa pemrograman *server-side* yang digunakan untuk menangani logika bisnis, proses transaksi, dan pengelolaan data sistem informasi.           |
| Laravel Framework | ^12.0  | *Framework* PHP berbasis MVC yang digunakan untuk mengatur alur sistem informasi, mulai dari routing, controller, model, hingga view secara terstruktur. | 
| Composer          | Latest | *Dependency* manager PHP yang digunakan untuk mengelola library dan package yang dibutuhkan oleh Laravel.                                 | 
| NPM               | Latest | Package manager JavaScript yang digunakan untuk mengelola *dependency frontend*.                                                          |
| Vite              | Latest | *Build tool frontend* yang digunakan untuk mengelola dan mengompilasi aset *frontend* agar lebih cepat dan optimal.                         | 
| MySQL/PostgreSQL  | Latest | *Database* relasional yang digunakan untuk menyimpan data sistem informasi seperti menu, transaksi, pengguna, dan laporan penjualan.              | 
| Filament          | ^3.3   | Admin panel Laravel yang digunakan untuk mempercepat pembuatan fitur CRUD, *form*, tabel, serta manajemen data pada sisi admin dan kasir. |

---
## Persyaratan Sistem

Sebelum menjalankan sistem informasi Cafelora, perangkat yang digunakan harus memenuhi beberapa persyaratan sistem agar website dapat berjalan dengan baik dan stabil.

### 1. PHP >= 8.2

Digunakan sebagai *runtime* utama aplikasi Laravel. Versi ini dipilih karena kompatibel dengan Laravel 12 dan mendukung fitur bahasa terbaru.

### 2. Composer

Digunakan untuk mengelola *dependency* PHP yang dibutuhkan oleh *framework* Laravel dan library pendukung lainnya.

### 3. Node.js & NPM     

Digunakan untuk menjalankan proses instalasi *dependency frontend* serta membangun aset *frontend* menggunakan Vite.

### 4. MySQL atau PostgreSQL

Digunakan sebagai sistem manajemen basis data untuk menyimpan seluruh data website.

### 5. Web Server           

Sistem informasi dapat dijalankan menggunakan web server seperti Apache atau Nginx, maupun menggunakan Laravel *built-in server* untuk kebutuhan development.

Untuk pengguna sistem operasi Windows, disarankan menggunakan salah satu tools berikut:
1. **Laragon**, karena menyediakan PHP, MySQL, dan Apache dalam satu paket sehingga memudahkan proses setup.
2. **XAMPP**, sebagai alternatif server lokal yang umum digunakan.
3. **Herd**, sebagai alternatif yang lebih ringan untuk pengembangan Laravel secara lokal.

---
## Langkah Instalasi

### 1. Clone atau Download Repository

```bash
# Clone repository (jika menggunakan git)
https://github.com/xnoname2003/cafelora.git
cd cafelora
```

Langkah ini bertujuan untuk memperoleh kode sumber sistem informasi Cafelora. Repository dapat di-clone menggunakan Git atau diunduh secara manual. Setelah proses selesai, pengguna masuk ke direktori proyek untuk melanjutkan ke tahap instalasi berikutnya.

### 2. Install Dependencies PHP

```bash
composer install
```

Perintah ini digunakan untuk menginstal seluruh *dependency* PHP yang dibutuhkan oleh aplikasi Laravel. *Dependency* tersebut didefinisikan dalam file `composer.json` dan diperlukan agar website dapat berjalan sesuai dengan konfigurasi yang telah ditentukan.

### 3. Install Dependencies JavaScript

```bash
npm install
```

Perintah ini digunakan untuk menginstal seluruh *dependency* JavaScript yang dibutuhkan untuk pengelolaan *frontend* website. *Dependency* ini mencakup library pendukung antarmuka dan proses *build* aset *frontend* menggunakan Vite.

### 4. Konfigurasi Environment

```bash
# Copy file .env.example menjadi .env
copy .env.example .env

# Generate application key
php artisan key:generate
```

Langkah ini dilakukan untuk menyiapkan file konfigurasi environment website:
1. File `.env` digunakan untuk menyimpan konfigurasi penting seperti koneksi *database* dan pengaturan website.
2. Perintah `php artisan key:generate` digunakan untuk membuat *application key* yang berfungsi menjaga keamanan data, seperti enkripsi *session* dan informasi sensitif lainnya.

### 5. Konfigurasi Database

Pengguna perlu menyesuaikan konfigurasi *database* pada file `.env` agar website dapat terhubung dengan *database* lokal.

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cafelora
DB_USERNAME=root
DB_PASSWORD=
```

Pengaturan ini meliputi jenis *database*, alamat server, port, nama *database*, serta kredensial yang digunakan. Pastikan *database* dengan nama yang sesuai sudah dibuat sebelumnya.

### 6. Jalankan Migration & Seeder

```bash
# Membuat tabel-tabel di database
php artisan migrate

# (Optional) Generate data dummy untuk testing
php artisan db:seed
```

1. `php artisan migrate` digunakan untuk membuat seluruh tabel *database* berdasarkan file migration.
2. `php artisan db:seed` bersifat opsional dan digunakan untuk mengisi *database* dengan data awal (dummy) agar website dapat langsung diuji tanpa memasukkan data secara manual.

### 7. Build Assets Frontend

```bash
# Untuk development (dengan hot reload)
npm run dev

# Untuk production
npm run build
```

1. `npm run dev` digunakan pada tahap pengembangan karena mendukung *hot reload* sehingga perubahan kode *frontend* dapat langsung terlihat.
2. `npm run build` digunakan untuk membangun aset *frontend* versi production yang lebih optimal.

### 8. Jalankan Aplikasi

```bash
# Menggunakan Laravel built-in server
php artisan serve
```

Perintah ini digunakan untuk menjalankan *Laravel built-in server*. Setelah server berjalan, website dapat diakses melalui browser pada alamat:

`http://127.0.0.1:8000`

---
