---
# FITUR CAFELORA

---
## Deskripsi Cafelora

Cafelora merupakan sistem yang dirancang untuk mendukung operasional kafe modern melalui sistem pemesanan, transaksi, dan pengelolaan data yang terintegrasi. Sistem ini dibuat untuk membantu kafe dalam menyajikan informasi menu secara jelas, memproses pesanan dengan lebih terstruktur, serta mempercepat alur transaksi secara efisien. 

Dalam penerapannya, Cafelora menyediakan antarmuka pelanggan yang informatif untuk memudahkan pemilihan menu, serta antarmuka kasir yang sederhana namun sistematis guna meminimalkan kesalahan dalam proses pemesanan dan pembayaran. Pendekatan digital yang diterapkan memungkinkan alur pelayanan kafe menjadi lebih tertata, meningkatkan efisiensi kerja staf, serta membantu pengelola dalam memantau dan mengelola aktivitas operasional secara lebih efektif.

Sebagai solusi berbasis website, Cafelora berperan dalam mendukung peningkatan kualitas layanan dan kelancaran operasional kafe secara keseluruhan melalui sistem yang praktis, terstruktur, dan mudah digunakan.

---
## Aktor yang Terlibat dalam Sistem

Dalam implementasi sistem informasi Cafelora, sistem melibatkan beberapa aktor yang memiliki peran dan hak akses berbeda sesuai dengan kebutuhan operasional kafe. Pembagian aktor ini bertujuan untuk memastikan setiap proses berjalan secara terstruktur serta menghindari penyalahgunaan akses terhadap fitur tertentu.

### 1. Admin

Admin berperan sebagai pengelola utama sistem. Admin memiliki hak akses penuh terhadap fitur manajemen data, termasuk pengelolaan menu, kategori, varian, topping, data pengguna, serta pemantauan laporan penjualan. Selain itu, admin juga bertanggung jawab dalam mengatur alur status transaksi dan memastikan sistem berjalan sesuai kebutuhan operasional kafe.

### 2. Kasir/Staf

Kasir atau staf bertugas menangani proses transaksi penjualan secara langsung. Aktor ini memiliki akses ke sistem *Point of Sale (POS)* untuk mencatat pesanan pelanggan, melakukan perhitungan total harga secara otomatis, menerima pembayaran, serta mencetak struk transaksi. Akses kasir dibatasi hanya pada fitur yang berkaitan dengan transaksi agar proses operasional tetap terkontrol.

### 3. Pelanggan

Pelanggan merupakan pengguna sistem pada sisi *frontend*. Pelanggan dapat mengakses sistem untuk melihat daftar menu, melakukan pencarian dan penyaringan menu berdasarkan (nama, harga, dan kategori), serta melihat detail informasi setiap menu. Peran pelanggan difokuskan pada kemudahan dalam memperoleh informasi menu tanpa memiliki akses ke proses pengelolaan data atau transaksi internal.

---
## Functional Requirement Sistem Informasi Cafelora

*Functional requirement* berikut menjelaskan kemampuan utama yang harus dimiliki oleh sistem informasi Cafelora agar dapat mendukung operasional kafe secara optimal sesuai dengan peran masing-masing pengguna.
1. Sistem dapat mengelola data menu kafe yang meliputi penambahan, perubahan, dan penghapusan menu, kategori, varian, serta topping oleh admin.
2. Sistem dapat menyimpan dan menampilkan gambar menu untuk setiap item agar informasi menu dapat disajikan secara visual dan informatif kepada pelanggan.
3. Sistem dapat mencatat dan memperbarui stok menu secara otomatis berdasarkan transaksi yang terjadi.
4. Sistem dapat mengelola data pengguna dengan peran berbeda, seperti admin dan kasir, serta membatasi akses fitur sesuai dengan peran masing-masing.
5. Sistem dapat menerima dan memproses pesanan yang terdiri dari beberapa item menu, termasuk penambahan topping atau varian sesuai pilihan pengguna.
6. Sistem dapat menghitung total harga pesanan secara otomatis berdasarkan menu, varian, dan topping yang dipilih.
7. Sistem dapat memproses pembayaran tunai dengan fitur input uang bayar dan perhitungan kembalian secara otomatis.
8. Sistem dapat mendukung pembayaran non-tunai melalui integrasi *payment gateway* sebagai alternatif metode pembayaran.
9. Sistem dapat mengelola status transaksi mulai dari pemesanan hingga transaksi selesai sesuai dengan alur yang telah ditentukan.
10. Sistem dapat menghasilkan struk transaksi dalam bentuk digital yang dapat dicetak atau disimpan dalam format HTML atau PDF.
11. Sistem dapat menampilkan daftar menu makanan dan minuman kepada pelanggan secara jelas dan terstruktur melalui antarmuka *frontend*.
12. Sistem dapat menyediakan fitur pencarian atau filter menu berdasarkan nama, harga, dan kategori untuk memudahkan pelanggan dalam memilih menu.
13. Sistem dapat menghasilkan laporan transaksi penjualan berdasarkan periode tertentu untuk membantu evaluasi usaha.
14. Sistem dapat menampilkan grafik atau chart penjualan sebagai ringkasan performa transaksi harian dan bulanan.
15. Sistem dapat mengekspor laporan transaksi ke dalam format PDF atau Excel untuk kebutuhan dokumentasi dan analisis lanjutan.

