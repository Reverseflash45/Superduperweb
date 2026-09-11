<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Rina Kusuma',
                'business_name' => 'Kopi Kenangan Senja',
                'role' => 'Pemilik',
                'content' => 'Awalnya saya pikir bikin website itu ribet dan mahal. Ternyata prosesnya jelas dari awal, dikabari terus progresnya, dan selesai lebih cepat dari perkiraan. Sekarang pelanggan tinggal buka menu sendiri, tidak perlu tanya-tanya lagi di DM.',
                'rating' => 5,
                'color' => ['#0F766E', '#134E4A'],
            ],
            [
                'name' => 'Hendra Wijaya',
                'business_name' => 'Batik Ayu Nusantara',
                'role' => 'Founder',
                'content' => 'Toko online kami akhirnya jalan sendiri. Ongkir otomatis, pembayaran langsung masuk, stok tidak bentrok lagi. Bulan pertama saja penjualan naik hampir dua kali lipat dibanding jualan lewat chat.',
                'rating' => 5,
                'color' => ['#9333EA', '#6B21A8'],
            ],
            [
                'name' => 'Bambang Setiawan',
                'business_name' => 'Koperasi Karyawan Sejahtera',
                'role' => 'Ketua Koperasi',
                'content' => 'Rekap bulanan yang dulu makan tiga hari sekarang selesai dalam hitungan menit. Tim SuperDuperWeb sabar sekali waktu melatih pengurus yang rata-rata tidak terbiasa dengan komputer.',
                'rating' => 5,
                'color' => ['#2563EB', '#1E3A8A'],
            ],
            [
                'name' => 'Dewi Anggraini',
                'business_name' => 'Warung Pintar Grup',
                'role' => 'Operational Manager',
                'content' => 'Yang paling saya suka: aplikasinya tetap jalan walau internet di cabang mati. Sekarang saya bisa lihat penjualan 12 cabang dari satu layar. Respons kalau ada kendala juga cepat.',
                'rating' => 5,
                'color' => ['#EA580C', '#9A3412'],
            ],
            [
                'name' => 'Ustaz Ahmad Fauzi',
                'business_name' => 'Yayasan Cahaya Harapan',
                'role' => 'Sekretaris Yayasan',
                'content' => 'Donatur jadi lebih percaya karena laporan penggunaan dana bisa dilihat siapa saja. Kami juga bisa update agenda sendiri tanpa perlu minta tolong ke siapa-siapa. Harganya sangat masuk akal untuk yayasan.',
                'rating' => 5,
                'color' => ['#0891B2', '#155E75'],
            ],
            [
                'name' => 'Siti Marlina',
                'business_name' => 'Laundry Kilat',
                'role' => 'Pemilik',
                'content' => 'Telepon menanyakan cucian turun drastis sejak ada notifikasi WhatsApp otomatis. Karyawan juga lebih tenang karena tidak ada lagi nota hilang. Benar-benar sesuai kebutuhan usaha kecil seperti kami.',
                'rating' => 4,
                'color' => ['#0EA5E9', '#075985'],
            ],
        ];

        foreach ($testimonials as $index => $testimonial) {
            [$from, $to] = $testimonial['color'];
            unset($testimonial['color']);

            $initials = Str::upper(Str::substr($testimonial['name'], 0, 1));
            $testimonial['avatar'] = DummyImage::make(
                'testimoni/'.Str::slug($testimonial['name']).'.svg',
                $initials,
                $from,
                $to,
                300,
                300,
            );
            $testimonial['is_published'] = true;
            $testimonial['sort_order'] = $index + 1;

            Testimonial::query()->updateOrCreate(
                ['name' => $testimonial['name'], 'business_name' => $testimonial['business_name']],
                $testimonial,
            );
        }
    }
}
