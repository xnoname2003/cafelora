---
# Entity Relationship Diagram Sistem CafeLora

Entity Relationship Diagram (ERD) ini menggambarkan struktur database dari sistem manajemen CafeLora, yang mencakup pengguna, menu, kategori, varian, topping, transaksi, hingga detail item pesanan.

1. User adalah entitas inti yang mewakili admin, staff kasir, dan customer. Akses kontrol diatur menggunakan role dan permission.
2. Menu terbagi menjadi kategori seperti makanan dan minuman. Menu dapat memiliki banyak varian serta banyak topping.
3. Transaksi dicatat ketika kasir membuat pesanan. Setiap transaksi memiliki banyak item pesanan. Setiap item dapat memiliki topping tambahan.
4. Variant dan Topping membantu menyesuaikan harga akhir berdasarkan pilihan pelanggan.
5. Relasi Many-to-Many digunakan pada menu ↔ varian, menu ↔ topping, user ↔ role, serta role ↔ permission.
6. Transaksi berjalan sesuai alur operasional cafe seperti pemesanan, perhitungan total, pembayaran, hingga pencetakan struk.

<img width="1264" height="1262" alt="ERD - Cafelora" src="https://github.com/user-attachments/assets/705af06a-d897-469c-b23e-ea317e1f3948" />

---
## Penjelasan Relasi Antar Tabel

| No | Entitas 1                  | Entitas 2                  | Tipe Relasi    | Penjelasan                                                                 |
|----|----------------------------|----------------------------|----------------|---------------------------------------------------------------------------|
| 1  | users                      | roles                      | Many-to-Many   | Satu user dapat memiliki banyak role dan sebaliknya.                      |
| 2  | roles                      | permissions                | Many-to-Many   | Role bisa memiliki banyak permission.                                     |
| 3  | users                      | permissions                | Many-to-Many   | User bisa diberi permission langsung.                                     |
| 4  | categories                 | menus                      | One-to-Many    | Satu kategori memiliki banyak menu.                                       |
| 5  | menus                      | variants                   | Many-to-Many   | Satu menu bisa memiliki banyak varian.                                    |
| 6  | menus                      | toppings                   | Many-to-Many   | Satu menu bisa memiliki banyak topping.                                   |
| 7  | users                      | transactions               | One-to-Many    | Satu user (kasir) dapat membuat banyak transaksi.                         |
| 8  | transactions               | transaction_items          | One-to-Many    | Satu transaksi terdiri dari banyak item pesanan.                          |
| 9  | menus                      | transaction_items          | Many-to-One    | Satu item pesanan merujuk ke satu menu.                                   |
| 10 | variants                   | transaction_items          | Many-to-One    | Item pesanan hanya memakai satu varian.                                   |
| 11 | transaction_items          | transaction_item_toppings  | One-to-Many    | Setiap item pesanan dapat memiliki beberapa topping.                      |
| 12 | toppings                   | transaction_item_toppings  | Many-to-One    | Topping digunakan sebagai tambahan pada item tertentu.                     |

---

## Tabel: users  _(Tabel Master)_
Berfungsi untuk menyimpan data user (Admin, Staff).  
**Relasi:**  
- One-to-Many → transactions  
- Many-to-Many → roles  
- Many-to-Many → permissions  

| Kolom              | Tipe Data      | Fungsi                          |
|--------------------|----------------|---------------------------------|
| id                 | bigint         | Primary key user                |
| name               | varchar        | Nama user                       |
| email              | varchar        | Email login                     |
| password           | varchar        | Password bcrypt                 |
| email_verified_at  | datetime       | Verifikasi email                |
| remember_token     | varchar        | Token login                     |
| created_at         | datetime       | Waktu dibuat                    |
| updated_at         | datetime       | Waktu update                    |


## Tabel: roles  _(Tabel Master)_
Berfungsi untuk menyimpan daftar peran.  
**Relasi:**  
- Many-to-Many → permissions  
- Many-to-Many → users  

| Kolom      | Tipe Data | Fungsi                     |
|------------|-----------|----------------------------|
| id         | bigint    | Primary key                |
| name       | varchar   | Nama role                  |
| guard_name | varchar   | Guard spatie               |
| created_at | datetime  | Waktu dibuat               |
| updated_at | datetime  | Waktu update               |


## Tabel: permissions  _(Tabel Master)_
Berfungsi untuk menyimpan daftar permission.  
**Relasi:**  
- Many-to-Many → roles  
- Many-to-Many → users  

| Kolom      | Tipe Data | Fungsi                     |
|------------|-----------|----------------------------|
| id         | bigint    | Primary key                |
| name       | varchar   | Nama permission            |
| guard_name | varchar   | Guard permission           |
| created_at | datetime  | Waktu dibuat               |
| updated_at | datetime  | Waktu update               |


## Tabel: role_has_permissions  _(Tabel Relasi Many-to-Many)_
Menghubungkan role ↔ permission.  
**Relasi:**  
- Many-to-Many → roles  
- Many-to-Many → permissions  

| Kolom         | Tipe Data | Fungsi                 |
|---------------|-----------|------------------------|
| permission_id | bigint    | FK permissions.id      |
| role_id       | bigint    | FK roles.id            |


## Tabel: model_has_roles  _(Tabel Relasi Many-to-Many)_
Assign role ke user.  
**Relasi:**  
- Many-to-Many → users  
- Many-to-Many → roles  

| Kolom      | Tipe Data | Fungsi                  |
|------------|-----------|-------------------------|
| role_id    | bigint    | FK roles.id             |
| model_type | varchar   | Model type (User)       |
| model_id   | bigint    | FK users.id             |


