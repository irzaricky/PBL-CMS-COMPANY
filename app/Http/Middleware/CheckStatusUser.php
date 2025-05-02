<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckStatusUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Cek status user (aktif/nonaktif)
        if ($user->status === 'nonaktif') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('filament.admin.auth.login')
                ->with('error', 'Akun Anda sudah tidak aktif. Silakan hubungi administrator.');
        }

        if (empty($user->status_kepegawaian) && $request->routeIs('filament.*')) {
            return redirect('/dashboard')->with('message', 'Anda tidak memiliki akses ke panel admin.');
        }

        // Lanjutkan dengan request
        return $next($request);
    }
}
