<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ArtikelController;
use App\Http\Controllers\Api\ProfilPerusahaanController;

// Artikel
Route::prefix('artikel')->group(function () {

    // Untuk mengambil semua artikel
    Route::get('/', [ArtikelController::class, 'index']);

    // Untuk mengambil semua kategori artikel
    Route::get('/categories', [ArtikelController::class, 'getCategories']);

    // untuk search artikel berdasarkan judul atau isi artikel
    Route::get('/search', [ArtikelController::class, 'search']);

    // untuk mengambil artikel berdasarkan id
    Route::get('/id/{id}', [ArtikelController::class, 'getArticleById']);

    // untuk mengambil artikel berdasarkan slug
    Route::get('/{slug}', [ArtikelController::class, 'getArticleBySlug']);


});



// Event
Route::prefix('events')->group(function () {

    // Untuk mengambil semua event
    Route::get('/', [EventController::class, 'index']);

    // untuk mengambil event berdasarkan id
    Route::get('/id/{id}', [EventController::class, 'getEventById']);

    // Untuk mengambil event berdasarkan slug
    Route::get('/{slug}', [EventController::class, 'getEventBySlug']);
});

// Profil Perusahaan
Route::prefix('profil-perusahaan')->group(function () {
    Route::get('/', [ProfilPerusahaanController::class, 'index']);
});