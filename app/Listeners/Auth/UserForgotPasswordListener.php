<?php

namespace App\Listeners\Auth;

use App\Events\Auth\UserForgotPassword;
use App\Notifications\Auth\UserResetPasswordRequestNotification;
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
