<?php

use App\Http\Controllers\Api\ArticleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Article routes
Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/category/{categoryId}', [ArticleController::class, 'getByCategory']);
Route::get('/articles/{id}', [ArticleController::class, 'show']);