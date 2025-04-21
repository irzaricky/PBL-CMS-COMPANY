<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Articles
Route::prefix('artikel')->group(function () {

    // Untuk mengambil semua artikel
    Route::get('/', [ArticleController::class, 'index']);

    // untuk mengambil artikel berdasarkan slug
    Route::get('/{slug}', [ArticleController::class, 'getArticleBySlug']);

    // untuk mengambil artikel berdasarkan id
    Route::get('/id/{id}', [ArticleController::class, 'getArticleById']);
});

// Events
Route::prefix('events')->group(function () {

    // Untuk mengambil semua event
    Route::get('/', [EventController::class, 'index']);

    // Untuk mengambil event berdasarkan id
    Route::get('/{id}', [EventController::class, 'view']);
});