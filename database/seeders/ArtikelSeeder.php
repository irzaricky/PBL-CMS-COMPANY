<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArtikelSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('artikel')->insert([
            [
                'id_artikel' => 1,
                'id_kategori_artikel' => 1,
                'id_user' => 1,
                'judul_artikel' => 'Penerapan AI dalam Bisnis Modern',
                'konten_artikel' => '<h2>Transformasi Digital dengan Kecerdasan Buatan</h2><p>&nbsp;Di era digital saat ini, kecerdasan buatan (AI) menjadi motor utama dalam mendorong perubahan di berbagai sektor industri. Lebih dari sekadar teknologi mutakhir, AI kini hadir sebagai solusi nyata dalam menghadapi tantangan bisnis modern.&nbsp;</p><h3>AI: Mengubah Cara Bisnis Beroperasi</h3><p>Penerapan AI telah membuka jalan bagi transformasi operasional yang lebih efisien dan cerdas. Beberapa manfaat utama yang dirasakan pelaku bisnis antara lain:</p><ul><li><strong>Otomatisasi Tugas Repetitif</strong><br> AI memungkinkan perusahaan mengotomatiskan pekerjaan berulang, sehingga tim dapat fokus pada tugas-tugas strategis yang lebih bernilai.</li><li><strong>Analisis Data Cepat dan Akurat</strong><br> Dengan kemampuan memproses data dalam jumlah besar secara real-time, AI membantu pengambilan keputusan menjadi lebih cepat dan berbasis fakta.</li><li><strong>Personalisasi Pengalaman Pelanggan</strong><br> Dari rekomendasi produk hingga layanan pelanggan berbasis chatbot, AI memungkinkan pengalaman yang lebih relevan dan personal bagi konsumen.</li></ul><h3><h3>Dampak di Berbagai Sektor</h3></h3><p>Menurut riset terbaru, penerapan AI dapat meningkatkan produktivitas hingga 40% di sektor-sektor seperti manufaktur, layanan keuangan, kesehatan, hingga logistik. Hal ini menunjukkan bahwa AI bukan hanya relevan di dunia teknologi, tetapi juga menjadi kekuatan transformatif di bidang-bidang yang sebelumnya tidak terlalu terdigitalisasi.</p><blockquote>"AI bukan hanya tentang teknologi, tapi tentang bagaimana kita menggunakannya untuk menyelesaikan masalah nyata dalam bisnis."<br>&nbsp;— <em>John Doe, AI Specialist</em></blockquote><h3>Penutup</h3><p>Transformasi digital dengan kecerdasan buatan bukan lagi sebuah pilihan, melainkan keharusan bagi bisnis yang ingin tetap relevan dan kompetitif. Ke depan, kemampuan untuk beradaptasi dan memanfaatkan AI secara strategis akan menjadi salah satu kunci utama kesuksesan.</p>',
                'slug' => 'penerapan-ai-dalam-bisnis-modern',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id_artikel' => 2,
                'id_kategori_artikel' => 2,
                'id_user' => 2,
                'judul_artikel' => 'Strategi Marketing Digital 2025',
                'konten_artikel' => '<h2>Evolusi Marketing di Era Digital</h2><p>&nbsp;Perkembangan teknologi dan perubahan perilaku konsumen terus membentuk lanskap marketing digital. Di tahun 2025, para pemasar menghadapi tantangan baru sekaligus peluang besar untuk menjangkau audiens dengan cara yang lebih relevan dan efektif.&nbsp;</p><h3>Tren Marketing Digital 2025</h3><p>Berikut beberapa tren utama yang diprediksi akan mendominasi strategi pemasaran digital tahun ini:</p><ul><li><strong>Video Pendek</strong><br> Platform seperti TikTok dan Instagram Reels semakin mendominasi atensi audiens. Konten singkat, kreatif, dan to the point menjadi senjata utama dalam membangun engagement.</li><li><strong>AI-Driven Marketing</strong><br> Personalisasi konten berbasis kecerdasan buatan memungkinkan brand menyampaikan pesan yang lebih tepat sasaran dengan menganalisis perilaku pengguna secara real-time.</li><li><strong>Voice Search Optimization</strong><br> Semakin populernya asisten virtual seperti Google Assistant dan Alexa membuat optimasi untuk pencarian suara menjadi langkah penting dalam strategi SEO modern.</li></ul><h3>&nbsp;Data: Platform &amp; ROI&nbsp;</h3><p>Survei terbaru menunjukkan efektivitas berbagai platform marketing berdasarkan jumlah pengguna dan return on investment (ROI):</p><p>many data hehe</p><p>Data ini menegaskan pentingnya memilih platform yang sesuai dengan karakteristik target audiens dan tujuan kampanye.&nbsp;</p><h3>Penutup: Strategi Adalah Kunci</h3><p>Di tengah persaingan digital yang semakin ketat, keberhasilan kampanye tidak hanya ditentukan oleh tren, tapi juga oleh strategi yang tepat. Memahami audiens, memanfaatkan teknologi, dan konsisten dalam membangun brand menjadi faktor penentu dalam memenangkan hati konsumen di era digital.</p>',
                'slug' => 'strategi-marketing-digital-2025',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'id_artikel' => 3,
                'id_kategori_artikel' => 3,
                'id_user' => 1,
                'judul_artikel' => 'Tutorial Laravel untuk Pemula',
                'konten_artikel' => '<h2>Memulai dengan Framework Laravel</h2>
                <p>Laravel adalah <strong>framework PHP yang elegan dan ekspresif</strong> yang dapat mempermudah pengembangan web modern.</p>
                
                <h3>Prasyarat Belajar Laravel</h3>
                <ul>
                    <li>Pemahaman dasar PHP</li>
                    <li>Familiar dengan konsep OOP</li>
                    <li>Pengetahuan dasar tentang MVC</li>
                </ul>
                
                <h3>Instalasi Laravel</h3>
                <p>Untuk menginstal Laravel, Anda dapat menggunakan Composer:</p>
                
                <pre><code>composer create-project laravel/laravel example-app</code></pre>
                
                <p>Setelah instalasi selesai, masuk ke direktori project:</p>
                
                <pre><code>cd example-app
php artisan serve</code></pre>
                
                <div class="note">
                    <p><strong>Catatan:</strong> <em>Pastikan Anda memiliki PHP dan Composer terinstal di sistem Anda sebelum memulai.</em></p>
                </div>
                
                <h3>Struktur Direktori Laravel</h3>
                <p>Laravel memiliki struktur direktori yang terorganisir dengan baik:</p>
                
                <ul>
                    <li><strong>app/</strong> - Berisi kode utama aplikasi</li>
                    <li><strong>config/</strong> - Berisi file konfigurasi</li>
                    <li><strong>database/</strong> - Berisi migrasi dan seeder</li>
                    <li><strong>resources/</strong> - Berisi view, asset, dan file bahasa</li>
                    <li><strong>routes/</strong> - Berisi definisi rute</li>
                </ul>
                
                <p>Laravel menyediakan <a href="https://laravel.com/docs" target="_blank">dokumentasi yang lengkap</a> untuk membantu Anda memahami setiap komponen dengan lebih baik.</p>',
                'slug' => 'tutorial-laravel-untuk-pemula',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}