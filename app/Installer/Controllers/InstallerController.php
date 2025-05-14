<?php

namespace App\Installer\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use App\Installer\Main\DatabaseManager;
use App\Installer\Main\InstalledManager;
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

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $redirect->route('profil_perusahaan')->withInput()->withErrors($validator->errors());
        }

        $data = $request->except(['_token', 'logo_perusahaan']);

        // Handle logo upload if provided
        if ($request->hasFile('logo_perusahaan')) {
            // Store the file in the same directory that Filament uses (perusahaan-logo)
            $logo = $request->file('logo_perusahaan');
            $logoName = time() . '.' . $logo->getClientOriginalExtension();
            $path = $logo->storeAs('perusahaan-logo', $logoName, 'public');
            $data['logo_perusahaan'] = $path;
        }

        // Create ProfilPerusahaan record
        \App\Models\ProfilPerusahaan::create($data);

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