## Tabel: model_has_permissions  _(Tabel Relasi Many-to-Many)_
Memberi permission langsung ke user.  
**Relasi:**  
- Many-to-Many → users  
- Many-to-Many → permissions  

| Kolom         | Tipe Data | Fungsi                    |
|---------------|-----------|---------------------------|
| permission_id | bigint    | FK permissions.id         |
| model_type    | varchar   | Model type (User)         |
| model_id      | bigint    | FK users.id               |

---

## MENU SYSTEM

## Tabel: categories  _(Tabel Master)_
Berfungsi untuk menyimpan kategori menu.  
**Relasi:**  
- One-to-Many → menus  

| Kolom      | Tipe Data | Fungsi              |
|------------|-----------|---------------------|
| id         | int       | Primary key         |
| name       | varchar   | Nama kategori       |
| created_at | datetime  | Waktu dibuat        |
| updated_at | datetime  | Waktu update        |


## Tabel: variants  _(Tabel Master)_
Berfungsi untuk menyimpan varian menu.  
**Relasi:**  
- Many-to-Many → menus  

| Kolom            | Tipe Data | Fungsi                          |
|------------------|-----------|---------------------------------|
| id               | int       | Primary key                     |
| name             | varchar   | Nama varian                     |
| price_adjustment | int       | Tambahan harga                  |
| created_at       | datetime  | Waktu dibuat                    |
| updated_at       | datetime  | Waktu update                    |


## Tabel: toppings  _(Tabel Master)_
Berfungsi untuk menyimpan topping menu.  
**Relasi:**  
- Many-to-Many → menus  
- Many-to-Many → transaction_items  

| Kolom      | Tipe Data | Fungsi                |
|------------|-----------|-----------------------|
| id         | int       | Primary key           |
| name       | varchar   | Nama topping          |
| price      | int       | Harga topping         |
| created_at | datetime  | Waktu dibuat          |
| updated_at | datetime  | Waktu update          |


## Tabel: menus  _(Tabel Master)_
Berfungsi untuk menyimpan menu makanan/minuman.  
**Relasi:**  
- One-to-Many ← categories  
- Many-to-Many → variants  
- Many-to-Many → toppings  
- One-to-Many → transaction_items  

| Kolom       | Tipe Data | Fungsi                                 |
|-------------|-----------|----------------------------------------|
| id          | int       | Primary key                            |
| category_id | int       | FK kategori                            |
| name        | varchar   | Nama menu                              |
| description | text      | Deskripsi                              |
| base_price  | int       | Harga dasar                            |
| image       | varchar   | Gambar                                 |
| stock       | int       | Stok menu                              |
| sales_qty   | int       | Total jumlah menu yang sudah terjual   |
| created_at  | datetime  | Waktu dibuat                           |
| updated_at  | datetime  | Waktu update                           |


## Tabel: menu_variant  _(Tabel Relasi Many-to-Many)_
Relasi menu ↔ varian.  
**Relasi:**  
- Many-to-Many → menus  
- Many-to-Many → variants  

| Kolom     | Tipe Data | Fungsi            |
|-----------|-----------|-------------------|
| id        | int       | Primary key       |
| menu_id   | int       | FK menus.id       |
| variant_id| int       | FK variants.id    |


## Tabel: menu_topping  _(Tabel Relasi Many-to-Many)_
Relasi menu ↔ topping.  
**Relasi:**  
- Many-to-Many → menus  
- Many-to-Many → toppings  

| Kolom     | Tipe Data | Fungsi              |
|-----------|-----------|---------------------|
| id        | int       | Primary key         |
| menu_id   | int       | FK menus.id         |
| topping_id| int       | FK toppings.id      |


---

## TRANSACTION SYSTEM (POS)

## Tabel: transactions  _(Tabel Master Transaksi)_
Berfungsi untuk menyimpan transaksi kasir.  
**Relasi:**  
- Many-to-One → users (kasir)  
- One-to-Many → transaction_items  

| Kolom         | Tipe Data    | Fungsi               |
|---------------|--------------|----------------------|
| id            | int          | Primary key          |
| user_id       | bigint       | FK kasir             |
| invoice       | varchar      | Nomor invoice        |
| status        | enum         | pending/paid/completed |
| total         | int          | Total transaksi      |
| paid_amount   | int          | Uang bayar           |
| change_amount | int          | Kembalian            |
| created_at    | datetime     | Waktu dibuat         |
| updated_at    | datetime     | Waktu update         |


## Tabel: transaction_items  _(Tabel Detail Transaksi)_
Berfungsi untuk menyimpan item dalam transaksi.  
**Relasi:**  
- Many-to-One → transactions  
- Many-to-One → menus  
- Many-to-One → variants  
- One-to-Many → transaction_item_toppings  

| Kolom          | Tipe Data | Fungsi                 |
|----------------|-----------|------------------------|
| id             | int       | Primary key            |
| transaction_id | int       | FK transaksi           |
| menu_id        | int       | FK menu                |
| variant_id     | int       | FK varian              |
| quantity       | int       | Jumlah                 |
| price          | int       | Harga satuan           |
| subtotal       | int       | Total harga            |
| created_at     | datetime  | Waktu dibuat           |
| updated_at     | datetime  | Waktu update           |


## Tabel: transaction_item_toppings  _(Relasi Many-to-Many per Item Transaksi)_
Berfungsi untuk menyimpan topping tambahan pada item transaksi.  
**Relasi:**  
- Many-to-One → transaction_items  
- Many-to-One → toppings  

| Kolom                | Tipe Data | Fungsi                |
|----------------------|-----------|-----------------------|
| id                   | int       | Primary key           |
| transaction_item_id  | int       | FK item transaksi     |
| topping_id           | int       | FK topping            |
| price                | int       | Harga topping         |

---
