<?php

namespace App\Listeners\Site\Auth;

use App\Events\Site\Auth\UserLoggedOut;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserLoggedOutListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(UserLoggedOut $event)
    {
        $event->user->metas()->update([
            'last_logout_at' => now(),
        ]);
    }
}
