# SuperDuperWeb

Situs dan sistem operasional untuk jasa pembuatan website & aplikasi, dengan target klien UMKM
dan organisasi di Indonesia. Seluruh antarmuka berbahasa Indonesia.

Terdiri dari dua bagian:

- **Situs publik** — landing page satu halaman, portofolio, form konsultasi, syarat & ketentuan,
  dan halaman invoice pribadi untuk klien.
- **Panel admin** (`/admin`) — pengelolaan konten, brief masuk, klien, proyek, pembayaran,
  biaya, dan dashboard keuangan.

## Teknologi

| Bagian | Teknologi |
|:--|:--|
| Framework | Laravel 12 + Blade |
| Panel admin | Filament 5 |
| Tampilan | Tailwind CSS 4, Alpine.js |
| Database | MySQL 8 |
| PDF invoice | barryvdh/laravel-dompdf |
| Minimum PHP | 8.2 |

## Menjalankan di lokal

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate

# Sesuaikan DB_* di .env, lalu:
php artisan migrate --seed
php artisan storage:link

npm run build          # atau: npm run dev
php artisan serve
```

Akun admin dari seeder (hanya di luar produksi): **admin@superduperweb.id** / **password**.
Di produksi akun ini tidak dibuat; pakai `php artisan make:filament-user`.

`db:seed` hanya mengisi konten yang memang dipakai di produksi: pengaturan, paket, FAQ, dan
portofolio proyek asli (tangkapan layarnya ada di `database/seeders/gambar`). Untuk
mencoba panel admin dengan data rekaan (rekening, QRIS, testimoni, klien, brief, proyek,
pembayaran, biaya):

```bash
php artisan db:seed --class=DemoSeeder   # hanya untuk lokal, jangan di produksi
```

Section testimoni dan statistik di halaman depan otomatis tersembunyi sampai datanya ada,
jadi situs produksi tidak pernah menampilkan angka atau kutipan rekaan.

> `APP_URL` harus sesuai dengan alamat yang dipakai di browser. Nilai ini dipakai untuk membangun
> URL gambar di `/storage`, jadi kalau tidak cocok, gambar portofolio dan QRIS akan tampak rusak.

## Menerbitkan

Langkah memasang di hosting cPanel atau VPS ada di [DEPLOY.md](DEPLOY.md). Sebelum situs
dibuka untuk klien, jalankan `php artisan superduperweb:cek-rilis`: perintah ini gagal
selama masih ada sandi bawaan, nomor WhatsApp contoh, rekening/QRIS contoh, atau data
rekaan dari `DemoSeeder`.

## Menjalankan pengujian

```bash
php artisan test
```

Pengujian memakai SQLite in-memory, jadi tidak menyentuh database pengembangan.

## Catatan arsitektur

### Uang selalu integer rupiah

Semua nominal (`price`, `deal_price`, `amount`) disimpan sebagai `unsignedBigInteger` berisi
rupiah penuh, tanpa desimal, supaya tidak ada galat pembulatan floating point. Penampilannya
lewat helper `rupiah()` di `app/Support/helpers.php` yang menghasilkan format `Rp 1.500.000`.

### Omzet, biaya, dan margin

- Hanya pembayaran berstatus **terverifikasi** yang dihitung sebagai omzet. Bukti yang baru
  diunggah klien berstatus *menunggu verifikasi* dan belum memengaruhi angka mana pun.
- `margin = total pembayaran terverifikasi − total biaya proyek`.
- Di tabel proyek, kedua angka itu diambil lewat subquery `withSum` supaya tidak terjadi N+1.

### Bukti pembayaran bersifat privat

Bukti pembayaran disimpan di disk `private` (`storage/app/private`), di luar document root.
Disk itu disajikan Laravel hanya lewat URL bertanda tangan yang kedaluwarsa, dan URL tersebut
cuma dibuat di dalam panel admin yang sudah terautentikasi. Permintaan tanpa tanda tangan
ditolak (404 di produksi). Disk `local` bawaan Laravel sengaja dimatikan penyajiannya agar
tidak ada rute akses lain ke direktori yang sama.

### Halaman invoice klien

Diakses lewat `/invoice/{kode}` dengan kode acak 40 karakter yang digenerate otomatis saat
proyek dibuat. Tidak perlu login. Halaman ini diberi `noindex, nofollow` dan sengaja tidak
pernah dimasukkan ke `sitemap.xml`.

### Anti-spam form publik

Form konsultasi dan unggah bukti pembayaran memakai tiga lapis: honeypot (kolom tersembunyi
yang hanya diisi bot), penolakan kiriman di bawah 3 detik sejak form dibuka, dan rate limit
per alamat IP (`AppServiceProvider::configureRateLimiting`).

### Pengaturan situs

Teks hero, kontak, link sosial media, gambar QRIS, isi Syarat & Ketentuan, ID Google Analytics,
dan ambang minimum statistik semuanya dikelola dari **Pengaturan Situs** di panel admin, tersimpan
di tabel `settings` sebagai pasangan key/value yang di-cache. Link sosial media yang dikosongkan
otomatis hilang dari footer, dan script Google Analytics hanya dimuat kalau ID-nya diisi.

## Deploy

### Shared hosting (PHP 8.2+)

1. Unggah seluruh berkas ke luar `public_html`, misalnya ke `~/superduperweb`.
2. Arahkan document root domain ke `~/superduperweb/public`. Kalau panel hosting tidak
   mengizinkan, salin isi `public/` ke `public_html` lalu sesuaikan path di `index.php`.
3. Jalankan lewat SSH atau Terminal hosting:

   ```bash
   composer install --no-dev --optimize-autoloader
   php artisan key:generate
   php artisan migrate --force
   php artisan storage:link
   php artisan optimize
   php artisan filament:optimize
   ```

4. Aset frontend di-build di komputer lokal (`npm run build`), lalu folder `public/build`
   ikut diunggah. Shared hosting umumnya tidak punya Node.js.
5. Pastikan `storage/` dan `bootstrap/cache/` bisa ditulis (biasanya `755`).
6. Isi `.env`: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` sesuai domain, kredensial
   database, dan SMTP untuk notifikasi email.

Kalau `php artisan storage:link` tidak bisa membuat symlink (sebagian shared hosting
melarangnya), buat folder `public/storage` lalu salin isi `storage/app/public` ke sana.

### VPS

Sama seperti di atas, ditambah:

- Arahkan Nginx/Apache ke `public/`.
- Node.js tersedia, jadi `npm ci && npm run build` bisa dijalankan langsung di server.
- Jalankan worker antrean bila nanti pengiriman email dipindah ke antrean:
  `php artisan queue:work --daemon` di bawah Supervisor.
- Pasang penjadwal: `* * * * * cd /path && php artisan schedule:run >> /dev/null 2>&1`.

### Setelah mengubah kode di produksi

```bash
php artisan optimize:clear && php artisan optimize && php artisan filament:optimize
```
