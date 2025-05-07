<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::get('/example', function () {
    return Inertia::render('Example');
});

Route::prefix('artikel')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Artikel/ListView');
    })->name('artikel.list');

    Route::get('/{slug}', function ($slug) {
        return Inertia::render('Artikel/Show', ['slug' => $slug]);
    })->name('artikel.show');
});

Route::prefix('event')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Event/ListView');
    })->name('event.list');

    Route::get('/{slug}', function ($slug) {
        return Inertia::render('Event/Show', ['slug' => $slug]);
    })->name('event.show');
});

Route::prefix('galeri')->group(function () {
    Route::get('/', action: function () {
        return Inertia::render('Galeri/ListView');
    });

    Route::get('/{slug}', action: function ($slug) {
        return Inertia::render('Galeri/Show', ['slug' => $slug]);
    });
});

Route::prefix('portofolio')->group(function () {
    Route::get('/', action: function () {
        return Inertia::render('Event/ListView');
    });

    Route::get('/{slug}', action: function ($slug) {
        return Inertia::render('Event/Show', ['slug' => $slug]);
    });
});

// Route::prefix('feedback')->group(function () {
//     Route::get('/', action: function () {
//         return Inertia::render('Event/ListView');
//     });

//     Route::get('/{slug}', action: function ($slug) {
//         return Inertia::render('Event/Show', ['slug' => $slug]);
//     });
// });



Route::prefix('unduhan')->group(function () {
    Route::get('/', action: function () {
        return Inertia::render('Event/ListView');
    });

    Route::get('/{slug}', action: function ($slug) {
        return Inertia::render('Event/Show', ['slug' => $slug]);
    });
});

Route::prefix('lowongan')->group(function () {
    Route::get('/', action: function () {
        return Inertia::render('Event/ListView');
    });

    Route::get('/{slug}', action: function ($slug) {
        return Inertia::render('Event/Show', ['slug' => $slug]);
    });
});

// Route::prefix('lamaran')->group(function () {
//     Route::get('/', action: function () {
//         return Inertia::render('Event/ListView');
//     });

//     Route::get('/{slug}', action: function ($slug) {
//         return Inertia::render('Event/Show', ['slug' => $slug]);
//     });
// });


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';