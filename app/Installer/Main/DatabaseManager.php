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
    public static function MigrateAndSeed(array $selectedSeeders = []): array
    {
        $dm = new DatabaseManager;
        $outputLog = new BufferedOutput;

        $migrateResult = $dm->migrate($outputLog);

        if ($migrateResult[0] === 'error') {
            return $migrateResult;
        }

        // If seeders are provided, run specific seeders
        if (!empty($selectedSeeders)) {
            return $dm->seedSelected($outputLog, $selectedSeeders);
        }

        return $migrateResult;
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
     * Seed the database with all seeders.
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

    /**
     * Seed the database with selected seeders.
     */
    private function seedSelected(BufferedOutput $outputLog, array $selectedSeeders): array
    {
        try {
            $logResult = '';

            // Run base seeders that should always run
            // (These are crucial for the application to function properly)
            $requiredSeeders = [
                'ShieldSeeder',
                'FilamentUserSeeder',
                'FeatureToggleSeeder'
            ];

            foreach ($requiredSeeders as $seeder) {
                $seederClass = "\\Database\\Seeders\\{$seeder}";
                Artisan::call('db:seed', ['--class' => $seederClass, '--force' => true], $outputLog);
                $logResult .= $outputLog->fetch() . "\n";
            }

            // Run selected seeders
            foreach ($selectedSeeders as $seeder => $enabled) {
                if ($enabled && $seeder !== 'ProfilPerusahaanSeeder') {
                    $seederClass = "\\Database\\Seeders\\{$seeder}";
                    Artisan::call('db:seed', ['--class' => $seederClass, '--force' => true], $outputLog);
                    $logResult .= $outputLog->fetch() . "\n";
                }
            }

            \Illuminate\Support\Facades\Log::info('Selected seeding result: ' . $logResult);

            if (stripos($logResult, 'error') !== false || stripos($logResult, 'exception') !== false) {
                \Illuminate\Support\Facades\Log::error('Selected seeding failed: ' . $logResult);
                return ['error', 'Database seeding failed: ' . $logResult];
            }

            return ['success', $logResult];
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Selected seeding exception: ' . $e->getMessage());
            return ['error', $e->getMessage()];
        }
    }
}
