# install.ps1
Write-Host "=== Laravel CMS Installer ===" -ForegroundColor Green

# Masuk ke folder source
Set-Location -Path ".\source"

# Jalankan composer install di window baru
Write-Host "Menjalankan 'composer install' di jendela baru..."
Start-Process powershell -ArgumentList '-NoExit', '-Command', 'cd .\source; composer install'

# Tunggu pengguna tekan Enter untuk lanjut
Read-Host "`nTekan ENTER jika 'composer install' telah selesai..."

# Jalankan npm install
Write-Host "`nMenjalankan 'npm install'..."
npm install

# Copy .env
Write-Host "`nMenyalin file .env.example ke .env..."
Copy-Item ".env.example" ".env" -Force

# Generate app key
Write-Host "`nMenjalankan 'php artisan key:generate'..."
php artisan key:generate

# Buat folder dan file SQLite jika belum ada
Write-Host "`nMemastikan database.sqlite tersedia..."
if (!(Test-Path -Path ".\database")) {
    New-Item -ItemType Directory -Path ".\database" | Out-Null
}

$sqlitePath = ".\database\database.sqlite"
if (!(Test-Path -Path $sqlitePath)) {
    New-Item -ItemType File -Path $sqlitePath | Out-Null
    Write-Host "File database.sqlite berhasil dibuat."
} else {
    Write-Host "File database.sqlite sudah ada."
}

# Kembali ke root dan ubah nama folder source
Set-Location -Path ".."
$folderName = Read-Host "`nMasukkan nama folder baru untuk project Anda (tanpa spasi atau karakter khusus)"

# Validasi nama folder
if ($folderName -match '^[a-zA-Z0-9-_]+$') {
    Rename-Item -Path "source" -NewName $folderName
    Write-Host "`nFolder 'source' berhasil diubah menjadi '$folderName'"
} else {
    Write-Host "Nama folder tidak valid. Gunakan hanya huruf, angka, dash (-) atau underscore (_)." -ForegroundColor Red
    Exit
}

# Instruksi akhir
Write-Host "`n=== INSTRUKSI MANUAL ==="
Write-Host "1. Salin folder '$folderName' ke direktori web server Anda:"
Write-Host "   - C:\laragon\www\  (untuk Laragon)"
Write-Host "   - C:\xampp\htdocs\ (untuk XAMPP)"
Write-Host "2. Akses lewat browser: http://$folderName.test"
Write-Host "3. Selesai."
Pause
