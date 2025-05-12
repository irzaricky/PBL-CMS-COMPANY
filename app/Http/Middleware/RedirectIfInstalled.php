<?php

namespace App\Http\Middleware;

use App\Services\InstallerService;
use Closure;
use Illuminate\Http\Request;

class RedirectIfInstalled
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
        // If application is already installed, redirect to home
        if ($this->installerService->isInstalled()) {
            return redirect('/');
        }

        return $next($request);
    }
}
