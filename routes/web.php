<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Services\InstallerService;


// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/login', function () {
    return redirect('/admin/login');
})->name('login')->name('filament.auth.login');


Route::get('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// Include installer routes without trying to connect to the database first
try {
    // First, check if the vendor directory exists (dependencies installed)
    if (!file_exists(base_path('vendor/autoload.php'))) {
        Route::get('/install/dependencies', [App\Http\Controllers\PreInstallController::class, 'index'])->name('installer.dependencies');
        Route::get('/install/dependencies/install', [App\Http\Controllers\PreInstallController::class, 'installDependencies'])->name('installer.dependencies.install');
    }

    // Then check installation status, but catch any database exceptions
    $installerService = app(InstallerService::class);
    if (!$installerService->isInstalled()) {
        require __DIR__ . '/installer.php';
    }
} catch (\Exception $e) {
    // If we get a database error, we assume we need the installer
    require __DIR__ . '/installer.php';
}

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::get('/example', function () {
    return Inertia::render('Example');
});


// Rute group untuk artikel
Route::prefix('artikel')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Artikel/ListView');
    })->name('artikel.list');

    Route::get('/{slug}', function ($slug) {
        return Inertia::render('Artikel/Show', ['slug' => $slug]);
    })->name('artikel.show');
});


// Rute group untuk event
Route::prefix('event')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Event/ListView');
    })->name('event.list');

    Route::get('/{slug}', function ($slug) {
        return Inertia::render('Event/Show', ['slug' => $slug]);
    })->name('event.show');
});


// Rute group untuk galeri
Route::prefix('galeri')->group(function () {
    Route::get('/', action: function () {
        return Inertia::render('Galeri/ListView');
    });

    Route::get('/{slug}', action: function ($slug) {
        return Inertia::render('Galeri/Show', ['slug' => $slug]);
    });
});


// Rute group untuk portofolio
Route::prefix('portofolio')->group(function () {
    Route::get('/', action: function () {
        return Inertia::render('Event/ListView');
    });

    Route::get('/{slug}', action: function ($slug) {
        return Inertia::render('Event/Show', ['slug' => $slug]);
    });
});


// Rute group untuk unduhan
Route::prefix('unduhan')->group(function () {
    Route::get('/', action: function () {
        return Inertia::render('Event/ListView');
    });

    Route::get('/{slug}', action: function ($slug) {
        return Inertia::render('Event/Show', ['slug' => $slug]);
    });
});


// Rute group untuk lowongan
Route::prefix('lowongan')->group(function () {
    Route::get('/', action: function () {
        return Inertia::render('Event/ListView');
    });

    Route::get('/{slug}', action: function ($slug) {
        return Inertia::render('Event/Show', ['slug' => $slug]);
    });
});


// Rute group untuk produk
Route::prefix('produk')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Produk/ListView');
    })->name('produk.list');

    Route::get('/{slug}', function ($slug) {
        return Inertia::render('Produk/Show', ['slug' => $slug]);
    })->name('produk.show');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';