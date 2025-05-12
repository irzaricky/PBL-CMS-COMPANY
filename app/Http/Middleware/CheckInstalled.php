<?php

namespace App\Http\Middleware;

use App\Services\InstallerService;
use Closure;
use Illuminate\Http\Request;

class CheckInstalled
{
    protected $installerService;

    public function __construct(InstallerService $installerService)
    {
        $this->installerService = $installerService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // If application is not installed and we're not accessing the installer routes
        if (!$this->installerService->isInstalled() && !$request->is('installer*')) {
            return redirect()->route('installer.welcome');
        }

        return $next($request);
    }
}
