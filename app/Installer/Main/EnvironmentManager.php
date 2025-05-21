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

        // Store the company name in this format to match env.example
        $companyName = $request->app_name;

        // Add APP_INSTALLED flag to indicate installation is complete
        $envFileData =
            'APP_NAME="${COMPANY_NAME}"' . "\n" .
            'APP_ENV=' . $request->environment . "\n" .
            'APP_KEY=' . 'base64:' . base64_encode(Str::random(32)) . "\n" .
            'APP_DEBUG=' . $request->app_debug . "\n" .
            'APP_TIMEZONE=' . ($request->app_timezone ?? 'UTC') . "\n" .
            'APP_URL=' . $request->app_url . "\n" .
            "\n" .
            'APP_LOCALE=' . ($request->app_locale ?? 'en') . "\n" .
            'APP_FALLBACK_LOCALE=en' . "\n" .
            'APP_FAKER_LOCALE=en_US' . "\n" .
            "\n" .
            'APP_MAINTENANCE_DRIVER=file' . "\n" .
            'PHP_CLI_SERVER_WORKERS=4' . "\n" .
            "\n" .
            'BCRYPT_ROUNDS=12' . "\n" .
            "\n" .
            'LOG_CHANNEL=stack' . "\n" .
            'LOG_STACK=single' . "\n" .
            'LOG_DEPRECATIONS_CHANNEL=null' . "\n" .
            'LOG_LEVEL=' . $request->app_log_level . "\n" .
            "\n" .
            'APP_INSTALLED=false' . "\n" .
            'COMPANY_NAME=' . $companyName . "\n" .
            "\n" .
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

        // Add session configuration
        $envFileData .= 'SESSION_DRIVER=database' . "\n" .
            'SESSION_LIFETIME=120' . "\n" .
            'SESSION_ENCRYPT=false' . "\n" .
            'SESSION_PATH=/' . "\n" .
            'SESSION_DOMAIN=null' . "\n\n" .
            'BROADCAST_CONNECTION=log' . "\n" .
            'FILESYSTEM_DISK=local' . "\n" .
            'QUEUE_CONNECTION=database' . "\n\n" .
            'CACHE_STORE=database' . "\n" .
            'CACHE_PREFIX=' . "\n\n" .
            'MEMCACHED_HOST=127.0.0.1' . "\n\n" .
            'REDIS_CLIENT=phpredis' . "\n" .
            'REDIS_HOST=127.0.0.1' . "\n" .
            'REDIS_PASSWORD=null' . "\n" .
            'REDIS_PORT=6379' . "\n\n" .
            'MAIL_MAILER=log' . "\n" .
            'MAIL_SCHEME=null' . "\n" .
            'MAIL_HOST=127.0.0.1' . "\n" .
            'MAIL_PORT=2525' . "\n" .
            'MAIL_USERNAME=null' . "\n" .
            'MAIL_PASSWORD=null' . "\n" .
            'MAIL_FROM_ADDRESS="hello@example.com"' . "\n" .
            'MAIL_FROM_NAME="${APP_NAME}"' . "\n\n";

        // Add the rest of the environment configuration
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
