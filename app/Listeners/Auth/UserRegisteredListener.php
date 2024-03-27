<?php

namespace App\Listeners\Site\Auth;

use App\Events\Site\Auth\UserRegistered;
use App\Notifications\User\Auth\VerificationEmailNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserRegisteredListener implements ShouldQueue
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
    public function handle(UserRegistered $event)
    {

        // dd('ddd');
        $user = $event->user;

        $user->metas()->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip() ?? null,
            'last_login_agent' => request()->userAgent() ?? null,
        ]);

        $user->notify(new VerificationEmailNotification());

        // $user->notify(new SendWelcomeNotification());

        // NewUserRegistered::dispatch($user);

        // Notification::route('mail', conf('ADMIN_EMAIL'))->notify(new SendNewSeoOrderToAdminNotification($event->details));
    }
}
