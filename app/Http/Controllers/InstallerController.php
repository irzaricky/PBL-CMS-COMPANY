<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\InstallerService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class InstallerController extends Controller
{
    protected $installerService;

    public function __construct(InstallerService $installerService)
    {
        $this->installerService = $installerService;
    }

    public function welcome()
    {
        return view('installer.welcome');
    }

    public function requirements()
    {
        $requirements = $this->installerService->checkRequirements();
        $requirementsMet = !in_array(false, $requirements);

        return view('installer.requirements', compact('requirements', 'requirementsMet'));
    }

    public function permissions()
    {
        $permissions = $this->installerService->checkPermissions();
        $permissionsMet = !in_array(false, $permissions);

        return view('installer.permissions', compact('permissions', 'permissionsMet'));
    }

    public function environment()
    {
        return view('installer.environment');
    }
    public function environmentSave(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
            'db_connection' => 'required|string',
            'db_host' => 'required|string',
            'db_port' => 'required|numeric',
            'db_database' => 'required|string',
            'db_username' => 'required|string',
            'db_password' => 'nullable|string',
        ]);

        try {
            // Test database connection
            $connection = $validated['db_connection'];
            $host = $validated['db_host'];
            $port = $validated['db_port'];
            $database = $validated['db_database'];
            $username = $validated['db_username'];
            $password = $validated['db_password'];

            $connectionTest = $this->installerService->testDatabaseConnection(
                $connection,
                $host,
                $port,
                $database,
                $username,
                $password
            );

            if (!$connectionTest['success']) {
                return redirect()->back()->withErrors([
                    'database' => $connectionTest['message'] . ' Please make sure the database exists and the credentials are correct.'
                ]);
            }

            // Update .env file
            $this->installerService->updateEnvironmentFile($validated);

            // Immediately clear config cache to use new database settings
            try {
                Artisan::call('config:clear');
            } catch (\Exception $e) {
                // Continue anyway if this fails
            }

            return redirect()->route('installer.database');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['database' => 'Database connection error: ' . $e->getMessage()]);
        }
    }

    public function database()
    {
        return view('installer.database');
    }
    public function runMigrations()
    {
        try {
            // Clear all caches to ensure fresh configuration
            Artisan::call('config:clear');
            Artisan::call('cache:clear');

            // Check if database connection is working
            try {
                DB::connection()->getPdo();
            } catch (\Exception $e) {
                return redirect()->route('installer.environment')->withErrors([
                    'database' => 'Could not connect to database. Please check your database settings. Error: ' . $e->getMessage()
                ]);
            }

            // Run migrations
            try {
                Artisan::call('migrate:fresh', ['--force' => true]);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors([
                    'migration' => 'Migration failed: ' . $e->getMessage() .
                        ' Please make sure your database exists and the user has proper permissions.'
                ]);
            }

            // Run seeders if needed
            try {
                Artisan::call('db:seed', ['--force' => true]);
            } catch (\Exception $e) {
                // Continue even if seeding fails
            }

            return redirect()->route('installer.admin');
        } catch (\Exception $e) {
            return redirect()->route('installer.environment')->withErrors([
                'database' => 'Installation error: ' . $e->getMessage()
            ]);
        }
    }

    public function admin()
    {
        return view('installer.admin');
    }

    public function saveAdmin(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            // Create admin user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);            // Assign admin role if using a role system
            if (Schema::hasTable('roles') && method_exists($user, 'assignRole')) {
                $user->assignRole('admin');
            }

            // Generate application key if not already set
            if (empty(config('app.key'))) {
                Artisan::call('key:generate', ['--force' => true]);
            }

            // Storage link
            Artisan::call('storage:link');

            return redirect()->route('installer.company');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['admin' => $e->getMessage()]);
        }
    }
    public function company()
    {
        return view('installer.company');
    }
    public function saveCompany(Request $request)
    {
        $validated = $request->validate([
            'nama_perusahaan' => 'required|string|max:100',
            'logo_perusahaan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'alamat_perusahaan' => 'required|string|max:200',
            'email_perusahaan' => 'required|email|max:50',
        ]);

        try {
            // Make sure storage is linked
            try {
                if (!file_exists(public_path('storage'))) {
                    Artisan::call('storage:link');
                }
            } catch (\Exception $e) {
                // Continue even if linking fails
                // We'll handle this differently below
            }

            // Handle logo upload
            $logoPath = null;
            if ($request->hasFile('logo_perusahaan')) {
                try {
                    $file = $request->file('logo_perusahaan');
                    $logoPath = $file->store('public/logos');
                    $logoPath = str_replace('public/', '', $logoPath);

                    // Verify file was actually saved
                    if (!$logoPath || !file_exists(storage_path('app/public/' . $logoPath))) {
                        // Something went wrong with the upload, but we'll continue without the logo
                        $logoPath = null;
                    }
                } catch (\Exception $e) {
                    // Continue without logo
                    $logoPath = null;
                }
            }

            // Create company profile
            DB::table('profil_perusahaan')->insert([
                'nama_perusahaan' => $validated['nama_perusahaan'],
                'logo_perusahaan' => $logoPath,
                'alamat_perusahaan' => $validated['alamat_perusahaan'],
                'email_perusahaan' => $validated['email_perusahaan'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Mark as installed
            $this->installerService->markAsInstalled();

            return redirect()->route('installer.final');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['company' => $e->getMessage()]);
        }
    }

    public function final()
    {
        return view('installer.final');
    }
}
