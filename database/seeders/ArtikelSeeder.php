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
                'konten_artikel' => '<h2>Transformasi Digital dengan Kecerdasan Buatan</h2>
                <p>Lorem ipsum dolor sit amet, <strong>consectetur adipiscing elit</strong>. Nullam auctor, nisl nec ultricies lacinia, nisl nisl aliquet nisl, eget aliquet nisl nisl eget nisl. Perkembangan teknologi AI telah membawa perubahan signifikan dalam dunia bisnis.</p>
                
                <p>Beberapa manfaat AI dalam bisnis:</p>
                <ul>
                    <li>Otomatisasi proses repetitif</li>
                    <li>Analisis data yang lebih cepat dan akurat</li>
                    <li>Personalisasi pengalaman pelanggan</li>
                </ul>
                
                <h3>Implementasi AI di Berbagai Sektor</h3>
                <p>Menurut riset terbaru, <em>implementasi AI dapat meningkatkan produktivitas hingga 40%</em> di berbagai sektor industri. Nullam auctor, nisl nec ultricies lacinia, nisl nisl aliquet nisl, eget aliquet nisl nisl eget nisl.</p>
                
                <blockquote>
                    <p>"AI bukan hanya tentang teknologi, tapi tentang bagaimana kita menggunakannya untuk menyelesaikan masalah nyata dalam bisnis." - John Doe, AI Specialist</p>
                </blockquote>',
                'slug' => 'penerapan-ai-dalam-bisnis-modern',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_artikel' => 2,
                'id_kategori_artikel' => 2,
                'id_user' => 2,
                'judul_artikel' => 'Strategi Marketing Digital 2025',
                'konten_artikel' => '<h2>Evolusi Marketing di Era Digital</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. <mark>Perubahan perilaku konsumen</mark> menjadi tantangan sekaligus peluang bagi marketer di tahun 2025.</p>
                
                <h3>Tren Marketing Digital 2025</h3>
                <ol>
                    <li><strong>Video Pendek</strong> - Platform seperti TikTok dan Instagram Reels mendominasi perhatian audiens</li>
                    <li><strong>AI-Driven Marketing</strong> - Personalisasi konten berdasarkan analisis perilaku</li>
                    <li><strong>Voice Search Optimization</strong> - Optimasi untuk pencarian suara</li>
                </ol>
                
                <p>Menurut survei terbaru:</p>
                <table border="1">
                    <thead>
                        <tr>
                            <th>Platform</th>
                            <th>Persentase Pengguna</th>
                            <th>ROI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Video Marketing</td>
                            <td>78%</td>
                            <td>Tinggi</td>
                        </tr>
                        <tr>
                            <td>Email Marketing</td>
                            <td>65%</td>
                            <td>Sedang</td>
                        </tr>
                    </tbody>
                </table>
                
                <p><em>Strategi yang tepat akan menentukan keberhasilan kampanye marketing Anda</em> di tengah persaingan yang semakin ketat.</p>',
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