---
## Fitur Sistem Informasi Cafelora

### Fitur Admin

#### 1. CRUD Menu, Kategori, Varian, dan Topping

Fitur ini memungkinkan admin untuk menambah, mengubah, menghapus, dan melihat data menu berdasarkan kategori, varian, dan topping yang tersedia. Dengan adanya fitur ini, admin dapat memastikan data menu selalu terbarui dan sesuai dengan kebutuhan operasional kafe.

#### 2. Upload dan Manajemen Gambar Menu

Admin dapat mengunggah dan mengelola gambar menu yang ditampilkan pada sistem. Gambar menu berfungsi sebagai informasi visual bagi pelanggan agar lebih mudah mengenali produk yang ditawarkan.

#### 3. Sistem Manajemen Stok Otomatis

Sistem secara otomatis mengelola stok berdasarkan transaksi yang terjadi. Fitur ini membantu admin dalam memantau ketersediaan produk dan mengurangi risiko kesalahan pencatatan stok.

#### 4. Workflow Status Transaksi dari Awal hingga Selesai

Fitur ini mengatur alur status transaksi mulai dari pesanan dibuat hingga transaksi selesai. Dengan *workflow* ini, admin dapat memantau proses transaksi secara lebih terstruktur.

#### 5. Pengelolaan User

Admin memiliki akses untuk mengelola data pengguna sistem, termasuk menambah, mengubah, atau menghapus akun kasir dan staf sesuai dengan kebutuhan operasional.

#### 6. Dashboard Pendapatan serta Grafik Penjualan Harian dan Bulanan

Dashboard menyajikan ringkasan pendapatan dan grafik penjualan dalam periode tertentu. Fitur ini membantu admin dalam memantau performa usaha secara cepat dan informatif.

#### 7. Export Laporan Transaksi ke Format PDF/Excel

Admin dapat mengekspor laporan transaksi ke dalam format PDF atau Excel untuk keperluan dokumentasi, evaluasi, maupun pelaporan.

---
### Fitur Kasir/Staf (POS)

#### 1. Input Pesanan menggunakan Komponen Repeater

Kasir dapat memasukkan beberapa item pesanan dalam satu transaksi secara fleksibel. Komponen repeater memudahkan pengelolaan item tanpa perlu mengulang proses input.

#### 2. Penambahan Topping dan Modifier secara Dinamis

Fitur ini memungkinkan kasir menambahkan topping atau modifier pada menu sesuai dengan permintaan pelanggan, sehingga pesanan menjadi lebih variatif.

#### 3. Perhitungan Total Harga secara Otomatis

Sistem secara otomatis menghitung total harga pesanan berdasarkan item, topping, dan jumlah yang dipilih, sehingga meminimalkan kesalahan perhitungan.

#### 4. Fitur Input Uang Bayar dan Kalkulasi Kembalian

Kasir dapat memasukkan nominal pembayaran pelanggan, dan sistem akan menghitung jumlah kembalian secara otomatis.

#### 5. Cetak Struk Transaksi dalam Bentuk PDF/HTML

Setelah transaksi selesai, sistem menyediakan struk dalam format PDF atau HTML yang dapat dicetak atau disimpan sebagai bukti transaksi.

#### 6. Akses Halaman yang Dibatasi sesuai Role

Kasir hanya dapat mengakses fitur yang berkaitan dengan transaksi, sehingga keamanan dan fokus kerja tetap terjaga.

---
### Fitur Pelanggan (Frontend)

#### 1. Melihat Daftar Menu Makanan dan Minuman

Pelanggan dapat melihat seluruh menu yang tersedia beserta informasi dasar seperti nama dan harga produk.

#### 2. Filter Menu Berdasarkan Nama, Harga, dan Kategori 

Fitur filter membantu pelanggan dalam menyaring menu sesuai dengan nama, harga, dan kategori yang diinginkan.

#### 3. Tampilan Menu Berbentuk Kartu 

Menu ditampilkan dalam bentuk kartu untuk memudahkan pelanggan dalam melihat dan memilih produk secara visual.

#### 4. Halaman Detail Menu 

Halaman ini menyajikan informasi lebih lengkap mengenai menu, seperti deskripsi dan detail tambahan yang relevan.

---
### Fitur Tambahan (Opsional)

#### 1. Integrasi Payment Gateway (Midtrans atau Xendit)

Sistem mendukung pembayaran non-tunai melalui integrasi *payment gateway* untuk memberikan fleksibilitas metode pembayaran kepada pelanggan.

#### 2. Filter Laporan Berdasarkan Tanggal dan Status Transaksi

Admin dapat menyaring laporan transaksi berdasarkan status dan rentang waktu tertentu agar analisis data lebih terfokus.

#### 3. Fitur Chart Penjualan untuk Monitoring Transaksi 7 Hari Terakhir

Grafik penjualan disediakan untuk membantu pemantauan tren transaksi dalam jangka waktu pendek secara visual.

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
