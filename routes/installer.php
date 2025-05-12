<?php

use App\Http\Controllers\InstallerController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'installer', 'as' => 'installer.', 'middleware' => ['web', 'not.installed']], function () {
    Route::get('/', [InstallerController::class, 'welcome'])->name('welcome');
    Route::get('/requirements', [InstallerController::class, 'requirements'])->name('requirements');
    Route::get('/permissions', [InstallerController::class, 'permissions'])->name('permissions');
    Route::get('/environment', [InstallerController::class, 'environment'])->name('environment');
    Route::post('/environment/save', [InstallerController::class, 'environmentSave'])->name('environment.save');
    Route::get('/database', [InstallerController::class, 'database'])->name('database');
    Route::get('/database/run-migrations', [InstallerController::class, 'runMigrations'])->name('database.run');
    Route::get('/admin', [InstallerController::class, 'admin'])->name('admin');
    Route::post('/admin/save', [InstallerController::class, 'saveAdmin'])->name('admin.save');
    Route::get('/company', [InstallerController::class, 'company'])->name('company');
    Route::post('/company/save', [InstallerController::class, 'saveCompany'])->name('company.save');
    Route::get('/final', [InstallerController::class, 'final'])->name('final');
});
