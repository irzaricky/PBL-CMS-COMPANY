<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Artikel
Route::prefix('artikel')->group(function () {

    // Untuk mengambil semua artikel
    Route::get('/', [ArticleController::class, 'index']);

    // Untuk mengambil semua kategori artikel
    Route::get('/categories', [ArticleController::class, 'getCategories']);

    // untuk search artikel berdasarkan judul atau isi artikel
    Route::get('/search', [ArticleController::class, 'search']);

    // untuk mengambil artikel berdasarkan id
    Route::get('/id/{id}', [ArticleController::class, 'getArticleById']);

    // untuk mengambil artikel berdasarkan slug
    Route::get('/{slug}', [ArticleController::class, 'getArticleBySlug']);


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

