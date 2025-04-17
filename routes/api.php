<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\GaleriController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\LowonganController;
use App\Http\Controllers\Api\MedsosController;
use App\Http\Controllers\Api\MitraController;
use App\Http\Controllers\Api\ProdukController;
use App\Http\Controllers\Api\ProfilController;
use App\Http\Controllers\Api\StrukturController;
use App\Http\Controllers\Api\TestimoniController;
use App\Http\Controllers\Api\UnduhanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Articles
Route::prefix('articles')->group(function () {

    // Untuk mengambil semua artikel
    Route::get('/', [ArticleController::class, 'index']);

    // untuk mengambil artikel berdasarkan id
    Route::get('/{id}', [ArticleController::class, 'view']);
});

// Events
Route::prefix('events')->group(function () {

    // Untuk mengambil semua event
    Route::get('/', [EventController::class, 'index']);

    // Untuk mengambil event berdasarkan id
    Route::get('/{id}', [EventController::class, 'view']);
});

// feedback
Route::prefix('feedback')->group(function () {
    Route::get('/', [FeedbackController::class, 'index']);
    Route::get('/{id}', [FeedbackController::class, 'view']);
});

// Galeri
Route::prefix('galeri')->group(function () {
    Route::get('/', [GaleriController::class, 'index']);
    Route::get('/{id}', [GaleriController::class, 'view']);
});

// Konten Slider
Route::prefix('slider')->group(function () {
    Route::get('/', [SliderController::class, 'index']);
});

// lowongan
Route::prefix('lowongan')->group(function () {
    Route::get('/', [LowonganController::class, 'index']);
    Route::get('/{id}', [LowonganController::class, 'view']);
});

// media sosial
Route::prefix('medsos')->group(function () {
    Route::get('/', [MedsosController::class, 'index']);
    Route::get('/{id}', [MedsosController::class, 'view']);
});

// mitra
Route::prefix('mitra')->group(function () {
    Route::get('/', [MitraController::class, 'index']);
    Route::get('/{id}', [MitraController::class, 'view']);
});

// produk
Route::prefix('produk')->group(function () {
    Route::get('/', [ProdukController::class, 'index']);
    Route::get('/{id}', [ProdukController::class, 'view']);
});

// profil perusahaan
Route::prefix('profil')->group(function () {
    Route::get('/', [ProfilController::class, 'index']);
    Route::get('/{id}', [ProfilController::class, 'view']);
});

// struktur organisasi
Route::prefix('struktur')->group(function () {
    Route::get('/', [StrukturController::class, 'index']);
    Route::get('/{id}', [StrukturController::class, 'view']);
});

// testimoni
Route::prefix('testimoni')->group(function () {
    Route::get('/', [TestimoniController::class, 'index']);
    Route::get('/{id}', [TestimoniController::class, 'view']);
});

// unduhan
Route::prefix('unduhan')->group(function () {
    Route::get('/', [UnduhanController::class, 'index']);
    Route::get('/{id}', [UnduhanController::class, 'view']);
});