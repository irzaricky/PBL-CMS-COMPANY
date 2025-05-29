<?php

namespace App\Http\Responses\Auth;

use Filament\Http\Responses\Auth\EmailVerificationResponse as BaseEmailVerificationResponse;
use Illuminate\Http\RedirectResponse;

class EmailVerificationResponse extends BaseEmailVerificationResponse
{
    public function toResponse($request): RedirectResponse
    {
        return redirect()->intended('/');
    }
}
