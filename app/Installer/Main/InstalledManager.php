<?php

namespace App\Installer\Main;

class InstalledManager
{
    /**
     * Create installed file.
     */
    public static function create(): string
    {
        $installedLogFile = storage_path('installed');
        $dateStamp = date('Y/m/d h:i:sa');
        $message = 'successfully installed';
        file_put_contents($installedLogFile, $message.' '.$dateStamp.PHP_EOL, FILE_APPEND | LOCK_EX);

        return $message;
    }
}
