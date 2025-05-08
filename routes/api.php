<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ArtikelController;
use App\Http\Controllers\Api\GaleriController;
use App\Http\Controllers\Api\LowonganController;
use App\Http\Controllers\Api\ProdukController;
use App\Http\Controllers\Api\ProfilPerusahaanController;
use App\Http\Controllers\Api\FeatureToggleController;

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


// Profil Perusahaan
Route::prefix('profil-perusahaan')->group(function () {

    // Untuk mengambil semua proful perusahaan
    Route::get('/', [ProfilPerusahaanController::class, 'index']);
});

// Produk
Route::prefix('produk')->group(function () {

    // Untuk mengambil semua produk
    Route::get('/', [ProdukController::class, 'index']);

    // untuk mengambil artikel berdasarkan id
    Route::get('/id/{id}', [ProdukController::class, 'getProdukById']);

    // untuk mengambil artikel berdasarkan slug
    Route::get('/{slug}', [ProdukController::class, 'getProdukBySlug']);

});



// lowongan
Route::prefix('lowongan')->group(function () {

    // Untuk mengambil semua lowongan
    Route::get('/', [LowonganController::class, 'index']);

    // untuk mengambil lowongan terbaru
    Route::get('/newest', [LowonganController::class, 'getMostRecentLowongan']);

});

