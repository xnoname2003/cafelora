---
# TUGAS BESAR: SISTEM INFORMASI CAFELORA 

---
# 📃 Identitas Kelompok

- **Kelompok**         : Kelompok 1
- **Anggota Kelompok** :
  1. Aditya Nur Lintang (4523210003)
  2. Alip Khoeril Akbar (4523210009)
  3. Muhammad Fauzan (4523210073)
  4. Revalina Adelia (4523210091)
  5. Chaerul Cahyadi (4523210120)
- **Mata Kuliah**      : Pemrograman Berbasis Web (A)
- **Dosen Pengampu**   : Adi Wahyu Pribadi, S.Si., M.Kom.
- **Topik Proyek**     : Cafe

---
## Daftar Isi

1. [Sistem Informasi Cafelora](https://github.com/xnoname2003/cafelora/blob/main/docs/2_FITUR.md)
2. [Tujuan Proyek](https://github.com/xnoname2003/cafelora/blob/main/docs/1_PENDAHULUAN.md)
3. [Aktor yang Terlibat](https://github.com/xnoname2003/cafelora/blob/main/docs/2_FITUR.md)
4. [Fitur Sistem Informasi cafelora](https://github.com/xnoname2003/cafelora/blob/main/docs/2_FITUR.md)
5. [Pembagian Jobdesk](https://github.com/xnoname2003/cafelora/blob/main/docs/2_FITUR.md)
6. [Entity Relationship Diagram (ERD)](https://github.com/xnoname2003/cafelora/blob/main/docs/3_ERD.md)
7. [Agenda Pekerjaan & Progres Pengembangan](https://github.com/xnoname2003/cafelora/blob/main/docs/4_AGENDA.md)
8. [Teknologi dan Persyaratan Sistem](https://github.com/xnoname2003/cafelora/blob/main/docs/5_INSTALASI.md)
9. [Langkah Instalasi](https://github.com/xnoname2003/cafelora/blob/main/docs/5_INSTALASI.md)
10. [Integrasi Payment Gateway (Midtrans)](https://github.com/xnoname2003/cafelora/blob/main/docs/6_MIDTRANS.md)
11. [Implementasi Deployment](https://github.com/xnoname2003/cafelora/blob/main/docs/7_DEPLOYMENT.md)
12. [Hasil Tampilan](https://github.com/xnoname2003/cafelora/blob/main/docs/8_TAMPILAN.md)

---
## Sistem Informasi Cafelora

Cafelora merupakan sistem informasi berbasis web yang dirancang untuk mendukung operasional kafe modern secara terintegrasi, mencakup penyajian menu, pemesanan, transaksi, dan pengelolaan data penjualan. Sistem ini membantu kafe menyajikan informasi menu secara jelas, memproses pesanan dengan alur yang terstruktur, serta mempercepat proses transaksi agar lebih efisien.

Cafelora menyediakan antarmuka pelanggan untuk memudahkan pemilihan menu, serta sistem *Point of Sale* (POS) bagi kasir yang dirancang sederhana dan sistematis guna meminimalkan kesalahan pemesanan dan pembayaran. Sebagai aplikasi berbasis website, Cafelora membantu meningkatkan efisiensi kerja staf dan memudahkan pengelola kafe dalam memantau aktivitas operasional secara menyeluruh.

---
## Tujuan Proyek

Tujuan dari pembuatan sistem informasi Cafelora adalah sebagai berikut:
1. Mendukung operasional kafe melalui sistem informasi yang terintegrasi.
2. Mempermudah proses pemesanan dan transaksi bagi kasir dan staf.
3. Menyediakan informasi menu yang jelas untuk membantu pelanggan.
4. Mendukung pembayaran tunai dan non-tunai melalui *payment gateway*.
5. Membantu pengelolaan data menu, stok, dan laporan penjualan.

---
## Aktor yang Terlibat

### 1. Admin

Mengelola sistem secara keseluruhan, data menu, pengguna, transaksi, dan laporan.

### 2. Kasir/Staf

Menangani transaksi melalui sistem POS, memproses pembayaran, dan mencetak struk.

### 3. Pelanggan

Mengakses *frontend* untuk melihat daftar menu dan detail produk.

---
## Fitur Sistem Informasi Cafelora

### Fitur Admin

1. CRUD menu, kategori, varian, dan topping.
2. Upload dan manajemen gambar menu.
3. Sistem manajemen stok otomatis.
4. *Workflow* status transaksi dari awal hingga selesai.
5. Pengelolaan user.
6. Dashboard pendapatan serta grafik penjualan harian dan bulanan.
7. Export laporan transaksi ke format PDF/Excel.

### Fitur Kasir/Staf (POS)

1. Input pesanan menggunakan komponen Repeater.
2. Penambahan topping dan modifier secara dinamis.
3. Perhitungan total harga secara otomatis.
4. Fitur input uang bayar dan kalkulasi kembalian.
5. Cetak struk transaksi dalam bentuk PDF/HTML.
6. Akses halaman yang dibatasi sesuai role.

### Fitur Pelanggan (Frontend)

1. Melihat daftar menu makanan dan minuman.
2. Filter menu berdasarkan nama, harga, dan kategori.
3. Tampilan menu berbentuk kartu untuk memudahkan pemilihan.
4. Halaman detail menu untuk memberikan informasi lebih lengkap.

### Fitur Tambahan (Opsional)

1. Integrasi *payment gateway* (Midtrans atau Xendit)
2. Filter laporan berdasarkan tanggal dan status transaksi.
3. Fitur chart penjualan untuk monitoring transaksi 7 hari terakhir.

---
## Pembagian Jobdesk

### 1. Chaerul Cahyadi (Ketua) (4523210120)

1. Menyusun struktur proyek secara keseluruhan.
2. Mengintegrasikan fitur transaksi dan perhitungan harga.
3. Menangani dashboard serta mengatur *workflow* status transaksi.
4. Melakukan final review terhadap seluruh fitur.
5. Mengintegrasikan *payment gateway* dan melakukan deployment.

### 2. Arya Wicaksana Putra (4520210092)

1. Mendesain ERD serta relasi antar tabel *database*.
2. Membuat CRUD data master seperti kategori dan topping.
3. Mengimplementasikan validasi *form* dan upload gambar menu.

**Note:** Tidak mengerjakan bagian yang diberikan dan tidak berkontribusi pada proyek, sehingga jobdesk dialihkan.

### 3. Aditya Nur Lintang (4523210003)

1. Melakukan setup awal Laravel dan Filament.
2. Mengimplementasikan role, *Policy*, dan *access control*.
3. Mengelola user Admin dan Staff.

### 4. Alip Khoeril Akbar (4523210009)

1. Mendesain UI untuk tampilan menu pelanggan (*frontend*).
2. Mengatur layout grid pada halaman menu.
3. Membuat komponen kartu menu dan fitur filter.

### 5. Muhammad Fauzan (4523210073)

1. Membuat fitur POS kasir.
2. Mengimplementasikan Repeater item transaksi dan kalkulasi otomatis.
3. Membuat fitur cetak struk dalam format HTML-PDF.

### 6. Revalina Adelia (4523210091)

1. Membuat fitur laporan dan export PDF/Excel.
2. Menyusun dokumentasi proyek dan README.

---
## Entity Relationship Diagram (ERD)

Struktur data Cafelora dirancang secara terintegrasi dan digambarkan melalui *Entity Relationship Diagram* (ERD).
1. ERD Cafelora menggambarkan struktur basis data yang mendukung pengelolaan **user dan hak akses, menu, transaksi**, serta **pembayaran** dalam satu sistem terintegrasi.
2. Sistem user menerapkan **role dan permission** untuk membedakan akses antara admin, staf/kasir, dan pengguna lainnya.
3. Menu dikelompokkan ke dalam kategori dan dapat memiliki **varian** serta **topping**, yang memengaruhi harga akhir pesanan.
4. Transaksi dicatat melalui sistem POS, dimana satu transaksi dapat memiliki banyak item pesanan, termasuk varian dan topping tambahan.
5. Proses pembayaran dikelola secara terpisah melalui entitas *payment* yang terhubung ke transaksi, sehingga status pembayaran dan metode pembayaran dapat dipantau dengan jelas.
6. Relasi antar entitas menggunakan kombinasi **One-to-Many** dan **Many-to-Many** untuk mencerminkan alur operasional kafe secara fleksibel dan realistis.

---
## Agenda Pekerjaan & Progres Pengembangan

Berikut ringkasan agenda pekerjaan dan progres pengembangan sistem informasi Cafelora selama proyek berlangsung.
1. Pengembangan sistem informasi Cafelora dilakukan secara kolaboratif melalui GitHub mulai dari **26 November 2025** hingga **29 Desember 2025**.
2. Proses pengembangan mencakup tahapan inisialisasi sistem, perancangan basis data, pengembangan *frontend* dan *backend*, hingga implementasi fitur inti seperti **POS kasir, manajemen menu**, dan **payment gateway Midtrans**.
3. Pekerjaan dilakukan secara bertahap dan terstruktur, dengan fokus pada integrasi sistem, stabilitas website, serta penyempurnaan fitur melalui proses *bug fixing*.
4. Progres pengembangan dievaluasi melalui **dua kali presentasi tugas besar** untuk memastikan kesesuaian fitur dengan kebutuhan sistem.
5. Seluruh fitur yang dikembangkan telah melalui uji fungsionalitas dan finalisasi dokumentasi, sehingga sistem dinyatakan siap digunakan.

---
## Teknologi dan Persyaratan Sistem

Pengembangan Cafelora memanfaatkan kombinasi teknologi *backend*, *frontend*, dan basis data yang disesuaikan dengan kebutuhan sistem informasi kafe modern serta lingkungan pengembangan web saat ini.
1. Cafelora dikembangkan menggunakan **PHP (Laravel)** sebagai *backend*, **Vite & NPM** untuk pengelolaan *frontend*, serta **MySQL/PostgreSQL** sebagai basis data.
2. Pengelolaan dependensi dilakukan melalui **Composer** (PHP) dan **NPM** (JavaScript), dengan **Filament** sebagai admin panel untuk mempercepat pengembangan fitur manajemen data.
3. Sistem membutuhkan lingkungan yang mendukung **PHP >= 8.2, Node.js, database relasional**, serta **web server** seperti Apache atau Nginx (atau *built-in server* Laravel untuk pengembangan lokal).

---
## Langkah Instalasi

Proses instalasi Cafelora dirancang agar dapat dilakukan secara bertahap dan terstruktur untuk memastikan website berjalan sesuai konfigurasi.
1. Melakukan *clone* atau mengunduh repository Cafelora dari GitHub.
2. Menginstal dependensi *backend* menggunakan Composer dan dependensi *frontend* menggunakan NPM.
3. Menyiapkan file konfigurasi environment (`.env`) serta melakukan generate *application key*.
4. Mengatur koneksi *database* agar website dapat terhubung dengan sistem basis data.
5. Menjalankan proses migrasi dan *seeding database* untuk membentuk struktur data awal.
6. Melakukan *buil aset frontend* sesuai kebutuhan pengembangan atau production.
7. Menjalankan website melalui server yang tersedia hingga sistem dapat diakses melalui browser.

---
## Integrasi Payment Gateway (Midtrans)

Sistem informasi Cafelora terintegrasi dengan Midtrans sebagai *payment gateway* untuk mendukung pembayaran non-tunai seperti QRIS, e-wallet, transfer bank, serta kartu debit/kredit, sehingga proses pembayaran dapat berjalan fleksibel, aman, dan terpantau secara *real-time*.

Proses pembayaran dimulai dari pembuatan transaksi melalui siste POS, dimana sistem menghasilkan order dan mengirimkan permintaan pembayaran ke Midtrans. Pelanggan memilih metode pembayaran melalui antarmuka Midtrans, sementara status transaksi diperbarui secara otomatis melalui notifikasi webhook, sehingga data transaksi, stok, dan penjualan tetap sinkron tanpa input manual.

Secara teknis, integrasi dilakukan melalui API Midtrans pada sisi *backend*, mencakup pembuatan dan validasi transaksi, penyimpanan token pembayaran, serta pemetaan status pembayaran. Mekanisme keamanan diterapkan melalui penggunaan *server key* dan validasi signature untuk menjaga konsistensi data. Seluruh transaksi tercatat dalam sistem dan dapat dipantau serta diekspor melalui laporan penjualan.

---
## Implementasi Deployment

Proses deployment sistem informasi Cafelora dilakukan melalui beberapa tahapan utama yang dirangkum sebagai berikut.
1. Aplikasi Cafelora dideploy pada layanan hosting yang mendukung akses SSH untuk memudahkan pengelolaan server dan proses deployment.
2. Proses deployment mencakup konfigurasi akses **SSH** dan **database** pada server hosting untuk mendukung koneksi website ke lingkungan produksi.
3. Repository proyek di-*clone* langsung dari **GitHub** ke server menggunakan mekanisme *deploy key* dan *personal access token* untuk keamanan akses.
4. Setup website dilakukan di server, meliputi instalasi dependensi, konfigurasi environment, pembuatan *application key*, pengaturan storage, serta migrasi dan *seeding database*.
5. Website yang telah dideploy dapat diakses melalui domain publik, dengan antarmuka terpisah untuk **pelanggan** serta **admin dan staf**.
6. Untuk hasil akhir tampilan bisa dilihat pada link berikut.
   1) Tampilan [Pelanggan](https://cafelora.my.id/)
   2) Tampilan [Admin dan Staff](https://cafelora.my.id/admin/)

---
## Hasil Tampilan

Hasil tampilan website Cafelora, mulai dari tahap pengembangan hingga versi final, dapat dilihat melalui dokumentasi tampilan pada link berikut:

[Hasil Tampilan](https://github.com/xnoname2003/cafelora/blob/main/docs/8_TAMPILAN.md)

---