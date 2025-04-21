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