<?php

namespace App\Installer\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Installer\Main\ConnectionSupervisor;
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

        // Check if database name and credentials are provided
        if (
            empty($request->input('database_name')) ||
            empty($request->input('database_hostname')) ||
            empty($request->input('database_username'))
        ) {
            Log::error('Missing required database information');

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['database_fields' => ['Please provide all required database information']]
                ], 422);
            }

            return $redirect->route('database_import')->withInput()->withErrors([
                'database_fields' => 'Please provide all required database information',
            ]);
        }

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

        // Check database connection and make sure to stop the process if it fails
        $dbConnectionSuccess = $this->checkDatabaseConnection($request);
        Log::info('Database connection check result: ' . ($dbConnectionSuccess ? 'success' : 'failed'));

        if (!$dbConnectionSuccess) {
            Log::error('Database connection failed - stopping installation process');

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['database_connection' => ['Database connection failed. Please check your credentials and make sure the database exists.']]
                ], 422);
            }

            return $redirect->route('database_import')->withInput()->withErrors([
                'database_connection' => 'Database connection failed. Please check your credentials and make sure the database exists.',
            ]);
        }

        try {
            // Double-check database connection once more before proceeding
            if (!$this->checkDatabaseConnection($request)) {
                Log::error('Final database connection check failed');

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'errors' => ['database_connection' => ['Database connection failed on final verification. Please check your credentials.']]
                    ], 422);
                }

                return $redirect->route('database_import')->withInput()->withErrors([
                    'database_connection' => 'Database connection failed on final verification. Please check your credentials.',
                ]);
            }

            $result = $this->EnvironmentManager->saveFileWizard($request);
            Log::info('Environment saved: ' . $result);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'redirect' => route('account')
                ]);
            }

            // Only proceed to next step if database connection is established
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

    /**
     * Check database permissions
     * 
     * @return array
     */
    private function checkDatabasePermissions(): array
    {
        return ConnectionSupervisor::checkPermissions();
    }

    private function checkDatabaseConnection(Request $request): bool
    {
        $connection = $request->input('database_connection');
        $database = $request->input('database_name');

        if (!$database) {
            Log::error('No database name provided');
            return false;
        }

        $settings = config("database.connections.$connection");

        // Handle SQLite differently
        if ($connection === 'sqlite') {
            // Set database file path
            $databasePath = storage_path('app/' . $database);

            // Create directory if it doesn't exist
            $dirPath = dirname($databasePath);
            if (!file_exists($dirPath)) {
                mkdir($dirPath, 0755, true);
            }

            // Create empty database file if it doesn't exist
            if (!file_exists($databasePath)) {
                try {
                    touch($databasePath);
                    chmod($databasePath, 0644);
                    Log::info('Created SQLite database file at: ' . $databasePath);
                } catch (\Exception $e) {
                    Log::error('Failed to create SQLite database file: ' . $e->getMessage());
                    return false;
                }
            }

            // Configure SQLite connection
            config([
                'database' => [
                    'default' => $connection,
                    'connections' => [
                        $connection => [
                            'driver' => 'sqlite',
                            'database' => $databasePath,
                            'prefix' => '',
                            'foreign_key_constraints' => true,
                        ],
                    ],
                ],
            ]);
        } else {
            // MySQL connection
            config([
                'database' => [
                    'default' => $connection,
                    'connections' => [
                        $connection => array_merge($settings, [
                            'driver' => $connection,
                            'host' => $request->input('database_hostname'),
                            'port' => $request->input('database_port'),
                            'database' => $database,
                            'username' => $request->input('database_username'),
                            'password' => $request->input('database_password') ?? '',
                        ]),
                    ],
                ],
            ]);
        }

        DB::purge();

        try {
            // Get PDO connection
            $pdo = DB::connection()->getPdo();

            if (!$pdo) {
                Log::error('Database connection failed: Could not get PDO instance');
                return false;
            }

            // Test the connection with a simple query
            $result = DB::connection()->select('SELECT 1 as connection_test');

            if (!$result || !isset($result[0]->connection_test) || $result[0]->connection_test !== 1) {
                Log::error('Database connection failed: Test query failed');
                return false;
            }

            // Check database permissions for MySQL/MariaDB
            if ($connection === 'mysql') {
                $permissionResults = $this->checkDatabasePermissions();

                if (!$permissionResults['success']) {
                    Log::error('Database permission check failed: ' . implode(', ', $permissionResults['messages']));
                    return false;
                }

                Log::info('Database permission check passed');
            }

            Log::info('Database connection verified successfully');
            return true;
        } catch (Exception $e) {
            Log::error('Database connection error: ' . $e->getMessage());
            return false;
        }
    }
}
