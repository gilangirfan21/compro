<?php

namespace Database\Seeders;

use App\Enums\PostStatus;
use App\Models\Message;
use App\Models\Portfolio;
use App\Models\Post;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Database\Seeder;

/**
 * Dummy company profile content for local testing.
 *
 * Safe to run more than once: each table is only seeded while it is still empty.
 */
class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSetting();
        $this->seedServices();
        $this->seedPortfolios();
        $this->seedPosts();
        $this->seedMessages();
    }

    protected function seedSetting(): void
    {
        if (Setting::query()->exists()) {
            $this->command?->info('Settings already exist, skipped.');

            return;
        }

        Setting::create([
            'company_name' => 'PT Nusantara Digital Solusi',
            'tagline' => 'Solusi digital yang tumbuh bersama bisnis Anda',
            'about' => "PT Nusantara Digital Solusi adalah perusahaan teknologi yang berdiri sejak 2015 dan berbasis di Jakarta. Kami membantu perusahaan dari berbagai skala, mulai dari UMKM hingga korporasi, untuk bertransformasi secara digital.\n\nDengan tim yang terdiri dari pengembang, desainer, dan konsultan berpengalaman, kami telah menyelesaikan lebih dari 150 proyek untuk klien di seluruh Indonesia.",
            'vision' => 'Menjadi mitra transformasi digital terpercaya bagi bisnis di Indonesia.',
            'mission' => "Menghadirkan solusi teknologi yang tepat guna dan mudah digunakan\nMembangun hubungan jangka panjang dengan klien berdasarkan kepercayaan\nTerus berinovasi dan mengembangkan kompetensi tim\nMemberikan layanan purna jual yang responsif",
            'address' => "Jl. Jenderal Sudirman No. 123\nJakarta Pusat, DKI Jakarta 10220",
            'phone' => '(021) 555-0123',
            'email' => 'info@nusantaradigital.test',
            'social_links' => [
                ['platform' => 'instagram', 'url' => 'https://instagram.com/nusantaradigital'],
                ['platform' => 'linkedin', 'url' => 'https://linkedin.com/company/nusantaradigital'],
                ['platform' => 'youtube', 'url' => 'https://youtube.com/@nusantaradigital'],
            ],
        ]);
    }

    protected function seedServices(): void
    {
        if (Service::query()->exists()) {
            $this->command?->info('Services already exist, skipped.');

            return;
        }

        $services = [
            ['Pengembangan Website', 'Website company profile, toko online, dan portal yang cepat, aman, dan mudah dikelola sendiri.'],
            ['Aplikasi Mobile', 'Aplikasi Android dan iOS yang dirancang sesuai kebutuhan bisnis dan nyaman digunakan pelanggan.'],
            ['Sistem Informasi', 'Sistem internal seperti ERP, HRIS, dan inventori untuk mengotomatiskan proses bisnis Anda.'],
            ['Desain UI/UX', 'Riset pengguna dan desain antarmuka yang membuat produk digital Anda mudah dan menyenangkan dipakai.'],
            ['Digital Marketing', 'Strategi SEO, iklan digital, dan pengelolaan media sosial untuk menjangkau lebih banyak pelanggan.'],
            ['Konsultasi IT', 'Pendampingan dalam memilih teknologi, infrastruktur cloud, dan keamanan sistem yang tepat.'],
        ];

        foreach ($services as $index => [$title, $description]) {
            Service::create([
                'title' => $title,
                'description' => $description,
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);
        }

        Service::create([
            'title' => 'Pelatihan Internal (nonaktif)',
            'description' => 'Contoh layanan nonaktif, tidak tampil di website.',
            'sort_order' => 99,
            'is_active' => false,
        ]);
    }

    protected function seedPortfolios(): void
    {
        if (Portfolio::query()->exists()) {
            $this->command?->info('Portfolios already exist, skipped.');

            return;
        }

        $portfolios = [
            ['Website Rumah Sakit Sehat Sentosa', 'Website', 'Website informasi rumah sakit dengan jadwal dokter dan pendaftaran online.'],
            ['Aplikasi Kasir Warung Kopi Senja', 'Aplikasi Mobile', 'Aplikasi kasir berbasis Android dengan laporan penjualan harian.'],
            ['Sistem Inventori PT Logistik Prima', 'Sistem Informasi', 'Sistem pengelolaan stok multi-gudang dengan pemindaian barcode.'],
            ['Rebranding Batik Lestari', 'Branding', 'Pembaruan identitas visual, logo, dan panduan merek untuk brand batik lokal.'],
            ['Toko Online Sayur Segar', 'Website', 'E-commerce sayur dan buah dengan integrasi pembayaran dan pengiriman.'],
            ['Aplikasi Absensi Karyawan', 'Aplikasi Mobile', 'Absensi berbasis lokasi dan swafoto untuk karyawan lapangan.'],
            ['Portal Akademik SMA Harapan Bangsa', 'Sistem Informasi', 'Portal nilai, jadwal, dan pengumuman untuk siswa, guru, dan orang tua.'],
            ['Identitas Visual Kafe Teduh', 'Branding', 'Desain logo, menu, dan materi promosi untuk kafe di Bandung.'],
        ];

        foreach ($portfolios as $index => [$title, $category, $summary]) {
            Portfolio::create([
                'title' => $title,
                'category' => $category,
                'description' => "{$summary}\n\nProyek ini dikerjakan dalam beberapa tahap, mulai dari analisis kebutuhan, perancangan, pengembangan, hingga pengujian bersama klien. Setelah diluncurkan, kami tetap memberikan pendampingan dan pemeliharaan agar sistem berjalan optimal.",
                'date' => now()->subMonths($index * 2 + 1)->startOfMonth(),
                'is_active' => true,
            ]);
        }
    }

    protected function seedPosts(): void
    {
        if (Post::query()->exists()) {
            $this->command?->info('Posts already exist, skipped.');

            return;
        }

        $posts = [
            '5 Alasan Bisnis Anda Membutuhkan Website di Tahun Ini',
            'Mengenal Perbedaan UI dan UX dalam Desain Produk Digital',
            'Tips Memilih Vendor Pengembangan Aplikasi yang Tepat',
            'Nusantara Digital Raih Penghargaan Startup Teknologi Terbaik',
            'Pentingnya Keamanan Data untuk Usaha Kecil dan Menengah',
            'Cara Meningkatkan Penjualan Lewat Media Sosial',
            'Transformasi Digital: Dari Mana Harus Memulai?',
        ];

        foreach ($posts as $index => $title) {
            Post::create([
                'title' => $title,
                'content' => $this->postContent($title),
                'status' => PostStatus::Published,
                'published_at' => now()->subDays($index * 9 + 1)->setTime(9, 0),
            ]);
        }

        Post::create([
            'title' => 'Draft: Rencana Acara Ulang Tahun Perusahaan',
            'content' => '<p>Contoh berita berstatus draft, tidak tampil di website.</p>',
            'status' => PostStatus::Draft,
        ]);

        Post::create([
            'title' => 'Pengumuman Libur Akhir Tahun',
            'content' => '<p>Contoh berita terjadwal, baru tampil saat tanggal terbitnya tiba.</p>',
            'status' => PostStatus::Published,
            'published_at' => now()->addWeek(),
        ]);
    }

    protected function postContent(string $title): string
    {
        return <<<HTML
            <p>{$title} menjadi topik yang sering ditanyakan oleh klien kami. Dalam artikel ini, kami merangkum beberapa hal penting yang perlu Anda ketahui.</p>
            <h2>Latar Belakang</h2>
            <p>Perkembangan teknologi yang pesat membuat pelaku usaha perlu beradaptasi lebih cepat. Pelanggan kini mencari informasi secara online sebelum memutuskan untuk membeli produk atau menggunakan jasa.</p>
            <h2>Poin Penting</h2>
            <ul>
                <li>Tentukan tujuan yang jelas dan terukur.</li>
                <li>Pahami kebutuhan dan kebiasaan pelanggan Anda.</li>
                <li>Mulai dari langkah kecil, lalu evaluasi secara berkala.</li>
            </ul>
            <blockquote>Teknologi yang baik adalah teknologi yang membantu, bukan membebani.</blockquote>
            <p>Jika Anda ingin berdiskusi lebih lanjut, jangan ragu untuk menghubungi tim kami melalui halaman kontak.</p>
            HTML;
    }

    protected function seedMessages(): void
    {
        if (Message::query()->exists()) {
            $this->command?->info('Messages already exist, skipped.');

            return;
        }

        Message::factory()->count(3)->create();
        Message::factory()->count(5)->read()->create();
    }
}
