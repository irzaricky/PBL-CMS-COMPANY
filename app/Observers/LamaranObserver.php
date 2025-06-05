<?php

namespace App\Observers;

use App\Models\Lamaran;
use App\Models\User;
use App\Notifications\LamaranStatusNotification;

class LamaranObserver
{
    /**
     * Handle the Lamaran "updated" event.
     *
     * @param  \App\Models\Lamaran  $lamaran
     * @return void
     */
    public function updated(Lamaran $lamaran)
    {
        // Only send notification when status_lamaran changes
        if ($lamaran->isDirty('status_lamaran')) {
            $oldStatus = $lamaran->getOriginal('status_lamaran');
            $newStatus = $lamaran->status_lamaran;
            
            // Only notify when status changes from "Diproses" to either "Diterima" or "Ditolak"
            if ($oldStatus === 'Diproses' && ($newStatus === 'Diterima' || $newStatus === 'Ditolak')) {
                $lowongan = $lamaran->lowongan;
                $user = $lamaran->user;
                
                if ($user && $lowongan) {
                    $user->notify(new LamaranStatusNotification($lamaran, $lowongan, $oldStatus, $newStatus));
                }
            }
        }
    }
}