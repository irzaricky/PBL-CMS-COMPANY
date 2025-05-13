<?php

namespace App\Installer\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Installer\Main\EnvironmentManager;

class DatabaseController extends Controller
{
    protected EnvironmentManager $EnvironmentManager;

    public function __construct(EnvironmentManager $environmentManager)
    {
        $this->EnvironmentManager = $environmentManager;
    }

    public function databaseImport(Request $request)
    {
        return view('InstallerEragViews::database-import');
    }

    public function saveWizard(Request $request, Redirector $redirect)
    {
        // Debug the request data
        Log::info('Form submission data:', $request->all());
        Log::info('Headers:', $request->headers->all());

        $rules = config('install.environment.form.rules');

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Log::error('Validation errors:', $validator->errors()->toArray());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            return $redirect->route('database_import')->withInput()->withErrors($validator->errors());
        }

        if (!$this->checkDatabaseConnection($request)) {
            Log::error('Database connection failed');

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['database_connection' => ['Database connection failed']]
                ], 422);
            }

            return $redirect->route('database_import')->withInput()->withErrors([
                'database_connection' => 'Database connection failed',
            ]);
        }

        try {
            $result = $this->EnvironmentManager->saveFileWizard($request);
            Log::info('Environment saved: ' . $result);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'redirect' => route('account')
                ]);
            }

            return redirect(route('account'));
        } catch (\Exception $e) {
            Log::error('Error saving environment: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['save_error' => ['Failed to save environment: ' . $e->getMessage()]]
                ], 500);
            }

            return $redirect->route('database_import')->withInput()->withErrors([
                'save_error' => 'Failed to save environment: ' . $e->getMessage(),
            ]);
        }
    }

    private function checkDatabaseConnection(Request $request): bool
    {
        $connection = $request->input('database_connection');

        $settings = config("database.connections.$connection");

        config([
            'database' => [
                'default' => $connection,
                'connections' => [
                    $connection => array_merge($settings, [
                        'driver' => $connection,
                        'host' => $request->input('database_hostname'),
                        'port' => $request->input('database_port'),
                        'database' => $request->input('database_name'),
                        'username' => $request->input('database_username'),
                        'password' => $request->input('database_password') ?? '',
                    ]),
                ],
            ],
        ]);

        DB::purge();

        try {
            DB::connection()->getPdo();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
