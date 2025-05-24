<?php

namespace App\Installer\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Installer\Main\DatabaseManager;
use Illuminate\Support\Facades\Artisan;
use App\Installer\Main\InstalledManager;
use Illuminate\Support\Facades\Validator;
use App\Installer\Main\PermissionsChecker;
use App\Installer\Main\RequirementsChecker;

class InstallerController extends Controller
{
    protected RequirementsChecker $requirements;

    protected PermissionsChecker $permissions;

    public function __construct(PermissionsChecker $permissions, RequirementsChecker $requirements)
    {
        $this->permissions = $permissions;
        $this->requirements = $requirements;
    }

    public function index()
    {

        $permissions = $this->permissions->check(
            config('install.permissions')
        );

        $phpSupportInfo = $this->requirements->checkPHPversion(
            config('install.core.minPhpVersion')
        );
        $requirements = $this->requirements->check(
            config('install.requirements')
        );

        return view('InstallerEragViews::index', compact('permissions', 'requirements', 'phpSupportInfo'));
    }

    public function install_check()
    {
        return redirect(route('database_import'));
    }

    public function profilPerusahaan()
    {
        // Double-check database connection
        try {
            $dbConnection = DB::connection()->getPdo();
            if (!$dbConnection) {
                return redirect()->route('database_import')
                    ->with('database_error', 'Database connection failed. Please check your configuration.');
            }

            // If DB connection works, run migrations and seed
            $migrationResult = DatabaseManager::MigrateAndSeed();

            if ($migrationResult[0] === 'error') {
                return redirect()->route('database_import')
                    ->with('database_error', 'Database migration failed: ' . ($migrationResult[1] ?? 'Unknown error'))
                    ->withErrors(['database_connection' => 'Database migration failed. Please check your database configuration.']);
            }

            return view('InstallerEragViews::profil-perusahaan');
        } catch (\Exception $e) {
            return redirect()->route('database_import')
                ->with('database_error', 'Database error: ' . $e->getMessage())
                ->withErrors(['database_connection' => 'Database connection failed: ' . $e->getMessage()]);
        }
    }

    public function saveProfilPerusahaan(Request $request, Redirector $redirect)
    {
        $rules = config('install.profil_perusahaan');

        // Debug information
        Log::info('Form submission received', [
            'has_file' => $request->hasFile('logo_perusahaan'),
            'all_inputs' => $request->all(),
        ]);

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Log::warning('Validation failed', ['errors' => $validator->errors()]);
            return $redirect->route('profil_perusahaan')->withInput()->withErrors($validator->errors());
        }

        $data = $request->except(['_token', 'logo_perusahaan']);

        // Check if ProfilPerusahaan with ID 1 exists
        $profilPerusahaan = \App\Models\ProfilPerusahaan::find(1);

        // Handle logo upload if provided
        if ($request->hasFile('logo_perusahaan')) {
            $logo = $request->file('logo_perusahaan');

            // Validate the file
            if ($logo->isValid()) {
                $logoName = time() . '.' . $logo->getClientOriginalExtension();

                // Delete old logo file if exists
                if ($profilPerusahaan && $profilPerusahaan->logo_perusahaan) {
                    $oldPath = storage_path('app/public/' . $profilPerusahaan->logo_perusahaan);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                // Make sure directory exists
                $directory = storage_path('app/public/perusahaan-logo');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }

                // Store the file in the same directory that Filament uses (perusahaan-logo)
                $path = $logo->storeAs('perusahaan-logo', $logoName, 'public');
                $data['logo_perusahaan'] = $path;
            } else {
                // Log error for debugging
                Log::error('Logo upload failed: ' . $logo->getErrorMessage());
                return $redirect->route('profil_perusahaan')
                    ->withInput()
                    ->withErrors(['logo_perusahaan' => 'File upload failed. Please try again.']);
            }
        }

        if ($profilPerusahaan) {
            // Update existing record
            $profilPerusahaan->update($data);
        } else {
            // Create new record with ID 1
            $data['id_profil_perusahaan'] = 1;
            \App\Models\ProfilPerusahaan::create($data);
        }

