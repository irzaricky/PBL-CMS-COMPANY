<?php

namespace App\Installer\Main;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EnvironmentManager
{
    private string $envPath;

    /**
     * Set the .env and .env.example paths.
     */
    public function __construct()
    {
        $this->envPath = base_path('.env');
    }

    public function saveFileWizard(Request $request): string
    {
        $results = 'Installer Successfully';

        $env = config('install.env');

        // Add APP_INSTALLED flag to indicate installation is complete
        $envFileData =
            'APP_NAME=\'' . $request->app_name . "'\n" .
            'APP_ENV=' . $request->environment . "\n" .
            'APP_KEY=' . 'base64:' . base64_encode(Str::random(32)) . "\n" .
            'APP_DEBUG=' . $request->app_debug . "\n" .
            'APP_LOG_LEVEL=' . $request->app_log_level . "\n" .
            'APP_URL=' . $request->app_url . "\n" .
            'APP_TIMEZONE=' . ($request->app_timezone ?? 'UTC') . "\n" .
            'APP_INSTALLED=false' . "\n\n" .
            'DB_CONNECTION=' . $request->database_connection . "\n";

        // Add database configuration based on connection type
        if ($request->database_connection === 'sqlite') {
            // For SQLite, store just the database name in .env
            // Laravel will use storage_path($request->database_name) when needed
            $envFileData .= 'DB_DATABASE=' . $request->database_name . "\n\n";
        } else {
            // For MySQL
            $envFileData .= 'DB_HOST=' . $request->database_hostname . "\n" .
                'DB_PORT=' . $request->database_port . "\n" .
                'DB_DATABASE=' . $request->database_name . "\n" .
                'DB_USERNAME=' . $request->database_username . "\n" .
                'DB_PASSWORD=' . ($request->database_password ?? '') . "\n\n";
        }

        $envFileData .= $env;

        try {
            // Make sure the directory exists
            $envDir = dirname($this->envPath);
            if (!file_exists($envDir)) {
                mkdir($envDir, 0755, true);
            }

            // Check if .env file exists and is writable
            if (file_exists($this->envPath) && !is_writable($this->envPath)) {
                chmod($this->envPath, 0644);
            }

            // Write the file
            if (file_put_contents($this->envPath, $envFileData) === false) {
                throw new Exception('Could not write to .env file. Check permissions.');
            }

            // Make sure Laravel can read it
            if (file_exists($this->envPath)) {
                chmod($this->envPath, 0644);
            }

        } catch (Exception $e) {
            Log::error('Error writing .env file: ' . $e->getMessage());
            $results = 'Installer Errors: ' . $e->getMessage();
            throw $e; // Re-throw to be caught by the controller
        }

        return $results;
    }
}
