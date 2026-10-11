# Menerbitkan SuperDuperWeb

Panduan memasang situs ini di hosting sungguhan, dari nol sampai bisa menerima klien.
Ada dua jalur: **hosting cPanel** (misalnya paket hosting Domainesia) yang paling murah,
atau **VPS** kalau butuh kendali penuh.

## Yang dibutuhkan

- PHP **8.2+** dengan ekstensi `ctype`, `curl`, `dom`, `fileinfo`, `gd`, `intl`, `mbstring`,
  `openssl`, `pdo_mysql`, `tokenizer`, `xml`, `zip` (`intl` wajib untuk panel Filament)
- MySQL 8 (atau MariaDB 10.6+)
- Akses SSH atau menu *Terminal* di cPanel. Tanpa ini, `artisan` tidak bisa dijalankan
- Akun email untuk SMTP (bisa dibuat di cPanel), supaya notifikasi brief dan bukti
  pembayaran sampai ke kamu

Aplikasi ini **tidak butuh** queue worker maupun cron: email dikirim langsung saat
brief atau bukti pembayaran masuk, dan kegagalannya hanya dicatat di log.

## 1. Siapkan berkas di laptop

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
```

Hasilnya: folder `vendor/` dan `public/build/` siap diunggah, jadi server tidak perlu
Node.js. Jangan ikut unggah `.env`, `node_modules/`, dan `storage/app/public/*` dari laptop.

## 2a. Hosting cPanel

1. **Unggah** seluruh folder proyek ke `~/superduperweb` (di luar `public_html`), lewat
   File Manager (zip lalu ekstrak) atau `git clone` dari Terminal.
2. **Arahkan domain** ke folder `public`: cPanel → *Domains* → domainmu → ubah
   *Document Root* menjadi `superduperweb/public`. Kalau paketmu tidak mengizinkan, hapus
   `public_html` lalu buat symlink: `ln -s ~/superduperweb/public ~/public_html`.
3. **Buat basis data**: cPanel → *MySQL Databases* → buat database dan user, lalu beri
   user itu *ALL PRIVILEGES* pada database tersebut.
4. **Pilih versi PHP** 8.2 atau lebih baru di *Select PHP Version* / *MultiPHP Manager*,
   dan centang ekstensi di atas.
5. **Aktifkan SSL** (cPanel → *SSL/TLS Status* → *Run AutoSSL*) sebelum lanjut, karena
   `APP_URL` harus https.

## 2b. VPS (Ubuntu + Nginx)

Pasang `php8.3-fpm` beserta ekstensinya, `mysql-server`, dan `nginx`. Arahkan `root`
Nginx ke `/var/www/superduperweb/public` dengan blok standar Laravel
(`try_files $uri $uri/ /index.php?$query_string;`), lalu pasang SSL dengan
`certbot --nginx`. Pastikan `storage/` dan `bootstrap/cache/` bisa ditulis oleh user
PHP-FPM (`www-data`).

## 3. Isi `.env` di server

```bash
cp .env.example .env
```

Ubah minimal baris berikut:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domainmu.id

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=nama_database
DB_USERNAME=nama_user
DB_PASSWORD=...

MAIL_MAILER=smtp
MAIL_HOST=mail.domainmu.id
MAIL_PORT=465
MAIL_SCHEME=smtps
MAIL_USERNAME=halo@domainmu.id
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS=halo@domainmu.id
ADMIN_NOTIFICATION_EMAIL=emailmu@gmail.com

SESSION_SECURE_COOKIE=true
LOG_LEVEL=warning
```

## 4. Jalankan sekali di Terminal

```bash
cd ~/superduperweb
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force        # konten awal: paket, FAQ, pengaturan, portofolio
php artisan storage:link
php artisan make:filament-user     # akun admin pertamamu
php artisan optimize
php artisan filament:optimize
```

**Jangan** jalankan `db:seed --class=DemoSeeder` di server: isinya testimoni, klien, dan
rekening rekaan (seeder ini juga menolak berjalan saat `APP_ENV=production`).

Kalau hosting melarang symlink sehingga `storage:link` gagal, ubah `public` disk di
`config/filesystems.php` agar `root`-nya `public_path('storage')`, supaya unggahan
langsung tersimpan di folder publik.

## 5. Isi data asli di panel admin

Masuk ke `https://domainmu.id/admin`, lalu:

- **Pengaturan**: nomor WhatsApp, email bisnis, tautan Instagram/Facebook/TikTok
  (kosongkan yang tidak dipakai), gambar **QRIS asli**, dan ID Google Analytics bila ada.
- **Rekening Bank**: rekening asli untuk halaman invoice.
- **Paket**, **FAQ**, dan **Syarat & Ketentuan**: sesuaikan harga dan isi.
- **Portofolio**: sudah terisi proyek pribadi. Tambahkan proyek klien begitu ada.

## 6. Periksa sebelum dibuka untuk klien

```bash
php artisan superduperweb:cek-rilis
```

Perintah ini gagal (kode keluar 1) selama masih ada hal yang bisa merugikan klien:
`APP_DEBUG` masih menyala, sandi admin bawaan, nomor WhatsApp contoh, rekening atau
QRIS contoh, serta testimoni dan klien rekaan. Peringatan lain (misalnya email masih ke
log) ditampilkan tanpa menggagalkan.

## Memperbarui situs

```bash
git pull                     # atau unggah ulang berkas yang berubah
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize
php artisan filament:optimize
```

Kalau ada perubahan tampilan, jalankan `npm run build` di laptop dan unggah ulang
`public/build/`.
