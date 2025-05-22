<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\File;

class SetupTestInstaller extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:setup-test-installer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup test installation by running series of commands';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting test installation setup...');

        // 1. Composer install
        $this->info('Step 1: Running composer install...');
        $this->executeCommand('composer install');

        // 2. NPM install
        $this->info('Step 2: Running npm install...');
        $this->executeCommand('npm install');

        // 3. NPM build
        $this->info('Step 3: Running npm run build...');
        $this->executeCommand('npm run build');

        // 4. Copy .env file
        $this->info('Step 4: Copying .env file...');
        if (File::exists('.env.example') && !File::exists('.env')) {
            File::copy('.env.example', '.env');
            $this->info('.env file created successfully.');
        } else {
            $this->warn('.env file already exists or .env.example not found.');
        }

        // 5. Generate application key
        $this->info('Step 5: Generating application key...');
        $this->executeArtisanCommand('key:generate');

        // 6. Create storage link
        $this->info('Step 6: Creating storage link...');
        $this->executeArtisanCommand('storage:link');

        // 7. Run specific migration
        $this->info('Step 7: Running users table migration...');
        $this->executeArtisanCommand('migrate --path=database/migrations/0001_01_01_000000_create_users_table.php');

        $this->info('Installation setup completed successfully!');
    }

    /**
     * Execute a shell command and display output
     *
     * @param string $command
     * @return void
     */
    protected function executeCommand($command)
    {
        $process = Process::run($command);

        if ($process->successful()) {
            $this->info($process->output());
        } else {
            $this->error("Command failed: {$command}");
            $this->error($process->errorOutput());
        }
    }

    /**
     * Execute an Artisan command
     *
     * @param string $command
     * @return void
     */
    protected function executeArtisanCommand($command)
    {
        $this->executeCommand("php artisan {$command}");
    }
}
