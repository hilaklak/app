<?php

namespace App\Listeners\Site\Auth;

use App\Events\Site\Auth\UserForgotPassword;
use App\Notifications\Site\Auth\UserResetPasswordRequestNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserForgotPasswordListener implements ShouldQueue
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
    public function handle(UserForgotPassword $event)
    {
        $event->user->notify(new UserResetPasswordRequestNotification($event->token, $event->type));
    }
}
