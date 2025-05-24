<?php

namespace App\Installer\Main;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\BufferedOutput;

class DatabaseManager
{
    /**
     * Migrate and seed the database.
     * 
     * @param bool $runFullSeeders Whether to run the full DatabaseSeeder (true) or just ShieldSeeder (false)
     */
    public static function MigrateAndSeed(bool $runFullSeeders = false): array
    {
        $dm = new DatabaseManager;
        $outputLog = new BufferedOutput;

        $migrateResult = $dm->migrate($outputLog);

        if ($migrateResult[0] === 'error') {
            return $migrateResult;
        }

        // Run seeders based on the selection
        if ($runFullSeeders) {
            // Run DatabaseSeeder which includes all seeders
            return $dm->runDatabaseSeeder($outputLog);
        } else {
            // Run only ShieldSeeder which is required
            return $dm->runShieldSeeder($outputLog);
        }
    }

    /**
     * Run the migration and call the seeder.
     */
    private function migrate(BufferedOutput $outputLog): array
    {
        try {
            Artisan::call('migrate:fresh', [
                '--force' => true, // You can use --force to avoid prompts if needed
            ], $outputLog);

            $logContents = $outputLog->fetch();
            \Illuminate\Support\Facades\Log::info('Migration result: ' . $logContents);

            if (stripos($logContents, 'error') !== false || stripos($logContents, 'exception') !== false) {
                \Illuminate\Support\Facades\Log::error('Migration failed: ' . $logContents);
                throw new \Exception('Database migration failed: ' . $logContents);
            }

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Migration exception: ' . $e->getMessage());
            return ['error', $e->getMessage()];
        }

        // Just return success without running all seeders
        return ['success', 'Migration completed successfully'];
    }

    /**
     * Run only the ShieldSeeder (required for the application)
     */
    private function runShieldSeeder(BufferedOutput $outputLog): array
    {
        try {
            $logResult = '';

            // Run ShieldSeeder which is required for the application to function properly
            $shieldSeederClass = "\\Database\\Seeders\\ShieldSeeder";
            Artisan::call('db:seed', ['--class' => $shieldSeederClass, '--force' => true], $outputLog);
            $logResult .= $outputLog->fetch() . "\n";

            \Illuminate\Support\Facades\Log::info('ShieldSeeder result: ' . $logResult);

            if (stripos($logResult, 'error') !== false || stripos($logResult, 'exception') !== false) {
                \Illuminate\Support\Facades\Log::error('ShieldSeeder failed: ' . $logResult);
                return ['error', 'ShieldSeeder failed: ' . $logResult];
            }

            return ['success', $logResult];
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('ShieldSeeder exception: ' . $e->getMessage());
            return ['error', $e->getMessage()];
        }
    }

    /**
     * Run the full DatabaseSeeder which includes all seeders
     */
    private function runDatabaseSeeder(BufferedOutput $outputLog): array
    {
        try {
            // Run the main DatabaseSeeder which includes all seeders including ShieldSeeder
            Artisan::call('db:seed', ['--force' => true], $outputLog);
            $logResult = $outputLog->fetch();

            \Illuminate\Support\Facades\Log::info('DatabaseSeeder result: ' . $logResult);

            if (stripos($logResult, 'error') !== false || stripos($logResult, 'exception') !== false) {
                \Illuminate\Support\Facades\Log::error('DatabaseSeeder failed: ' . $logResult);
                return ['error', 'DatabaseSeeder failed: ' . $logResult];
            }

            return ['success', $logResult];
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('DatabaseSeeder exception: ' . $e->getMessage());
            return ['error', $e->getMessage()];
        }
    }
}
