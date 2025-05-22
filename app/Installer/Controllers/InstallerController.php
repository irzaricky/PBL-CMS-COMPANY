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
