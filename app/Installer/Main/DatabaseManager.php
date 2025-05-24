<?php

namespace App\Installer\Main;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\BufferedOutput;

class DatabaseManager
{
    /**
     * Migrate and seed the database.
     */
    public static function MigrateAndSeed(): array
    {
        $dm = new DatabaseManager;
        $outputLog = new BufferedOutput;

        return $dm->migrate($outputLog);
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

        return $this->seed($outputLog);
    }

    /**
     * Seed the database.
     */
    private function seed(BufferedOutput $outputLog): array
    {
        try {
            Artisan::call('db:seed', ['--force' => true], $outputLog);

            $logContents = $outputLog->fetch();
            \Illuminate\Support\Facades\Log::info('Seeding result: ' . $logContents);

            if (stripos($logContents, 'error') !== false || stripos($logContents, 'exception') !== false) {
                \Illuminate\Support\Facades\Log::error('Seeding failed: ' . $logContents);
                return ['error', 'Database seeding failed: ' . $logContents];
            }

            return ['success', $logContents];
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Seeding exception: ' . $e->getMessage());
            return ['error', $e->getMessage()];
        }
    }
}
