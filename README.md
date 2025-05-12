<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# CMS Perusahaan

## Tentang Aplikasi

Seiring dengan kemajuan teknologi, kehadiran website perusahaan menjadi elemen penting untuk meningkatkan eksposur, membangun brand, dan mempermudah komunikasi antara perusahaan dengan pelanggan, mitra, dan bahkan calon karyawan. Aplikasi Content Management System (CMS) untuk perusahaan memungkinkan pengelolaan konten secara efisien dan fleksibel tanpa memerlukan pengetahuan teknis yang mendalam.

### Fitur Utama

-   Slider
-   Profil Perusahaan
-   Artikel
-   Galeri
-   Event
-   Download
-   Struktur Organisasi
-   Produk
-   Testimoni
-   Mitra
-   Magang
-   Web Installer (seperti WordPress)

### Web Installer

Aplikasi ini dilengkapi dengan web installer seperti WordPress yang memudahkan proses instalasi tanpa memerlukan pengetahuan teknis yang mendalam. Installer akan memandu Anda melalui langkah-langkah berikut:

1. **Pengecekan Persyaratan Server** - Memastikan server memenuhi semua persyaratan teknis
2. **Verifikasi Izin File** - Memeriksa bahwa izin file dan direktori sudah benar
3. **Konfigurasi Database** - Memandu Anda mengatur koneksi database
4. **Migrasi Database** - Membuat struktur tabel secara otomatis
5. **Pembuatan Akun Admin** - Membuat user admin pertama
6. **Profil Perusahaan** - Mengatur data dasar perusahaan

#### Langkah Instalasi

1. Upload aplikasi ke server atau jalankan di lingkungan local
2. Buka aplikasi melalui browser (http://domain-anda.com atau http://localhost/PBL-CMS-COMPANY)
3. Jika dependensi belum terinstal, ikuti panduan untuk menginstal:
    ```
    composer install
    ```
4. Setelah dependensi terinstal, Anda akan diarahkan ke halaman instalasi web
5. Ikuti langkah-langkah yang ditampilkan pada installer untuk:
    - Memverifikasi persyaratan sistem
    - Mengatur koneksi database
    - Membuat tabel-tabel yang diperlukan
    - Membuat akun admin
    - Mengatur data dasar perusahaan
6. Setelah proses instalasi selesai, Anda dapat login ke panel admin

#### Persyaratan Sistem

-   PHP 8.1 atau lebih tinggi
-   MySQL 5.7+ atau MariaDB 10.3+
-   Ekstensi PHP: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML
-   Composer
-   Apache/Nginx dengan mod_rewrite atau konfigurasi setara

#### Troubleshooting

Jika Anda mengalami masalah selama instalasi:

1. **Koneksi Database Gagal** - Pastikan username dan password database benar, dan database sudah dibuat
2. **Error Vendor/autoload.php** - Jalankan `composer install` dari direktori aplikasi
3. **Error No Application Encryption Key** - Akses file `generate-key.php` melalui browser
4. **Permissions Error** - Pastikan folder `storage` dan `bootstrap/cache` writable
   npm install
   npm run build
    ```

    ```
5. Akses URL aplikasi melalui browser
6. Ikuti langkah-langkah instalasi melalui antarmuka web:
    - Cek persyaratan server
    - Cek izin direktori
    - Konfigurasi lingkungan dan database (cukup masukkan informasi database)
    - Migrasi database akan dijalankan otomatis
    - Buat akun administrator
    - Isi profil perusahaan (nama dan logo)
    - Selesai!

### Penanganan Error

#### Error "Failed to open stream: vendor/autoload.php"

Jika Anda mengalami error ini:

```
Warning: require(../vendor/autoload.php): Failed to open stream: No such file or directory
```

Ini berarti dependencies Composer belum diinstall. Aplikasi akan otomatis menampilkan halaman panduan untuk menginstall dependencies. Ikuti langkah-langkah berikut:

1. Pastikan Composer sudah terinstall di sistem Anda
2. Jalankan perintah berikut di direktori utama aplikasi:
    ```
    composer install
    ```
3. Setelah selesai, refresh halaman untuk melanjutkan instalasi

#### Error "No application encryption key has been specified"

Jika Anda mengalami error ini setelah melakukan `composer install`:

```
production.ERROR: No application encryption key has been specified.
```

Ini berarti kunci enkripsi aplikasi Laravel belum di-generate. Ikuti langkah-langkah berikut:

1. Jalankan perintah berikut di direktori utama aplikasi:
    ```
    php artisan key:generate --force
    ```
2. Atau akses `public/generate-key.php` melalui browser untuk membuat kunci secara otomatis
3. Setelah kunci dibuat, refresh halaman untuk melanjutkan instalasi

-   Feedback & Saran
-   Link Media Sosial
-   Manajemen Konten

### Tujuan

Tujuan utama dari pengembangan aplikasi CMS untuk perusahaan adalah:

-   Menyederhanakan pengelolaan konten website perusahaan
-   Memberikan kemudahan dalam pembaruan informasi
-   Meningkatkan interaksi dengan pelanggan dan karyawan
-   Memperkenalkan produk dan layanan secara efektif
-   Meningkatkan engagement dengan audiens

## Teknologi

Aplikasi ini dibangun menggunakan Laravel Framework dengan beberapa keunggulan:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

## Dokumentasi

Untuk mempelajari lebih lanjut tentang Laravel dan filament, silakan kunjungi:

-   [Laravel Documentation](https://laravel.com/docs)
-   [Laravel Bootcamp](https://bootcamp.laravel.com)
-   [Laracasts](https://laracasts.com)
-   [Filament Documentation](https://filamentphp.com/docs)
-   [Filament Blog](https://filamentphp.com/blog)
-   [Filament Community](https://github.com/filamentphp/filament/discussions)

## Keamanan

Jika Anda menemukan celah keamanan dalam aplikasi, silakan kirim email ke [irzasoul12@gmail.com](mailto:irzasoul12@gmail.com).

## Lisensi

Aplikasi ini dilisensikan di bawah [MIT license](https://opensource.org/licenses/MIT).