        // Redirect to super admin configuration instead of feature toggles
        return redirect(route('super_admin_config'));
    }

    public function superAdminConfig()
    {
        return view('InstallerEragViews::super-admin-config');
    }

    public function saveSuperAdmin(Request $request, Redirector $redirect)
    {
        // Check if account already exists
        $existingUser = \App\Models\User::where('email', $request->email)->first();

        if ($existingUser) {
            // If user already exists, check if they have super_admin role
            if ($existingUser->hasRole('super_admin')) {
                return $redirect->route('user_roles_list')
                    ->with('account_exists', 'Super Admin dengan email ' . $request->email . ' sudah ada dan memiliki akses super admin. Anda dapat melanjutkan.');
            } else {
                // User exists but doesn't have super_admin role
                try {
                    // Assign super_admin role to existing user and update status_kepegawaian
                    if (class_exists('Spatie\Permission\Models\Role')) {
                        $superAdminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'super_admin']);
                        $existingUser->assignRole($superAdminRole);
                        // Update status_kepegawaian to Tetap
                        $existingUser->status_kepegawaian = 'Tetap';
                        $existingUser->save();
                    }

                    return $redirect->route('user_roles_list')
                        ->with('account_exists', 'User dengan email ' . $request->email . ' sudah ada. Role Super Admin telah diberikan pada akun tersebut.');
                } catch (\Exception $e) {
                    Log::error('Error assigning super admin role: ' . $e->getMessage());
                    return $redirect->route('super_admin_config')
                        ->withInput()
                        ->withErrors(['general_error' => 'Gagal memberikan role Super Admin: ' . $e->getMessage()]);
                }
            }
        }

        // Regular validation and creation for new user
        $rules = config('install.super_admin');

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Log::warning('Validation failed', ['errors' => $validator->errors()]);
            return $redirect->route('super_admin_config')->withInput()->withErrors($validator->errors());
        }

        try {
            // Create super admin user
            $user = \App\Models\User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status_kepegawaian' => 'Tetap', // Set status kepegawaian to Tetap
            ]);

            // Assign super admin role (menggunakan shield package)
            if (class_exists('Spatie\Permission\Models\Role')) {
                $superAdminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'super_admin']);
                $user->assignRole($superAdminRole);
            }

            // Arahkan ke halaman daftar user dengan role
            return redirect(route('user_roles_list'));
        } catch (\Exception $e) {
            Log::error('Error creating super admin: ' . $e->getMessage());
            return $redirect->route('super_admin_config')
                ->withInput()
                ->withErrors(['general_error' => 'Failed to create super admin: ' . $e->getMessage()]);
        }
    }

    /**
     * Tampilkan daftar user dengan role mereka
     */
    public function userRolesList()
    {
        // Ambil super admin yang baru saja dibuat
        $superAdmin = \App\Models\User::whereHas('roles', function ($query) {
            $query->where('name', 'super_admin');
        })->latest()->first();

        // Jika tidak ada super admin, kemungkinan user langsung mengakses URL ini
        // Kita redirect ke halaman sebelumnya
        if (!$superAdmin) {
            return redirect(route('super_admin_config'));
        }

        // Ambil semua user dengan role mereka
        $users = \App\Models\User::with('roles')->get();

        return view('InstallerEragViews::user-roles-list', [
            'superAdmin' => $superAdmin,
            'users' => $users
        ]);
    }

    public function featureToggles()
    {
        // Get all feature toggles or create default ones if none exist
        $features = \App\Models\FeatureToggle::all();

        // If no features exist yet, create default ones
        if ($features->isEmpty()) {
            $defaultFeatures = [
                ['key' => 'artikel_module', 'label' => 'Modul Artikel', 'status_aktif' => true],
                ['key' => 'produk_module', 'label' => 'Modul Produk', 'status_aktif' => true],
                ['key' => 'galeri_module', 'label' => 'Modul Galeri', 'status_aktif' => true],
                ['key' => 'event_module', 'label' => 'Modul Event', 'status_aktif' => true],
                ['key' => 'download_module', 'label' => 'Modul Download', 'status_aktif' => true],
                ['key' => 'struktur_organisasi_module', 'label' => 'Modul Struktur Organisasi', 'status_aktif' => true],
                ['key' => 'testimoni_module', 'label' => 'Modul Testimoni', 'status_aktif' => true],
                ['key' => 'mitra_module', 'label' => 'Modul Mitra', 'status_aktif' => true],
                ['key' => 'magang_module', 'label' => 'Modul Magang', 'status_aktif' => true],
                ['key' => 'feedback_module', 'label' => 'Modul Feedback & Saran', 'status_aktif' => true],
            ];

            foreach ($defaultFeatures as $feature) {
                \App\Models\FeatureToggle::create($feature);
            }

            $features = \App\Models\FeatureToggle::all();
        }

        return view('InstallerEragViews::feature-toggles', compact('features'));
    }

    public function saveFeatureToggles(Request $request, Redirector $redirect)
    {
        // Validate using rules from config
        $rules = config('install.feature_toggles');

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Log::warning('Feature toggle validation failed', ['errors' => $validator->errors()]);
            return $redirect->route('feature_toggles')->withInput()->withErrors($validator->errors());
        }

        // Get all feature toggles
        $allFeatures = \App\Models\FeatureToggle::all();

        // Extract submitted features from form
        $submittedFeatures = $request->input('features', []);

        // Update each feature's status
        foreach ($allFeatures as $feature) {
            $feature->status_aktif = isset($submittedFeatures[$feature->key]) ? true : false;
            $feature->save();
        }

        return redirect(route('finish'));
    }

    public function finish()
    {
        return view('InstallerEragViews::finish');

    }

    public function finishSave()
    {
        InstalledManager::create();

        // Update .env file to set APP_INSTALLED=true
        $envPath = base_path('.env');
        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);

            // Check if APP_INSTALLED already exists
            if (strpos($envContent, 'APP_INSTALLED') !== false) {
                $envContent = preg_replace('/APP_INSTALLED=(.*)/i', 'APP_INSTALLED=true', $envContent);
            } else {
                $envContent .= "\nAPP_INSTALLED=true\n";
            }

            file_put_contents($envPath, $envContent);
        }

        return redirect(URL::to('/'));
    }
}
