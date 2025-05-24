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
    public static function MigrateAndSeed(bool $includeDummyData = false): array
    {
        $dm = new DatabaseManager;
        $outputLog = new BufferedOutput;

        return $dm->migrate($outputLog, $includeDummyData);
    }

    /**
     * Migrate and run essential seeders only.
     */
    public static function MigrateAndEssentialSeed(): array
    {
        $dm = new DatabaseManager;
        $outputLog = new BufferedOutput;

        return $dm->migrateAndEssential($outputLog);
    }

    /**
     * Run only seeding without migration.
     */
    public static function SeedOnly(bool $includeDummyData = false): array
    {
        $dm = new DatabaseManager;
        $outputLog = new BufferedOutput;

        return $dm->seed($outputLog, $includeDummyData);
    }

    /**
     * Run the migration and call the seeder.
     */
    private function migrate(BufferedOutput $outputLog, bool $includeDummyData = false): array
    {
        try {
            // Clear any existing output
            $outputLog->fetch();

            Artisan::call('migrate:fresh', [
                '--force' => true,
            ], $outputLog);

            $logContents = $outputLog->fetch();
            \Illuminate\Support\Facades\Log::info('Migration result: ' . $logContents);

            // Check for migration errors more thoroughly
            if (
                stripos($logContents, 'error') !== false ||
                stripos($logContents, 'exception') !== false ||
                stripos($logContents, 'failed') !== false ||
                stripos($logContents, 'could not') !== false
            ) {
                \Illuminate\Support\Facades\Log::error('Migration failed: ' . $logContents);
                throw new \Exception('Database migration failed: ' . $logContents);
            }

            // Check if any migrations were actually run
            if (
                stripos($logContents, 'Nothing to migrate') !== false &&
                stripos($logContents, 'Dropped all tables successfully') === false
            ) {
                \Illuminate\Support\Facades\Log::warning('No migrations found or executed');
            }

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Migration exception: ' . $e->getMessage());
            return ['error', $e->getMessage()];
        }

        return $this->seed($outputLog, $includeDummyData);
    }

    /**
     * Run the migration and call the essential seeder.
     */
    private function migrateAndEssential(BufferedOutput $outputLog): array
    {
        try {
            // Clear any existing output
            $outputLog->fetch();

            Artisan::call('migrate:fresh', [
                '--force' => true,
            ], $outputLog);

            $logContents = $outputLog->fetch();
            \Illuminate\Support\Facades\Log::info('Migration result: ' . $logContents);

            // Check for migration errors more thoroughly
            if (
                stripos($logContents, 'error') !== false ||
                stripos($logContents, 'exception') !== false ||
                stripos($logContents, 'failed') !== false ||
                stripos($logContents, 'could not') !== false
            ) {
                \Illuminate\Support\Facades\Log::error('Migration failed: ' . $logContents);
                throw new \Exception('Database migration failed: ' . $logContents);
            }

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Migration exception: ' . $e->getMessage());
            return ['error', $e->getMessage()];
        }

        return $this->essentialSeed($outputLog);
    }

    /**
     * Seed the database.
     */
    private function seed(BufferedOutput $outputLog, bool $includeDummyData = false): array
    {
        try {
            // Clear any existing output
            $outputLog->fetch();

            if ($includeDummyData) {
                // Jalankan semua seeder (termasuk data dummy) tanpa migrate:fresh
                // karena migration sudah dilakukan sebelumnya
                Artisan::call('db:seed', ['--force' => true], $outputLog);
            } else {
                // Hanya jalankan ShieldSeeder
                Artisan::call('db:seed', [
                    '--class' => 'ShieldSeeder',
                    '--force' => true,
                ], $outputLog);
            }

            $logContents = $outputLog->fetch();
            \Illuminate\Support\Facades\Log::info('Seeding result: ' . $logContents);

            // Check for seeding errors more thoroughly
            if (
                stripos($logContents, 'error') !== false ||
                stripos($logContents, 'exception') !== false ||
                stripos($logContents, 'failed') !== false ||
                stripos($logContents, 'could not') !== false ||
                stripos($logContents, 'class not found') !== false
            ) {
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
     * Seed the database with essential data only.
     */
    private function essentialSeed(BufferedOutput $outputLog): array
    {
        try {
            $essentialSeeders = [
                'ShieldSeeder',
                'ProfilPerusahaanSeeder',
                'KontenSliderSeeder',
                'FeatureToggleSeeder'
            ];

            // Clear any existing output
            $outputLog->fetch();

            foreach ($essentialSeeders as $seeder) {
                try {
                    Artisan::call('db:seed', [
                        '--class' => $seeder,
                        '--force' => true,
                    ], $outputLog);

                    \Illuminate\Support\Facades\Log::info("Successfully ran seeder: $seeder");
                } catch (Exception $e) {
                    \Illuminate\Support\Facades\Log::warning("Seeder $seeder failed: " . $e->getMessage());
                    // Continue with other seeders even if one fails (some might not exist)
                }
            }

            $logContents = $outputLog->fetch();
            \Illuminate\Support\Facades\Log::info('Essential seeding result: ' . $logContents);

            // Check for critical seeding errors - but be less strict than general seeding
            if (
                stripos($logContents, 'fatal') !== false ||
                stripos($logContents, 'syntax error') !== false
            ) {
                \Illuminate\Support\Facades\Log::error('Essential seeding failed: ' . $logContents);
                return ['error', 'Essential seeding failed: ' . $logContents];
            }

            return ['success', $logContents];
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Essential seeding exception: ' . $e->getMessage());
            return ['error', $e->getMessage()];
        }
    }
}
