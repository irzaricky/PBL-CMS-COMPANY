<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Artikel
Route::prefix('artikel')->group(function () {

    // Untuk mengambil semua artikel
    Route::get('/', [ArticleController::class, 'index']);

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