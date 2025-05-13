<?php

use Illuminate\Support\Facades\Route;
use App\Installer\Controllers\InstallerController;
use App\Installer\Controllers\DatabaseController;

Route::get('install-app', function () {
    return redirect(route('installs'));
});

Route::group(['middleware' => ['installCheck'], 'prefix' => 'install-app'], function () {
    // Your routes go here
    Route::get('requirements-permissions', [InstallerController::class, 'index'])->name('installs');
    Route::post('install-check', [InstallerController::class, 'install_check'])->name('install_check');

    Route::get('database-import', [DatabaseController::class, 'databaseImport'])->name('database_import');
    Route::post('save-wizard', [DatabaseController::class, 'saveWizard'])->name('saveWizard');

    Route::get('account', [InstallerController::class, 'account'])->name('account');
    Route::post('account-save', [InstallerController::class, 'saveAccount'])->name('saveAccount');

    Route::get('finish', [InstallerController::class, 'finish'])->name('finish');
    Route::get('finish-save', [InstallerController::class, 'finishSave'])->name('finishSave');
});