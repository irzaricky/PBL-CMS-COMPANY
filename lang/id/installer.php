<?php

/**
 * File Bahasa Indonesia untuk Proses Instalasi CMS.
 * Dikelompokkan berdasarkan halaman, komponen, dan jenis pesan.
 */

return [
    //======================================================================
    // 1. Teks Umum & Navigasi
    //======================================================================

    'install_title' => 'Instalasi CMS Perusahaan',
    'language' => 'Bahasa',
    'select_language' => 'Pilih Bahasa',
    'english' => 'English',
    'indonesian' => 'Bahasa Indonesia',

    // Tombol Aksi
    'back' => 'Kembali',
    'next' => 'Lanjut',
    'continue' => 'Lanjutkan',
    'save' => 'Simpan',
    'finish' => 'Selesai',
    'skip' => 'Lewati',
    'install' => 'Install',
    'test_connection' => 'Tes Koneksi',
    'test_email' => 'Tes Email',
    'create_account' => 'Buat Akun',
    'finalize_installation' => 'Finalisasi Instalasi',

    // Status & Label Umum
    'enable' => 'Aktifkan',
    'disable' => 'Nonaktifkan',
    'version' => 'Versi',
    'required' => 'Diperlukan',
    'supported' => 'Didukung',
    'not_supported' => 'Tidak Didukung',
    'writable' => 'Dapat Dikelola', // Atau 'Dapat Ditulis'
    'not_writable' => 'Tidak Dapat Dikelola', // Atau 'Tidak Dapat Ditulis'


    //======================================================================
    // 2. Langkah-langkah Instalasi (Steps)
    //======================================================================

    'step_welcome' => 'Selamat Datang',
    'step_requirements' => 'Persyaratan',
    'step_database' => 'Database',
    'step_company' => 'Perusahaan',
    'step_admin' => 'Admin',
    'step_roles' => 'Peran',
    'step_features' => 'Fitur',
    'step_finish' => 'Selesai',


    //======================================================================
    // 3. Halaman Instalasi
    //======================================================================

    // Halaman: Welcome
    'welcome_title' => 'Selamat Datang di Instalasi CMS Company',
    'welcome_subtitle' => 'Terima kasih telah memilih solusi CMS kami. Installer ini akan memandu Anda melalui proses pengaturan.',
    'installation_process' => 'Proses Instalasi',
    'get_started' => 'Mulai',
    'installation_time' => 'Waktu Instalasi',
    'installation_time_description' => 'Proses ini biasanya memakan waktu 5-10 menit untuk diselesaikan.',
    'preparation_note' => 'Pastikan Anda telah menyiapkan kredensial database dan informasi perusahaan.',

    // Halaman: Requirements
    'requirements_title' => 'Persyaratan Sistem & Izin',
    'requirements_subtitle' => 'Pastikan server Anda memenuhi semua persyaratan dan memiliki izin yang tepat.',
    'server_requirements' => 'Versi Minimal',
    'software_type' => 'Software',
    'php_extensions' => 'Ekstensi',
    'folder_permissions' => 'Izin Folder',
    'folder' => 'Folder',

    // Halaman: Database & Email
    'database_title' => 'Konfigurasi Database & Email',
    'database_configuration' => 'Konfigurasi Database',
    'database_connection' => 'Koneksi Database',
    'database_connection_type' => 'Tipe Koneksi Database',
    'database_host' => 'Host Database',
    'database_port' => 'Port Database',
    'database_name' => 'Nama Database',
    'database_username' => 'Username Database',
    'database_password' => 'Password Database',
    'database_user' => 'Pengguna Database',
    'sqlite_help_text' => 'File SQLite akan otomatis dibuat di direktori storage/',
    'email_configuration' => 'Konfigurasi Email',
    'mail_driver' => 'Driver Email',
    'mail_host' => 'Host Email',
    'mail_port' => 'Port Email',
    'mail_username' => 'Username Email',
    'mail_password' => 'Password Email',
    'mail_encryption' => 'Enkripsi Email',
    'mail_from_address' => 'Alamat Email Pengirim',
    'mail_from_name' => 'Nama Pengirim Email',
    'mail_from_name_description' => 'Nama pengirim email akan otomatis disetel ke nama perusahaan Anda',
    'app_debug' => 'App Debug',
    'app_log_level' => 'Level Log Aplikasi',
    'app_url' => 'URL Aplikasi',
    'app_timezone' => 'Zona Waktu',
    'app_locale' => 'Bahasa Aplikasi',
    'example' => 'contoh',

    // Halaman: Company Profile
    'company_title' => 'Konfigurasi Profil Perusahaan',
    'company_name' => 'Nama Perusahaan',
    'company_email' => 'Email Perusahaan',
    'company_address' => 'Alamat Perusahaan',
    'company_description' => 'Deskripsi Perusahaan',
    'company_location_link' => 'Link Lokasi Perusahaan',
    'company_logo' => 'Logo Perusahaan',
    'logo_requirements' => 'Persyaratan logo: Format PNG, JPG, atau JPEG, maksimal 2MB',

    // Halaman: Super Admin
    'super_admin_title' => 'Konfigurasi Super Admin',
    'super_admin_configuration' => 'Konfigurasi Akun Super Admin',
    'full_name' => 'Nama Lengkap',
    'email' => 'Email',
    'password' => 'Password',
    'password_confirmation' => 'Konfirmasi Password',
    'password_description' => 'Password harus minimal 8 karakter',
    'include_dummy_data' => 'Isi dengan Data Sample',
    'dummy_data_description' => 'Centang opsi ini jika Anda ingin mengisi database dengan data contoh untuk keperluan testing atau demo. Data Sample termasuk user, artikel, produk, galeri, dan konten lainnya.',

    // Halaman: User Roles
    'roles_title' => 'Peran Pengguna & Izin',
    'user_roles_list' => 'Daftar User dengan Role',
    'name' => 'Nama',
    'role' => 'Role',
    'no_role' => 'Tidak Ada Role',
    'super_admin_created' => 'Akun Super Admin berhasil dibuat! Anda bisa login menggunakan email:',
    'dummy_password_info' => 'Password default untuk akun Sample adalah',
    'continue_to_features' => 'Lanjut ke Konfigurasi Fitur',

    // Halaman: Features
    'features_title' => 'Konfigurasi Fitur',
    'features_subtitle' => 'Pilih fitur yang akan diaktifkan dan ditampilkan pada frontend website.',
    'feature_status' => 'Status',

    // Halaman: Finish
    'finish_title' => 'Instalasi Selesai!',
    'finish_subtitle' => 'Selamat! CMS Company Anda telah berhasil diinstall.',
    'next_steps' => 'Langkah Selanjutnya',
    'next_steps_description' => 'Klik tombol di bawah untuk menyelesaikan instalasi dan mulai menggunakan CMS Anda.',
    'support' => 'Dapatkan Dukungan',
    'database_configured' => 'Database Terkonfigurasi',
    'database_ready' => 'Koneksi database telah terhubung dan siap',
    'admin_created' => 'Akun Admin Dibuat',
    'admin_ready' => 'Akun super admin telah diatur',
    'system_configured' => 'Sistem Terkonfigurasi',
    'system_ready' => 'Semua fitur sistem telah dikonfigurasi',

    // Deskripsi Fitur pada Halaman Welcome (dipisah agar lebih rapi)
    'features' => [
        'requirements' => [
            'title' => 'Persyaratan Sistem',
            'description' => 'Periksa persyaratan server dan izin file'
        ],
        'database' => [
            'title' => 'Konfigurasi Database',
            'description' => 'Konfigurasi koneksi database dan pengaturan email'
        ],
        'company' => [
            'title' => 'Profil Perusahaan',
            'description' => 'Atur informasi dan branding perusahaan Anda'
        ],
        'admin' => [
            'title' => 'Akun Admin',
            'description' => 'Buat akun super administrator Anda'
        ],
        'roles' => [
            'title' => 'Peran Pengguna',
            'description' => 'Tinjau dan konfigurasi peran dan izin pengguna'
        ],
        'features' => [
            'title' => 'Konfigurasi Fitur',
            'description' => 'Aktifkan atau nonaktifkan fitur sistem'
        ],
        'complete' => [
            'title' => 'Instalasi Selesai',
            'description' => 'Finalisasi instalasi Anda dan mulai gunakan sistem'
        ]
    ],


    //======================================================================
    // 4. Pesan Sistem (Alerts, Messages, Feedback)
    //======================================================================

    // Pesan Status Umum
    'success' => 'Berhasil!',
    'error' => 'Error!',
    'warning' => 'Peringatan!',
    'info' => 'Informasi',
    'loading' => 'Memuat...',
    'please_wait' => 'Mohon tunggu...',
    'testing' => 'Mengetes...',
    'processing' => 'Memproses...',

    // Pesan Feedback Aksi Pengguna
    'company_profile_saved' => 'Profil perusahaan berhasil disimpan.',
    'company_profile_save_error' => 'Gagal menyimpan profil perusahaan.',
    'feature_toggles_saved' => 'Pengaturan fitur berhasil disimpan.',
    'feature_toggles_save_error' => 'Gagal menyimpan pengaturan fitur.',

    // Pesan Koneksi & AJAX
    'connection_error' => 'Error Koneksi!',
    'could_not_test_database' => 'Tidak dapat menguji koneksi database.',
    'email_test_success' => 'Tes Email Berhasil!',
    'email_test_failed' => 'Tes Email Gagal!',
    'email_test_error' => 'Error Tes Email!',
    'could_not_test_email' => 'Tidak dapat menguji konfigurasi email.',
    'server_communication_error' => 'Terjadi kesalahan saat berkomunikasi dengan server. Silakan periksa koneosi Anda dan coba lagi.',
    'database_connection_error' => 'Kesalahan Koneksi Database!',
    'please_make_sure' => 'Pastikan hal berikut',
    'database_server_running' => 'Server database Anda berjalan',
    'database_exists' => 'Database sudah ada',
    'credentials_correct' => 'Username dan password Anda benar',
    'user_has_permissions' => 'Pengguna memiliki izin yang tepat pada database',

    // Pesan Khusus (Logika Super Admin)
    'super_admin_exists' => 'Super Admin dengan email :email sudah ada dan memiliki akses super admin. Anda dapat melanjutkan.',
    'user_exists_role_assigned' => 'User dengan email :email sudah ada. Role Super Admin telah diberikan pada akun tersebut.',
    'super_admin_created_msg' => 'Super Admin berhasil dibuat.',
    'failed_assign_role' => 'Gagal memberikan role Super Admin.',

];