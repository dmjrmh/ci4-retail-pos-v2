# Retail POS (CI4) — Project Test

Implementasi aplikasi POS sederhana untuk kebutuhan soal project test. Fitur meliputi transaksi dengan identifikasi outlet, manajemen promo/diskon dinamis, dan report penjualan (summary per outlet + detail transaksi).

## Quick Start

- Requirements: PHP 8+, Composer, MySQL/MariaDB
- Install deps: `composer install`
- Copy env: `cp env .env` lalu set `database.default.*` dan `app.baseURL`
- Migrasi + seed: `php spark migrate:refresh && php spark db:seed DatabaseSeeder`
- Jalankan dev server: `php spark serve` lalu buka `http://localhost:8080`

## Fitur Utama (sesuai soal)

- Web app CI4.
- Relasi tabel: `stores`, `masterbarang`, `transaction_headers`, `transaction_details`, `discount_headers`, `discount_details`.
- Transaksi menyimpan `store_id` untuk identifikasi outlet asal data.
- Promo dinamis:
  - Berlaku pada rentang tanggal dan jam tertentu, aktif/nonaktif per outlet atau global.
  - Berlaku pada item tertentu (per-PCode) dengan tipe diskon Persen (`P`) atau Rupiah (`R`).
  - Syarat minimal belanja (`min_amount`).
  - Perhitungan di server: `Transactions::calculateTotals()` dipakai oleh `preview()` dan `store()` agar konsisten.
  - UI: panel "Promo Aktif" di atas form transaksi + hint promo per baris item (halaman `Transaksi Baru`).
- Report penjualan:
  - Summary total per outlet: halaman `Reports → Sales Report` (`/reports/sales`) lengkap dengan filter tanggal/outlet dan Grand Total di footer.
  - Daftar transaksi: halaman `Transaksi` (`/transactions`) dengan filter dan total per halaman di footer.
  - Detail transaksi: klik NoStruk untuk melihat item yang dijual beserta diskon per item.

## Routes Utama

- `GET /transactions` — daftar transaksi (filter + total halaman)
- `GET /transactions/create` — transaksi baru (preview realtime + info promo aktif)
- `POST /transactions/preview` — API preview total transaksi
- `GET /transactions/show/{id}` — detail transaksi (header + item)
- `GET /api/discounts/active` — API daftar promo aktif per outlet (opsional `pcode`)
- `GET /reports/sales` — sales report summary per outlet (GRAND TOTAL)

## Seeder

- `MasterBarangSeeder` — contoh data barang
- `StoresSeeder` — contoh data outlet
- `DiscountsSeeder` — beberapa promo aktif (tgl: today → +30 hari)
- `TransactionsSeeder` — membuat transaksi (header+detail) acak 5 hari terakhir per outlet, menghitung diskon sesuai logika promo

Jalankan semua via: `php spark db:seed DatabaseSeeder`

## Catatan

- Total pada halaman daftar transaksi adalah total dari baris yang tampil pada halaman aktif (terpengaruh pagination).
- Proyek ini untuk demonstrasi konsep; belum mencakup autentikasi/otorisasi.

---

## CodeIgniter 4 Framework

Di bawah ini adalah informasi default dari framework CI4.

CodeIgniter adalah PHP full‑stack web framework yang ringan, cepat, fleksibel, dan aman. Panduan lengkap ada di [User Guide](https://codeigniter4.github.io/userguide/).

`index.php` berada di folder `public` — arahkan web server ke folder tersebut.

Server Requirements: PHP 7.4+ dengan ekstensi `intl`, `mbstring`, serta `json`, `mysqlnd` (untuk MySQL), dan `libcurl` bila menggunakan HTTP\CURLRequest.
