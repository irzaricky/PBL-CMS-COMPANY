<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\MitraController;
use App\Http\Controllers\Api\GaleriController;
use App\Http\Controllers\Api\ProdukController;
use App\Http\Controllers\Api\ArtikelController;
use App\Http\Controllers\Api\LamaranController;
use App\Http\Controllers\Api\UnduhanController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\LowonganController;
use App\Http\Controllers\Api\CaseStudyController;
use App\Http\Controllers\Api\TestimoniController;
use App\Http\Controllers\Api\MediaSosialController;
use App\Http\Controllers\Api\FeatureToggleController;
use App\Http\Controllers\Api\TestimoniProdukController;
use App\Http\Controllers\Api\ProfilPerusahaanController;
use App\Http\Controllers\Api\StrukturOrganisasiController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

// Post feedback (AUTENTIKASI BELUM DITAMBAHKAN)
Route::post('/feedback', [FeedbackController::class, 'store']);

// Lamaran routes (AUTENTIKASI BELUM DITAMBAHKAN)
Route::post('/lamaran', [LamaranController::class, 'store']);
Route::get('/lamaran/user/{userId}', [LamaranController::class, 'getByUserId']);


// Artikel
Route::prefix('artikel')->group(function () {

    // Untuk mengambil semua artikel
    Route::get('/', [ArtikelController::class, 'index']);

    // Untuk mengambil semua kategori artikel
    Route::get('/categories', [ArtikelController::class, 'getCategories']);

    // untuk search artikel berdasarkan judul atau isi artikel
    Route::get('/search', [ArtikelController::class, 'search']);

    // untuk mengambil artikel dengan view terbanyak
    Route::get('/most-viewed', [ArtikelController::class, 'getArticleByMostView']);

    // untuk mengambil artikel berdasarkan id
    Route::get('/id/{id}', [ArtikelController::class, 'getArticleById']);

    // untuk mengambil artikel berdasarkan slug
    Route::get('/{slug}', [ArtikelController::class, 'getArticleBySlug']);
});

// Event
Route::prefix('event')->group(function () {

    // Untuk mengambil semua event
    Route::get('/', [EventController::class, 'index']);

    // untuk mengambil event yang baru saja dibuat
    Route::get('/newest', [EventController::class, 'getMostRecentEvent']);

    // untuk search event berdasarkan nama atau lokasi
    Route::get('/search', [EventController::class, 'search']);

    // untuk mengambil event berdasarkan id
    Route::get('/id/{id}', [EventController::class, 'getEventById']);

    // Untuk mengambil event berdasarkan slug
    Route::get('/{slug}', [EventController::class, 'getEventBySlug']);
});

// Galeri
Route::prefix('galeri')->group(function () {

    // Untuk mengambil semua galeri
    Route::get('/', [GaleriController::class, 'index']);

    // Untuk mengambil semua kategori galeri
    Route::get('/categories', [GaleriController::class, 'getCategories']);

    // untuk search artikel berdasarkan judul atau isi galeri
    Route::get('/search', [GaleriController::class, 'search']);

    // Untuk mengunduh galeri dan menambah jumlah unduhan
    Route::get('/download/{id}', [GaleriController::class, 'downloadGaleri']);

    // untuk mengambil galeri berdasarkan id
    Route::get('/id/{id}', [GaleriController::class, 'getGaleriById']);

    // Untuk mengambil galeri berdasarkan slug
    Route::get('/{slug}', [GaleriController::class, 'getGaleriBySlug']);
});

Route::get('/feature-toggles', [FeatureToggleController::class, 'index']);

// Media Sosial
Route::get('/media-sosial', [MediaSosialController::class, 'index']);

// Testimoni
// Route::get('/testimoni', [TestimoniController::class, 'index']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/testimoni/produk/{produkId}', [TestimoniProdukController::class, 'store']);
});
Route::get('/testimoni/produk/{produkId}', [TestimoniProdukController::class, 'index']);


// Mitra
Route::prefix('mitra')->group(function () {
    // Untuk mengambil semua mitra yang aktif
    Route::get('/', [MitraController::class, 'index']);

    // Untuk search mitra berdasarkan nama
    Route::get('/search', [MitraController::class, 'search']);

    // Untuk mengambil mitra berdasarkan id
    Route::get('/{id}', [MitraController::class, 'getMitraById']);
});

// Struktur Organisasi
Route::get('/struktur-organisasi', [StrukturOrganisasiController::class, 'index']);

// Profil Perusahaan
Route::prefix('profil-perusahaan')->group(function () {

    // Untuk mengambil profil perusahaan
    Route::get('/', [ProfilPerusahaanController::class, 'index']);

    // Untuk mengambil profil perusahaan untuk navbar
    Route::get('/navbar', [ProfilPerusahaanController::class, 'getDataNavbar']);
});

// Produk
Route::prefix('produk')->group(function () {

    // Untuk mengambil semua produk
    Route::get('/', [ProdukController::class, 'index']);

    // untuk search produk berdasarkan nama atau deskripsi
    Route::get('/search', [ProdukController::class, 'search']);

    // untuk mengambil produk berdasarkan id
    Route::get('/id/{id}', [ProdukController::class, 'getProdukById']);

    // untuk mengambil produk berdasarkan slug
    Route::get('/{slug}', [ProdukController::class, 'getProdukBySlug']);
});



// lowongan
Route::prefix('lowongan')->group(function () {

    // Untuk mengambil semua lowongan
    Route::get('/', [LowonganController::class, 'index']);

    // untuk mengambil lowongan terbaru
    Route::get('/newest', [LowonganController::class, 'getMostRecentLowongan']);

    // untuk search lowongan
    Route::get('/search', [LowonganController::class, 'search']);

    // untuk mengambil lowongan berdasarkan id
    Route::get('/id/{id}', [LowonganController::class, 'getLowonganById']);

    // untuk mengambil lowongan berdasarkan slug
    Route::get('/{slug}', [LowonganController::class, 'getLowonganBySlug']);
});

// Case Study
Route::prefix('case-study')->group(function () {
    // Untuk mengambil semua case study (published)
    Route::get('/', [CaseStudyController::class, 'index']);

    // Untuk search case study
    Route::get('/search', [CaseStudyController::class, 'search']);

    // Untuk mengambil case study berdasarkan id
    Route::get('/id/{id}', [CaseStudyController::class, 'getCaseStudyById']);

    // Untuk mengambil case study berdasarkan slug (termasuk menambah view)
    Route::get('/{slug}', [CaseStudyController::class, 'getCaseStudyBySlug']);
});

// Unduhan
Route::prefix('unduhan')->group(function () {
    // Untuk mengambil semua unduhan yang terpublikasi
    Route::get('/', [UnduhanController::class, 'index']);

    // Untuk mengambil semua kategori unduhan
    Route::get('/categories', [UnduhanController::class, 'getCategories']);

    // Untuk search unduhan berdasarkan nama atau kategori
    Route::get('/search', [UnduhanController::class, 'search']);

    // Untuk mengambil unduhan dengan jumlah download terbanyak
    Route::get('/most-downloaded', [UnduhanController::class, 'getMostDownloaded']);

    // Untuk mengunduh unduhan dan menambah jumlah unduhan
    Route::get('/download/{id}', [UnduhanController::class, 'downloadUnduhan']);

    // Untuk mengambil unduhan berdasarkan id
    Route::get('/id/{id}', [UnduhanController::class, 'getUnduhanById']);

    // Untuk mengambil unduhan berdasarkan slug
    Route::get('/{slug}', [UnduhanController::class, 'getUnduhanBySlug']);
});
