<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Check for missing dependencies before attempting to load Laravel
if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/pre-install.php';
    // The pre-install.php script will exit, so execution won't continue
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__ . '/../bootstrap/app.php')
    ->handleRequest(Request::capture());
