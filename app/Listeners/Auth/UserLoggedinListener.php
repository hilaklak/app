<?php

namespace App\Listeners\Site\Auth;

use App\Events\Site\Auth\UserLoggedIn;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserLoggedInListener implements ShouldQueue
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
    public function handle(UserLoggedIn $event)
    {
        $event->user->metas()->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip() ?? null,
            'last_login_agent' => request()->userAgent() ?? null,
        ]);

        // $event->user->notify(new UserLoggedInNotification());
    }
}
