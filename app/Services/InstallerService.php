<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;

class InstallerService
{    /**
     * Check if the application is already installed
     * 
     * This method should not access the database to avoid errors during installation
     *
     * @return bool
     */
    public function isInstalled(): bool
    {
        // Only check for the existence of the 'installed' file
        return file_exists(storage_path('installed')) && file_exists(base_path('.env'));
    }

    /**
     * Check if composer dependencies are installed
     *
     * @return bool
     */
    public function dependenciesInstalled(): bool
    {
        return file_exists(base_path('vendor/autoload.php'));
    }

    /**
     * Install composer dependencies
     *
     * @return array
     */
    public function installDependencies(): array
    {
        try {
            $composerPath = $this->findComposerPath();
            if (!$composerPath) {
                return [
                    'success' => false,
                    'message' => 'Composer not found. Please install Composer first.'
                ];
            }

            $command = "{$composerPath} install --no-interaction --no-dev";
            $result = Process::timeout(300)->run($command);

            if ($result->successful()) {
                return ['success' => true, 'message' => 'Dependencies installed successfully.'];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to install dependencies: ' . $result->errorOutput()
                ];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Find the composer executable path
     *
     * @return string|null
     */
    protected function findComposerPath(): ?string
    {
        // Check for locally installed composer.phar
        if (file_exists(base_path('composer.phar'))) {
            return 'php ' . base_path('composer.phar');
        }

        // Check for globally installed composer
        $testCommand = Process::run('composer --version');
        if ($testCommand->successful()) {
            return 'composer';
        }

        return null;
    }

    /**
     * Mark the application as installed
     *
     * @return bool
     */
    public function markAsInstalled(): bool
    {
        $installedLogFile = storage_path('installed');
        $dateStamp = date('Y/m/d h:i:sa');

        // Generate application key if it doesn't exist
        $this->generateAppKeyIfNeeded();

        if (!file_exists($installedLogFile)) {
            $message = 'Installation completed on ' . $dateStamp . "\n";
            File::put($installedLogFile, $message);
        }

        return true;
    }

    /**
     * Generate application key if not already set
     *
     * @return bool
     */
    public function generateAppKeyIfNeeded(): bool
    {
        $envPath = base_path('.env');

        // If .env doesn't exist, copy from .env.example
        if (!file_exists($envPath) && file_exists(base_path('.env.example'))) {
            copy(base_path('.env.example'), $envPath);
        }

        // Check if APP_KEY is empty
        $env = file_get_contents($envPath);
        if (preg_match('/APP_KEY=(.*)/', $env, $matches)) {
            if (empty($matches[1])) {
                try {
                    // Generate a new application key
                    $result = Process::run('php artisan key:generate --force');
                    return $result->successful();
                } catch (\Exception $e) {
                    // If the command fails, try to set the key manually
                    $key = 'base64:' . base64_encode(random_bytes(32));
                    $env = $this->setEnvValue($env, 'APP_KEY', $key);
                    file_put_contents($envPath, $env);
                    return true;
                }
            }
        }

        return true;
    }

    /**
     * Check PHP and server requirements
     *
     * @return array
     */
    public function checkRequirements(): array
    {
        return [
            'php' => version_compare(PHP_VERSION, '8.1.0', '>='),
            'pdo' => extension_loaded('PDO'),
            'mbstring' => extension_loaded('mbstring'),
            'fileinfo' => extension_loaded('fileinfo'),
            'openssl' => extension_loaded('openssl'),
            'tokenizer' => extension_loaded('tokenizer'),
            'json' => extension_loaded('json'),
            'curl' => extension_loaded('curl'),
            'xml' => extension_loaded('xml'),
            'gd' => extension_loaded('gd'),
        ];
    }

    /**
     * Check directory permissions
     *
     * @return array
     */
    public function checkPermissions(): array
    {
        return [
            'storage/app' => is_writable(storage_path('app')),
            'storage/framework' => is_writable(storage_path('framework')),
            'storage/logs' => is_writable(storage_path('logs')),
            'bootstrap/cache' => is_writable(base_path('bootstrap/cache')),
            '.env' => is_writable(base_path('.env')),
        ];
    }    /**
         * Test database connection
         *
         * @param string $connection
         * @param string $host
         * @param string $port
         * @param string $database
         * @param string $username
         * @param string $password
         * @return array
         */
    public function testDatabaseConnection(
        string $connection,
        string $host,
        string $port,
        string $database,
        string $username,
        string $password
    ): array {
        try {
            // First try connecting without specifying database to check server connection
            try {
                $serverConnection = new \PDO(
                    "{$connection}:host={$host};port={$port}",
                    $username,
                    $password
                );

                // Check if database exists
                $stmt = $serverConnection->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$database'");
                $dbExists = $stmt->fetch();

                if (!$dbExists) {
                    // Try to create the database
                    try {
                        $serverConnection->exec("CREATE DATABASE `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                        return ['success' => true, 'message' => 'Connection successful. Database created.'];
                    } catch (\Exception $e) {
                        return [
                            'success' => false,
                            'message' => "Database '$database' does not exist and could not be created. " . $e->getMessage()
                        ];
                    }
                }
            } catch (\Exception $e) {
                // Server connection failed, continue to try full connection anyway
            }

            // Try connecting with database specified
            $testConnection = new \PDO(
                "{$connection}:host={$host};port={$port};dbname={$database}",
                $username,
                $password,
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );

            return ['success' => true, 'message' => 'Connection successful'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }    /**
         * Update the .env file with installation data
         *
         * @param array $data
         * @return bool
         */
    public function updateEnvironmentFile(array $data): bool
    {
        $envPath = base_path('.env');

        if (!file_exists($envPath)) {
            // If .env doesn't exist, copy .env.example
            if (!file_exists(base_path('.env.example'))) {
                // Create minimal .env if example doesn't exist
                $defaultEnv = "APP_NAME=Laravel\nAPP_ENV=local\nAPP_KEY=\nAPP_DEBUG=true\nAPP_URL=http://localhost\n\n";
                $defaultEnv .= "DB_CONNECTION=mysql\nDB_HOST=127.0.0.1\nDB_PORT=3306\nDB_DATABASE=laravel\nDB_USERNAME=root\nDB_PASSWORD=\n\n";
                file_put_contents($envPath, $defaultEnv);
            } else {
                copy(base_path('.env.example'), $envPath);
            }
        }

        // Make sure .env is writable
        if (!is_writable($envPath)) {
            chmod($envPath, 0666);
            if (!is_writable($envPath)) {
                return false;
            }
        }

        $env = file_get_contents($envPath);

        // Update app settings
        $env = $this->setEnvValue($env, 'APP_NAME', '"' . $data['app_name'] . '"');
        $env = $this->setEnvValue($env, 'APP_URL', $data['app_url']);

        // Update database settings
        $env = $this->setEnvValue($env, 'DB_CONNECTION', $data['db_connection']);
        $env = $this->setEnvValue($env, 'DB_HOST', $data['db_host']);
        $env = $this->setEnvValue($env, 'DB_PORT', $data['db_port']);
        $env = $this->setEnvValue($env, 'DB_DATABASE', $data['db_database']);
        $env = $this->setEnvValue($env, 'DB_USERNAME', $data['db_username']);
        $env = $this->setEnvValue($env, 'DB_PASSWORD', $data['db_password']);

        // Generate app key if not exists or is empty
        $appKey = $this->getEnvValue($env, 'APP_KEY');
        if (empty($appKey)) {
            $env = $this->setEnvValue($env, 'APP_KEY', 'base64:' . base64_encode(random_bytes(32)));
        }

        // Set debug mode to false for production
        $env = $this->setEnvValue($env, 'APP_DEBUG', 'false');

        // Set environment to production
        $env = $this->setEnvValue($env, 'APP_ENV', 'production');

        $result = file_put_contents($envPath, $env) !== false;

        // Clear configuration cache to ensure new .env is used
        if (function_exists('exec')) {
            @exec('php ' . base_path('artisan') . ' config:clear');
        }

        return $result;
    }

    /**
     * Set a value in the .env file
     *
     * @param string $env
     * @param string $key
     * @param string $value
     * @return string
     */
    protected function setEnvValue(string $env, string $key, string $value): string
    {
        $key = strtoupper($key);

        if (str_contains($env, "{$key}=")) {
            $env = preg_replace("/{$key}=.*/", "{$key}={$value}", $env);
        } else {
            $env .= "\n{$key}={$value}";
        }

        return $env;
    }

    /**
     * Get a value from the .env file content
     *
     * @param string $env
     * @param string $key
     * @return string|null
     */
    protected function getEnvValue(string $env, string $key): ?string
    {
        $key = strtoupper($key);

        if (preg_match("/{$key}=(.*)/", $env, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }
}
