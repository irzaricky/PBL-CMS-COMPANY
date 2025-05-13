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

    public function account()
    {
        DatabaseManager::MigrateAndSeed();

        return view('InstallerEragViews::account');
    }

    public function saveAccount(Request $request, Redirector $redirect)
    {
        $rules = config('install.account');

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $redirect->route('account')->withInput()->withErrors($validator->errors());
        }

        unset($request['_token']);

        $request['password'] = Hash::make($request->password);

        DB::table('users')->insert($request->all());

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
