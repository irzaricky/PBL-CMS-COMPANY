<?php

namespace App\Http\Controllers;

use App\Services\InstallerService;

class PreInstallController extends Controller
{
    public function index()
    {
        $basePath = dirname(dirname(dirname(__FILE__)));
        $installerService = new InstallerService();

        // Check if dependencies are installed
        $dependenciesInstalled = file_exists($basePath . '/vendor/autoload.php');

        if (!$dependenciesInstalled) {
            return view('installer.pre-install', [
                'dependenciesInstalled' => false
            ]);
        }

        // If dependencies are installed, redirect to the main installer
        return redirect()->route('installer.welcome');
    }
    public function installDependencies()
    {
        $installerService = new InstallerService();
        $result = $installerService->installDependencies();

        if ($result['success']) {
            // Generate app key after dependencies are installed
            $installerService->generateAppKeyIfNeeded();

            return redirect()->route('installer.welcome')->with('success', $result['message']);
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }
}
