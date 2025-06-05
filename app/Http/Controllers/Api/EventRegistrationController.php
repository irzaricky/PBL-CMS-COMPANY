<?php

namespace App\Http\Controllers\Api;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class EventRegistrationController extends Controller
{
    /**
     * Register the authenticated user for an event.
     */
    public function store(Request $request, Event $event): RedirectResponse
    {
        $user = Auth::user();

        // Attach user to event pivot
        $event->users()->attach($user->id_user);

        // Increment jumlah_pendaftar
        $event->increment('jumlah_pendaftar');

        // Send reminder notifications
        $user->notify(new \App\Notifications\EventRegistrationNotification($event));

        return back()->with('success', 'Berhasil mendaftar event.');
    }

    /**
     * Unregister the authenticated user from an event.
     */
    public function destroy(Request $request, Event $event): RedirectResponse
    {
        $user = Auth::user();

        // Detach user from event pivot
        $event->users()->detach($user->id_user);

        // Decrement jumlah_pendaftar
        $event->decrement('jumlah_pendaftar');

        // Send cancellation notification
        $user->notify(new \App\Notifications\EventCancellationNotification($event));

        return back()->with('success', 'Pendaftaran event dibatalkan.');
    }
}
