<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class UpdateLastLogin
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        if ($event->user instanceof \App\Models\User) {
            $event->user->update([
                'last_login' => now(),
            ]);
        }
    }
}
