<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\EmailVerifiedNotification;

class SendEmailVerifiedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(Verified $event): void
    {
        $event->user->notify(new EmailVerifiedNotification());
    }
}
