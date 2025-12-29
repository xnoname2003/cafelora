---
# IMPLEMENTASI MIDTRANS

---
## Deskripsi Midtrans

Midtrans adalah layanan *payment gateway* di Indonesia yang membantu bisnis menerima pembayaran online lewat berbagai metode dalam satu integrasi, seperti transfer bank, *virtual account*, kartu kredit dan debit, e-wallet, serta pembayaran di minimarket. Pengguna bisa menghubungkan Midtrans ke website atau aplikasi lewat API dan dashboard untuk membuat invoice, memproses pembayaran, memantau status transaksi secara *real-time*, dan mengelola fitur seperti notifikasi webhook, pembayaran otomatis, hingga laporan transaksi. Midtrans juga menyediakan fitur keamanan dan deteksi risiko, sehingga alur pembayaran lebih rapi dan mudah dikelola dari sisi sistem.

---
## Lingkup Pendukung

1. **Website Utama**: https://midtrans.com/
2. **Halaman Dashboard Production**: https://dashboard.midtrans.com/l
3. **Halaman Dashboard Sandbox**: https://dashboard.sandbox.midtrans.com/
4. **Github**: https://github.com/Midtrans/midtrans-php

---
## Alur Kerja Midtrans

### Alur Kerja Midtrans secara Umum

Alur Midtrans di Cafelora itu sederhana dan rapi. Pelanggan cukup melihat daftar menu di website untuk menentukan pilihan makanan dan minuman, termasuk melihat detail menu agar pesanan lebih jelas. Setelah pelanggan menyampaikan pilihannya, kasir atau admin memasukkan pesanan ke sistem POS, memilih varian, menambahkan topping atau modifier bila diperlukan, lalu sistem otomatis menghitung total belanja. Saat proses *checkout*, kasir atau admin menekan tombol bayar, kemudian Midtrans menampilkan pilihan metode pembayaran seperti QRIS, e-wallet, transfer bank, kartu, atau minimarket. Pelanggan memilih metode yang paling nyaman, lalu melakukan pembayaran sesuai instruksi yang muncul di layar. Begitu pembayaran berhasil, Midtrans langsung mengirim notifikasi ke sistem Cafelora sehingga status transaksi otomatis berubah menjadi *paid* tanpa input manual. Setelah itu kasir tinggal menyiapkan pesanan, mencetak struk, menghitung kembalian jika ada pembayaran tunai di luar sistem, dan memastikan pesanan berjalan sesuai alur pelayanan. Di sisi admin, semua transaksi tercatat otomatis sehingga mudah dipantau melalui dashboard, bisa difilter berdasarkan status dan tanggal, terlihat pada grafik penjualan, dan bisa diekspor menjadi laporan PDF atau Excel. Jika pembayaran gagal, tertunda, atau dibatalkan, status transaksi ikut terupdate sehingga transaksi tidak dianggap selesai dan data tetap konsisten.

---
### Alur Kerja Midtrans secara Teknis

#### 1. Setup Midtrans

1. Buat akun Midtrans dan pilih environment Sandbox atau Production.
2. Ambil Server Key dan Client Key.
3. Simpan key di env aplikasi, contoh MIDTRANS_SERVER_KEY, MIDTRANS_CLIENT_KEY, MIDTRANS_IS_PRODUCTION.
4. Set konfigurasi: isProduction, isSanitized, is3ds (untuk kartu).

---
#### 2. Desain Data Transaksi di Database

1. Tabel transaksi minimal punya: invoice/order_id, gross_amount, status, payment_type, snap_token, midtrans_transaction_id, paid_at.
2. Simpan detail item: menu, varian, topping, qty, price, subtotal.
3. Status internal yang umum: draft, pending, paid, failed, expired, cancelled, refunded.

---
#### 3. Flow Pembuatan Order dari POS

1. Kasir/admin buat order di POS.
2. *Backend* hitung ulang total (jangan percaya total dari *frontend*).
3. *Backend* generate order_id unik (misal INV-20251222202546-GCC1).
4. Simpan transaksi ke DB dengan status *pending* atau *unpaid*.

---
#### 4. Request ke Midtrans untuk Generate Pembayaran

1. *Backend* panggil API Midtrans Snap untuk create transaction.

2. Kirim payload:

   1) transaction_details: order_id, gross_amount.

   2) item_details: list item (menu, topping) dengan price dan qty.

   3) customer_details: nama, email, hp (bisa data kasir jika offline).

   4) callbacks atau expiry jika dipakai.

3. Midtrans mengembalikan snap_token dan redirect_url.

4. *Backend* simpan snap_token dan redirect_url ke DB.

---
#### 5. Menampilkan UI Pembayaran

1. *Frontend* POS load Snap.js pakai clientKey.

2. Saat klik “Bayar”, *frontend* panggil endpoint *backend* untuk ambil snap_token.

3. *Frontend* menjalankan snap.pay(snapToken, callbacks...) untuk menampilkan *pop-up* pembayaran.

4. *Callback frontend*:

   onSuccess, onPending, onError, onClose.

5. Catatan: *callback frontend* hanya untuk UX. Sumber kebenaran tetap webhook.

---
#### 6. Notifikasi Status via Webhook

1. Midtrans kirim HTTP POST ke endpoint webhook pengguna, contoh:

   POST /api/midtrans/notify.

2. *Backend* verifikasi:

   1) validasi signature key (Midtrans signature).

   2) cocokkan order_id dengan transaksi di DB.

3. *Backend* mapping status Midtrans:

   1) transaction_status=settlement atau capture -> set paid.

   2) pending -> set pending.

   3) deny, cancel, expire -> set sesuai.

   4) refund / chargeback -> set refunded / chargeback.

4. Update DB secara idempotent:

   1) kalau status sudah paid, jangan overwrite jadi pending.

   2) simpan raw payload untuk audit.

5. Trigger proses lanjutan:

   1) set paid_at

   2) kurangi stok

   3) generate nomor struk dan data print

---
#### 7. Status Transaksi Real-Time untuk POS

1. POS polling endpoint transaksi atau pakai WebSocket.
2. Kasir lihat status berubah otomatis jadi paid.
3. *Workflow* lanjut: paid -> processing -> done (sesuai alur Cafelora).

---
#### 8. Rekonsiliasi dan Fallback

1. Jika webhook telat, *backend* bisa cek status via API Midtrans (Status API) berdasarkan order_id.

2. Jalankan cron job untuk transaksi pending yang lama:

   1) query status ke Midtrans.

   2) update DB jika sudah settle atau expire.

---
#### 9. Keamanan dan Best Practice

1. Server Key hanya di *backend*. Jangan taruh di *frontend*.

2. Semua total dihitung di *backend*.

3. Endpoint webhook harus:

   1) tanpa auth user biasa

   2) diproteksi signature verification

   3) rate limit dan log request

4. Pastikan order_id unik dan tidak dipakai ulang.

---