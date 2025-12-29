---
# Deployment

---
## A. Beli Hosting yang Memiliki Fitur SSH

1. Kami menggunakan [Anymhost](https://anymhost.id/), lalu pilih menu `Developer Hosting`.

![alt text](images/1A_Deploy.png)

---
2. Pilih paket `Newbie` karena cukup untuk kebutuhan kami dan sudah didukung `SSH Access` dengan harga hanya Rp180.000/tahun dan sudah free domain.

![alt text](images/2A_Deploy.png)

---
3. Selesaikan pembayaran hingga masuk ke *client side* dari hosting.

![alt text](images/3A.1_Deploy.png)

![alt text](images/3A.2_Deploy.png)

![alt text](images/3A.3_Deploy.png)

---
## B. Setting Akses SSH ke Server

1. Masuk CPanel AnymHost.

![alt text](images/1B_Deploy.png)

---
2. Buka `SSH Access` lalu `Manage SSH Keys`.

![alt text](images/2B.1_Deploy.png)

![alt text](images/2B.2_Deploy.png)

---
3. Generate a new key.

![alt text](images/3B.1_Deploy.png)

![alt text](images/3B.2_Deploy.png)

![alt text](images/3B.3_Deploy.png)

---
4. Klik `Manage` pada *public key* lalu klik `Authorize`.

![alt text](images/4B.1_Deploy.png)

![alt text](images/4B.2_Deploy.png)

![alt text](images/4B.3_Deploy.png)

---
5. Simpan `public keys` dan `private keys` di notepad agar tidak hilang.

---
## C. Setting Akses Database

1. Masuk CPanel AnymHost, lalu klik `Manage My Database`.

![alt text](images/1C_Deploy.png)

---
2. Pada menu `Create New Database` isi kolom `New Database` untuk membuat *database* baru, kemudian klik `Create Database`.

![alt text](images/2C.1_Deploy.png)

![alt text](images/2C.2_Deploy.png)

![alt text](images/2C.3_Deploy.png)

---
3. Pada menu `Database Users`, isi kolom `Add New User` untuk membuat user baru yang mengakses *database*, jika sudah klik `Create User`.

![alt text](images/3C.1_Deploy.png)

![alt text](images/3C.2_Deploy.png)

![alt text](images/3C.3_Deploy.png)

---
4. Simpan `nama database`, `username`, dan `password` users *database* di notepad agar tidak hilang.

---
5. Pada menu `Add User To Database`, pilih kolom `User` dan `Database` sesuai dengan user dan *database* yang sudah dibuat untuk memberikan akses user ke *database*, jika sudah klik `Add`.

![alt text](images/5C_Deploy.png)

---
6. Pilih *privilage* apa yang digunakan user pada *database*, jika sudah klik `Make Changes`.

![alt text](images/6C.1_Deploy.png)

![alt text](images/6C.2_Deploy.png)

---
## D. Setting Deploy Keys dan Personal Access Tokens(clasic) di GitHub

1. Klik `Settings` pada repo yang ingin dideploy.

![alt text](images/1D_Deploy.png)

---
2. Klik `Deploy keys` pada sidebar menu.

![alt text](images/2D_Deploy.png)

---
3. Klik `Add deploy key`.

![alt text](images/3D_Deploy.png)

---
4. Masukkan `Title` dan `Key` lalu klik `Add key`, untuk key copy `public keys` pada poin *B.4*.

![alt text](images/4D.1_Deploy.png)

![alt text](images/4D.2_Deploy.png)

---
## E. Setting Personal Access Tokens(clasic) di GitHub

1. Klik `Settings` pada menu profile.

![alt text](images/1E_Deploy.png)

---
2. Klik `Developer Settings`.

![alt text](images/2E_Deploy.png)

---
3. Klik `Personal access tokens`, lalu klik `Tokens (classic)`.

![alt text](images/3E_Deploy.png)

---
4. Klik `Generate new token`, lalu klik `Generate new token (classic)`.

![alt text](images/4E_Deploy.png)

---
5. Isi kolom `Note`, kemudian atur `Expiration`, lalu pilih `scopes`nya. Kalau sudah klik `Generate token`.

![alt text](images/5E.1_Deploy.png)

![alt text](images/5E.2_Deploy.png)

---
6. Copy `personal access tokens (classic)` di notepad agar tidak hilang.

![alt text](images/6E_Deploy.png)

---
## F. Remote Server menggunakan SSH dan Cloning Project Cafelora di Server

1. Tanyakan ke *customer service*, port berapa yang digunakan untuk akses SSH. AnymHost menggunakan port `6401`.

---
2. Catat `User_CPanel`, `HOSTNAME_SERVER`, dan `PASSWORD_CPanel` di notepad.

![alt text](images/2F_Deploy.png)

---
3. Buka `terminal` pada laptop, kemudian ketikkan perintah berikut. Jika sudah klik `enter`.

```bash
ssh -p PORT USER_CPanel@HOSTNAME_SERVER
```

![alt text](images/3F_Deploy.png)

---
4. Masukkan *password* CPanel pada `terminal`, lalu klik `enter` nanti akan otomatis masuk ke server via `terminal`.

![alt text](images/4F_Deploy.png)

---
5. Setelah masuk ke server, ketikkan perintah berikut untuk clone repository. Jika sudah klik `enter`.

```bash
git clone https://<YOUR_PERSONAL_TOKENS>@github.com/<YOUR_USERNAME>/<YOUR_REPOSITORY>.git
```

![alt text](images/5F_Deploy.png)

---
6. Ketikkan perintah berikut untuk memastikan project Cafelora sudah diclone.

```bash
dir
```

![alt text](images/6F_Deploy.png)

---
7. Hapus semua file yang ada pada path `/home/YOUR_USERNAME/public_html` dengan menjalankan perintah berikut. Jika sudah klik `enter`.

```bash
cd /home/YOUR_USERNAME/public_html
ls -all
rm -rf ./* ./.*
ls -all
```

![alt text](images/7F_Deploy.png)

---
## G. Setup Project di Server

1. Masuk ke directory project dengan mengetikkan perintah berikut. Jika sudah klik `enter`.

```bash
cd YOUR_PROJECT
```

![alt text](images/1G_Deploy.png)

---
2. Update composer untuk mendownload `vendor` yang dibutuhkan Laravel dengan menjalankan perintah berikut. Jika sudah klik `enter`.

```bash
composer update
```

![alt text](images/2G_Deploy.png)

---
3. Copy file `.env.example` dengan nama `.env` dengan menjalankan perintah berikut, jika sudah klik `enter`.

```bash
cp .env.example .env
```

![alt text](images/3G_Deploy.png)

---
4. Generate key Laravel dengan menjalankan perintah berikut, jika sudah klik `enter`.

```bash
php artisan key:generate
```

![alt text](images/4G_Deploy.png)

---
5. Symlink `storage/app/public/menu-images` ke `public/storage/menu-images` dengan melakukan perintah berikut. Jika sudah klik `enter`.

```bash
php artisan storage:link
```

![alt text](images/5G_Deploy.png)

---
6. Edit file `.env` untuk mengatur environment yang dibutuhkan oleh web seperti *database*, midtrans, dan sebagainya menggunakan perintah berikut. Jika sudah klik `CTRL + X`, kemudian pilih `y`, lalu klik `enter`.

```bash
nano .env
```

![alt text](images/6G.1_Deploy.png)

![alt text](images/6G.2_Deploy.png)

![alt text](images/6G.3_Deploy.png)

---
7. Symlink `/home/YOUR_USERNAME/YOUR_PROJECT/public` ke `/home/YOUR_USERNAME/public_html` dengan melakukan perintah berikut. Jika sudah klik `enter`.

```bash
ln -s /home/YOUR_USERNAME/YOUR_PROJECT/public/* /home/YOUR_USERNAME/public_html
ln -s /home/YOUR_USERNAME/YOUR_PROJECT/public/.htaccess /home/YOUR_USERNAME/public_html
cd /home/YOUR_USERNAME/public_html && ls -l
```

![alt text](images/7G.1_Deploy.png)

![alt text](images/7G.2_Deploy.png)

![alt text](images/7G.3_Deploy.png)

![alt text](images/7G.4_Deploy.png)

---
8. Jalankan perintah berikut untuk migrate *database* dan seeder *database*. Jika sudah klik `enter`.

```bash
php artisan migrate:fresh --seed
```

![alt text](images/8G.1_Deploy.png)

![alt text](images/8G.2_Deploy.png)

---
## H. Hasil Akhir

1. Tampilan [Pelanggan](https://cafelora.my.id/)
2. Tampilan [Admin dan Staff](https://cafelora.my.id/admin/)

---