<?php

namespace App\Listeners;

use App\Events\UserOnlineStatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Support\Facades\Auth;

class UpdateLastSeenListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Authenticated $event): void
    {
        $event->user->update([
            'last_seen_at' => now(),
        ]);
        event(new UserOnlineStatusUpdated(Auth::user()));
    }
}
