<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;


// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/login', function () {
//     return Inertia::render('Login');
// })->name('login');

Route::get('/login', function () {
    return redirect('/admin/login');
})->name('login')->name('filament.auth.login');

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

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

// Rute group feedback
Route::prefix('feedback')->group(function () {
    Route::get('/', action: function () {
        return Inertia::render('Feedback/Main');
    });
});

// Rute group Profil Perusahaan
Route::prefix('profilperusahaan')->group(function () {
    Route::get('/', action: function () {
        return Inertia::render('ProfilPerusahaan/Main');
    });
});

// Rute group untuk Visi Misi
Route::prefix('visi-misi')->group(function () {
    Route::get('/', action: function () {
        return Inertia::render('VisiMisiPerusahaan/Main');
    });
});

// Rute group untuk Sejarah Perusahaan
Route::prefix('sejarah-perusahaan')->group(function () {
    Route::get('/', action: function () {
        return Inertia::render('SejarahPerusahaan/Main');
    });
});

// Rute group untuk Struktur Organisasi
Route::prefix('struktur-organisasi')->group(function () {
    Route::get('/', action: function () {
        return Inertia::render('StrukturOrganisasi/Main');
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

// Rute group untuk Tentang Kami
Route::prefix('tentangkami')->group(function () {
    Route::get('/profilperusahaan', function () {
        return Inertia::render('TentangKami/ProfilPerusahaan');
    })->name('tentangkami.profilperusahaan');

    Route::get('/visimisiperusahaan', function () {
        return Inertia::render('TentangKami/VisiMisiPerusahaan');
    })->name('tentangkami.visimisiperusahaan');

    Route::get('/sejarahperusahaan', function () {
        return Inertia::render('TentangKami/SejarahPerusahaan');
    })->name('tentangkami.sejarahperusahaan');

    Route::get('/strukturorganisasi', function () {
        return Inertia::render('TentangKami/StrukturOrganisasi');
    })->name('tentangkami.strukturorganisasi');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
